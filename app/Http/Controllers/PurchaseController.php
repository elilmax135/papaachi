<?php

namespace App\Http\Controllers;
use App\Models\customer;
use App\Models\Product;
use App\Models\Branch;
use App\Models\Stock;
use App\Models\Purchase;
use App\Models\AvailableProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(){
        $br=Branch::all();
         $product=product::all();
         $lastRecord = customer::orderBy('id', 'desc')->first();

         $customerId = $lastRecord->id ?? null; // Ensure 'id' is extracted

         if (!$customerId || !is_numeric($customerId)) {
            // Handle gracefully instead of throwing an exception
            $stockproduct = collect(); // Empty collection for no records
        } else {
            // Fetch stockproduct based on customerId
            $stockproduct = DB::table('stock')
                ->join('product', 'stock.product_id', '=', 'product.product_id')
                ->join('customer', 'stock.customer_id', '=', 'customer.id')
                ->select('product.*', 'stock.quantity','stock.id')
                ->where('stock.customer_id', '=', $customerId)
                ->get();
        }
        return view('purchase.index',compact('product','br','stockproduct'));
    }





    public function updateStock(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        // Validate quantity

        $lastRecord = customer::orderBy('id', 'desc')->first();

        $customerId = $lastRecord->id ?? null; // Ensure 'id' is extracted

        if (!$customerId || !is_numeric($customerId)) {
            throw new \Exception("Invalid customer ID provided.");
        }

        // Add to cart logic



        $stock = Stock::updateOrCreate(
    [
        'customer_id' => $customerId,  // Corrected the syntax here
        'product_id' => $product->product_id,
    ],
    [
        'quantity' => $request->quantity,
    ]
      );
        // Reduce product stock
        $product->stock_quantity += $request->quantity;
        $product->save();


        return redirect()->back();

    }
    public function customer(Request $request){

        $request->validate([
            'name' => 'required|string|max:255',
        ]);


        $customer = Customer::create([
            'name' => $request->name,
        ]);

        session(['customer_name' => $request->name]);

        return redirect()->back();
    }


    public function logout(){

         session()->forget('customer_name'); // This will remove the session data
    return redirect()->back()->with('success', 'You have logged out successfully.');
    }


    public function cartremove($id){
        $rm=Stock::find($id);
        $rm->delete();
        return redirect()->back();
    }

}




