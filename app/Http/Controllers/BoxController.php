<?php

namespace App\Http\Controllers;
use App\Models\BoxType;
use App\Models\BoxColor;
use App\Models\FlowerColor;
use Illuminate\Database\Events\DatabaseRefreshed;
use Illuminate\Http\Request;

class BoxController extends Controller
{

    public function boxcolor(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'box_color' => 'required|string|max:255',
        ]);

        // Create a new BoxColor entry
        $boxColor = new BoxColor();

        $boxColor->box_color_name = $request->box_color;
        $boxColor->save();


        // Redirect back to the page with a success message
        return redirect()->back();
    }


    public function bcoloredit($id)
    {
    // Find the box color by its ID
    $bcolor = BoxColor::findOrFail($id);

    // Return a view with the box color data (you will also pass the data to the modal here)
    return response()->json($bcolor);  // Return the color in JSON to populate the modal form
     }

     public function bcolorupdate($id,Request $request){

        $bcolor = BoxColor::find($id);




        $bcolor->box_color_name = $request->box_color;
        $bcolor->save();
         return redirect()->back();
    }

    public function bcolordestroy($id)
    {
        $bcolor = BoxColor::findOrFail($id);
        $bcolor->delete();

        return redirect()->back();
    }




    public function boxtype(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'btype' => 'required|string|max:255',
        ]);

        // Create a new Boxtype entry
        $boxType = new BoxType();
        $boxType->box_type_name	= $request->btype;
        $boxType->save();

        // Redirect back to the page with a success message
        return redirect()->back();
    }


    public function btypeedit($id)
    {
    // Find the box color by its ID
    $boxtype = BoxType::findOrFail($id);

    // Return a view with the box color data (you will also pass the data to the modal here)
    return redirect()->back();  // Return the color in JSON to populate the modal form
     }

     public function btypeupdate($id,Request $request){

        $boxtype = BoxType::find($id);




        $boxtype->box_type_name = $request->btype;
        $boxtype->save();
         return redirect()->back();
    }

    public function btypedestroy($id)
    {
        $boxtype = BoxType::findOrFail($id);
        $boxtype->delete();

        return redirect()->back();
    }








    public function flowercolor(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'fname' => 'required|string|max:255',
        ]);

        // Create a new FlowerColor entry
        $FlowerColor = new FlowerColor();
        if($FlowerColor){
        $FlowerColor->flower_color_name = $request->fname;
        $FlowerColor->save();
        }

        // Redirect back to the page with a success message
        return redirect()->back();
    }


    public function fcoloredit($id)
    {
    // Find the flower color by its ID
    $fcolor = FlowerColor::findOrFail($id);

    // Return a view with the flower color data (you will also pass the data to the modal here)
    return redirect()->back(); // Return the color in JSON to populate the modal form
     }

     public function fcolorupdate($id,Request $request){

        $fcolor = FlowerColor::find($id);




        $fcolor->flower_color_name = $request->fname;
        $fcolor->save();
         return redirect()->back();
    }

    public function fcolordestroy($id)
    {
        $fcolor = FlowerColor::find($id);
        $fcolor->delete();

        return redirect()->back();
    }

}
