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
                                <input type="text" id="supplier-name" name="supplier_name" class="form-control" required>
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
                                    <option value="branch1">Branch 1</option>
                                    <option value="branch2">Branch 2</option>
                                    <option value="branch3">Branch 3</option>
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

                    <button type="submit" id="submit-btn" class="btn btn-success" style="display: none;">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="products" id="products">
</form>

<!-- Payment Form -->
<form id="payment-form" method="POST" action="{{ url('/payment-submit') }}">
    @csrf
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-header">Payment Details</div>
            <div class="card-body">
                <div class="form-group mb-3">
                    <label for="payment-method">Payment Method</label>
                    <select id="payment-method" class="form-control" name="payment_method" onchange="togglePaymentFields()">
                        <option value="" disabled selected>Select payment method</option>
                        <option value="cash">Cash</option>
                        <option value="check">Check</option>
                        <option value="online">Online</option>
                    </select>
                </div>

                <div id="cash-fields" class="payment-fields" style="display: none;">
                    <div class="form-group mb-3">
                        <label for="cash-amount">Cash Amount</label>
                        <input type="number" id="cash-amount" class="form-control" name="cash_amount" placeholder="Enter cash amount">
                    </div>
                </div>

                <div id="check-fields" class="payment-fields" style="display: none;">
                    <div class="form-group mb-3">
                        <label for="check-number">Check Number</label>
                        <input type="text" id="check-number" class="form-control" name="check_number" placeholder="Enter check number">
                    </div>
                    <div class="form-group mb-3">
                        <label for="bank-name">Bank Name</label>
                        <input type="text" id="bank-name" class="form-control" name="bank_name" placeholder="Enter bank name">
                    </div>
                </div>

                <div id="online-fields" class="payment-fields" style="display: none;">
                    <div class="form-group mb-3">
                        <label for="transaction-id">Transaction ID</label>
                        <input type="text" id="online-transaction-id" class="form-control" name="online_transaction_id" placeholder="Enter transaction ID">
                    </div>
                    <div class="form-group mb-3">
                        <label for="payment-platform">Payment Platform</label>
                        <input type="text" id="payment-platform" class="form-control" name="payment_platform" placeholder="Enter payment platform (e.g., PayPal, Stripe)">
                    </div>
                </div>

                <button type="submit" class="btn btn-success">Submit Payment</button>
            </div>
        </div>
    </div>
</form>
<!-- End of the form -->

<script>document.addEventListener('DOMContentLoaded', function () {
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
                    <td><input type="number" class="form-control" name="selling_price[]" value="${product.price_selling}"></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-product-btn">Remove</button></td>
                `;
                productTable.style.display = 'table';
                submitBtn.style.display = 'inline-block';

                // Trigger stock update when product is added
                const quantity = row.querySelector('.quantity').value;
                updateStockQuantity(product.product_id, quantity); // Add quantity to stock
            }
        }
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
    function updateStockQuantity(productId, quantityChange) {
        fetch('{{ url("/update-product-stock") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity_change: quantityChange
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Stock updated successfully');
            } else {
                console.error('Failed to update stock');
            }
        })
        .catch(error => console.error('Error:', error));
    }

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
    // Get the selected payment method
    const method = document.getElementById('payment-method').value;

    // Get all payment field containers
    const cashFields = document.getElementById('cash-fields');
    const checkFields = document.getElementById('check-fields');
    const onlineFields = document.getElementById('online-fields');

    // Hide all fields initially
    cashFields.style.display = 'none';
    checkFields.style.display = 'none';
    onlineFields.style.display = 'none';

    // Show the relevant fields based on the selected payment method
    if (method === 'cash') {
        cashFields.style.display = 'block';
    } else if (method === 'check') {
        checkFields.style.display = 'block';
    } else if (method === 'online') {
        onlineFields.style.display = 'block';
    }
}

</script>
@endsection
