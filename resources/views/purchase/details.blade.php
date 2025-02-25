@extends('layouts.admin.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/product_css.css') }}">
<div class="container">
    <div class="row">
        <div class="col-12 text-center mb-4">
            <img src="{{ asset('pappachi/pp.jpeg') }}" alt="Pappaachchi Funeral Parlour Logo" style="max-width: 200px;">
            <h1 style="font-size: 60px;">Pappachi</h1>
            <h2><strong>Funeral Parlour</strong></h2>
            <div class="phone-container">
                <table class="phone-table">
                    <tr>
                        <td class="phone-prefix" style="vertical-align: bottom;">077717</td>
                        <td class="last-digits" rowspan="2" style="font-size: 60px">6998</td>
                    </tr>
                    <tr>
                        <td class="phone-prefix" style="vertical-align: top;">077465</td>
                    </tr>
                </table>
            </div>

            <hr>
            <h3><strong>Purchase Payment Bill</strong></h3>
            <p><strong>Purchase ID:</strong> {{ $purchase_id }}</p>
            <p><strong>Date:</strong> {{ now()->format('d-m-Y') }}</p>
        </div>
    </div>

    <!-- General Purchase Information (without table) -->
    <div class="mb-4">
        <h5>Purchase Information</h5>
        <div class="row">
            <div class="col-6">
                <p><strong>Supplier Name:</strong> {{ $purchase->supplier_name }}</p>
                <p><strong>Branch:</strong> {{ $purchase->branch_name }}</p>
                <p><strong>Purchase Date:</strong> {{ $purchase->purchase_date }}</p>
            </div>
            <div class="col-6">
                <p><strong>Transaction ID:</strong> {{ $purchase->transaction_id }}</p>
                <p><strong>Purchase Total:</strong> {{ $purchase->total }}</p>
                <p><strong>Purchase Status:</strong>
                    @if ($purchase->purchase_status === 'true')
                        <span class="badge bg-success">Complete</span>
                    @elseif ($purchase->purchase_status === 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @elseif ($purchase->purchase_status === 'failed')
                        <span class="badge bg-danger">Failed</span>
                    @else
                        <span class="badge bg-secondary">Unknown</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Payment Details Table -->
    <div class="mb-4">
        <h5>Payment Details</h5>
        <table class="table table-bordered">
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
    </div>

    <!-- Product Details Table -->
    <div class="mb-4">
        <h5>Products in this Purchase</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->quantity }} × {{ $product->purchase_price }}</td>
                        <td>{{ $product->purchase_price }}</td>
                        <td>{{ $product->subtotal }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No products found for this Purchase ID.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Total Amount -->
    <div class="mb-4 text-right">
        <h5><strong>Total Purchase Amount:</strong> {{ $purchase->total }}</h5>
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

    <!-- Footer and Buttons -->
    <div class="d-flex justify-content-between no-print">
        <a href="{{ url('/ListPurchase') }}" class="btn btn-primary">Back to Purchase List</a>
        <button onclick="printPage()" class="btn btn-success">Print</button>
        <a href="{{ url('/sendWhatsAppPdf_pur', $purchase_id) }}" target="_blank" class="btn btn-success">Print &amp; Send via WhatsApp</a>
    </div>
</div>

<script>
    function printPage() {
        window.print();
    }
</script>
<style>
    /* Configure the printed page to mimic a POS receipt */
    @page {
        size: 80mm auto; /* Receipt width common for POS printers */
        margin: 5mm;
    }

    @media print {
        .no-print {
            display: none;
        }
        /* Adjust overall print font sizes */
        body, p, td, th {
            font-size: 8px;
        }
        h1 {
            font-size: 20px;
        }
        h2 {
            font-size: 16px;
        }
        h3 {
            font-size: 12px;
        }
        h5 {
            font-size: 10px;
        }
        /* Narrow the container width for receipt printing */
        .container {
            width: 80mm;
            margin: auto;
        }
        /* Adjust table text if necessary */
        .table, .phone-table td {
            font-size: 8px;
        }
    }

    .phone-table {
        border-collapse: collapse;
        margin: 0 auto;
    }
</style>
@endsection
