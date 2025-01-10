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


    public function list(){
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



        return view('purchase.list',compact('productbox','productflower'));
    }
    public function updateProductStock(Request $request)
{
    $productId = $request->input('product_id');
    $quantityChange = $request->input('quantity_change');

    // Find the product
    $product = Product::find($productId);

    if ($product) {
        // Update stock quantity
        $product->stock_quantity += $quantityChange;
        $product->save();

        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false], 400);
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
            'purchase_id' => $purchase->id,
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

        // Log the saved payment


        return redirect()->back()->with('success', 'Payment successfully recorded!');
    }
}

