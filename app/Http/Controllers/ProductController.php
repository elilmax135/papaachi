<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Box;
use App\Models\BoxColor;
use App\Models\BoxType;
use App\Models\Flower;
use App\Models\Branch;
use App\Models\FlowerColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    public function index()
    {

        // Fetch paginated data directly from the box_info table


        // Pass data to the view
        $branches = Branch::all(); // If branches are needed elsewhere
        $bcolor = BoxColor::all();
        $boxtype = BoxType::all();
        $fcolor = FlowerColor::all();


        return view('Product.index', compact(  'branches','bcolor','boxtype','fcolor'));
      }


      //newboxtype
      public function newboxcolor()
      {

          // Pass data to the view

          $bcolor = BoxColor::all();



          return view('Product.addnewboxcolor', compact('bcolor'));
        }

        public function newboxtype()
        {

            // Pass data to the view

            $boxtype  = BoxType::all();



            return view('Product.addnewboxtype', compact('boxtype'));
          }

      public function newflowercolor()
      {

          // Pass data to the view

          $fcolor = FlowerColor::all();



          return view('Product.addnewflowercolor', compact('fcolor'));
        }



      public function list()
      {



        $CombinedProducts = DB::table('product')
    // Join with flower_info only if available
    ->leftJoin('flower_info', 'product.color_id', '=', 'flower_info.fw_color_id')
    ->leftJoin('flower_color', 'flower_info.fw_color_id', '=', 'flower_color.flower_color_id')

    // Join with box_info only if flower_info is not available
    ->leftJoin('box_info', 'product.product_id', '=', 'box_info.box_unique_id')
    ->leftJoin('box_color', 'box_info.bx_color_id', '=', 'box_color.box_color_id')
    ->leftJoin('box_type', 'box_info.bx_type_id', '=', 'box_type.box_type_id')

    ->select(
        'product.*',
        'box_color.box_color_name as color_name',

        'box_type.box_type_name',
        'flower_color.flower_color_name as flower_color_name'
  )
    ->distinct() // Make sure the query selects distinct products
    ->orderBy('product.created_at', 'desc')
    ->get();


        $btype=BoxType::all();
        $fcolor = FlowerColor::all();
        $bcolor = BoxColor::all();

        // Pass data to the view
        return view('Product.list', compact('CombinedProducts','btype','fcolor','bcolor'));
     }







    public function boxstore(Request $request)
    {
        // Validate the incoming data
        $data= new Box();

        $image = $request->box_image ?? null;

        if ($image) {
            // Process and save the image
            $imagename = time() . '.' . $image->getClientOriginalExtension();
            $request->box_image->move(public_path('boxImage'), $imagename);
            $data->box_image = $imagename; // Save the image name
        } else {
            // Set to null if no image is provided
            $data->box_image = null;
        }

         $data->box_name= $request->box_name;

         $data->bx_color_id = $request->box_color; // Map box_color to bx_color_id
         $data->bx_type_id = $request->box_type;   // Map box_type to bx_type_id
         $data->price_purchase = $request->box_price_purchase;
         $data->price_selling = $request->box_price_selling;
         $data->save();


         return redirect()->back()->with('success', 'Box added successfully!');;

    }

    public function flowerstore(Request $request)
    {
        // Validate the incoming data
        $data= new Flower();

        $image = $request->flower_image ?? null;

        if ($image) {
            // Process and save the image
            $imagename = time() . '.' . $image->getClientOriginalExtension();
            $request->flower_image->move(public_path('flowerImage'), $imagename);
            $data->flower_image = $imagename; // Save the image name
        } else {
            // Set to null if no image is provided
            $data->flower_image = null;
        }
         $data->flower_name= $request->flower_name;
         $data->fw_color_id = $request->f_color_id; // Map flower_color to fw_color_id
         $data->price_purchase= $request->flower_price_purchase;
         $data->price_selling = $request->flower_price_selling;
         $data->save();
         return redirect()->back()->with('success', 'Flower Ring added successfully!');;

    }
    public function proupdate(Request $request, $id)
    {
        $product = Product::findOrFail($id);

          // Validate the request data
          $validatedData = $request->validate([
            'product_name' => 'string|max:255',
            'product_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_type' => 'string|in:box,flower',
            'product_boxtype_id' => 'string|max:255',
            'color_id' => 'string|max:255',
            'price_purchase' => 'numeric|min:0',
            'price_selling' => 'numeric|min:0',
        ]);

        // Update only fields that are provided in the request
        if ($request->has('product_name')) {
            $product->product_name = $validatedData['product_name'];
        }

        if ($request->hasFile('product_image')) {


            if($request->product_type==='box')
            {
                $imageName = time() . '.' . $request->product_image->getClientOriginalExtension();
                $request->product_image->move('boxImage', $imageName);

                $product->product_image = $imageName;

            }
            else if($request->product_type==='flower'){
                $imageName = time() . '.' . $request->product_image->getClientOriginalExtension();
                $request->product_image->move('flowerImage', $imageName);

                $product->product_image = $imageName;
            }
            // Handle image upload

        }

        if ($request->has('product_type')) {
            $product->product_type = $validatedData['product_type'];
        }

        if ($request->has('product_boxtype_id')) {
            $product->product_boxtype_id = $validatedData['product_boxtype_id'];
        }

        if ($request->has('color_id')) {
            $product->color_id = $validatedData['color_id'];
        }

        if ($request->has('price_purchase')) {
            $product->price_purchase = $validatedData['price_purchase'];
        }

        if ($request->has('price_selling')) {
            $product->price_selling = $validatedData['price_selling'];
        }
        // Save updated product
        $product->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Product updated successfully!');
    }
    public function destroyproduct($id)
    {
        $Product = Product::findOrFail($id);
        $Product->delete();

        return redirect()->back()->with('success', 'Product destroy successfully!');
    }


}
