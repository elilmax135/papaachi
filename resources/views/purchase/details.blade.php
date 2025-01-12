@extends('layouts.admin.app')

@section('content')
<div class="container">
    <h3>Payment Details for Purchase ID: {{ $purchase_id }}</h3>

    <!-- General Purchase Information -->
    <div class="mb-4">
        <h5>Purchase Information</h5>
        <ul class="list-unstyled">
            <li><strong>Supplier Name:</strong> {{ $purchase->supplier_name }}</li>
            <li><strong>Branch:</strong> {{ $purchase->branch }}</li>
            <li><strong>Purchase Date:</strong> {{ $purchase->purchase_date }}</li>
            <li><strong>Transaction ID:</strong> {{ $purchase->transaction_id }}</li>
            <li><strong>Purchase Total:</strong> {{ $purchase->total }}</li>
            <li>
                <strong>Purchase Status:</strong>
                @if ($purchase->purchase_status === 'true')
                    <span class="badge bg-success">Complete</span>
                @elseif ($purchase->purchase_status === 'Pending')
                    <span class="badge bg-warning">Pending</span>
                @elseif ($purchase->purchase_status === 'Failed')
                    <span class="badge bg-danger">Failed</span>
                @else
                    <span class="badge bg-secondary">Unknown</span>
                @endif
            </li>
        </ul>
    </div>

    <!-- Payment Details Table -->
    <table class="table table-bordered mb-4">
        <thead>
            <tr>
                <th>Pay ID</th>
                <th>Pay Date</th>
                <th>Pay Amount</th>
                <th>Pay Due</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($payments as $payment)
                <tr>
                    <td>{{ $payment->payment_id }}</td>
                    <td>{{ $payment->payment_date }}</td>
                    <td>{{ $payment->pay_amount }}</td>
                    <td>{{ $payment->pay_due }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No payment records found for this Purchase ID.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Product Details -->
    <div class="mb-4">
        <h5>Products in this Purchase</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->quantity }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">No products found for this Purchase ID.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Buttons -->
    <div class="d-flex justify-content-between">
        <a href="{{ url('/ListPurchase') }}" class="btn btn-primary">Back to Purchase List</a>
        <button onclick="printPage()" class="btn btn-success">Print</button>
    </div>
</div>

<script>
    function printPage() {
        window.print();
    }
</script>
@endsection
