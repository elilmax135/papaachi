<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Branch;
use App\Models\Purchase;
use App\Models\Payment;
use App\Models\ProductPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    public function index(){
        $br=Branch::all();
         $product=product::all();




         $productbox = DB::table('product')
         ->select('product.*') // Select all columns from the product table
         ->where('product_type', '=', 'box') // Add a condition (e.g., select products in the "box" category)
         ->get();
         $productflower = DB::table('product')
         ->select('product.*') // Select all columns from the product table
         ->where('product_type', '=', 'flower') // Add a condition (e.g., select products in the "box" category)
         ->get();

         $lastRecord = Purchase::orderBy('purchase_id', 'desc')->first();

         $lasttransect = Purchase::orderBy('created_at', 'desc')->first();


        return view('purchase.index',compact('productbox','productflower','lastRecord','br','lasttransect'));
    }


    public function list()
    {
        $purchases = DB::table('purchase')
        ->join('branches', 'purchase.branch', '=', 'branches.id')
        ->leftJoinSub(
            DB::table('payment_info')
                ->select('payment_id', 'purchase_id', 'payment_date', 'pay_amount', 'pay_due')
                ->whereIn('payment_id', function ($query) {
                    $query->select(DB::raw('MAX(payment_id)'))
                        ->from('payment_info')
                        ->groupBy('purchase_id');
                }),
            'latest_payment', // Alias for the subquery
            function ($join) {
                $join->on('purchase.purchase_id', '=', 'latest_payment.purchase_id');
            }
        )
        ->select(
            'purchase.*',
            'branches.branch_name',
            'latest_payment.payment_id',
            'latest_payment.payment_date',
            'latest_payment.pay_amount',
            'latest_payment.pay_due AS last_pay_due' // Alias the pay_due column for clarity
        )
        ->orderBy('latest_payment.payment_id', 'desc')
        ->get()
        ->groupBy('purchase_id');






    return view('purchase.list', compact('purchases'));
    }






