@extends('layouts.admin.app')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Filters Section -->
        <div class="card mb-3 p-3">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="filter-customer-name" class="form-label">Customer Name</label>
                    <input type="text" id="filter-customer-name" class="form-control filter-input" data-column="customer-name" placeholder="Filter by Customer Name">
                </div>
                <div class="col-md-3">
                    <label for="filter-sale-id" class="form-label">Sale ID</label>
                    <input type="text" id="filter-sale-id" class="form-control filter-input" data-column="sale-id" placeholder="Filter by Sale ID">
                </div>
                <div class="col-md-3">
                    <label for="filter-sale-date" class="form-label">Sale Date</label>
                    <input type="date" id="filter-sale-date" class="form-control filter-input" data-column="sale-date">
                </div>
                <div class="col-md-3">
                    <label for="filter-customer-mobile" class="form-label">Customer Mobile</label>
                    <input type="text" id="filter-customer-mobile" class="form-control filter-input" data-column="customer-mobile" placeholder="Filter by Mobile">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label for="filter-month" class="form-label">Filter by Month</label>
                    <input type="month" id="filter-month" class="form-control filter-input" data-column="month">
                </div>
                <div class="col-md-3">
                    <label for="filter-status" class="form-label">Sale Status</label>
                    <select id="filter-status" class="form-control filter-input" data-column="sale-status">
                        <option value="">All</option>
                        <option value="completed">Completed</option>
                        <option value="pending">Pending</option>
                        <option value="fail">Failed</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="card mb-3">
            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th>Customer Mobile</th>
                            <th>Sale Date</th>
                            <th>Customer Address</th>
                            <th>Doctor Confirm</th>
                            <th>Service Name</th>
                            <th>Sale Total</th>
                            <th>Sale Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sell as $sale_id => $sale_group)
                        <tr class="sale-row"
                            data-customer-name="{{ $sale_group[0]->customer_name }}"
                            data-sale-id="{{ $sale_id }}"
                            data-sale-date="{{ $sale_group[0]->sell_date }}"
                            data-month="{{ \Carbon\Carbon::parse($sale_group[0]->created_at)->format('Y-m') }}"
                            data-customer-mobile="{{ $sale_group[0]->customer_mobile }}"
                            data-sale-status="{{ $sale_group[0]->sell_status }}">
                            <td>{{ $sale_group[0]->customer_name }}</td>
                            <td>{{ $sale_group[0]->customer_mobile }}</td>
                            <td>{{ $sale_group[0]->sell_date }}</td>
                            <td>{{ $sale_group[0]->customer_address }}</td>
                            <td><img src="/doctorImage/{{ $sale_group[0]->doctor_confirm }}" alt="Box Image" width="150" height="150"></td>
                            <td>{{ $sale_group[0]->service_id }}</td>
                            <td>{{ $sale_group[0]->total }}</td>
                            <td>
                                @if ($sale_group[0]->sell_status == 'true')
                                    <span class="badge bg-success">Completed</span>
                                @elseif ($sale_group[0]->sell_status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif ($sale_group[0]->sell_status == 'fail')
                                    <span class="badge bg-danger">Failed</span>
                                @else
                                    <span class="badge bg-secondary">Unknown</span>
                                @endif
                            </td>
                            <td>
                                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#payNowModal-{{ $sale_id }}">
                                                    💳 Pay Now
                                                </button>
                                                <a href="{{ url('/sales/Redetails/' . $sale_id) }}" class="btn btn-primary btn-sm"> 📄 Details</a>
                                            <!-- Pay Now Modal for Sales -->
                                            <div class="modal fade" id="payNowModal-{{ $sale_id }}" tabindex="-1" aria-labelledby="payNowModalLabel-{{ $sale_id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="payNowModalLabel-{{ $sale_id }}">Pay Now</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ url('/sales/Repayment', $sale_id) }}" method="POST">
                                                                @csrf

                                                                <div class="mb-3">
                                                                    <input type="hidden" class="form-control" id="sale_id-{{ $sale_id }}" name="sale_id" value="{{ $sale_group[0]->id }}">

                                                                    <label for="sale-total-{{ $sale_id }}" class="form-label">Sale Total</label>
                                                                    <input type="number" class="form-control" id="sale-total-{{ $sale_id }}" name="sale_total" value="{{ $sale_group[0]->total }}" readonly>
                                                                </div>

                                                                <!-- Location Total and Price Calculation -->


                                                                <!-- Due Amount -->
                                                                <div class="mb-3">
                                                                    <label for="sale_total-{{ $sale_id }}" class="form-label">Due</label>
                                                                    @if ($sale_group[0]->sell_status === 'fail')
                                                                        <input type="number" id="sale_total-{{ $sale_id }}" name="sale_total" value="{{ $sale_group[0]->total }}" class="form-control" readonly>
                                                                    @else
                                                                        <input type="number" class="form-control" id="sale_total-{{ $sale_id }}" name="sale_total" value="{{ $sale_group[0]->last_pay_due }}" readonly>
                                                                    @endif
                                                                </div>

                                                                <!-- Amount and Payment Method -->
                                                                <div class="mb-3">
                                                                    <label for="amount-{{ $sale_id }}" class="form-label">Enter Amount</label>
                                                                    <input type="number" class="form-control" id="amount-{{ $sale_id }}" name="pay_amount" placeholder="Enter amount">
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label>Payment Date</label>
                                                                    <input type="date" name="payment_date" class="form-control">
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="payment-method-{{ $sale_id }}" class="form-label">Payment Method</label>
                                                                    <select id="payment-method-{{ $sale_id }}" class="form-control" name="payment_method" onchange="togglePaymentFields({{ $sale_id }})">
                                                                        <option value="" disabled selected>Select payment method</option>
                                                                        <option value="cash">Cash</option>
                                                                        <option value="check">Check</option>
                                                                        <option value="online">Online</option>
                                                                    </select>
                                                                </div>

                                                                <!-- Check Payment Fields (Initially Hidden) -->
                                                                <div id="check-fields-{{ $sale_id }}" class="payment-fields" style="display: none;">
                                                                    <div class="mb-3">
                                                                        <label for="check_number-{{ $sale_id }}" class="form-label">Check Number</label>
                                                                        <input type="text" name="check_number" class="form-control" placeholder="Enter check number">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="bank_name-{{ $sale_id }}" class="form-label">Bank Name</label>
                                                                        <input type="text" name="bank_name" class="form-control" placeholder="Enter bank name">
                                                                    </div>
                                                                </div>

                                                                <!-- Online Payment Fields (Initially Hidden) -->
                                                                <div id="online-fields-{{ $sale_id }}" class="payment-fields" style="display: none;">
                                                                    <div class="mb-3">
                                                                        <label for="transection_id-{{ $sale_id }}" class="form-label">Transaction ID</label>
                                                                        <input type="text" name="transection_id" class="form-control" value="{{ $sale_group[0]->transaction_id }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="payment_platform-{{ $sale_id }}" class="form-label">Payment Platform</label>
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

<!-- Filter Script -->
<script>
    document.querySelectorAll('.filter-input').forEach(input => {
        input.addEventListener('input', function () {
            const column = this.dataset.column;
            const value = this.value.toLowerCase();
            const rows = document.querySelectorAll('.sale-row');

            rows.forEach(row => {
                const cellValue = row.getAttribute(`data-${column}`)?.toLowerCase() || '';

                if (column === "sale-status" || column === "month") {
                    // For dropdown or month, match exact value
                    row.style.display = value === "" || cellValue === value ? '' : 'none';
                } else {
                    // For text or date inputs, check if the value is included
                    row.style.display = cellValue.includes(value) ? '' : 'none';
                }
            });
        });
    });
</script>

@endsection
