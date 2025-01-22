<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

use App\Models\Branch;
use App\Models\Stock;

use App\Models\Transfer;
use App\Models\TransferPayment;
use App\Models\TransferProduct;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\select;

class TransferController extends Controller
{
    public function index()
    {
        $branch = DB::table('branches')->get();
    // Fetch products for the "box" category.
    $productbox = DB::table('stocks')
    ->where('product_type', 'box')
    ->get();

// Fetch products for the "flower" category.
$productflower = DB::table('stocks')
    ->where('product_type', 'flower')
    ->get();

// Log the preloaded product data (optional, for debugging)
\Log::info("Preloaded Box Products:", $productbox->toArray());
\Log::info("Preloaded Flower Products:", $productflower->toArray());

// Pass the branches and preloaded product arrays to the view.
return view('transfer.index', compact('branch', 'productbox', 'productflower'));

    }

    public function getProducts(Request $request)
    {
        // Validate the incoming request parameters.
        $validatedData = $request->validate([
            'branch_id' => 'required|integer',
            'category'  => 'required|string',
        ]);

        // Retrieve the validated parameters.
        $branch_id = $validatedData['branch_id'];
        $category  = $validatedData['category'];

        // Log the received inputs for debugging purposes.
        \Log::info("Fetching products for Branch ID: $branch_id, Category: $category");

        try {
            // Query the database for products that match the category and branch_id.
            $products = DB::table('stocks')
                ->where('product_type', $category)
                ->where('branch_id', $branch_id)
                ->select('product_id', 'product_name', 'total_quantity', 'selling_price')
                ->get();

            // If no products were found, return a 404 JSON response.
            if ($products->isEmpty()) {
                return response()->json(['message' => 'No products found'], 404);
            }

            // Return the products as a JSON array with a 200 status code.
            return response()->json($products, 200);
        } catch (\Exception $e) {
            // Log any exceptions that occur.
            \Log::error("Error fetching products: " . $e->getMessage());

            // Return a 500 error response with a JSON error message.
            return response()->json(['message' => 'Error fetching products'], 500);
        }
    }



    public function store(Request $request)
{
    DB::beginTransaction();
    try {
        // Validate the request at the beginning
        $request->validate([
            'transfer_date'  => 'required|date',
            'f_id'           => 'required|exists:branches,id',
            't_id'           => 'required|exists:branches,id|different:f_id',
            'transaction_id' => 'required|string',
            'total'          => 'required|numeric|min:0',
            'products'       => 'required|json',
        ]);

        // Decode the products JSON string


        // Create the transfer record
        $transfer = new Transfer();
        $transfer->transfer_date = $request->transfer_date;
        $transfer->branch_id2 = $request->f_id;
        $transfer->branch_id = $request->t_id;
        $transfer->transaction_id = $request->transaction_id;
        $transfer->total = $request->total;
        $transfer->save();

        $products = json_decode($request->products);
        if (!is_array($products)) {
            throw new \Exception("Invalid JSON format for products.");
        }

        foreach ($products as $product) {
            $branchFrom = $request->f_id;
            $branchTo   = $request->t_id;
            $productId  = $product->product_id;
            $quantity   = $product->quantity;
            $price  = $product->price;

            // Retrieve and update stock for the transferring branch
            $stockFrom = Stock::where('product_id', $product->product_id)
                ->where('branch_id', $branchFrom)
                ->first();
                $stockTo = Stock::where('product_id', $product->product_id)
                ->where('branch_id', $branchTo)
                ->first();


            if (!$stockFrom) {
                throw new \Exception("Insufficient stock for product ID: $productId at branch ID: $branchFrom.");
            }else{

            $stockFrom->total_quantity -= $quantity;
            $stockFrom->save();
            }



            if (!$stockTo) {
                // Correctly instantiate Stock model
                $stockTo = new Stock();
                $stockTo->product_id = $product->product_id;
                $stockTo->branch_id = $branchTo;
                $stockTo->branch_name = Branch::where('id', $branchTo)->value('branch_name');
                $stockTo->product_name = product::where('product_id', $product->product_id)->value('product_name');
                $stockTo->total_quantity = $quantity;
                $stockTo->selling_price = $price;

                // Check product_id range and assign product_type
                if ($product->product_id >= 500 && $product->product_id <= 6999) {
                    $stockTo->product_type = 'box';  // Assign 'box' if product_id is between 500 and 6999
                } else {
                    $stockTo->product_type = 'flower';  // Assign 'flower' for other product_ids
                }

                $stockTo->save();
            } else {
                $stockTo->total_quantity += $quantity;
                $stockTo->save();
            }





            // Store transfer details in transfer_product table
            TransferProduct::create([
                'transfer_id'    => $transfer->id,
                'product_id'     => $productId,
                'quantity'       => $quantity,
                'purchase_price' => $product->purchase_price ?? 0,
                'selling_price'  => $product->price ?? 0,
                'subtotal'       => $quantity * ($product->price ?? 0),
            ]);
        }

        DB::commit();
return redirect()->back()->with('success', 'Transfer completed successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', $e->getMessage());

    }
}




