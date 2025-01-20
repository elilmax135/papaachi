<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

use App\Models\Branch;


use App\Models\Transfer;
use App\Models\TransferPayment;
use App\Models\TransferProduct;

use Illuminate\Support\Facades\DB;


class TransferController extends Controller
{
    public function index(){
        $productbox = DB::table('product')
        ->select('product.*', 'stock_quantity') // Include stock_quantity
        ->where('product_type', '=', 'box')
        ->get();

    $productflower = DB::table('product')
        ->select('product.*', 'stock_quantity') // Include stock_quantity
        ->where('product_type', '=', 'flower')
        ->get();

        $branch=Branch::all();



        $lastRecord = transfer::orderBy('id', 'desc')->first();
        $lasttransect = Transfer::orderBy('created_at', 'desc')->first();
        return view('transfer.index',compact('productbox','productflower','branch','lastRecord','lasttransect'));
    }





    public function store(Request $request){

   // Save the sell details
   $transfer = new Transfer();

   $transfer->transfer_date = $request->transfer_date;
   $transfer->transaction_id = $request->transaction_id;
   $transfer->branch_id = $request->t_id;


   $transfer->total = $request->total;
   $transfer->save();


    $validatedData = $request->validate([
        'products' => 'required|json', // Validate JSON structure
    ]);

    $products = json_decode($request->products);


   $transfer->products = json_encode($products); // Storing the products as JSON

   foreach ($products as $product) {
       $purchasePrice = is_numeric($product->purchase_price) ? (float)$product->purchase_price : 0.00;
       $transferingPrice = is_numeric($product->selling_price) ? (float)$product->selling_price : 0.00;
       $subtotalField = $product->quantity * $transferingPrice;

       // Fetch the product from the database
       $existingProduct = Product::find($product->product_id);

       if ($existingProduct) {
           // Update the stock quantity
           $existingProduct->stock_quantity -= $product->quantity;
           // Update purchase price and selling price
           $existingProduct->price_purchase = $purchasePrice;
           $existingProduct->price_selling = $transferingPrice;
           $existingProduct->save();
       }


       // Store the product in the sell_product table
       $transferProduct = new TransferProduct();
       $transferProduct->transfer_id = $transfer->id;
       $transferProduct->product_id = $product->product_id;
       $transferProduct->quantity = $product->quantity;
       $transferProduct->purchase_price = $purchasePrice;
       $transferProduct->selling_price = $transferingPrice;
       $transferProduct->subtotal = $subtotalField;

       $transferProduct->save();
   }
     return redirect()->back()->with('success','successfully Submitted');
    }





    public function Transpay(Request $request)
{
    // Validate the input
    $request->validate([
        'transfer_id' => 'required|string|exists:transfer,id', // Ensure transfer_id exists in transfers table
        'payment_method' => 'required|string',
        'check_number' => 'nullable|string',
        'bank_name' => 'nullable|string',
        'transection_id' => 'nullable|string',
        'payment_platform' => 'nullable|string',
        'payment_date' => 'required|date',
        'payment_total' => 'required|numeric',
        'pay_amount' => 'required|numeric',
    ]);

    // Create a new payment record
    $payment = new TransferPayment();
    $payment->transfer_id = $request->transfer_id;
    $payment->payment_method = $request->payment_method;
    $payment->check_number = $request->check_number ?? '--';
    $payment->bank_name = $request->bank_name ?? '--';
    $payment->transection_id = ($request->payment_method === 'online' && !empty($request->transection_id))
        ? $request->transection_id
        : '--';
    $payment->payment_platform = $request->payment_platform ?? '--';
    $payment->payment_date = $request->payment_date;
    $payment->transfer_total = $request->payment_total;
    $payment->pay_amount = $request->pay_amount;
    $payment->pay_due = max(0, $payment->transfer_total - $payment->pay_amount); // Ensure no negative values
    $payment->save();

    // Update the transfer status
    $transfer = Transfer::find($request->transfer_id); // Retrieve the transfer record

    if ($transfer) {
        if ($payment->pay_due == 0) {
            $transfer->transfer_status = 'true'; // Fully paid
        } elseif ($payment->pay_due > 0 && $payment->pay_due < $payment->transfer_total) {
            $transfer->transfer_status = 'pending'; // Partially paid
        }
        $transfer->save();
    }

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Payment successfully recorded!');
}


public function list(){
    $transfers = DB::table('transfer') // Change from 'purchase' to 'transfers'
 ->join('branches', 'transfer.branch_id', '=', 'branches.id') // Join for the destination branch
    ->leftJoinSub(
        DB::table('transfer_payment')
            ->select('transfer_pay_id', 'transfer_id', 'payment_date', 'pay_amount', 'pay_due')
            ->whereIn('transfer_pay_id', function ($query) {
                $query->select(DB::raw('MAX(transfer_pay_id)'))
                    ->from('transfer_payment')
                    ->groupBy('transfer_id');
            }),
        'latest_payment', // Alias for the subquery
        function ($join) {
            $join->on('transfer.id', '=', 'latest_payment.transfer_id');
        }
    )
    ->select(
        'transfer.*',
        'branches.branch_name as branch_name', // Rename column for clarity

        'latest_payment.transfer_pay_id',
        'latest_payment.payment_date',
        'latest_payment.pay_amount',
        'latest_payment.pay_due AS last_pay_due' // Alias the pay_due column for clarity
    )
    ->orderBy('id', 'desc') // Change ordering field to transfer_id
    ->get()
    ->groupBy('id'); // Group by transfer_id instead of purchase_id

return view('transfer.list', compact('transfers'));
}


