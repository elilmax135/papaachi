<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;

use App\Models\Product;
use App\Models\sell;
use App\Models\sellProduct;
use App\Models\sellPayment;
use App\Models\Branch;
use App\Models\Location;

use App\Models\service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class SellController extends Controller
{
    public function index()
    {
        $productbox = DB::table('product')
            ->select('product.*', 'stock_quantity') // Include stock_quantity
            ->where('product_type', '=', 'box')
            ->get();

        $productflower = DB::table('product')
            ->select('product.*', 'stock_quantity') // Include stock_quantity
            ->where('product_type', '=', 'flower')
            ->get();
            $lastRecord = sell::orderBy('id', 'desc')->first();

            $service=service::all();
            $locate=location::all();


            $location = DB::table('locations')
            ->select('locations.total_distance') // Retrieve `total_distance` column
            ->orderBy('id', 'desc')    // Order by the ID in descending order
            ->first();


            $lasttransect = Sell::orderBy('created_at', 'desc')->first();
        return view('sell.index', compact('productbox', 'productflower','lastRecord','lasttransect','service','location'));
    }
    public function sell_info(Request $request){
   // Handle the doctor confirmation file upload


   // Save the sell details
   $sell = new Sell();
   $image=$request->doctor_confirmation;

   $imagename=time().'.'.$image->getClientOriginalExtension();
    $request->doctor_confirmation->move('doctorImage',$imagename);
    $sell->doctor_confirm =$imagename;
   $sell->customer_name = $request->customer_name;
   $sell->customer_mobile = $request->customer_mobile;
   $sell->sell_date = $request->sell_date;
   $sell->transaction_id = $request->transaction_id;
   $sell->transport_mode = $request->transport_mode;
   $sell->service_id = $request->s_id;
   $sell->customer_address = $request->customer_address;

   $sell->total = $request->total;
   $sell->save();


    // Validate and decode the products JSON
    $validatedData = $request->validate([
        'products' => 'required|json', // Validate JSON structure
    ]);

    $products = json_decode($request->products);

   // Save products as JSON to the 'products' field
   $sell->products = json_encode($products); // Storing the products as JSON



   // Save the products associated with the sell
   foreach ($products as $product) {
       $purchasePrice = is_numeric($product->purchase_price) ? (float)$product->purchase_price : 0.00;
       $sellingPrice = is_numeric($product->selling_price) ? (float)$product->selling_price : 0.00;
       $subtotalField = $product->quantity * $sellingPrice;

       // Fetch the product from the database
       $existingProduct = Product::find($product->product_id);

       if ($existingProduct) {
           // Update the stock quantity
           $existingProduct->stock_quantity -= $product->quantity;
           // Update purchase price and selling price
           $existingProduct->price_purchase = $purchasePrice;
           $existingProduct->price_selling = $sellingPrice;
           $existingProduct->save();
       }

       // Store the product in the sell_product table
       $sellProduct = new SellProduct();
       $sellProduct->sell_id = $sell->id;
       $sellProduct->product_id = $product->product_id;
       $sellProduct->quantity = $product->quantity;
       $sellProduct->purchase_price = $purchasePrice;
       $sellProduct->selling_price = $sellingPrice;
       $sellProduct->subtotal = $subtotalField;

       $sellProduct->save();
   }

   return redirect()->back()->with('success', 'Sale Details successfully!');



}









public function sell_pay(Request $request){


    $request->validate([
        'sell_id' => 'required|string',
        'payment_method' => 'required|string',
        'check_number' => 'nullable|string',
        'bank_name' => 'nullable|string',
        'transection_id' => 'nullable|string',
        'payment_platform' => 'nullable|string',
        'payment_date' => 'required|date',
        'payment_total' => 'required|numeric',
        'pay_amount' => 'required|numeric',
    ]);

    $payment=new sellPayment();
    $payment->sell_id = $request->sell_id; // Map flower_color to fw_color_id
    $payment->payment_method = $request->payment_method;
    $payment->check_number = !empty($request->check_number) ? $request->check_number : '--';

    // Similarly handle other fields
    $payment->bank_name = !empty($request->bank_name) ? $request->bank_name : '--';
    $payment->transection_id = ($request->payment_method == 'online' && !empty($request->transection_id))
    ? $request->transection_id
    : '--';
    $payment->payment_platform = !empty($request->payment_platform) ? $request->payment_platform : '--';

    $payment->payment_date = $request->payment_date;
    $payment->sell_total = $request->payment_total; // Use 'payment_total' instead of 'total'
    $payment->pay_amount = $request->pay_amount;
    $payment->pay_due = $payment->sell_total - $payment->pay_amount ?? 0;
    $payment->save();

    $sell = Sell::where('id', $request->sell_id)->first(); // Ensure this returns a model instance

    if ($sell) {
        if ($payment->pay_due == 0) {
            $sell->sell_status = 'true'; // Fully paid
        } elseif ($payment->pay_due > 0 && $payment->pay_due < $payment->sell_total) {
            $sell->sell_status = 'pending'; // Partially paid
        }



        $sell->save();

    }

    return redirect()->back()->with('success', 'Payment successfully recorded!');



}

public function getPaymentsBySaleId($sale_id)
{
    // Fetch sale details
    $sale = DB::table('sells')
        ->select('sells.*')
        ->where('id', $sale_id)
        ->first();

    // Fetch payment details
    $payments = DB::table('sell_payment')
        ->where('sell_id', $sale_id)
        ->orderBy('sell_pay_id', 'desc') // Order by latest payment
        ->get();

    // Fetch products and their quantities directly from the sell_product table
    $products = DB::table('sell_product')
        ->join('product', 'sell_product.product_id', '=', 'product.product_id')
        ->select(
            'product.product_name as product_name',
            'sell_product.quantity',
            'sell_product.subtotal',
            'sell_product.selling_price'
        )
        ->where('sell_product.sell_id', $sale_id)
        ->get();

    return view('sell.details', compact('sale', 'payments', 'products', 'sale_id'));
}


public function list()
{
    $sell = DB::table('sells')
        ->leftJoinSub(
            DB::table('sell_payment')
                ->select('sell_pay_id', 'sell_id', 'payment_date', 'pay_amount', 'pay_due')
                ->whereIn('sell_pay_id', function ($query) {
                    $query->select(DB::raw('MAX(sell_pay_id)'))
                        ->from('sell_payment')
                        ->groupBy('sell_id');
                }),
            'latest_payment', // Alias for the subquery
            function ($join) {
                $join->on('sells.id', '=', 'latest_payment.sell_id');
            }
        )

        ->select(
            'sells.*',
            'latest_payment.sell_pay_id',
            'latest_payment.payment_date',
            'latest_payment.pay_amount',
            'latest_payment.pay_due AS last_pay_due',
            // Alias the pay_due column for clarity
        )
        ->orderBy('id', 'desc')
        ->get()
        ->groupBy('id');

    return view('sell.list', compact('sell'));
}
public function processSalePayment(Request $request)
{
    $request->validate([
        'sale_id' => 'required|string',
        'payment_method' => 'required|string',
        'check_number' => 'nullable|string',
        'bank_name' => 'nullable|string',
        'transection_id' => 'nullable|string',
        'payment_platform' => 'nullable|string',
        'payment_date' => 'required|date',
        'sale_total' => 'required|numeric',
        'pay_amount' => 'required|numeric',
    ]);

    // Create new payment record
    $payment = new SellPayment(); // Assuming SalePayment model handles sale payments
    $payment->sell_id = $request->sale_id; // Map to sale
    $payment->payment_method = $request->payment_method;
    $payment->check_number = !empty($request->check_number) ? $request->check_number : '--';
    $payment->bank_name = !empty($request->bank_name) ? $request->bank_name : '--';
    $payment->transection_id = ($request->payment_method == 'online' && !empty($request->transection_id)) ? $request->transection_id : '--';
    $payment->payment_platform = !empty($request->payment_platform) ? $request->payment_platform : '--';
    $payment->payment_date = $request->payment_date;
    $payment->sell_total = $request->sale_total; // Use 'sale_total' instead of 'total'
    $payment->pay_amount = $request->pay_amount;
    $payment->pay_due = $payment->sell_total - $payment->pay_amount;
    $payment->pay_due = max($payment->pay_due, 0); // Ensure pay_due is never negative
    $payment->save();

    // Fetch the associated sale record
    $sale = Sell::where('id', $request->sale_id)->first();

    if ($sale) {
        // Update the sale status based on the payment status
        if ($payment->pay_due == 0) {
            // Fully paid
            $sale->sell_status = 'true';
        } elseif ($payment->pay_due > 0 && $payment->pay_due < $payment->sell_total) {
            // Partially paid
            $sale->sell_status = 'pending';
        }

        // Save the updated sale status
        $sale->save();
    }

    // Redirect back or to another page with success message
    return redirect()->back()->with('success', 'Sale payment processed successfully');
}


public function update_total(Request $request){
 // Validate the input
 $request->validate([
    'location_price' => 'required|numeric|min:0', // Ensure location_price is a valid number
]);

// Get the last record from the sells table
$lastRecord = Sell::orderBy('id', 'desc')->first();

if (!$lastRecord) {
    return response()->json(['error' => 'No sell record found.'], 404);
}

// Update the sell record
$lastRecord->total += $request->location_price; // Add location price to the current total
$lastRecord->save();

// Return a success response
return redirect()->back()->with('you can pay!');
}

}
