<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sale Details - #{{ $sale_id }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 100%; margin: 0 auto; padding: 20px; }
        h1, h2, h3 { margin-bottom: 10px; }
        p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 8px; text-align: left; }
        .badge { padding: 4px 8px; color: #fff; border-radius: 4px; }
        .bg-success { background-color: #28a745; }
        .bg-warning { background-color: #ffc107; }
        .bg-danger { background-color: #dc3545; }
        .bg-secondary { background-color: #6c757d; }
        .doctor-confirm { margin-top: 20px; }
        .doctor-confirm img { max-width: 150px; height: auto; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sale Header -->
        <h1>Sale Details - #{{ $sale_id }}</h1>
        <p><strong>Customer Name:</strong> {{ $sale->customer_name }}</p>
        <p><strong>Sale Date:</strong> {{ $sale->sell_date }}</p>
        <p><strong>Total:</strong> {{ $sale->total }}</p>

        <!-- Transaction Details -->
        <h2>Transaction Details</h2>
        <p><strong>Transaction ID:</strong> {{ $sale->transaction_id }}</p>
        <p><strong>Sell Total:</strong> {{ $sale->total }}</p>
        <p><strong>Sell Status:</strong>
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

        <!-- Doctor Confirmation -->
        @if ($sale->doctor_confirm)
            <div class="doctor-confirm">
                <h3>Doctor Confirmation Image</h3>
                <img src="{{ asset('/doctorImage/' . $sale->doctor_confirm) }}" alt="Doctor Confirmation">
            </div>
        @endif

        <!-- Payment History -->
        <h2>Payment History</h2>
        <table>
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

        <!-- Products List -->
        <h2>Products</h2>
        <table>
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
                        <td>{{ $product->quantity }} × {{ $product->selling_price }}</td>
                        <td>{{ $product->selling_price }}</td>
                        <td>{{ $product->subtotal }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <h3 style="text-align: right;"> Sell Amount: {{ $sale->total }}</h3>
        <h3 style="text-align: right;">Total Payments: {{ $totalPayments }}</h3>
        <h3 style="text-align: right;">
            Last Due Amount:
            @if ($payments->isNotEmpty())
            {{ $payments->sortByDesc('created_at')->first()->pay_due }}
        @else
            No payments available.
        @endif

        </h3>
    </div>
</body>
</html>
