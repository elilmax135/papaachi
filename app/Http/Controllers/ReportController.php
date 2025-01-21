<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function Purchaseindex()
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
        ->orderBy('purchase_id', 'desc')
        ->get()
        ->groupBy('purchase_id');






    return view('Report.PurchaseIndex', compact('purchases'));
    }

    public function Sellindex(){
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
        return view('Report.SellIndex',compact('sell'));
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
        ->select('product.product_name as product_name', 'product_purchases.quantity','product_purchases.subtotal','product_purchases.purchase_price')
        ->where('product_purchases.purchase_id', $purchase_id)
        ->get();

        return view('Report.Redetails', compact('purchase', 'payments', 'products', 'purchase_id'));
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

    return view('Report.Selldetails', compact('sale', 'payments', 'products', 'sale_id'));
}

public function Transferindex(){

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

return view('Report.TransferIndex', compact('transfers'));
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
    return view('Report.Transdetails', compact('transfer', 'payments', 'products', 'transfer_id'));

      }

}
