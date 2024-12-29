@extends('layouts.app')

@section('content')
<div class="row">
    <!-- Permission form starting area -->
    <div class="col-4">
        <div class="card">
            <div class="card-header">Create Permission</div>
                <div class="card-body">
                <form action="{{ route('permissions.store') }}" method="post">
                    @csrf

                    <div class="mb-3 row">
                        <label for="name" class="col-md-4 col-form-label  text-start">Name</label>
                        <div class="col-md-12">
                          <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-12">
                            <input type="submit" class="col-md-3 offset-md-0 btn btn-primary" value="Add User">
                        </div>
                    </div>
                </form>
                </div>
            </div>
    </div>
    <!-- Permission table starting area -->
    <div class="col-8">
    <div class="card">
        <div class="card-header">Manage Permissions</div>
        <div class="card-body">
            @can('create-role')
            @endcan
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                    <th scope="col">S#</th>
                    <th scope="col">Name</th>
                    <th scope="col" style="width: 250px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($permissions as $permission)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $permission->name }}</td>
                        <td>
                            <form action="{{ route('permissions.destroy', $permission->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                @if ($permission->name!='Super Admin')
                                    @can('delete-permission')
                                        @if ($permission->name!=Auth::user()->hasRole($permission->name))
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Do you want to delete this role?');"><i class="bi bi-trash"></i> Delete</button>
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

            {{ $permissions->links() }}

        </div>
    </div> 
    </div>
</div>
@endsection