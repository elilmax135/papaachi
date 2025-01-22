@extends('layouts.admin.app')

@section('content')
<!-- Filter Form -->
<form method="GET" action="{{ url('/filter-results') }}" class="mb-4">
    <div class="row">
        <!-- Date filters -->
        <div class="col-md-3">
            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" value="{{ old('start_date', $startDate ?? '') }}" class="form-control">
        </div>

        <div class="col-md-3">
            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" value="{{ old('end_date', $endDate ?? '') }}" class="form-control">
        </div>

        @php
            $status = $status ?? '';  // Default to empty string if status is not passed
            $filterType = $filterType ?? '';
            $data = $data ?? collect(); // Default to empty collection if data is not passed
        @endphp

        <!-- Status filter -->
        <div class="col-md-3">
            <label for="status">Status:</label>
            <select name="status" class="form-control">
                <option value="">All</option>
                <option value="true" {{ old('status', $status) === 'true' ? 'selected' : '' }}>Completed</option>
                <option value="pending" {{ old('status', $status) === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="fail" {{ old('status', $status) === 'fail' ? 'selected' : '' }}>
                    {{ $filterType === 'purchase' ? 'Failed' : 'Fail' }}
                </option>
            </select>
        </div>

        <!-- Filter type -->
        <div class="col-md-3">
            <label for="filter_type">Filter Type:</label>
            <select name="filter_type" class="form-control">
                <option value="purchase" {{ old('filter_type', $filterType) === 'purchase' ? 'selected' : '' }}>Purchase</option>
                <option value="sell" {{ old('filter_type', $filterType) === 'sell' ? 'selected' : '' }}>Sell</option>
                <option value="transfer" {{ old('filter_type', $filterType) === 'transfer' ? 'selected' : '' }}>Transfer</option>
            </select>
        </div>

     <!-- Branch filter for purchase and transfer types -->
<div class="col-md-3">
    <label for="branch_name">Branch Name:</label>
    <input type="text" name="branch_name" value="{{ old('branch_name', $branchName ?? '') }}" class="form-control">
</div>
    </div>

    <button type="submit" class="btn btn-primary mt-3">Filter</button>
</form>

<!-- Table displaying the results inside a scrollable container -->
<div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
    <table class="table table-bordered table-striped" id="filterTable">
        <thead>
            @if ($filterType === 'purchase')
                <tr>
                    <th>Purchase ID</th>
                    <th>Supplier Name</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Branch Name</th>
                </tr>
            @elseif ($filterType === 'sell')
                <tr>
                    <th>Sell ID</th>
                    <th>Customer Name</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                </tr>
            @elseif ($filterType === 'transfer')
                <tr>
                    <th>Transfer ID</th>
                    <th>Transaction ID</th>

                    <th>Branch Name</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Payment Method</th>
                    <th>Product Name</th>
                </tr>
            @endif
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                @if ($filterType === 'purchase')
                    <td>{{ $item->purchase_id }}</td>
                    <td>{{ $item->supplier_name }}</td>
                    <td>{{ $item->purchase_date }}</td>
                    <td>{{ $item->total }}</td>
                    <td>
                        <span class="badge
                        @if ($item->purchase_status === 'true') badge-success
                        @elseif ($item->purchase_status === 'pending') badge-warning
                        @elseif ($item->purchase_status === 'failed') badge-danger
                        @endif">
                        {{ $item->purchase_status === 'true' ? 'Completed' : ($item->purchase_status === 'pending' ? 'Pending' : 'Fail') }}
                    </span>
                    </td>
                    <td>{{ $item->product_id }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->branch_name }}</td>
                @elseif ($filterType === 'sell')
                    <td>{{ $item->sell_id }}</td>
                    <td>{{ $item->customer_name }}</td>
                    <td>{{ $item->sell_date }}</td>
                    <td>{{ $item->total }}</td>
                    <td>
                        <span class="badge
                        @if ($item->sell_status === 'true') badge-success
                        @elseif ($item->sell_status === 'pending') badge-warning
                        @elseif ($item->sell_status === 'fail') badge-danger
                        @endif">
                        {{ $item->sell_status === 'true' ? 'Completed' : ($item->sell_status === 'pending' ? 'Pending' : 'Fail') }}
                    </span>
                    </td>
                    <td>{{ $item->product_id }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                @elseif ($filterType === 'transfer')
                    <td>{{ $item->transfer_id }}</td>
                    <td>{{ $item->transaction_id }}</td>

                    <td>{{ $item->branch_name }}</td>
                    <td>{{ $item->total }}</td>
                    <td>
                        <span class="badge
                        @if ($item->transfer_status === 'true') badge-success
                        @elseif ($item->transfer_status === 'pending') badge-warning
                        @elseif ($item->transfer_status === 'fail') badge-danger
                        @endif">
                        {{ $item->transfer_status === 'true' ? 'Completed' : ($item->transfer_status === 'pending' ? 'Pending' : 'Fail') }}
                    </span>
                    </td>
                    <td>{{ $item->payment_method }}</td>
                    <td>{{ $item->product_name }}</td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<!-- Total -->
<div class="mt-3 p-3 bg-light border rounded d-inline-block">
    <strong>Total: </strong> {{ $total ?? '' }}
</div>

<!-- Scroll to Bottom Button -->



<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterTypeSelect = document.getElementById('filter_type');
        const branchFilter = document.querySelector('.branch-filter');

        // Function to toggle branch filter visibility
        const toggleBranchFilter = () => {
            const selectedFilter = filterTypeSelect.value;
            if (selectedFilter === 'purchase' || selectedFilter === 'transfer') {
                branchFilter.style.display = 'block';
            } else {
                branchFilter.style.display = 'none';
            }
        };

        // Initial check on page load
        toggleBranchFilter();

        // Add event listener to update on filter type change
        filterTypeSelect.addEventListener('change', toggleBranchFilter);
    });
</script>

<script>
    $(document).ready(function() {
        $('#filterTable').DataTable(); // Initialize DataTable
    });
</script>
@endsection
