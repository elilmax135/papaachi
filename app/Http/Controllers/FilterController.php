<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class FilterController extends Controller
{

    public function filterData(Request $request)
    {
        $startDate = $request->input('start_date', null);
        $endDate = $request->input('end_date', null);
        $filterType = $request->input('filter_type'); // purchase, sell, transfer
        $status = $request->input('status'); // Status filter (optional)
        $branchName = $request->input('branch_name'); // Branch name filter (optional)
        $data = [];
        $total = 0;

        if ($filterType === 'purchase' && $status === 'fail') {
            $status = 'failed'; // Change 'fail' to 'Failed' for purchase
        }

        // Ensure the start_date and end_date are valid and format them
        if ($startDate) {
            $startDate = date('Y-m-d', strtotime($startDate));
        }
        if ($endDate) {
            $endDate = date('Y-m-d', strtotime($endDate));
        }

        switch ($filterType) {
            case 'purchase':
                $query = DB::table('purchase')
                    ->join('product_purchases', 'purchase.purchase_id', '=', 'product_purchases.purchase_id')
                    ->join('product', 'product.product_id', '=', 'product_purchases.product_id') // Join the product table
                    ->leftJoin('branches', 'purchase.branch', '=', 'branches.id'); // Join the branches table

                // Apply date filters
                if ($startDate && $endDate) {
                    $query->whereBetween('purchase.created_at', [$startDate, $endDate]);
                }

                // Apply status filter if provided
                if ($status) {
                    $query->where('purchase.purchase_status', $status);
                }

                // Apply branch name filter if provided
                if ($branchName) {
                    $query->where('branches.branch_name', 'LIKE', '%' . $branchName . '%');
                }

                // Get the data
                $data = $query->select(
                    'purchase.purchase_id',
                    'supplier_name',
                    'purchase_date',
                    'total',
                    'purchase_status',
                    'product_purchases.product_id',
                    'product_purchases.quantity',
                    'product.product_name',  // Select the product name
                    'branches.branch_name' // Include branch name
                )
                ->get();

                // Calculate total purchase amount
                $total = DB::table('purchase')
                    ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                        return $query->whereBetween('created_at', [$startDate, $endDate]);
                    })
                    ->when($status, function ($query) use ($status) {
                        return $query->where('purchase_status', $status);
                    })
                    ->sum('total');
                break;

            case 'sell':
                $query = DB::table('sell_product')
                    ->join('sells', 'sell_product.sell_id', '=', 'sells.id')
                    ->join('product', 'product.product_id', '=', 'sell_product.product_id') // Join the product table
                    ->join('branches', 'branches.id', '=', 'sells.branch_id');
                // Apply date filters
                if ($startDate && $endDate) {
                    $query->whereBetween('sells.created_at', [$startDate, $endDate]);
                }

                // Apply status filter if provided
                if ($status) {
                    $query->where('sells.sell_status', $status);
                }

                // Get the data
                $data = $query->select(
                    'sells.id as sell_id',
                    'sells.customer_name',
                    'sells.sell_date',
                    'sells.total',
                    'sells.branch_id',
                    'sells.sell_status',
                    'sell_product.product_id',
                    'sell_product.quantity',
                    'branches.branch_name',
                    'product.product_name'  // Select the product name
                )
                ->get();

                // Calculate total sell amount
                $total = DB::table('sells')
                    ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                        return $query->whereBetween('created_at', [$startDate, $endDate]);
                    })
                    ->when($status, function ($query) use ($status) {
                        return $query->where('sell_status', $status);
                    })
                    ->sum('total');
                break;

            case 'transfer':
                $query = DB::table('transfer')
    ->join('transfer_product', 'transfer.id', '=', 'transfer_product.transfer_id')
    ->join('product', 'product.product_id', '=', 'transfer_product.product_id')
    ->leftJoin('transfer_payment', 'transfer.id', '=', 'transfer_payment.transfer_id') // Changed to leftJoin
    ->join('branches', 'branches.id', '=', 'transfer.branch_id')
    ->leftJoin('branches as b2', 'transfer.branch_id2', '=', 'b2.id');


                // Apply date filters
                if ($startDate && $endDate) {
                    $query->whereBetween(DB::raw('DATE(transfer.created_at)'), [$startDate, $endDate]);

                }

                // Apply status filter if provided
                if ($status) {
                    $query->where('transfer.transfer_status', $status);
                }

                // Apply branch name filter if provided
                if ($branchName) {
                    $query->where('branches.branch_name', 'LIKE', '%' . $branchName . '%');
                }

                // Get the data
                $data = $query->select(
                    'transfer.id as transfer_id',
                    'transfer.transaction_id',
                    'transfer.branch_id',
                    'transfer.total',
                    'transfer.transfer_status',
                    'transfer_payment.payment_method',
                    'product.product_name',
                    'branches.branch_name',
                    'b2.branch_name as branch_name_2'
                )->get();



                // Calculate total transfer amount
                $total = DB::table('transfer')
                ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    return $query->whereBetween('created_at', [$startDate, $endDate]);
                })

                ->when($status, function ($query) use ($status) {
                    return $query->where('transfer_status', $status);
                })
                ->sum('transfer.total'); //

                break;


            default:
                return back()->with('error', 'Invalid filter type selected.');
        }

        return view('Report.filter', compact('data', 'filterType', 'startDate', 'endDate', 'status', 'branchName', 'total'));
    }
}
