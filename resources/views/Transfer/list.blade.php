@extends('layouts.admin.app')

@section('content')
<div class="row">
    <!-- Transfer Details -->
    <div class="col-12">


        <div class="card mb-3">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="transfertable">
                    <thead>
                        <tr>
                            <th>Transfer id</th>
                            <th>To Branch</th>
                            <th>Transfer Date</th>
                            <th>Transaction ID</th>
                            <th>Transfer Total</th>
                            <th>Transfer Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transfers as $transfer_id => $transfer_group)
                        <tr class="transfer-row" data-branch-name="{{ $transfer_group[0]->branch_name }}" data-transfer-id="{{ $transfer_id }}">
                            <td>{{ $transfer_group[0]->id  }}</td>
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
                                <!-- Dropdown Button -->
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton-{{ $transfer_id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-cogs"></i>  Actions
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton-{{ $transfer_id }}">
                                        <li>
                                            <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#payNowModal-{{ $transfer_id }}">
                                                <i class="fas fa-credit-card"></i> Pay Now
                                            </button>
                                        </li>
                                        <li>
                                            <a href="{{ url('/transdetails/' . $transfer_id) }}" class="dropdown-item">
                                                <i class="fas fa-info-circle"></i> Details
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ url('/delete-transfer/' . $transfer_id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this transfer and all its products?')">
                                                    <i class="fas fa-trash"></i>Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>

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
    // Scroll to Bottom of the Table
    document.getElementById('scrollDownButton')?.addEventListener('click', function () {
        document.querySelector('.table-responsive')?.scrollTo({ top: document.querySelector('.table-responsive').scrollHeight, behavior: 'smooth' });
    });

    document.getElementById('filter-input').addEventListener('input', function () {
    const filterValue = this.value.trim().toLowerCase(); // Get and normalize the filter input
    const rows = document.querySelectorAll('.transfer-row'); // Select all transfer rows

    rows.forEach(row => {
        const branchName = row.dataset.branchName?.toLowerCase() || ''; // Get branch name from data attribute
        const transferId = row.dataset.transferId?.toLowerCase() || ''; // Get transfer ID from data attribute
        const transactionId = row.querySelector('td:nth-child(3)')?.textContent.trim().toLowerCase() || ''; // Get transaction ID

        // Show or hide the row based on whether branch name, transfer ID, or transaction ID matches filter input
        if (
            branchName.includes(filterValue) ||
            transferId.includes(filterValue) ||
            transactionId.includes(filterValue) // Ensuring Transaction ID is included in filtering
        ) {
            row.style.display = ''; // Show the row
        } else {
            row.style.display = 'none'; // Hide the row
        }
    });
});


    // Toggle Additional Payment Fields
    function togglePaymentFields(transferId) {
        const method = document.getElementById('payment-method-' + transferId).value;
        document.getElementById('check-fields-' + transferId).style.display = method === 'check' ? 'block' : 'none';
        document.getElementById('online-fields-' + transferId).style.display = method === 'online' ? 'block' : 'none';
    }
</script>
<script>
    $(document).ready(function() {
        $('#transfertable').DataTable(); // Initialize DataTable
    });
</script>
@endsection
