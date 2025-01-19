@extends('layouts.admin.app')


@section('content')
<link rel="stylesheet" href="{{ asset('css/product_css.css') }}">

<div class="row">
    <div class="col-12">

        <!-- Service Name Modal -->


            <a href="{{ url('/service') }}" class="btn btn-primary">
                addService</a>
                <a href="{{ url('/NewBoxColor') }}" class="btn btn-primary">
                    addnewboxcolor</a>  <a href="{{ url('/NewBoxType') }}" class="btn btn-primary">
                        addnewboxtype</a>  <a href="{{ url('/NewFlowerColor') }}" class="btn btn-primary">
                            addflowercolor</a>
    </div>
</div>
@endsection