public function submit(Request $request)
{
    $validatedData = $request->validate([
        'supplier_name' => 'required|string|max:255',
        'purchase_date' => 'required|date',
        'transaction_id' => 'required|string|max:255',
        'branch' => 'required|string',
        'total' => 'required|numeric',
        'products' => 'required|json',
    ]);
    // Decode the JSON string of products into an array
    $products = json_decode($request->products);

    $purchase = Purchase::create([
        'supplier_name' => $request->supplier_name,
        'purchase_date' => $request->purchase_date,
        'transaction_id' => $request->transaction_id,
        'branch' => $request->branch,
        'total' => $request->total,
    ]);
    // Iterate over each product and handle database operations
    foreach ($products as $product) {
        // Ensure purchase_price and selling_price are numeric and cast them to float
        $purchasePrice = is_numeric($product->purchase_price) ? (float)$product->purchase_price : 0.00;
        $sellingPrice = is_numeric($product->selling_price) ? (float)$product->selling_price : 0.00;



        // Fetch the product from the database
        $existingProduct = Product::find($product->id);

        if ($existingProduct) {
            // Update the stock quantity
            $existingProduct->stock_quantity += $product->quantity;
                // Update the purchase price and selling price
                $existingProduct->price_purchase = $purchasePrice;
                $existingProduct->price_selling = $sellingPrice;

            $existingProduct->save();
        }
        DB::table('product_purchases')->insert([
            'purchase_id' => $purchase->purchase_id,
            'product_id' => $product->id,
            'quantity' => $product->quantity,
            'purchase_price' => $purchasePrice,
            'selling_price' => $sellingPrice,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // Return a JSON response indicating success
    return redirect()->back();

}

public function payment(Request $request)
    {
        // Validate input

        $request->validate([
            'purchase_id' => 'required|string',
            'payment_method' => 'required|string',
            'check_number' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'transection_id' => 'nullable|string',
            'payment_platform' => 'nullable|string',
            'payment_date' => 'required|date',
            'payment_total' => 'required|numeric',
            'pay_amount' => 'required|numeric',
        ]);

        $payment=new Payment();
        $payment->purchase_id = $request->purchase_id; // Map flower_color to fw_color_id
        $payment->payment_method = $request->payment_method;
        $payment->check_number = !empty($request->check_number) ? $request->check_number : '--';

        // Similarly handle other fields
        $payment->bank_name = !empty($request->bank_name) ? $request->bank_name : '--';
        $payment->transection_id = ($request->payment_method == 'online' && !empty($request->transection_id))
        ? $request->transection_id
        : '--';
        $payment->payment_platform = !empty($request->payment_platform) ? $request->payment_platform : '--';

        $payment->payment_date = $request->payment_date;
        $payment->purchase_total = $request->payment_total; // Use 'payment_total' instead of 'total'
        $payment->pay_amount = $request->pay_amount;
        $payment->pay_due = $payment->purchase_total - $payment->pay_amount ?? 0;
        $payment->save();

        $purchase = Purchase::where('purchase_id', $request->purchase_id)->first();// Assuming purchase_id is the foreign key



        if ($purchase->purchase_id) {
            if ($payment->pay_due == 0) {
                $purchase->purchase_status = 'true'; // Fully paid
            } elseif ($payment->pay_due > 0 && $payment->pay_due < $payment->purchase_total) {
                $purchase->purchase_status = 'pending'; // Partially paid
            }elseif($payment->pay_due < 0)
            {
                $purchase->purchase_status = 'true';
            }

            $purchase->save();
        }
        // Log the saved payment


        return redirect()->back()->with('success', 'Payment successfully recorded!');
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'purchase_id' => 'required|string',
            'payment_method' => 'required|string',
            'check_number' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'transection_id' => 'nullable|string',
            'payment_platform' => 'nullable|string',
            'payment_date' => 'required|date',
            'payment_total' => 'required|numeric',
            'pay_amount' => 'required|numeric',
        ]);

        // Create new payment record
        $payment = new Payment();
        $payment->purchase_id = $request->purchase_id; // Map to purchase
        $payment->payment_method = $request->payment_method;
        $payment->check_number = !empty($request->check_number) ? $request->check_number : '--';
        $payment->bank_name = !empty($request->bank_name) ? $request->bank_name : '--';
        $payment->transection_id = ($request->payment_method == 'online' && !empty($request->transection_id)) ? $request->transection_id : '--';
        $payment->payment_platform = !empty($request->payment_platform) ? $request->payment_platform : '--';
        $payment->payment_date = $request->payment_date;
        $payment->purchase_total = $request->payment_total; // Use 'payment_total' instead of 'total'
        $payment->pay_amount = $request->pay_amount;
        $payment->pay_due = $payment->purchase_total - $payment->pay_amount;
        $payment->pay_due = max($payment->pay_due, 0); // Ensure pay_due is never negative
        $payment->save();

        $purchase = Purchase::where('purchase_id', $request->purchase_id)->first();

        if ($purchase) {
            // Update the purchase status based on the payment status
            if ($payment->pay_due == 0) {
                // Fully paid
                $purchase->purchase_status = 'true';
            } elseif ($payment->pay_due > 0 && $payment->pay_due < $payment->purchase_total) {
                // Partially paid
                $purchase->purchase_status = 'pending';
            }

            // Save the updated purchase status
            $purchase->save();
        }

        // Redirect back or to another page with success message
        return redirect()->back()->with('success', 'Payment processed successfully');
        // Redirect back or to another page with success message

    }

    public function getPaymentsByPurchaseId($purchase_id)
    {
        // Fetch purchase details (including supplier details directly from the purchase_info table)
        $purchase = DB::table('purchase')
            ->select(
                'supplier_name', // Assuming these fields exist directly in the purchase_info table
                'branch',
                'purchase_date',
                'transaction_id',
                'total',
                'purchase_status'
            )
            ->where('purchase_id', $purchase_id)
            ->first();

        // Fetch payment details
        $payments = DB::table('payment_info')
            ->where('purchase_id', $purchase_id)
            ->orderBy('payment_id', 'desc') // Order by latest payment
            ->get();

        // Fetch products and their quantities directly from the purchase_products table
        $products = DB::table('product_purchases')
        ->join('product', 'product_purchases.product_id', '=', 'product.product_id')
        ->select('product.product_name as product_name', 'product_purchases.quantity')
        ->where('product_purchases.purchase_id', $purchase_id)
        ->get();

        return view('purchase.details', compact('purchase', 'payments', 'products', 'purchase_id'));
    }


}

