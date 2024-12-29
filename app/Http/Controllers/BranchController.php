<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    // Display a listing of the branches
    public function index()
    {
        $data_branch = Branch::all();
        return view('branches.index', compact('data_branch'));
    }

    // Show the form for creating a new branch
    public function create()
    {
        return view('branches.create');
    }

    // Store a newly created branch in the database
    public function store(Request $request)
    {
        $request->validate([
            'branch_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'incharge' => 'nullable|string|max:255',
            'contact_no' => 'required|string|max:15',
        ]);

        Branch::create([
            'branch_name' => $request->branch_name,
            'address' => $request->address,
            'incharge' => $request->incharge,
            'contact_no' => $request->contact_no,
        ]);

        return redirect()->back();
    }

    // Show the form for editing a specific branch
    public function edit($id)
    {
        $branch = Branch::findOrFail($id);
        return view('branches.edit', compact('branch'));
    }

    // Update the specified branch in the database
    public function update(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);

        $request->validate([
            'branch_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'incharge' => 'nullable|string|max:255',
            'contact_no' => 'required|string|max:15',
        ]);

        $branch->update([
            'branch_name' => $request->branch_name,
            'address' => $request->address,
            'incharge' => $request->incharge,
            'contact_no' => $request->contact_no,
        ]);

         return redirect()->back();
    }

    // Remove the specified branch from the database
    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();

        return redirect()->back();
    }
}
