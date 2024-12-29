@extends('layouts.admin.app')

@section('content')
<div class="container mt-5">
    <h1>Edit Branch</h1>

    <form action="{{url('/update',$data->id)}}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="branch_name" class="form-label">Branch Name</label>
            <input type="text" class="form-control" name="branch_name" value="{{ $branch->branch_name }}" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" name="address" value="{{ $branch->address }}" required>
        </div>
        <div class="mb-3">
            <label for="incharge" class="form-label">Incharge</label>
            <input type="text" class="form-control" name="incharge" value="{{ $branch->incharge }}">
        </div>
        <div class="mb-3">
            <label for="contact_no" class="form-label">Contact No</label>
            <input type="text" class="form-control" name="contact_no" value="{{ $branch->contact_no }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Branch</button>
    </form>
</div>
@endsection
