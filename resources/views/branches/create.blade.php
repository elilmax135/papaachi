@extends('layouts.admin.app')

@section('content')
<div class="container mt-5">
    <h1>Add New Branch</h1>

    <form action="{{ route('branches.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="branch_name" class="form-label">Branch Name</label>
            <input type="text" class="form-control" name="branch_name" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" name="address" required>
        </div>
        <div class="mb-3">
            <label for="incharge" class="form-label">Incharge</label>
            <input type="text" class="form-control" name="incharge">
        </div>
        <div class="mb-3">
            <label for="contact_no" class="form-label">Contact No</label>
            <input type="text" class="form-control" name="contact_no" required>
        </div>

        <button type="submit" class="btn btn-primary">Create Branch</button>
    </form>
</div>
@endsection
