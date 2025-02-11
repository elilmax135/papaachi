@extends('layouts.admin.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Stock Inventory</h2>

    <!-- Branch Name Filter -->

        <div class="col-md-2 offset-md-0">
            <label for="branchFilter" class="form-label">Filter by Branch Name:</label>
            <select id="branchFilter" class="form-control">
                <option value="">All Branches</option>
                @foreach($BranchV as $branch)
                    <option value="{{ $branch->branch_name }}">{{ $branch->branch_name }}</option>
                @endforeach
            </select>
        </div>



    <table class="table table-bordered table-striped" id="stockTable">
        <thead>
            <tr>
                <th>Branch ID</th>
                <th>Branch Name</th>

                <th>Product Name</th>
                <th>Total Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($custock as $row)
            <tr>
                <td>{{ $row->branch_id }}</td>
                <td>{{ $row->branch_name }}</td>

                <td>{{ $row->product_name }}</td>
                <td>{{ $row->total_quantity }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Include jQuery and DataTables -->

<!-- Filtering Script -->
<script>
    $(document).ready(function() {
        var table = $('#stockTable').DataTable();

        // Filter by Branch Name
        $('#branchFilter').on('change', function() {
            var branchName = $(this).val();
            table.column(1).search(branchName).draw(); // Column 1 = "Branch Name"
        });
    });
</script>

@endsection
