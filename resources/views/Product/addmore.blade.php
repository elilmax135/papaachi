@extends('layouts.admin.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/product_css.css') }}">

<div class="row">
    <div class="col-12">
        <!-- Action Buttons -->
        <div class="d-flex gap-3 justify-content-center">

            <a href="{{ url('/NewBoxColor') }}" class="btn btn-warning btn-lg shadow-sm hover-shadow-lg">
                <i class="fas fa-paint-brush"></i> Add New Box Color
            </a>
            <a href="{{ url('/NewBoxType') }}" class="btn btn-info btn-lg shadow-sm hover-shadow-lg">
                <i class="fas fa-box"></i> Add New Box Type
            </a>
            <a href="{{ url('/NewFlowerColor') }}" class="btn btn-danger btn-lg shadow-sm hover-shadow-lg">
                <i class="fas fa-flower"></i> Add Flower Color
            </a>
        </div>
    </div>
</div>

@endsection
