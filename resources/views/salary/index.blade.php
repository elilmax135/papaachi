@extends('layouts.admin.app')

@section('content')

<!-- Filter Form -->
<div class="card mb-3">
    <div class="card-header">
        Salary
    </div>
    <div class="card-body">
<table class="table table-bordered table-striped" id="salaryTable">
    <thead>
        <tr>
            <th>Staff Name</th>
            <th>Total Payment</th>
            <th>Total Paid</th>
            <th>Total Due</th>
            <th>Salary Status</th>
            <th>Latest Payment Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($salary as $row)
        <tr class="salary-row">
            <td class="staff_name">{{ $row->full_name }}</td>
            <td class="total_payment">{{ $row->payment }}</td>
            <td class="total_paid">{{ $row->paid }}</td>
            <td class="total_due">{{ $row->payment - $row->paid }}</td>
            <td class="salary_status">
                @php
                    if ($row->paid == 0) {
                        $salary_status = 'fail';
                    } elseif ($row->paid < $row->payment) {
                        $salary_status = 'pending';
                    } else {
                        $salary_status = 'completed';
                    }
                @endphp
                @if ($salary_status == 'fail')
                    <span class="badge bg-danger">Failed</span>
                @elseif ($salary_status == 'pending')
                    <span class="badge bg-warning">Pending</span>
                @else
                    <span class="badge bg-success">Completed</span>
                @endif
            </td>
            <td class="latest_payment_date">{{ $row->payment_date ?? 'N/A' }}</td>
            <td>
                <!-- Pay Salary Button -->
                <button class="btn btn-dark btn-pay"
                data-bs-toggle="modal"
                data-bs-target="#paySalaryModal"
                data-id="{{ $row->staff_id }}"
                data-name="{{ $row->full_name }}"
                data-total-due="{{ $row->payment - $row->paid }}">
                Pay Salary
            </button>


            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Pay Salary Modal (placed once outside the loop) -->
<!-- Pay Salary Modal (placed once outside the loop) -->
<div class="modal fade" id="paySalaryModal" tabindex="-1" aria-labelledby="paySalaryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="paySalaryForm" action="{{ url('/pay-staffsalary') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="paySalaryModalLabel">Pay Salary</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="staff_id" id="staffId">
                    <div class="mb-3">
                        <label for="staffName" class="form-label">Staff Name</label>
                        <input type="text" class="form-control" id="staffName" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="totalDue" class="form-label">Total Due</label>
                        <input type="text" class="form-control" id="totalDue" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="paymentAmount" class="form-label">Payment Amount</label>
                        <input type="number" class="form-control" id="paymentAmount" name="payment_amount" required>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark">Submit Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>


<!-- JavaScript for Live Filtering -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get input fields
        let staffNameFilter = document.getElementById('staff_name_filter');
        let sellsIdFilter = document.getElementById('sells_id_filter');
        let paymentDateFilter = document.getElementById('payment_date_filter');

        // Function to filter the table
        function filterTable() {
            let staffNameValue = staffNameFilter.value.toLowerCase();
            let sellsIdValue = sellsIdFilter.value.toLowerCase();
            let paymentDateValue = paymentDateFilter.value;

            let rows = document.querySelectorAll('#salaryTable .salary-row');

            rows.forEach(function (row) {
                let staffName = row.querySelector('.staff_name').textContent.toLowerCase();
                let sellsId = row.querySelector('.sells_id').textContent.toLowerCase();
                let paymentDate = row.querySelector('.latest_payment_date').textContent;

                let matchesStaffName = staffName.includes(staffNameValue);
                let matchesSellsId = sellsId.includes(sellsIdValue);
                let matchesPaymentDate = paymentDate.includes(paymentDateValue);

                row.style.display = matchesStaffName && matchesSellsId && matchesPaymentDate ? '' : 'none';
            });
        }

        // Add event listeners for real-time filtering
        staffNameFilter.addEventListener('input', filterTable);
        sellsIdFilter.addEventListener('input', filterTable);
        paymentDateFilter.addEventListener('change', filterTable);
    });

    $(document).ready(function () {
        $('#salaryTable').DataTable(); // Initialize DataTable
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Select all Pay Salary buttons
        const payButtons = document.querySelectorAll('.btn-pay');

        payButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Retrieve data from the clicked button
                const staffId = this.getAttribute('data-id');
                const staffName = this.getAttribute('data-name');
                const totalDue = this.getAttribute('data-total-due');

                // Populate the modal's fields
                document.getElementById('staffId').value = staffId;
                document.getElementById('staffName').value = staffName;
                document.getElementById('totalDue').value = totalDue;
                document.getElementById('paymentAmount').setAttribute('max');
                // Optionally prefill the payment amount with the due amount

            });
        });
    });
    </script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const paySalaryForm = document.getElementById('paySalaryForm');
        const paymentAmountInput = document.getElementById('paymentAmount');
        const totalDueInput = document.getElementById('totalDue');

        paySalaryForm.addEventListener('submit', function (event) {
            const paymentAmount = parseFloat(paymentAmountInput.value);
            const totalDue = parseFloat(totalDueInput.value);

            // Check if payment amount is greater than the due amount
            if (paymentAmount > totalDue) {
                event.preventDefault(); // Prevent form submission
                alert('Payment amount cannot exceed the total due.');
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#salaryTable').DataTable(); // Initialize DataTable
    });
</script>


@endsection
