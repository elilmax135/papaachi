<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\service;
use App\Models\sell;
class ServiceController extends Controller
{
public function index(){

    return view('product.addmore');
}
public function service(){

    $services=service::all();
    return view('product.service',compact('services'));
}


public function addservice(Request $request)
{
    // Validate the incoming data
    $request->validate([
        'sname' => 'required|string|max:255',
    ]);

    // Create a new Boxtype entry
    $service = new service();
    $service->service_name	= $request->sname;
    $service->save();

    // Redirect back to the page with a success message
    return redirect()->back();
}


public function serviceedit($id)
{
// Find the box color by its ID
$service = service::findOrFail($id);

// Return a view with the box color data (you will also pass the data to the modal here)
return response()->json($service);  // Return the color in JSON to populate the modal form
 }

 public function serviceupdate($id,Request $request){

    $service = service::find($id);




    $service->service_name = $request->sname;
    $service->save();
     return redirect()->back();
}

public function servicedestroy($id)
{
    $service = service::findOrFail($id);
    $service->delete();

    return redirect()->back();
}



}
