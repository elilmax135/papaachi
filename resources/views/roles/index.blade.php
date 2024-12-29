@extends('layouts.admin.app')

@section('content')
<div class="row">
    <!-- Role create form -->
    <div class="col-4">
    <div class="card">
            <div class="card-header">
                <div class="float-start">
                {{ isset($role) ? 'Update Role Permissions' : 'Add Role Permissions' }}
                </div>
            </div>
            <div class="card-body">
                <form action="{{ isset($role) ? route('roles.update', $role->id) : route('roles.store') }}" method="post">
                    @csrf
                    @if(isset($role))
                        @method('PUT')
                    @endif
                    <div class="mb-3 row">
                        <label for="name" class="col-md-4 col-form-label text-start">Name</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', isset($role) ? $role->name : '') }}">
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="permissions" class="col-md-4 col-form-label text-start">Permissions</label>
                        <div class="col-md-6" style="margin-left:20px">
                                @forelse ($permissions as $permission)
                                    <input id="permissions" name="permissions[]" class="form-check-input" type="checkbox" value="{{ $permission->id }}" {{ in_array($permission->id, $rolePermissions ?? []) ? 'checked' : '' }}>
                                    <label style="margin-left:8px;" class="form-check-label" for="flexCheckChecked">{{ $permission->display_name }}</label><br>
                                @empty

                                @endforelse
                            @if ($errors->has('permissions'))
                                <span class="text-danger">{{ $errors->first('permissions') }}</span>
                            @endif
                        </div>
                    </div>

                    <div style="margin-left:148px">
                            <button type="submit" class="col-4 btn btn-sm btn-primary">{{ isset($role) ? 'Update' : 'Save' }}</button>
                            <button type="reset" class="col-4 btn btn-sm btn-danger">Clear</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <!-- Roles list table -->
    <div class="col-8">
    <div class="card">
    <div class="card-header">Manage Roles</div>
    <div class="card-body">
        <!-- @can('create-role')
            <a href="{{ route('roles.create') }}" class="btn btn-success btn-sm my-2"><i class="bi bi-plus-circle"></i> Add New Role</a>
        @endcan -->
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                <th scope="col">S#</th>
                <th scope="col">Name</th>
                <th scope="col" style="width: 250px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($roles as $role)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $role->name }}</td>
                    <td>
                    <form id="deleteForm_{{ $role->id }}" action="{{ route('roles.destroy', $role->id) }}" method="post">
                            @csrf

                            @method('DELETE')
                                <a href="{{ route('roles.show', $role->id) }}" class="btn btn-warning btn-sm show-role-details"
                                data-role-id="{{ $role->id }}">
                                <i class="bi bi-eye"></i> Show
                                </a>
                            @if ($role->name!='Super Admin')
                                @can('edit-role')
                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>
                                @endcan

                                @can('delete-role')
                                    @if ($role->name!=Auth::user()->hasRole($role->name))
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDeleteRole('{{ $role->id }}')">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                    @endif
                                @endcan
                            @endif
                        </form>
                    </td>
                </tr>
                @empty
                    <td colspan="3">
                        <span class="text-danger">
                            <strong>No Role Found!</strong>
                        </span>
                    </td>
                @endforelse
            </tbody>
        </table>

        {{ $roles->links() }}

    </div>
</div>
</div>
</div>
@endsection