  public function getPaymentsByTransferId($transfer_id){
// Fetch transfer details (including branch details directly from the transfers table)
$transfer = DB::table('transfer')
    ->join('branches', 'transfer.branch_id', '=', 'branches.id') // Join with branches table
    ->select(
        'branches.branch_name', // Fetch the branch name
        'transfer.branch_id',
        'transfer.transfer_date',
        'transfer.transaction_id',
        'transfer.total',
        'transfer.transfer_status'
    )
    ->where('transfer.id', $transfer_id)
    ->first();

// Fetch payment details for the transfer
$payments = DB::table('transfer_payment')
    ->where('transfer_id', $transfer_id)
    ->orderBy('transfer_pay_id', 'desc') // Order by latest payment
    ->get();

// Fetch products and their quantities directly from the transfer_products table
$products = DB::table('transfer_product')
    ->join('product', 'transfer_product.product_id', '=', 'product.product_id')
    ->select(
        'product.product_name as product_name',
        'transfer_product.quantity',
        'transfer_product.subtotal',
        'transfer_product.selling_price'
    )
    ->where('transfer_product.transfer_id', $transfer_id)
    ->get();
return view('transfer.details', compact('transfer', 'payments', 'products', 'transfer_id'));

  }

   public function processTransPayment(Request $request){
    $request->validate([
        'transfer_id' => 'required|string', // Updated for transfer
        'payment_method' => 'required|string',
        'check_number' => 'nullable|string',
        'bank_name' => 'nullable|string',
        'transection_id' => 'nullable|string',
        'payment_platform' => 'nullable|string',
        'payment_date' => 'required|date',
        'transfer_total' => 'required|numeric', // Updated for transfer
        'pay_amount' => 'required|numeric',
    ]);

    // Create new payment record
    $payment = new TransferPayment();
    $payment->transfer_id = $request->transfer_id; // Map to transfer
    $payment->payment_method = $request->payment_method;
    $payment->check_number = !empty($request->check_number) ? $request->check_number : '--';
    $payment->bank_name = !empty($request->bank_name) ? $request->bank_name : '--';
    $payment->transection_id = ($request->payment_method == 'online' && !empty($request->transection_id)) ? $request->transection_id : '--';
    $payment->payment_platform = !empty($request->payment_platform) ? $request->payment_platform : '--';
    $payment->payment_date = $request->payment_date;
    $payment->transfer_total = $request->transfer_total; // Use 'transfer_total' instead of 'payment_total'
    $payment->pay_amount = $request->pay_amount;
    $payment->pay_due = $payment->transfer_total - $payment->pay_amount;
    $payment->pay_due = max($payment->pay_due, 0); // Ensure pay_due is never negative
    $payment->save();

    // Update the transfer status based on the payment status
    $transfer = Transfer::where('id', $request->transfer_id)->first();

    if ($transfer) {
        if ($payment->pay_due == 0) {
            // Fully paid
            $transfer->transfer_status = 'true';
        } elseif ($payment->pay_due > 0 && $payment->pay_due < $payment->transfer_total) {
            // Partially paid
            $transfer->transfer_status = 'pending';
        }

        // Save the updated transfer status
        $transfer->save();
    }

    // Redirect back or to another page with success message
    return redirect()->back()->with('success', 'Transfer payment processed successfully.');

   }
}
