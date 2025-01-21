@extends('layouts.admin.app')

@section('content')
<div class="row">
    <!-- Filters -->
    <div class="col-12">
        <div class="row mb-3">
            <!-- Individual Filters -->
            <div class="col-md-3">
                <label for="filter-branch-name" class="form-label">Branch Name</label>
                <input type="text" id="filter-branch-name" class="form-control" placeholder="Enter Branch Name">
            </div>
            <div class="col-md-3">
                <label for="filter-transfer-id" class="form-label">Transfer ID</label>
                <input type="text" id="filter-transfer-id" class="form-control" placeholder="Enter Transfer ID">
            </div>
            <div class="col-md-3">
                <label for="filter-transaction-id" class="form-label">Transaction ID</label>
                <input type="text" id="filter-transaction-id" class="form-control" placeholder="Enter Transaction ID">
            </div>
            <div class="col-md-3">
                <label for="filter-month" class="form-label">Filter by Month</label>
                <input type="month" id="filter-month" class="form-control filter-input" data-column="month">
            </div>
            <div class="col-md-3 mt-2">
                <label for="filter-status" class="form-label">Transfer Status</label>
                <select id="filter-status" class="form-control">
                    <option value="">All</option>
                    <option value="completed">Completed</option>
                    <option value="pending">Pending</option>
                    <option value="fail">Failed</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Transfer Table -->
    <div class="col-12">
        <div class="card mb-3">
            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>To Branch</th>
                            <th>Transfer Date</th>
                            <th>Transaction ID</th>
                            <th>Transfer Total</th>
                            <th>Transfer Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="transfer-table-body">
                        @foreach ($transfers as $transfer_id => $transfer_group)
                        <tr class="transfer-row"
                            data-branch-name="{{ $transfer_group[0]->branch_name }}"
                            data-transfer-id="{{ $transfer_id }}"
                            data-transaction-id="{{ $transfer_group[0]->transaction_id }}"
                            data-month="{{ \Carbon\Carbon::parse($transfer_group[0]->created_at)->format('Y-m') }}"
                            data-status="{{ $transfer_group[0]->transfer_status }}">
                            <td>{{ $transfer_group[0]->branch_name }}</td>
                            <td>{{ $transfer_group[0]->transfer_date }}</td>
                            <td>{{ $transfer_group[0]->transaction_id }}</td>
                            <td>{{ $transfer_group[0]->total }}</td>
                            <td>
                                @if ($transfer_group[0]->transfer_status == 'true')
                                    <span class="badge bg-success">Completed</span>
                                @elseif ($transfer_group[0]->transfer_status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif ($transfer_group[0]->transfer_status == 'fail')
                                    <span class="badge bg-danger">Failed</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#payNowModal-{{ $transfer_id }}">
                                    💳 Pay Now
                                </button>
                                <a href="{{ url('/Retransdetails/' . $transfer_id) }}" class="btn btn-primary btn-sm">📄 Details</a>
                                    <!-- Modal for Payment -->
                                    <div class="modal fade" id="payNowModal-{{ $transfer_id }}" tabindex="-1" aria-labelledby="payNowModalLabel-{{ $transfer_id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Pay Now</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ url('/additiontransferpay', $transfer_id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="transfer_id" value="{{ $transfer_group[0]->id }}">

                                                        <div class="mb-3">
                                                            <label for="total-{{ $transfer_id }}" class="form-label">Transfer Total</label>
                                                            <input type="number" class="form-control" id="total-{{ $transfer_id }}" name="transfer_total" value="{{ $transfer_group[0]->total }}" readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="due-{{ $transfer_id }}" class="form-label">Due</label>
                                                            <input type="number" class="form-control" id="due-{{ $transfer_id }}" name="transfer_total" value="{{ $transfer_group[0]->last_pay_due ?? $transfer_group[0]->total }}" readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="amount-{{ $transfer_id }}" class="form-label">Enter Amount</label>
                                                            <input type="number" class="form-control" id="amount-{{ $transfer_id }}" name="pay_amount" placeholder="Enter amount" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Payment Date</label>
                                                            <input type="date" name="payment_date" class="form-control" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="payment-method-{{ $transfer_id }}" class="form-label">Payment Method</label>
                                                            <select id="payment-method-{{ $transfer_id }}" class="form-control" name="payment_method" onchange="togglePaymentFields({{ $transfer_id }})" required>
                                                                <option value="" disabled selected>Select payment method</option>
                                                                <option value="cash">Cash</option>
                                                                <option value="check">Check</option>
                                                                <option value="online">Online</option>
                                                            </select>
                                                        </div>

                                                        <!-- Check Fields -->
                                                        <div id="check-fields-{{ $transfer_id }}" class="payment-fields" style="display: none;">
                                                            <div class="mb-3">
                                                                <label for="check_number-{{ $transfer_id }}">Check Number</label>
                                                                <input type="text" name="check_number" class="form-control" placeholder="Enter check number">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="bank_name-{{ $transfer_id }}">Bank Name</label>
                                                                <input type="text" name="bank_name" class="form-control" placeholder="Enter bank name">
                                                            </div>
                                                        </div>

                                                        <!-- Online Fields -->
                                                        <div id="online-fields-{{ $transfer_id }}" class="payment-fields" style="display: none;">
                                                            <div class="mb-3">
                                                                <label for="transaction_id-{{ $transfer_id }}">Transaction ID</label>
                                                                <input type="text" name="transaction_id" class="form-control" placeholder="Enter transaction ID">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="payment_platform-{{ $transfer_id }}">Payment Platform</label>
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

<script>
    // Filter Logic
    function applyFilters() {
        const branchName = document.getElementById('filter-branch-name').value.toLowerCase();
        const transferId = document.getElementById('filter-transfer-id').value.toLowerCase();
        const transactionId = document.getElementById('filter-transaction-id').value.toLowerCase();
        const month = document.getElementById('filter-month').value;
        const status = document.getElementById('filter-status').value;

        const rows = document.querySelectorAll('.transfer-row');

        rows.forEach(row => {
            const rowBranchName = row.dataset.branchName.toLowerCase();
            const rowTransferId = row.dataset.transferId.toLowerCase();
            const rowTransactionId = row.dataset.transactionId.toLowerCase();
            const rowMonth = row.dataset.month;
            const rowStatus = row.dataset.status;

            const matchesBranch = branchName ? rowBranchName.includes(branchName) : true;
            const matchesTransferId = transferId ? rowTransferId.includes(transferId) : true;
            const matchesTransactionId = transactionId ? rowTransactionId.includes(transactionId) : true;
            const matchesMonth = month ? rowMonth === month : true;
            const matchesStatus = status ? (status === 'completed' ? rowStatus === 'true' : rowStatus === status) : true;

            if (matchesBranch && matchesTransferId && matchesTransactionId && matchesMonth && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Event Listeners
    document.getElementById('filter-branch-name').addEventListener('input', applyFilters);
    document.getElementById('filter-transfer-id').addEventListener('input', applyFilters);
    document.getElementById('filter-transaction-id').addEventListener('input', applyFilters);
    document.getElementById('filter-month').addEventListener('change', applyFilters);
    document.getElementById('filter-status').addEventListener('change', applyFilters);
</script>
@endsection
