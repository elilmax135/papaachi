<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;

use App\Models\Product;
use App\Models\salary;
use App\Models\sell;
use App\Models\sellProduct;
use App\Models\sellPayment;
use App\Models\Branch;
use App\Models\Location;
use App\Models\Staff;
use App\Models\Stock;
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

            $branch=branch::all();
            $locate=location::all();


           $staff=staff::all();




        return view('sell.index', compact('productbox', 'productflower','lastRecord','branch','staff'));
    }
    public function sell_info(Request $request){
   // Handle the doctor confirmation file upload


   // Save the sell details
   $sell = new Sell();
   $image=$request->doctor_confirmation;
   if ($request->hasFile('doctor_confirmation') && $request->file('doctor_confirmation')->isValid()) {
    $image = $request->file('doctor_confirmation');
    $imagename = time() . '.' . $image->getClientOriginalExtension(); // Generate image name
    $image->move('doctorImage', $imagename); // Move the image to the folder

    $sell->doctor_confirm = $imagename; // Save the image name in the database
} else {
    $sell->doctor_confirm = null; // If no image is uploaded, set it to null
}
   $sell->customer_name = $request->customer_name;
   $sell->customer_mobile = $request->customer_mobile;
   $sell->sell_date = $request->sell_date;
   $sell->transaction_id = $request->transaction_id;
   $sell->transport_mode = $request->transport_mode;
   $sell->branch_id = $request->s_id;
   $sell->customer_address = $request->customer_address;
   $sell->emapoming_date = $request->empaming_date;
 // Amount fields

$sell->empoming_amount = $request->empaming_amount ?? 0;
$sell->panthal_amount = $request->panthal_amount ?? 0;
$sell->lift_amount = $request->lift_amount ?? 0;
$sell->band_amount = $request->band_amount ?? 0;
$sell->melam_amount = $request->instrument_amount ?? 0;
$sell->transport_amount = $request->transport_amount ?? 0;
$sell->total = $request->total; // Set the calculated total amount

$sell->save();



    // Validate and decode the products JSON
    $validatedData = $request->validate([
        'products' => 'required|json', // Validate JSON structure
    ]);

    $products = json_decode($request->products);

   $sell->products = json_encode($products); // Storing the products as JSON
   // Save the products associated with the sell
   foreach ($products as $product) {
    $branchFrom = $request->s_id;

    $productId  = $product->product_id;
    $quantity   = $product->quantity;
    $price  = $product->price;

    // Retrieve and update stock for the transferring branch
    $stockFrom = Stock::where('product_id', $product->product_id)
        ->where('branch_id', $branchFrom)
        ->first();


    if (!$stockFrom) {
        throw new \Exception("Insufficient stock for product ID: $productId at branch ID: $branchFrom.");
    }else{

    $stockFrom->total_quantity -= $quantity;
    $stockFrom->save();
    }

// Store the product in the sell_product table
       $sellProduct = new SellProduct();
       $sellProduct->sell_id = $sell->id;
       $sellProduct->product_id = $product->product_id;
       $sellProduct->quantity = $product->quantity;

       $sellProduct->selling_price = $price;
       $sellProduct->subtotal = $quantity * ($product->price ?? 0);

       $sellProduct->save();
   }
   if ($request->person_name1 && $request->amount1) {
   Salary::create([
        'staff_id' => $request->person_name1,
        'payment' => $request->amount1,
        'remarks' => $request->remarks1,
        'sells_id' => $sell->id,  // Store the sale ID for relation
    ]);
}

// 3. Store Salary Information for Person 2 (if available)
if ($request->person_name2 && $request->amount2) {
    Salary::create([
        'staff_id' => $request->person_name2,
        'payment' => $request->amount2,
        'remarks' => $request->remarks2,
        'sells_id' => $sell->id,  // Store the sale ID for relation
    ]);
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
        'latest_payment', // Alias for subquery
        function ($join) {
            $join->on('sells.id', '=', 'latest_payment.sell_id');
        }
    )
    ->select(
        'sells.id as sell_id',
        'sells.sell_date',
        'sells.total', 'sells.*',
        'sells.customer_name',
        'latest_payment.sell_pay_id',
        'latest_payment.payment_date AS last_payment_date',
        'latest_payment.pay_amount AS last_pay_amount',
        'latest_payment.pay_due AS last_pay_due'
    )
    ->orderBy('sells.id', 'desc')
    ->get()
    ->groupBy('sell_id'); // Group by sale ID




    $salaries = DB::table('salary')
    ->join('sells', 'salary.sells_id', '=', 'sells.id')
    ->leftJoin('staffs', 'salary.staff_id', '=', 'staffs.id')
    ->select(
        'salary.sells_id as sell_id', // Link salary to sell
        'staffs.id as staff_id',
        'staffs.full_name',
        'salary.id',
        'salary.payment AS salary_amount',
        'salary.payment_date AS salary_payment_date',
        'salary.salary_status',
        'salary.due AS salary_due',
        'salary.paid AS salary_paid'
    )
    ->orderBy('salary.sells_id', 'desc')
    ->get()
    ->groupBy('sell_id'); // Group salaries by sale ID

return view('sell.list', compact('sell','salaries'));
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



public function paySalary(Request $request, $first_id, $second_id)
{
    // Validate input
    $request->validate([
        'first_person_salary_payment_amount' => 'required|numeric|min:0',
        'second_person_salary_payment_amount' => 'required|numeric|min:0',
        'payment_date1' => 'required|date',
        'payment_date2' => 'required|date',
    ]);

    // Retrieve salary records by ID
    $firstSalary = Salary::findOrFail($first_id);
    $secondSalary = Salary::findOrFail($second_id);

    // Get `pay1` and `pay2` from user input (hidden input fields in form)
    $pay1 = $request->input('pay1', $firstSalary->due); // Default to current due if not provided
    $pay2 = $request->input('pay2', $secondSalary->due);

    // Get input payment amounts
    $firstPaymentAmount = $request->input('first_person_salary_payment_amount', 0);
    $secondPaymentAmount = $request->input('second_person_salary_payment_amount', 0);

    $firstsalarydate = $request->input('payment_date1', null);
    $secondsalarydate = $request->input('payment_date2', null);

    $firstSalary->payment_date = $firstsalarydate;
    $secondSalary->payment_date = $secondsalarydate;

    $firstSalary->paid += $firstPaymentAmount;
    $secondSalary->paid += $secondPaymentAmount;


    // Save updates
    $firstSalary->save();
    $secondSalary->save();

    // Redirect back with success message
    return redirect()->back()->with('success', 'Salaries updated successfully!');
}

public function paySalaries(Request $request)
{
    $sellId = $request->input('sell_id'); // Get sale ID
    $payAmounts = $request->input('amount'); // Array of amounts
    $payDates = $request->input('payment_date'); // Array of dates

    if (!$payAmounts) {
        return back()->with('error', 'No payments were entered.');
    }

    foreach ($payAmounts as $salaryId => $amount) {
        if ($amount > 0) {
            // Find the salary associated with sells_id and salary_id
            $salary = Salary::where('sells_id', $sellId)->where('id', $salaryId)->first();

            if ($salary) {
                // Update existing salary payment
                $salary->paid += $amount;

                $salary->due = $salary->payment -  $salary->paid ;
                if ($salary->paid == 0) {
                    $salary->salary_status = 'fail';
                } elseif ($salary->paid > 0 && $salary->paid < $salary->salary_amount) {
                    $salary->salary_status = 'Partial';
                } elseif ($salary->paid == $salary->salary_amount) {
                    $salary->salary_status = 'Paid';
                }

                $salary->payment_date = $payDates[$salaryId] ?? now(); // Store payment date
                $salary->save();

                // Ensure staff_id is included in the payment record

            }
        }
    }

    return redirect()->back()->with('success', 'Salaries paid successfully.');
}


//deleteSells
public function deleteSells($sale_id)
{

    $sells =Sell::find($sale_id);
    if (!$sells) {
        return response()->json(['error' => 'Transfer not found'], 404);
    }

    // Get all purchase products
    $sellProducts = sellProduct::where('sell_id', $sale_id)->get();

    // Restore stock quantity before deleting the purchase products
    foreach ($sellProducts as $sellProd) {

        $stockFrom = Stock::where('product_id', $sellProd->product_id)->where('branch_id', $sells->branch_id)->first();

        if ($stockFrom) {
            $stockFrom->total_quantity += $sellProd->quantity;
            $stockFrom->save();
        }
    }

    // Delete the purchase products first
    sellProduct::where('sell_id', $sale_id)->delete();

    // Delete the purchase record
    sell::where('id', $sale_id)->delete();

    salary::where('sells_id', $sale_id)->delete();

    return redirect()->back()->with('success', 'sale and associated products  deleted successfully!');
}


}
