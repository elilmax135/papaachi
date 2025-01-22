@extends('layouts.admin.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Stock Inventory</h2>

    <table class="table table-bordered table-striped" id="stockTable">
        <thead>
            <tr>
                <th>Branch ID</th>
                <th>Branch Name</th>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Total Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($custock as $row)
            <tr>
                <td>{{ $row->branch_id }}</td>
                <td>{{ $row->branch_name }}</td>
                <td>{{ $row->product_id }}</td>
                <td>{{ $row->product_name }}</td>
                <td>{{ $row->total_quantity }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('#stockTable').DataTable();
    });
</script>
@endsection
