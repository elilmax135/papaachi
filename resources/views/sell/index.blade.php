@extends('layouts.admin.app')

@section('content')
<form id="product-form" method="POST" action="{{ url('/sell') }}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <!-- Customer & Order Details -->
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">Customer & Order Details</div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="customer-name">Customer Name</label>
                            <input type="text" id="customer_name" name="customer_name" class="form-control" placeholder="Customer name" required>
                        </div>
                        <div class="col-md-4">
                            <label for="customer-mobile">Customer Mobile</label>
                            <input type="text" id="customer_mobile" name="customer_mobile" class="form-control" placeholder="Customer mobile" required>
                        </div>
                        <div class="col-md-4">
                            <label for="sell-date">Date</label>
                            <input type="date" id="sell_date" name="sell_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <!-- Transaction ID -->
                        <div class="col-md-4">
                            <label for="transaction_id" class="form-label">Transaction ID</label>
                            <input
                                type="text"
                                id="transaction_id"
                                name="transaction_id"
                                class="form-control"
                                value="{{ uniqid('txn_') }}"
                                readonly>
                        </div>

                        <!-- Transport Mode -->
                        <div class="col-md-4">
                            <label for="transport_mode" class="form-label">Transport Mode</label>
                            <input
                                type="text"
                                id="transport_mode"
                                name="transport_mode"
                                class="form-control"
                                placeholder="e.g., van, own-vehicle"
                                required>
                        </div>

                        <!-- Select Service -->
                        <div class="col-md-4">
                            <label for="s_id" class="form-label">Select Branch</label>
                            <select
                                id="s_id"
                                name="s_id"
                                class="form-control">
                                <option value="" disabled selected>Select Branch</option>
                                @foreach ($branch as $br)
                                    <option value="{{$br->id}}">{{$br->branch_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="customer-address">Customer Address</label>
                            <input type="text" id="customer_address" name="customer_address" class="form-control" rows="2" placeholder="Customer address" required></textarea>
                        </div>

                         <div class="col-md-12">
                            <label for="doctor-confirmation">Doctor Confirmation (Image)</label>
                            <input type="file" id="doctor_confirmation" name="doctor_confirmation" class="form-control" accept="image/*" required>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card mb-3">
            <div class="card-header">Empaming Details</div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="empaming_type">Empaming Type</label>
                        <select id="emp_id" name="emp_id" class="form-control" onchange="showDetails()">
                            <option value="" disabled selected>Empaming Service</option>
                            <option value="service1">Person</option>

                        </select>

                        <div id="details" style="display: none; margin-top: 15px;">
                            <!-- Row 1: Person Name, Amount, and Remarks -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="person_name1">Person Name</label>
                                    <select id="person_name1" name="person_name1" class="form-control">
                                        <option value="" disabled selected>Select Person</option>
                                        @foreach ($staff as $stf)
                                        <option value="{{$stf->id}}">{{$stf->full_name}}</option>
                                        @endforeach


                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="amount1">Amount</label>
                                    <input type="number" id="amount1" name="amount1" class="form-control" placeholder="Enter Amount">
                                </div>
                                <div class="col-md-4">
                                    <label for="remarks1">Remarks</label>
                                    <input type="text" id="remarks1" name="remarks1" class="form-control" placeholder="Enter Remarks">
                                </div>
                            </div>

                            <!-- Row 2: Optional Person Details -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="person_name2">Person Name</label>
                                    <select id="person_name2" name="person_name2" class="form-control">
                                        <option value="" disabled selected>Select Person</option>
                                        @foreach ($staff as $stf)
                                        <option value="{{$stf->id}}">{{$stf->full_name}}</option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="amount2">Amount</label>
                                    <input type="number" id="amount2" name="amount2" class="form-control" placeholder="Enter Amount">
                                </div>
                                <div class="col-md-4">
                                    <label for="remarks2">Remarks</label>
                                    <input type="text" id="remarks2" name="remarks2" class="form-control" placeholder="Enter Remarks">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="empaming_amount">Empaming Amount</label>
                        <input type="number" id="empaming_amount" name="empaming_amount" class="form-control" min="0" value="0">
                    </div>
                    <div class="col-md-4">
                        <label for="empaming_date">Empaming Date</label>
                        <input type="date" id="empaming_date" name="empaming_date" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="panthal_amount" class="form-label">Panthal Amount</label>
                        <input type="number" id="panthal_amount" name="panthal_amount" class="form-control" min="0" value="0">
                    </div>
                    <div class="col-md-4">
                        <label for="lift_amount" class="form-label">Lifting Machine</label>
                        <input type="number" id="lift_amount" name="lift_amount" class="form-control" min="0" value="0">
                    </div>
                    <div class="col-md-4">
                        <label for="band_amount" class="form-label">Band Amount</label>
                        <input type="number" id="band_amount" name="band_amount" class="form-control" min="0" value="0">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="instrument_amount" class="form-label">Instrument Amount</label>
                        <input type="number" id="instrument_amount" name="instrument_amount" class="form-control" min="0" value="0">
                    </div>
                    <div class="col-md-4">
                        <label for="transport_amount" class="form-label">Transport Machine</label>
                        <input type="number" id="transport_amount" name="transport_amount" class="form-control" min="0" value="0">
                    </div>
                </div>
            </div>

        </div>
        </div>

        </div>
    </div>
    <div>

    </div>
    <input type="hidden" id="totalAmountx" name="total" value="0.00">
    <input type="hidden" name="products" id="products">
    </form>
        <!-- Product Selection -->
        <div class="card mb-3">
            <div class="card-header">Fetch Products</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="category">Category</label>
                        <select id="category" class="form-control">
                            <option value="box">Box</option>
                            <option value="flower">Flower</option>
                        </select>
                    </div>
                </div>
                <div id="productList" class="mt-3"></div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Available Quantity</th>
                            <th>Transfer Quantity</th>
                            <th>Selling Price</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="selectedProducts"></tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-end"><strong>Total Transfer Quantity:</strong></td>
                            <td id="totalQuantity">0</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Total Transfer Amount:</strong></td>
                            <td id="totalAmount" >$0.00</td>
                            <td></td>

                        </tr>
                    </tfoot>
                </table>
                <div class="col-12 text-end">
                    <button type="submit" id="submit-btn" class="btn btn-success" style="display: none;">Submit</button>
                </div>
            </div>
        </div>
<!-- End of the form -->
@endsection

<script>
    function showDetails() {
        var empSelect = document.getElementById("emp_id");
        var detailsDiv = document.getElementById("details");

        if (empSelect.value === "service1") {
            detailsDiv.style.display = "block";
        } else {
            detailsDiv.style.display = "none";
        }
    }
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>$(document).ready(function () {
    $("#category, #f_id").change(fetchProducts);

    function fetchProducts() {
        let category = $("#category").val();
        let f_id = $("#s_id").val();

        if (!f_id || !category) {
            $("#productList").html("<p style='color:red;'>Please select both a branch and category.</p>");
            return;
        }

        $.ajax({
            url: "{{ url('/products') }}",
            type: "GET",
            data: { branch_id: f_id, category: category },
            dataType: "json",
            success: function (response) {
                if (response.length > 0) {
                    let html = "<ul class='list-group'>";
                    response.forEach(product => {
                        html += `
                            <li class='list-group-item d-flex justify-content-between align-items-center'>
                                ${product.product_name} - Available: ${product.total_quantity}, Price: $${product.selling_price}
                                <button class="btn btn-sm btn-success addProduct"
                                    data-id="${product.product_id}"
                                    data-name="${product.product_name}"
                                    data-quantity="${product.total_quantity}"
                                    data-price="${product.selling_price}">
                                    Add
                                </button>
                            </li>`;
                    });
                    html += "</ul>";
                    $("#productList").html(html);
                } else {
                    $("#productList").html("<p style='color:red;'>No products found.</p>");
                }
            },
            error: function (xhr) {
                console.log(xhr.responseText); // Debugging
                $("#productList").html("<p style='color:red;'>Error fetching products.</p>");
            }
        });
    }

    $(document).on("click", ".addProduct", function () {
        let productId = $(this).data("id");
        let productName = $(this).data("name");
        let availableQuantity = $(this).data("quantity");
        let price = parseFloat($(this).data("price"));

        if ($(`#row-${productId}`).length > 0) {
            alert("Product already added.");
            return;
        }

        let row = `
            <tr id="row-${productId}" data-product-id="${productId}">
                <td>${productName}</td>
                <td>${availableQuantity}</td>
                <td>
                    <input type="number" class="form-control transferQuantity" data-id="${productId}" data-price="${price}" min="0" max="${availableQuantity}" value="0">
                </td>
                <td>
                    <input type="number" class="form-control sellingPrice" data-id="${productId}" value="${price.toFixed(2)}" step="0.01">
                </td>
                <td class="subTotal">$${price.toFixed(2)}</td>
                <td><button type="button" class="btn btn-danger removeProduct">Remove</button></td>
            </tr>`;

        $("#selectedProducts").append(row);
        updateTotals();
        $("#submit-btn").show();
    });

    $(document).on("input", ".transferQuantity, .sellingPrice", function () {
    let row = $(this).closest("tr");

    // Get quantity and price
    let quantity = parseInt(row.find(".transferQuantity").val()) || 0;
    let maxQuantity = parseInt(row.find(".transferQuantity").attr("max"));
    let price = parseFloat(row.find(".sellingPrice").val()) || 0;

    // Ensure quantity stays within valid range
    if (quantity < 1) {
        quantity = 1;
        row.find(".transferQuantity").val(1);
    } else if (quantity > maxQuantity) {
        quantity = maxQuantity;
        row.find(".transferQuantity").val(maxQuantity);
    }

    // Ensure price is not negative
    if (price < 0) {
        price = 0;
        row.find(".sellingPrice").val(price.toFixed(2));
    }

    // Calculate and update subtotal
    let subTotal = quantity * price;
    row.find(".subTotal").text(`$${subTotal.toFixed(2)}`);

    // Update overall totals
    updateTotals();
});
// Add event listener for removing a product (event delegation)
$("#selectedProducts").on("click", ".removeProduct", function () {
    $(this).closest("tr").remove();
    updateTotals();
    if ($("#selectedProducts tr").length === 0) $("#submit-btn").hide();
});


// Update the overall total (called by other event listeners)
function updateTotals() {
    let totalQuantity = 0;
    let totalAmount = 0;

    // Iterate over each row to calculate the total cost
    $(".transferQuantity").each(function () {
        let quantity = parseInt($(this).val()) || 0;
        let price = parseFloat($(this).closest("tr").find(".sellingPrice").val()) || 0;

        let subTotal = quantity * price;
        $(this).closest("tr").find(".subTotal").text(`$${subTotal.toFixed(2)}`); // Update subtotal for this row

        totalQuantity += quantity;
        totalAmount += subTotal;
    });

    // Fetch all additional charge amounts
    let empamingAmount = parseFloat($("#empaming_amount").val()) || 0;
    let panthalAmount = parseFloat($("#panthal_amount").val()) || 0;
    let liftAmount = parseFloat($("#lift_amount").val()) || 0;
    let bandAmount = parseFloat($("#band_amount").val()) || 0;
    let instrumentAmount = parseFloat($("#instrument_amount").val()) || 0;
    let transportAmount = parseFloat($("#transport_amount").val()) || 0;

    // Add all charges to the total amount
    totalAmount += empamingAmount + panthalAmount + liftAmount + bandAmount + instrumentAmount + transportAmount;

    // Update the UI with total quantity and amount
    $("#totalQuantity").text(totalQuantity);
    $("#totalAmount").text(`$${totalAmount.toFixed(2)}`);
    $("#totalAmountx").val(totalAmount.toFixed(2));
}

// Recalculate totals when any input field changes
$(document).on("input", ".transferQuantity, .sellingPrice, #empaming_amount, #panthal_amount, #lift_amount, #band_amount, #instrument_amount, #transport_amount", updateTotals);

    $("#product-form").submit(function (e) {
        if ($("#selectedProducts tr").length === 0) {
            alert("Please add at least one product before submitting.");
            e.preventDefault();
            return;
        }

        let products = [];
        $("#selectedProducts tr").each(function () {
            let productId = $(this).data("product-id");
            let quantity = parseInt($(this).find(".transferQuantity").val());
            let price = parseFloat($(this).find(".sellingPrice").val());

            if (quantity > 0) {
                products.push({
                    product_id: productId,
                    quantity: quantity,
                    price: price
                });
            }
        });

        if (products.length === 0) {
            alert("No products selected!");
            e.preventDefault();
            return;
        }

        $("#products").val(JSON.stringify(products));
    });

    $("#submit-btn").click(function () {
        $("#product-form").submit();
    });

    // Ensure expenses trigger total amount update
    $(".expense-input").on("input", updateTotals);
});

</script>
