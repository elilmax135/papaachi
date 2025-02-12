<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SellProduct;
use App\Models\Branch;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\ProductPurchase;
use App\Models\sell;
use App\Models\staff_salary;
use App\Models\Transfer;

class DashboardController extends Controller
{
    public function index(){


        $sales = Sell::all(); // Or use your query for sales
        $totalSum = $sales->sum('total');


        $purchase = Purchase::all(); // Or use your query for sales
        $totalpurSum = $purchase->sum('total');


        $transfer = Transfer::all(); // Or use your query for sales
        $totaltransSum = $transfer->sum('total');

        $totalCount = $sales->count();


        $salesR = Sell::orderBy('created_at', 'desc')->take(10)->get();


        return view('dash.index',compact('totalSum','totalpurSum','totaltransSum','totalCount','salesR'));
    }
}
