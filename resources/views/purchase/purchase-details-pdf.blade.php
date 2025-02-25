<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Details - #{{ $purchase_id }}</title>
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
        img {
            max-width: 60mm; /* Ensures logo fits within 88mm width */
            display: block;
            margin: 0 auto;
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
        <!-- Logo -->
        <div style="text-align: left;">
        <h1 style="font-size: 35px;">Pappachi</h1>
        <h2><strong>Funeral Parlour</strong></h2>
        <h2><strong>0777176998,0774656998</strong></h2>
        <hr style="width: 75%; text-align: left; margin-left: 0;">

        <!-- Purchase Details -->
        <h2>Purchase Details - #{{ $purchase_id }}</h2>
        <p><strong>Branch:</strong> {{ $branch->branch_name }}</p>
        <p><strong>Purchase Date:</strong> {{ $purchase->purchase_date }}</p>


        <!-- Transaction Details -->
        <h2>Transaction Details</h2>
        <p><strong>Transaction ID:</strong> {{ $purchase->transaction_id }}</p>
        <p><strong>Purchase Total:</strong> {{ $purchase->total }}</p>
        <p><strong>Status:</strong>
            @if ($purchase->purchase_status === 'true')
                <span style="color: green;">Complete</span>
            @elseif ($purchase->purchase_status === 'pending')
                <span style="color: orange;">Pending</span>
            @elseif ($purchase->purchase_status === 'failed')
                <span style="color: red;">Failed</span>
            @else
                <span style="color: gray;">Unknown</span>
            @endif
        </p>
</div>
        <!-- Payment History -->
        <h2>Payment History</h2>
        <table>
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Method</th>
                    <th>Due</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_id }}</td>
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
                    <th>Product</th>
                    <th>Qty × Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->quantity }} × {{ $product->purchase_price }}</td>
                        <td>{{ $product->subtotal }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <p class="total">Purchase Amount: {{ $purchase->total }}</p>
        <p class="total">Total Payments: {{ $totalPayments }}</p>
        <p class="total">
            Last Due Amount:
            @if ($payments->isNotEmpty())
                {{ $payments->sortByDesc('created_at')->first()->pay_due }}
            @else
                No payments available.
            @endif
        </p>
    </div>
</body>
</html>
