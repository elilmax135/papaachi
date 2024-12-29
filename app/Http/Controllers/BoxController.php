<?php

namespace App\Http\Controllers;
use App\Models\Box;
use App\Models\Branch;
use Illuminate\Http\Request;

class BoxController extends Controller
{
    public function index(Request $request)
    {     $itemsPerPage = $request->query('itemsPerPage', 5);

        // Fetch paginated data with branch details using Eloquent
        $data_boxes = Box::leftJoin('branches', 'box_info.branch_id', '=', 'branches.id')
            ->select('box_info.*', 'branches.branch_name')
            ->paginate($itemsPerPage);

        // Pass data to the view
        $branches = Branch::all();
        return view('boxes.index', compact('data_boxes', 'itemsPerPage','branches'));
    }


    public function box_store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'box_name' => 'required|string|max:255',
            'box_type' => 'required|string|max:255',
            'price' => 'required|numeric',
            'box_size' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'branch_id' => 'required|exists:branches,id', // Ensure branch exists
            'box_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Create a new Box instance
        $box = new Box();

        // Check if an image is uploaded
        if ($request->hasFile('box_image') && $request->file('box_image')) {
            $box_image = $request->file('box_image'); // Get the uploaded file
            $imagename = time() . '.' . $box_image->getClientOriginalExtension(); // Get file extension and create a unique name

            // Store the image in the 'BoxImage' folder
            $box_image->move(public_path('BoxImage'), $imagename); // Use move() instead of storing directly
            $box->box_image = $imagename; // Save the image name in the database
        }

        // Save other fields to the Box model
        $box->box_name = $request->box_name;
        $box->box_type = $request->box_type;
        $box->price = $request->price;
        $box->size = $request->box_size;
        $box->color = $request->color;
        $box->quantity = $request->quantity;
        $box->branch_id = $request->branch_id;

        try {
            // Save the Box instance to the database
            $box->save();
            return redirect()->back()->with('success', 'Box added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Error saving box: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $data_boxes = Box::findOrFail($id);
        return view('boxes.index', compact('data_boxes'));
    }
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'box_name' => 'required|string|max:255',
            'box_type' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'box_size' => 'required|string|max:255',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'branch_id' => 'required|exists:branches,id',
            'imageedit' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Image validation
        ]);

        // Find the box by ID
        $box = Box::findOrFail($id);

        // Update box attributes
        $box->box_name = $request->box_name;
        $box->box_type = $request->box_type;
        $box->color = $request->color;
        $box->size = $request->box_size;
        $box->price = $request->price;
        $box->quantity = $request->quantity;
        $box->branch_id = $request->branch_id;

        // Handle the image upload
        if ($request->hasFile('imageedit')) {
            $image = $request->file('imageedit');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('boxImage'), $imageName);

            // Delete the old image if exists
            if ($box->box_image && file_exists(public_path('boxImage/' . $box->box_image))) {
                unlink(public_path('boxImage/' . $box->box_image));
            }

            // Save the new image name
            $box->box_image = $imageName;
        }

        // Save the updated box
        $box->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Box updated successfully.');
    }
    public function destroy($id)
    {
        $box = Box::findOrFail($id);
        $box->delete();

        return redirect()->back();
    }
}
