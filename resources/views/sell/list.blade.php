@extends('layouts.admin.app')

@section('content')

<!-- Start of the form -->
<div class="row">
    <div class="col-12">


        <div class="card mb-3">

                <table class="table table-bordered table-striped" id="selltable">
                    <thead>
                        <tr>
                            <th>Sell ID</th>
                            <th>Customer Name</th>
                            <th>Customer Mobile</th>
                            <th>Sale Date</th>
                            <th>Customer Address</th>


                            <th>Sale Total</th>
                            <th>Sale Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sell as $sale_id => $sale_group)
                        <tr class="sale-row" data-customer-name="{{ $sale_group[0]->customer_name }}" data-sale-id="{{ $sale_id }}">
                            <td>{{ $sale_group[0]->id }}</td>
                            <td>{{ $sale_group[0]->customer_name }}</td>
                            <td>{{ $sale_group[0]->customer_mobile }}</td>
                            <td>{{ $sale_group[0]->sell_date }}</td>
                            <td>{{ $sale_group[0]->customer_address }}</td>

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
                                <!-- Action Dropdown -->
                                <div class="dropdown">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="actionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-cogs"></i> Actions
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="actionDropdown">
                                        <!-- Pay Now Button -->
                                        <li>
                                            <button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#payNowModal-{{ $sale_id }}">
                                                <i class="fas fa-credit-card"></i>Pay Now
                                            </button>
                                        </li>

                                        <!-- Sale Details Button -->
                                        <li>
                                            <a href="{{ url('/sales/details/' . $sale_id) }}" class="dropdown-item">
                                                <i class="fas fa-info-circle"></i>Details
                                            </a>
                                        </li>

                                        <!-- Pay Salary and Sale Button for Two People -->
                                        <li>
                                            <button type="button" class="dropdown-item salary-btn" data-id11="{{ $sale_id }}">
                                                <i class="fas fa-dollar-sign"></i>View Salary
                                            </button>
                                        </li>
                                        @if($sale_group[0]->doctor_confirm)
                                        <li>
                                            <button type="button" class="dropdown-item doctor-btn" data-bs-toggle="modal" data-bs-target="#doctorValueModal-{{ $sale_id }}">
                                                <i class="fas fa-user-md"></i> View Doctor Confirmation
                                            </button>
                                        </li>
                                    @else
                                        <li>
                                            <button type="button" class="dropdown-item add-doctor-btn" data-bs-toggle="modal" data-bs-target="#addDoctorModal-{{ $sale_id }}">
                                                <i class="fas fa-user-plus"></i> Add Doctor Confirmation
                                            </button>
                                        </li>
                                    @endif


                                        <!-- Delete Button -->
                                        <li>
                                            <form action="{{ url('/delete-sell/' . $sale_id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this purchase and all its products?')">
                                                    <i class="fas fa-trash"></i>Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Pay Now Modal -->
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
                                                        <input type="hidden" class="form-control" name="sale_id" value="{{ $sale_group[0]->id }}">
                                                        <label class="form-label">Sale Total</label>
                                                        <input type="number" class="form-control" name="sale_total" value="{{ $sale_group[0]->total }}" readonly>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Due</label>
                                                        <input type="number" class="form-control" name="sale_total" value="{{ $sale_group[0]->last_pay_due ?? $sale_group[0]->total }}" readonly>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Enter Amount</label>
                                                        <input type="number" class="form-control" name="pay_amount" placeholder="Enter amount">
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

                                <!-- Pay Salary Modal for Two People -->
     <!-- Pay Salary Modal -->
     <div class="modal fade" id="payHybridModal" tabindex="-1" aria-labelledby="payHybridModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"> <!-- Changed to modal-lg for large size -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pay Salaries</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="salaryPaymentForm" action="" method="POST">
                        @csrf <!-- Include CSRF Token -->
                        <input type="hidden" name="sell_id" id="sellIdField">

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Salary</th>

                                </tr>
                            </thead>
                            <tbody id="salaryDetailsBody">
                                <!-- Dynamic Data will be inserted here -->
                            </tbody>
                        </table>


                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($sale_group[0]->doctor_confirm)
    <!-- Modal to display the doctor_confirm value -->
    <div class="modal fade" id="doctorValueModal-{{ $sale_id }}" tabindex="-1" aria-labelledby="doctorValueLabel-{{ $sale_id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="doctorValueLabel-{{ $sale_id }}">Doctor Confirmation for Sale ID: {{ $sale_id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($sale_group[0]->doctor_confirm)
                    <div style="border: 1px solid #ddd; padding: 10px; text-align: center;">
                        <img src="{{ asset('/doctorImage/' . $sale_group[0]->doctor_confirm) }}" alt="Doctor Image" style="max-width: 100%; height: auto;">
                    </div>
                @else
                    <div style="border: 1px solid #ddd; padding: 10px; text-align: center;">
                        <p>No Doctor Image Available.</p>
                    </div>
                @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Modal for adding doctor image via form -->
    <div class="modal fade" id="addDoctorModal-{{ $sale_id }}" tabindex="-1" aria-labelledby="addDoctorLabel-{{ $sale_id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDoctorLabel-{{ $sale_id }}">Add Doctor for Sale ID: {{ $sale_id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addDoctorForm-{{ $sale_id }}" action="{{url('/doctor_confirm',$sale_id)}}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="doctorImage-{{ $sale_id }}" class="form-label">Upload Doctor Image</label>
                            <input type="file" class="form-control" id="doctorImage-{{ $sale_id }}" name="doctorImage" accept="image/*" required>
                        </div>
                        <input type="hidden" name="sale_id" value="{{ $sale_id }}">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="addDoctorForm-{{ $sale_id }}" class="btn btn-primary">Add Doctor</button>
                </div>
            </div>
        </div>
    </div>
@endif



                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

        </div>
    </div>
</div>


<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<!-- Bootstrap JS (optional) -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<!-- Filter Script -->
<script>

    // JavaScript to handle the scroll down button

    document.getElementById('sales-filter-input').addEventListener('input', function () {
        const filterValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('.sale-row');

        rows.forEach(function (row) {
            const customerName = row.getAttribute('data-customer-name').toLowerCase();
            const saleId = row.getAttribute('data-sale-id').toString().toLowerCase();

            if (customerName.includes(filterValue) || saleId.includes(filterValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

<!-- Toggle Payment Fields Script -->
<script>
    function togglePaymentFields(saleId) {
        const paymentMethod = document.getElementById(`payment-method-${saleId}`).value;
        const checkFields = document.getElementById(`check-fields-${saleId}`);
        const onlineFields = document.getElementById(`online-fields-${saleId}`);

        // Hide all payment fields initially
        checkFields.style.display = "none";
        onlineFields.style.display = "none";

        // Show fields based on selected payment method
        if (paymentMethod === "check") {
            checkFields.style.display = "block";
        } else if (paymentMethod === "online") {
            onlineFields.style.display = "block";
        }
    }
</script>

<script>

$(document).ready(function () {
    $(".salary-btn").click(function () {
        let sellId = $(this).data("id11"); // Get sale ID
        $("#sellIdField").val(sellId); // Assign it to the hidden input field

        $.ajax({
            url: "/get-salary/" + sellId,
            type: "GET",
            success: function (response) {
                let salaryDetails = response.salaries;
                let html = "";

                if (salaryDetails.length > 0) {
                    salaryDetails.forEach(function (salary) {
                        html += `
                            <tr>
                                <td>${salary.full_name}</td>
                                <td>${salary.salary_amount}</td>

                            </tr>`;
                    });
                } else {
                    html = `<tr><td colspan="7" class="text-center">No Salary Assigned</td></tr>`;
                }

                $("#salaryDetailsBody").html(html);
                $("#payHybridModal").modal("show");
            },
            error: function () {
                alert("Failed to fetch salary details!");
            }
        });
    });
});


    </script>

<script>
    $(document).ready(function() {
        $('#selltable').DataTable({
            "order": [[0, "desc"]] // Order by first column (index 0) in descending order
        }); // Initialize DataTable
    });
</script>


@endsection
