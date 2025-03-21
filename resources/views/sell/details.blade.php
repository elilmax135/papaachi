@extends('layouts.admin.app')

@section('content')

<div class="container">
    <div class="col-6">
        <h1>Sale Details - #{{ $sale_id }}</h1>
        <p>Customer Name: {{ $sale->customer_name }}</p>
        <p>Sale Date: {{ $sale->sell_date }}</p>
        <p>Total: {{ $sale->total }}</p>
    </div>
    <div class="col-6">
        <p><strong>Transaction ID:</strong> {{ $sale->transaction_id }}</p>
        <p><strong>sell Total:</strong> {{ $sale->total }}</p>
        <p><strong>sell Status:</strong>
            @if ($sale->sell_status === 'true')
                <span class="badge bg-success">Complete</span>
            @elseif ($sale->sell_status === 'pending')
                <span class="badge bg-warning">Pending</span>
            @elseif ($sale->sell_status === 'fail')
                <span class="badge bg-danger">Failed</span>
            @else
                <span class="badge bg-secondary">Unknown</span>
            @endif
        </p>
    </div>
    @if ($sale->doctor_confirm)
        <div class="mt-4 left-aligned">
            <h3>Doctor Confirmation Image</h3>
            <div style="border: 1px solid #ddd; padding: 5px; text-align: center; max-width: 200px;">
                <img src="{{ asset('/doctorImage/' . $sale->doctor_confirm) }}" alt="Doctor Confirmation Image" style="width: 150px; height: auto;">
            </div>
        </div>
    @endif

    <h2>Payment History</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Amount Paid</th>
                <th>Payment Date</th>
                <th>Payment Method</th>
                <th>Due</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
                <tr>
                    <td>{{ $payment->sell_pay_id }}</td>
                    <td>{{ $payment->pay_amount }}</td>
                    <td>{{ $payment->payment_date }}</td>
                    <td>{{ $payment->payment_method }}</td>
                    <td>{{ $payment->pay_due }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Products</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Sale Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->quantity }}×{{ $product->selling_price }}</td>
                    <td>{{ $product->selling_price }}</td>
                    <td>{{ $product->subtotal }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mb-4 text-right">
    <h5><strong>Total sell Amount:</strong> {{ $sale->total }}</h5>
</div>
<div class="mb-4 text-right">
    <h5><strong>Total Paid Amount:</strong> {{ $totalPayments }}</h5>
</div>

<!-- Last Due Amount -->
<div class="mb-4 text-right">
    <h5>Last Due Amount</h5>
    @if ($payments->isNotEmpty())
        <h5>{{ $payments->sortByDesc('created_at')->first()->pay_due }}</h5>
    @else
        <p>No payments available.</p>
    @endif
</div>

<div class="d-flex justify-content-between no-print">
    <a href="{{ url('/ListSell') }}" class="btn btn-primary">Back to Sale List</a>
    <button onclick="printPage()" class="btn btn-success">Print</button>
    <!-- Use the controller route to send PDF via WhatsApp -->
    <a href="{{ url('/sendWhatsAppPdf', $sale_id) }}" target="_blank" class="btn btn-success">Print &amp; Send via WhatsApp</a>
</div>

<script>
    function printPage() {
        window.print();
    }
</script>

<style>
    @media print {
        .no-print {
            visibility: hidden;
            position: absolute;
        }
    }
</style>

@endsection
