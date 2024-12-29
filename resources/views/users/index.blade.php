@extends('layouts.admin.app')

@section('content')
<div class="row">
    <!-- User creation form -->
    <div class="col-4">
    <div class="card">
            <div class="card-header">
                <div class="float-start">
                {{ isset($user) ? 'Update User' : 'Add New User' }}
                </div>
            </div>
            <div class="card-body">
                <form action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" method="post">
                    @csrf
                    @if(isset($user))
                        @method('PUT')
                    @endif
                    <div class="mb-3 row">
                        <label for="name" class="col-md-4 col-form-label  text-start">Name</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', isset($user) ? $user->name : '') }}">
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="email" class="col-md-4 col-form-label  text-start">Email Address</label>
                        <div class="col-md-6">
                          <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', isset($user) ? $user->email : '') }}">
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="password" class="col-md-4 col-form-label  text-start">Password</label>
                        <div class="col-md-6">
                          <input type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                            @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="password_confirmation" class="col-md-4 col-form-label  text-start">Confirm Password</label>
                        <div class="col-md-6">
                          <input type="password" class="form-control"  name="password_confirmation">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="roles" class="col-md-4 col-form-label  text-start">Roles</label>
                        <div class="col-md-6">
                                <select class="form-control" name="roles[]">

                                @forelse ($roles as $role)
                                    @if ($role !='Super Admin')
                                        <option value="{{ $role }}" {{ in_array($role, $userRoles ?? []) ? 'selected' : '' }}>{{ $role }}</option>
                                    @else
                                        @if (Auth::user()->hasRole('Super Admin'))
                                            <option value="{{ $role }}" {{ in_array($role, $userRoles ?? []) ? 'selected' : '' }}>{{ $role }}</option>
                                        @endif
                                    @endif
                                @empty

                                @endforelse
                                </select>

                            @if ($errors->has('roles'))
                                <span class="text-danger">{{ $errors->first('roles') }}</span>
                            @endif
                        </div>
                    </div>

                    <div style="margin-left:148px">
                            <button type="submit" class="col-md-4 btn btn-sm  btn-primary">{{ isset($user) ? 'Update' : 'Save' }}</button>
                            <button type="reset" class="col-md-4 btn btn-sm btn-danger">Clear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- User list table -->
    <div class="col-8">
    <div class="card">
    <div class="card-header">Manage Users</div>
    <div class="card-body">
        <!-- @can('create-user')
            <a href="{{ route('users.create') }}" class="btn btn-success btn-sm my-2"><i class="bi bi-plus-circle"></i> Add New User</a>
        @endcan -->
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                <th scope="col">S#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Roles</th>
                <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @forelse ($user->getRoleNames() as $role)
                            <span class="badge bg-primary">{{ $role }}</span>
                        @empty
                        @endforelse
                    </td>
                    <td>
                    <form action="{{ route('users.destroy', $user->id) }}" method="post" id="deleteForm_{{ $user->id }}">
                            @csrf
                            @method('DELETE')

                            <a href="{{ route('users.show', $user->id) }}" class="btn btn-warning btn-sm show-user-details"
                                data-user-id="{{ $user->id }}">
                                <i class="bi bi-eye"></i> Show
                            </a>



                            @if (in_array('Super Admin', $user->getRoleNames()->toArray() ?? []) )
                                @if (Auth::user()->hasRole('Super Admin'))
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>
                                @endif
                            @else
                                @can('edit-user')
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>
                                @endcan

                                @can('delete-user')
                                    @if (Auth::user()->id!=$user->id)
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $user->id }}')"><i class="bi bi-trash"></i>Delete </button>

                                    @endif
                                @endcan
                            @endif

                    </form>
                    </td>
                </tr>
                @empty
                    <td colspan="5">
                        <span class="text-danger">
                            <strong>No User Found!</strong>
                        </span>
                    </td>
                @endforelse
            </tbody>
        </table>

        {{ $users->links() }}

    </div>
</div>
    </div>
</div>
@endsection
