<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>transfer Details - #{{ $transfer_id }}</title>
    <style>
            body {
            font-family: Arial, sans-serif;
            font-size: 10px; /* Adjusted for POS readability */
            width: 88mm;
            height: 600mm;
            margin: 0 auto;
            padding: 5px;
        }
        .container {
            width: 88mm;
            padding: 5px;
            text-align: center;
        }
        h1, h2, h3 {
            margin: 5px 0;
            font-size: 12px;
        }
        p {
            margin: 2px 0;
            font-size: 10px;
        }

        table {
            width: 80%;
            border-collapse: collapse;
            font-size: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
        }
        .total {
            text-align: left;
            font-weight: bold;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div style="text-align: left;">
            <h1 style="font-size: 35px;">Pappachi</h1>
            <h2><strong>Funeral Parlour</strong></h2>
            <h2><strong>0777176998,0774656998</strong></h2>
            <hr style="width: 50%; text-align: left; margin-left: 0;">
        <!-- transfer Header -->
        <h1>transfer Details - #{{ $transfer_id }}</h1>
        <p><strong>To branch:</strong> {{ $branch->branch_name }}</p>
        <p><strong>From branch:</strong> {{ $branch->branch_name_2 }}</p>
        <p><strong>transfer Date:</strong> {{ $transfer->transfer_date }}</p>


        <!-- Transaction Details -->
        <h2>Transaction Details</h2>
        <p><strong>Transaction ID:</strong> {{ $transfer->transaction_id }}</p>
        <p><strong>Transfer Total:</strong> {{ $transfer->total }}</p>
        <p><strong>Transfer Status:</strong>
            @if ($transfer->transfer_status === 'true')
                <span class="badge bg-success">Complete</span>
            @elseif ($transfer->transfer_status === 'pending')
                <span class="badge bg-warning">Pending</span>
            @elseif ($transfer->transfer_status === 'fail')
                <span class="badge bg-danger">Failed</span>
            @else
                <span class="badge bg-secondary">Unknown</span>
            @endif
        </p>

        <!-- Doctor Confirmation -->
        </div>

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
                        <td>{{ $payment->transfer_pay_id }}</td>
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
                    <th>transfer Price</th>
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
        <h3 style="text-align: left;"> Transfer Amount: {{ $transfer->total }}</h3>
        <h3 style="text-align: left;">Total Payments: {{ $totalPayments }}</h3>
        <h3 style="text-align: left;">
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
