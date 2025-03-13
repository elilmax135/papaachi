@extends('layouts.admin.app')

@section('content')
<div class="container mt-4">
    <div class="card">
         <div class="card-body">
    <div class="row g-4">
        <!-- Total Sales -->
        <div class="col-3">
            <div class="p-4 bg-light text-dark rounded shadow">
                <h5 class="text-center">Total Sales</h5>
                <div class="d-flex align-items-center justify-content-between mt-3">
                    <i class="bi bi-currency-dollar fs-1"></i>
                    <div class="text-end">
                        <h3 class="mb-0">{{ number_format($totalSum, 2) }}</h3>
                        <p class="mb-0">total</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Purchase -->
        <div class="col-3">
            <div class="p-4 bg-light text-dark rounded shadow">
                <h5 class="text-center">Total Purchase</h5>
                <div class="d-flex align-items-center justify-content-between mt-3">
                    <i class="bi bi-bag-check fs-1"></i>
                    <div class="text-end">
                        <h3 class="mb-0">{{ number_format($totalpurSum, 2) }}</h3>
                        <p class="mb-0">total</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Transfer -->
        <div class="col-3">
            <div class="p-4 bg-light text-dark rounded shadow">
                <h5 class="text-center">Total Transfer</h5>
                <div class="d-flex align-items-center justify-content-between mt-3">
                    <i class="bi bi-arrow-left-right fs-1"></i>
                    <div class="text-end">
                        <h3 class="mb-0">{{ number_format($totaltransSum, 2) }}</h3>
                        <p class="mb-0">total</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Customer -->
        <div class="col-3">
            <div class="p-4 bg-light text-dark rounded shadow">
                <h5 class="text-center">Total Customer</h5>
                <div class="d-flex align-items-center justify-content-between mt-3">
                    <i class="bi bi-people fs-1"></i>
                    <div class="text-end">

                        <h3 class="mb-0">{{ number_format($totalCount) }}</h3>
                        <p class="mb-0">Active Customers</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

    <!-- Recent Sales Table -->
    <div class="mt-5">

        <div class="card">
            <div class="card-header">
                <h4>Recent Sales</h4>
            </div>
            <div class="card-body">

    <table class="table table-striped table-bordered shadow-sm" id="sales-table">
      <thead class="table-dark">
        <tr>
          <th>Customer Name</th>
          <th>Sell Total</th>
          <th>Sell Status</th>
          <th>Sell Date</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($salesR as $rec)
          <tr>
            <td>{{ $rec->customer_name }}</td>
            <td>{{ $rec->total }}</td>
            <td>
                @if($rec->sell_status == 'fail')
                    <span class="badge bg-danger">Fail</span>
                @elseif($rec->sell_status == 'pending')
                    <span class="badge bg-warning text-dark">Pending</span>
                @elseif($rec->sell_status == 'true')
                    <span class="badge bg-success">True</span>
                @else
                    <span class="badge bg-secondary">Unknown</span>
                @endif
            </td>
            <td>{{ $rec->created_at }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
</div>
</div>



<script>
    $(document).ready(function() {
        $('#sales-table').DataTable(); // Initialize DataTable
    });
</script>

@endsection
