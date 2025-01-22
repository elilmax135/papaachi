@extends('layouts.admin.app')

@section('content')
<!-- Transfer Form -->
<form id="product-form" action="{{ url('/trans') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">Transfer Details</div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="transfer_date">Date</label>
                            <input type="date" id="transfer_date" name="transfer_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="t_id">To Branch</label>
                            <select id="t_id" name="t_id" class="form-control">
                                <option value="" disabled selected>Select Branch</option>
                                @foreach ($branch as $br)
                                    <option value="{{ $br->id }}">{{ $br->branch_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="f_id">From Branch</label>
                            <select id="f_id" name="f_id" class="form-control">
                                <option value="" disabled selected>Select Branch</option>
                                @foreach ($branch as $br)
                                    <option value="{{ $br->id }}">{{ $br->branch_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="transaction_id">Transaction ID</label>
                            <input type="text" id="transaction_id" name="transaction_id" class="form-control" value="{{ uniqid('txn_') }}" readonly>
                        </div>
                        <input type="hidden" id="totalAmountx" name="total" value="0.00">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="products" id="products">
</form>

<!-- Fetch Products Section -->
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
                    <td id="totalAmount">$0.00</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        <div class="col-12 text-end">
            <button type="submit" id="submit-btn" class="btn btn-success" style="display: none;">Submit</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $("#category, #f_id").change(fetchProducts);

    function fetchProducts() {
        let category = $("#category").val();
        let f_id = $("#f_id").val();

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
                    <input type="number" class="form-control transferQuantity"
                        data-id="${productId}"
                        min="0" max="${availableQuantity}"
                        value="1">
                </td>
                <td>
                    <input type="number" class="form-control sellingPrice"
                        data-id="${productId}"
                        min="0" step="0.01"
                        value="${price.toFixed(2)}">
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
        let quantity = parseInt(row.find(".transferQuantity").val()) || 1;
        let price = parseFloat(row.find(".sellingPrice").val()) || 0;

        if (quantity < 1) {
            quantity = 1;
            row.find(".transferQuantity").val(1); // Ensure quantity doesn't go below 1
        }

        if (price < 0) {
            price = 0;
            row.find(".sellingPrice").val(0); // Ensure price is not negative
        }

        let subTotal = quantity * price;
        row.find(".subTotal").text(`$${subTotal.toFixed(2)}`);

        updateTotals();
    });

    $(document).on("click", ".removeProduct", function () {
        $(this).closest("tr").remove();
        updateTotals();
        if ($("#selectedProducts tr").length === 0) $("#submit-btn").hide();
    });

    function updateTotals() {
        let totalQuantity = 0;
        let totalAmount = 0;

        $(".transferQuantity").each(function () {
            let row = $(this).closest("tr");
            let quantity = parseInt(row.find(".transferQuantity").val()) || 0;
            let price = parseFloat(row.find(".sellingPrice").val()) || 0;

            totalQuantity += quantity;
            totalAmount += quantity * price;
        });

        $("#totalQuantity").text(totalQuantity);
        $("#totalAmount").text(`$${totalAmount.toFixed(2)}`);
        $("#totalAmountx").val(totalAmount.toFixed(2));
    }

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
});


</script>

@endsection
