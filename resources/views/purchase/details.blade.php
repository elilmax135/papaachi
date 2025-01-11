@extends('layouts.admin.app')

@section('content')
<div class="container">
    <h3>Payment Details for Purchase ID: {{ $purchase_id }}</h3>
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

    <!-- Close Button to go back to Purchase List -->
    <a href="{{ url('/ListPurchase') }}" class="btn btn-primary">Back to Purchase List</a>
</div>
@endsection
