@extends('layouts.admin.app')

@section('content')
<!-- Start of the form -->
<!-- Product and Supplier Form -->
<form id="product-form" method="POST" action="{{ url('/submit') }}">
    @csrf
    <div class="row">
        <!-- Supplier and Purchase Details -->
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">Supplier & Purchase Details</div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="supplier-name">Supplier Name</label>
                                <input type="text" id="supplier-name" name="supplier_name" class="form-control" placeholder="supplier name" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="purchase-date">Purchase Date</label>
                                <input type="date" id="purchase-date" name="purchase_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="transaction-id">Transaction ID</label>
                                <input type="text" id="transaction-id" name="transaction_id" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="branch">Branch</label>
                                <select id="branch" name="branch" class="form-control" required>
                                    <option value="" disabled selected>Select branch</option>
                                    @foreach ($br as $branch)
                                    <option value="{{$branch->id}}">{{$branch->branch_name}}</option>
                                    @endforeach


                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Selection -->
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="product-category">Select Product Category</label>
                        <select id="product-category" class="form-control">
                            <option value="" disabled selected>Select a category</option>
                            <option value="box">Box</option>
                            <option value="flower">Flower</option>
                        </select>
                    </div>

                    <div id="product-selection" class="form-group mb-3" style="display: none;">
                        <label for="product-name">Select Product Name</label>
                        <select id="product-name" class="form-control">
                            <option value="" disabled selected>Select a product</option>
                        </select>
                    </div>

                    <button id="add-product-btn" type="button" class="btn btn-primary" style="display: none;">Add Product</button>

                    <table id="product-table" class="table table-bordered" style="display: none;">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Purchase Price</th>
                                <th>Subtotal</th>
                                <th>Selling Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Rows dynamically added -->
                        </tbody>

                    </table>
                    <div class="col-md-4 offset-md-8">
                     <div class="form-group">
            <label for="total">Total Amount</label>
            <input type="number" id="total" name="total" class="form-control" value="0" readonly>
                     </div>
        </div>

                    <button type="submit" id="submit-btn" class="btn btn-success" style="display: none;">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="products" id="products">
</form>

<!-- Payment Form -->
<form id="payment-form" method="POST" action="{{ url('/payments') }}">
    @csrf
    <div class="col-12">
        <div class="card-header">Payment Details</div>
        <div class="col-md-4 offset-md-8">
        <div class="form-group mb-3">
            <label>Total</label>
            @if ($lastRecord)
            <input type="number" id="payment_total" name="payment_total" value="{{ $lastRecord->total }}" class="form-control" readonly>
            @endif
        </div>
    </div>
        <div class="form-group mb-3">

            @if ($lastRecord)
            <input type="hidden" id="purchase_id" name="purchase_id" value="{{ $lastRecord->purchase_id }}" class="form-control" readonly>
            @endif
        </div>
        <div class="col-md-4">
        <div class="form-group mb-3">
            <label>Cash Amount</label>
            <input type="number" name="pay_amount" class="form-control" placeholder="Enter cash amount" required oninput="validatePayAmount()">
            <span id="error-message" style="color: red; display: none;">Amount cannot exceed the total.</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label>Payment Date</label>
            <input type="date" name="payment_date" class="form-control">
        </div>
    </div>

        <div class="col-md-4">
        <div class="form-group mb-3">
            <label for="payment-method">Payment Method</label>
            <select id="payment-method" class="form-control" name="payment_method" onchange="togglePaymentFields()">
                <option value="" disabled selected>Select payment method</option>
                <option value="cash">Cash</option>
                <option value="check">Check</option>
                <option value="online">Online</option>
            </select>
        </div>
    </div>
        <!-- Check Payment Fields (Initially Hidden) -->
        <div id="check-fields" class="payment-fields" style="display: none;">
            <div class="form-group mb-3">
                <label>Check Number</label>
                <input type="text" name="check_number" class="form-control" placeholder="Enter check number">
            </div>
            <div class="form-group mb-3">
                <label>Bank Name</label>
                <input type="text" name="bank_name" class="form-control" placeholder="Enter bank name">
            </div>
        </div>

        <!-- Online Payment Fields (Initially Hidden) -->
        <div id="online-fields" class="payment-fields" style="display: none;">
            <div class="form-group mb-3">
                <label>Transaction ID</label>
                @if ($lasttransect)
                <input type="text" id="transection_id" name="transection_id" value="{{ $lasttransect->transaction_id }}" class="form-control" readonly>
                @endif

            </div>
            <div class="form-group mb-3">
                <label>Payment Platform</label>
                <input type="text" name="payment_platform" class="form-control" placeholder="Enter payment platform">
            </div>
        </div>
    </div>
    <div class="text-end">
        <button type="submit" class="btn btn-success">Submit Payment</button>
    </div>

