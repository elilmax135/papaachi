@extends('layouts.admin.app')

@section('content')

<!-- Filter Form -->


<!-- Start of the Table -->
<table class="table table-bordered table-striped" id="salaryTable">
    <thead>
        <tr>
            <th>Sale ID</th>
            <th>Staff Name</th>
            <th>Customer Name</th>
            <th>Total Payment</th>
            <th>Total Paid</th>
            <th>Total Due</th>
            <th>Salary Status</th>
            <th>Latest Payment Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($salary as $row)
        <tr class="salary-row">
            <td class="sells_id">{{ $row->sells_id }}</td>
            <td class="staff_name">{{ $row->full_name }}</td>
            <td class="customer_name">{{ $row->customer_name }}</td>
            <td class="total_payment">{{ $row->total_payment }}</td>
            <td class="total_paid">{{ $row->total_paid }}</td>
            <td class="total_due">{{ $row->total_payment - $row->total_paid }}</td>
            <td class="salary_status">
                @php
                    if ($row->total_paid == 0) {
                        $salary_status = 'fail';
                    } elseif ($row->total_paid < $row->total_payment) {
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
            </td>
            <td class="latest_payment_date">{{ $row->latest_payment_date ?? 'N/A' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

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

@endsection
