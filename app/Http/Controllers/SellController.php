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

        return view('sell.index', compact('productbox', 'productflower'));
    }
}