</form>

<!-- End of the form -->

<script>
document.addEventListener('DOMContentLoaded', function () {
    const productCategoryDropdown = document.getElementById('product-category');
    const productNameDropdown = document.getElementById('product-name');
    const addProductBtn = document.getElementById('add-product-btn');
    const productTable = document.getElementById('product-table');
    const productTableBody = productTable.querySelector('tbody');
    const submitBtn = document.getElementById('submit-btn');
    const productsInput = document.getElementById('products');


    const productData = {
        box: @json($productbox),
        flower: @json($productflower)
    };

    // Update product dropdown based on category
    productCategoryDropdown.addEventListener('change', function () {
        const selectedCategory = this.value;

        if (productData[selectedCategory]) {
            productNameDropdown.innerHTML = '<option value="" disabled selected>Select a product</option>';
            productData[selectedCategory].forEach(product => {
                const option = document.createElement('option');
                option.value = product.product_name;
                option.textContent = product.product_name;
                productNameDropdown.appendChild(option);
            });

            document.getElementById('product-selection').style.display = 'block';
            addProductBtn.style.display = 'inline-block';
        }
    });

    // Add selected product to table
    addProductBtn.addEventListener('click', function () {
    const selectedCategory = productCategoryDropdown.value;
    const selectedProductName = productNameDropdown.value;

    if (selectedCategory && selectedProductName) {
        const product = productData[selectedCategory].find(p => p.product_name === selectedProductName);

        if (product) {
            const row = productTableBody.insertRow();
            row.innerHTML = `
                <input type="hidden" class="form-control" name="id[]" value="${product.product_id}">
                <td><input type="text" class="form-control" name="product_name[]" value="${product.product_name}" readonly></td>
                <td><input type="number" class="form-control quantity" name="quantity[]" value="1" min="1" data-product-id="${product.product_id}"></td>
                <td><input type="number" class="form-control purchase-price" name="purchase_price[]" value="${product.price_purchase}"></td>
                <td><input type="number" class="form-control subtotal" name="subtotal[]" value="${product.price_purchase}" readonly></td>
                <td><input type="number" class="form-control selling-price" name="selling_price[]" value="${product.price_selling}"></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-product-btn">Remove</button></td>
            `;
            productTable.style.display = 'table';
            submitBtn.style.display = 'inline-block';

            // Trigger stock update when product is added
            const quantityInput = row.querySelector('.quantity');
            const purchasePriceInput = row.querySelector('.purchase-price');
            const subtotalInput = row.querySelector('.subtotal');

            // Update subtotal when quantity or purchase price changes
            quantityInput.addEventListener('input', function () {
                updateSubtotal(row); // Update the subtotal when quantity changes
                updateTotal(); // Recalculate the overall total
            });

            purchasePriceInput.addEventListener('input', function () {
                updateSubtotal(row); // Update the subtotal when purchase price changes
                updateTotal(); // Recalculate the overall total
            });

            // Handle the row removal logic
            row.querySelector('.remove-product-btn').addEventListener('click', function () {
                row.remove();
                updateTotal(); // Recalculate the overall total after a row is removed
            });

            // Function to update the subtotal for the current row
            function updateSubtotal(row) {
                const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
                const purchasePrice = parseFloat(row.querySelector('.purchase-price').value) || 0;
                const subtotal = quantity * purchasePrice;

                // Update the subtotal field for the row
                row.querySelector('.subtotal').value = subtotal.toFixed(2);
            }

            // Function to update the overall total
            function updateTotal() {
                let total = 0;
                const rows = productTableBody.querySelectorAll('tr');

                rows.forEach(row => {
                    const subtotal = parseFloat(row.querySelector('.subtotal').value) || 0;
                    total += subtotal;
                });

                // Update the total input field
                const totalField = document.getElementById('total');
                totalField.value = total.toFixed(2);

            }

            // Initial subtotal calculation and total update
            updateSubtotal(row); // Initial calculation for the row
            updateTotal(); // Initial total calculation
        }
    }



// Add a click event listener to the submit button

});


    // Handle quantity and purchase price changes
    productTableBody.addEventListener('input', function (e) {
        if (e.target.classList.contains('quantity') || e.target.classList.contains('purchase-price')) {
            const row = e.target.closest('tr');
            const quantity = parseInt(row.querySelector('.quantity').value, 10);
            const purchasePrice = parseFloat(row.querySelector('.purchase-price').value);
            const subtotalField = row.querySelector('.subtotal');

            if (!isNaN(quantity) && !isNaN(purchasePrice) && quantity > 0) {
                // Update subtotal (quantity * purchase price)
                const subtotal = quantity * purchasePrice;
                subtotalField.value = subtotal;

                // Trigger stock update when quantity is changed
                const productId = row.querySelector('.quantity').getAttribute('data-product-id');
                updateStockQuantity(productId, quantity);
            }
        }
    });

    // Handle product removal
    productTableBody.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-product-btn')) {
            const row = e.target.closest('tr');
            const productId = row.querySelector('.quantity').getAttribute('data-product-id');
            const quantity = row.querySelector('.quantity').value;

            // Trigger stock update when product is removed
            updateStockQuantity(productId, -quantity); // Subtract quantity from stock

            // Remove the product row
            row.remove();

            // Hide the table and submit button if no rows remain
            if (productTableBody.rows.length === 0) {
                productTable.style.display = 'none';
                submitBtn.style.display = 'none';
            }
        }
    });

    // Update stock quantity through AJAX


    // Handle form submission
    document.getElementById('product-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const products = Array.from(productTableBody.rows).map(row => {
            return {
                id: row.querySelector('input[name="id[]"]').value,
                name: row.cells[0].textContent,
                quantity: row.querySelector('.quantity').value,
                purchase_price: row.querySelector('input[name="purchase_price[]"]').value,
                selling_price:  row.querySelector('input[name="selling_price[]"]').value
            };
        });

        productsInput.value = JSON.stringify(products);
        this.submit();
    });

    const transactionIdField = document.getElementById('transaction-id');

    // Function to generate the transaction ID
    function generateTransactionId() {
        const now = new Date();
        const transactionId = 'TXN-' + now.getFullYear() + (now.getMonth() + 1).toString().padStart(2, '0') + now.getDate().toString().padStart(2, '0') + '-' + Math.floor(Math.random() * 10000).toString().padStart(4, '0');
        return transactionId;
    }

    // Set the transaction ID field value when the page loads
    transactionIdField.value = generateTransactionId();
});


</script>


<script>
  function togglePaymentFields() {
        const paymentMethod = document.getElementById('payment-method').value;

        // Hide all payment-specific fields initially
        document.getElementById('check-fields').style.display = 'none';
        document.getElementById('online-fields').style.display = 'none';

        // Show specific fields based on selected payment method
        if (paymentMethod === 'check') {
            document.getElementById('check-fields').style.display = 'block';
        } else if (paymentMethod === 'online') {
            document.getElementById('online-fields').style.display = 'block';
        }
    }
    function validatePayAmount() {
        const total = parseFloat(document.getElementById('payment_total').value);
        const payAmount = parseFloat(document.querySelector('input[name="pay_amount"]').value);
        const errorMessage = document.getElementById('error-message');

        if (payAmount > total) {
            errorMessage.style.display = 'block';
        } else {
            errorMessage.style.display = 'none';
        }
    }

</script>
@endsection
