<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\sell;
use App\Models\Purchase;
use App\Models\Transfer;

use App\Models\Setting;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

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

    public function nav()
    {
        $setting = Setting::findOrFail(1);
        return view('layouts.nav',compact('setting'));
    }

}
