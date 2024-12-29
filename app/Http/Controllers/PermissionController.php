<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermissionRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use DB;

class PermissionController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('permission:create-permission|edit-permission|delete-permission', ['only' => ['index','show']]);
       $this->middleware('permission:create-permission', ['only' => ['create','store']]);
       $this->middleware('permission:edit-permission', ['only' => ['edit','update']]);
       $this->middleware('permission:delete-permission', ['only' => ['destroy']]);
    }


    public function index()
    {
        return view('permissions.index', [
            'permissions' => Permission::orderBy('id','DESC')->paginate(5)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request) : RedirectResponse
    {
        Permission::create($request->all());
        return redirect()->route('permissions.index')
                ->withSuccess('New permission is added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $permission = Permission::find($id); 
        $permission->delete();
        return redirect()->route('permissions.index')->withSuccess('Permission is deleted successfully.');
    }
}