        private function updateStock($branchId, $productId, $quantity, $operation = 'increase')
        {
            DB::transaction(function () use ($branchId, $productId, $quantity, $operation) {
                Log::info("Updating stock for Branch ID: $branchId, Product ID: $productId, Quantity: $quantity, Operation: $operation");

                // Lock the stock row to prevent race conditions
                $stock = Stock::where([
                    ['branch_id', '=', $branchId],
                    ['product_id', '=', $productId]
                ])->lockForUpdate()->first();

                if ($operation === 'decrease') {
                    if (!$stock || $stock->total_quantity < $quantity) {
                        Log::error("Insufficient stock for Product ID: $productId at Branch ID: $branchId. Available: " . ($stock ? $stock->total_quantity : 0));
                        throw new \Exception("Insufficient stock for product ID: $productId at branch ID: $branchId.");
                    }
                    Log::info("Decreasing stock by $quantity for Product ID: $productId at Branch ID: $branchId");
                    $stock->decrement('total_quantity', $quantity);
                } else {
                    if ($stock) {
                        Log::info("Increasing stock by $quantity for Product ID: $productId at Branch ID: $branchId");
                        $stock->increment('total_quantity', $quantity);
                    } else {
                        Log::info("Creating new stock entry for Product ID: $productId at Branch ID: $branchId");
                        // Fetch branch and product names
                        $branchName = Branch::find($branchId)->branch_name ?? 'Unknown';
                        $productName = Product::find($productId)->product_name ?? 'Unknown';

                        Stock::create([
                            'branch_id'     => $branchId,
                            'product_id'    => $productId,
                            'total_quantity'=> $quantity,
                            'branch_name'   => $branchName,
                            'product_name'  => $productName,
                        ]);
                    }
                }
            });
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



   public function deleteTransfer($transfer_id)
{

 $transfer = Transfer::find($transfer_id);
    if (!$transfer) {
        return response()->json(['error' => 'Transfer not found'], 404);
    }

    $transferProducts = TransferProduct::where('transfer_id', $transfer_id)->get();
    $stockData = []; // Array to store stock details

    foreach ($transferProducts as $transferProduct) {
        $branchTo   = $transfer->branch_id;  // Receiving Branch
        $branchFrom = $transfer->branch_id2; // Sending Branch
        $productId  = $transferProduct->product_id;
        $quantity   = $transferProduct->quantity;

        // Get Stock from Receiving Branch
        $stockTo = Stock::where('product_id', $productId)->where('branch_id', $branchTo)->first();
        $stockFrom = Stock::where('product_id', $productId)->where('branch_id', $branchFrom)->first();

        // Store data before changes
        $stockData[] = [
            'product_id'   => $productId,
            'branch_to'    => $branchTo,
            'stock_to_qty' => $quantity,
            'branch_from'  => $branchFrom,
            'stock_from_qty' => $quantity,
        ];

        // Reverse stock changes
        if ($stockTo) {
            $stockTo->total_quantity -= $quantity;
            $stockTo->save();
        }
        if ($stockFrom) {
            $stockFrom->total_quantity += $quantity;
            $stockFrom->save();
        }
    }

    // Return JSON response with stock details

    return redirect()->back()->with('success', 'Transfer and associated products deleted successfully!');
}
}
