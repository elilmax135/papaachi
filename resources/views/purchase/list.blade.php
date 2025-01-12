@extends('layouts.admin.app')

@section('content')
<!-- Start of the form -->
<div class="row">
    <!-- Supplier and Purchase Details -->
    <div class="col-12">
        <div class="card mb-3">
            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Supplier Name</th>
                            <th>Branch</th>
                            <th>Purchase Date</th>
                            <th>Transaction ID</th>
                            <th>Purchase Total</th>
                            <th>Purchase Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchases as $purchase_id => $purchase_group)
                        <tr>
                            <td>{{ $purchase_group[0]->supplier_name }}</td>
                            <td>{{ $purchase_group[0]->branch_name }}</td>
                            <td>{{ $purchase_group[0]->purchase_date }}</td>
                            <td>{{ $purchase_group[0]->transaction_id }}</td>
                            <td>{{ $purchase_group[0]->total }}</td>
                            <td>
                                @if ($purchase_group[0]->purchase_status == 'true')
                                    <span class="badge bg-success">Completed</span>
                                @elseif ($purchase_group[0]->purchase_status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif ($purchase_group[0]->purchase_status == 'failed')
                                    <span class="badge bg-danger">Failed</span>
                                @else

                                @endif
                            </td>
                            <td>
                                <!-- Pay Now and Details Buttons -->
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#payNowModal-{{ $purchase_id }}">
                                    💳 Pay Now
                                </button>

                                <a href="{{ url('/details/' . $purchase_id) }}" class="btn btn-primary btn-sm"> 📄 Details</a>


                                <!-- Pay Now Modal -->
                                <div class="modal fade" id="payNowModal-{{ $purchase_id }}" tabindex="-1" aria-labelledby="payNowModalLabel-{{ $purchase_id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="payNowModalLabel-{{ $purchase_id }}">Pay Now</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ url('/paymentpay', $purchase_id) }}" method="POST">
                                                    @csrf

                                                    <div class="mb-3">
                                                        <input type="number" class="form-control" id="purchase_id-{{ $purchase_id }}" name="purchase_id" value="{{ $purchase_group[0]->purchase_id }}">


                                                        <label for="amount-{{ $purchase_id }}" class="form-label">Purchase Total</label>
                                                        <input type="number" class="form-control" id="payment_total-{{ $purchase_id }}" name="payment_total" value="{{ $purchase_group[0]->total }}" readonly>
                                                    </label>

                                                    <label for="amount-{{ $purchase_id }}" class="form-label">Due</label>
                                                        @if ($purchase_group[0]->purchase_status == 'failed')
                                                            <input type="number" id="payment_total-{{ $purchase_id }}" name="payment_total" value="{{ $purchase_group[0]->total }}" class="form-control" readonly>
                                                        @else
                                                            <input type="number" class="form-control" id="payment_total-{{ $purchase_id }}" name="payment_total" value="{{ $purchase_group[0]->last_pay_due }}" readonly>

                                                       @endif
                                                </label>

                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="amount-{{ $purchase_id }}" class="form-label">Enter Amount</label>
                                                        <input type="number" class="form-control" id="amount-{{ $purchase_id }}" name="pay_amount" placeholder="Enter amount">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label>Payment Date</label>
                                                        <input type="date" name="payment_date" class="form-control">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="payment-method-{{ $purchase_id }}" class="form-label">Payment Method</label>
                                                        <select id="payment-method-{{ $purchase_id }}" class="form-control" name="payment_method" onchange="togglePaymentFields({{ $purchase_id }})">
                                                            <option value="" disabled selected>Select payment method</option>
                                                            <option value="cash">Cash</option>
                                                            <option value="check">Check</option>
                                                            <option value="online">Online</option>
                                                        </select>
                                                    </div>

                                                    <!-- Check Payment Fields (Initially Hidden) -->
                                                    <div id="check-fields-{{ $purchase_id }}" class="payment-fields" style="display: none;">
                                                        <div class="mb-3">
                                                            <label for="check_number-{{ $purchase_id }}" class="form-label">Check Number</label>
                                                            <input type="text" name="check_number" class="form-control" placeholder="Enter check number">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="bank_name-{{ $purchase_id }}" class="form-label">Bank Name</label>
                                                            <input type="text" name="bank_name" class="form-control" placeholder="Enter bank name">
                                                        </div>
                                                    </div>

                                                    <!-- Online Payment Fields (Initially Hidden) -->
                                                    <div id="online-fields-{{ $purchase_id }}" class="payment-fields" style="display: none;">
                                                        <div class="mb-3">
                                                            <label for="transection_id-{{ $purchase_id }}" class="form-label">Transaction ID</label>
                                                            <input type="text" name="transection_id" class="form-control" value="{{ $purchase_group[0]->transaction_id }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="payment_platform-{{ $purchase_id }}" class="form-label">Payment Platform</label>
                                                            <input type="text" name="payment_platform" class="form-control" placeholder="Enter payment platform">
                                                        </div>
                                                    </div>

                                                    <button type="submit" class="btn btn-success">Confirm Payment</button>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Scroll Down Button -->
<button id="scrollDownButton" class="btn btn-primary btn-sm">Scroll to Bottom</button>

<script>
    // JavaScript to handle the scroll down button
    document.getElementById('scrollDownButton').addEventListener('click', function () {
        const scrollableDiv = document.querySelector('.table-responsive');
        scrollableDiv.scrollTo({
            top: scrollableDiv.scrollHeight,
            behavior: 'smooth'
        });
    });


</script>
<script>

    function togglePaymentFields(purchaseId) {
        const paymentMethod = document.getElementById('payment-method-' + purchaseId).value;

        // Hide all payment-specific fields initially
        document.getElementById('check-fields-' + purchaseId).style.display = 'none';
        document.getElementById('online-fields-' + purchaseId).style.display = 'none';

        // Show specific fields based on selected payment method
        if (paymentMethod === 'check') {
            document.getElementById('check-fields-' + purchaseId).style.display = 'block';
        } else if (paymentMethod === 'online') {
            document.getElementById('online-fields-' + purchaseId).style.display = 'block';
        }
    }

</script>

@endsection
