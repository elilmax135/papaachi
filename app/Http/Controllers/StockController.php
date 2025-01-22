<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\ProductPurchase;
use App\Models\SellProduct;
use App\Models\Branch;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\sell;
use App\Models\staff_salary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class StockController extends Controller
{
    public function stock()
    {
        // Fetch the stock data from purchases

        $custock=stock::all();
        return view('stock.index', compact('custock'));
    }

    /**
     * Update or create stock entry for a product at a branch.
     */


}
