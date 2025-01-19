<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\sell;
class LocationController extends Controller
{



    public function open(){


        $lastRecord = sell::orderBy('id', 'desc')->first();
        return view('sell.setlocate',compact('lastRecord'));
    }



    public function store(Request $request)
    {
        $request->validate([
          // Ensure the sell_id exists in the sells table
            'total_distance' => 'required|numeric|min:0',
        ]);

        // Find the parent `Sell` model
        Location::create([
         'total_distance' => $request->total_distance,
        ]);
        return redirect()->back()->with('success', 'Location saved successfully!');
    }



}
