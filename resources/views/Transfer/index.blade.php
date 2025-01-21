@extends('layouts.admin.app')

@section('content')
<form id="product-form" action="{{url('/trans')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <!-- Customer & Order Details -->
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">Transfer Details</div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="transfer-date">Date</label>
                            <input type="date" id="transfer_date" name="transfer_date" class="form-control" required>
                        </div>
                        <div class="col-md-4 offset-md-2">
                            <label for="t_id" class="form-label">To</label>
                            <select id="t_id" name="t_id" class="form-control">
                                <option value="" disabled selected>Select Branch</option>
                                @foreach ($branch as $br)
                                <option value="{{ $br->id }}">{{ $br->branch_name }}</option>
                                @endforeach
                            </select>
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


                        <!-- Select Service -->

                    </div>
                </div>
            </div>
        </div>

        <!-- Product Selection -->
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-header">Product Selection</div>
                <div class="card-body">
                    <label for="product-category">Product Category</label>
                    <select id="product-category" class="form-control">
                        <option value="" disabled selected>Select a category</option>
                        <option value="box">Box</option>
                        <option value="flower">Flower</option>
                    </select>

                    <div id="product-selection" class="form-group mt-3" style="display: none;">
                        <label for="product-name">Product Name</label>
                        <select id="product-name" class="form-control">
                            <option value="" disabled selected>Select a product</option>
                        </select>
                    </div>

                    <button id="add-product-btn" type="button" class="btn btn-primary mt-3" style="display: none;">
                        Add Product
                    </button>

                    <table id="product-table" class="table table-bordered mt-3" style="display: none;">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Purchase Price</th>
                                <th>Selling Price</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <div class="form-group mt-3">
                        <label for="total">Total Amount</label>
                        <input type="number" id="total" name="total" class="form-control" value="0" readonly>
                    </div>

                    <input type="hidden" name="products" id="products">
                </div>
            </div>
        </div>
    </div>


    <button type="submit" id="submit-btn" class="btn btn-success" style="display: none;">Submit</button>
</form>





<!-- Payment Form -->
<form id="payment-form" method="POST" action="{{ url('/transferPay') }}">
    @csrf
    <div class="col-12">
        <div class="card-header">Payment Details</div>
        <div class="card-body">
            <!-- Payment Method & Fields -->
            <div class="row mb-3">
                <div class="form-group mb-3">
                    <label>Total</label>
                    @if($lastRecord)
                    <input type="number" id="payment_total" name="payment_total" value="{{$lastRecord->total  }}" class="form-control" readonly>
                    @endif

                </div>

                <div class="form-group mb-3">

                    @if($lastRecord)
                    <input type="hidden" id="transfer_id" name="transfer_id" value="{{$lastRecord->id  }}" class="form-control" readonly>
                    @endif
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="pay_amount">Cash Amount</label>
                        <input type="number" name="pay_amount" class="form-control" placeholder="Enter cash amount" required oninput="validatePayAmount()">
                        <span id="error-message" style="color: red; display: none;">Amount cannot exceed the total.</span>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="payment_date">Payment Date</label>
                        <input type="date" name="payment_date" class="form-control">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="payment-method">Payment Method</label>
                        <select id="payment-method" class="form-control" name="payment_method" onchange="togglePaymentFields()">
                            <option value="" disabled selected>Select payment method</option>
                            <option value="cash">Cash</option>
                            <option value="check">Check</option>
                            <option value="online">Online</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Check Payment Fields (Initially Hidden) -->
            <div id="check-fields" class="payment-fields" style="display: none;">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Check Number</label>
                            <input type="text" name="check_number" class="form-control" placeholder="Enter check number">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Bank Name</label>
                            <input type="text" name="bank_name" class="form-control" placeholder="Enter bank name">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Online Payment Fields (Initially Hidden) -->
            <div id="online-fields" class="payment-fields" style="display: none;">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Transaction ID</label>
                            @if($lastRecord)
                            <input type="number" id="payment_total" name="payment_total" value="{{$lastRecord->	transection_id  }}" class="form-control" readonly>
                            @endif
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Payment Platform</label>
                            <input type="text" name="payment_platform" class="form-control" placeholder="Enter payment platform">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Submit Payment -->
        </div>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-success">Submit Payment</button>
    </div>
</form>

<!-- End of the form -->
@endsection

<script>document.addEventListener('DOMContentLoaded', function () {
    const productCategoryDropdown = document.getElementById('product-category');
    const productNameDropdown = document.getElementById('product-name');
    const addProductBtn = document.getElementById('add-product-btn');
    const productTable = document.getElementById('product-table');
    const productTableBody = productTable.querySelector('tbody');
    const totalInput = document.getElementById('total');
    const productsInput = document.getElementById('products');
    const submitButton = document.getElementById('submit-btn');

    const productForm = document.getElementById('product-form');
    // Backend product data
    const productData = {
        box: @json($productbox),
        flower: @json($productflower)
    };

    const productStockMap = {};

    // Initialize stock data
    Object.keys(productData).forEach(category => {
        productData[category].forEach(product => {
            productStockMap[product.product_id] = product.stock_quantity;
        });
    });

    // Populate product dropdown on category selection
    productCategoryDropdown.addEventListener('change', function () {
        const selectedCategory = this.value;
        productNameDropdown.innerHTML = '<option value="" disabled selected>Select a product</option>';

        if (productData[selectedCategory]) {
            productData[selectedCategory].forEach(product => {
                const stock = productStockMap[product.product_id] || 0;
                productNameDropdown.innerHTML += `
                    <option value="${product.product_id}"
                            data-purchase-price="${product.price_purchase}"
                            data-selling-price="${product.price_selling}"
                            data-stock="${stock}">
                        ${product.product_name} (${stock > 0 ? `${stock} in stock` : 'Out of stock'})
                    </option>`;
            });

            document.getElementById('product-selection').style.display = 'block';
            addProductBtn.style.display = 'inline-block';

            updateDropdownStockStatus();
        }
    });

    addProductBtn.addEventListener('click', function () {
    const selectedOption = productNameDropdown.options[productNameDropdown.selectedIndex];
    if (!selectedOption || selectedOption.value === "") {
        alert('Please select a product');
        return;
    }

    const productId = selectedOption.value;
    let stock = parseInt(selectedOption.dataset.stock);
    const purchasePrice = parseFloat(selectedOption.dataset.purchasePrice);
    const sellingPrice = parseFloat(selectedOption.dataset.sellingPrice);
    const productName = selectedOption.textContent.split('(')[0].trim();

    if (stock <= 0) {
        alert('This product is out of stock');
        return;
    }

    let row = Array.from(productTableBody.rows).find(row => row.dataset.productId === productId);
    if (row) {
        // Update quantity if row exists
        const quantityInput = row.querySelector('.quantity');
        let currentQuantity = parseInt(quantityInput.value) || 0;
        let newQuantity = currentQuantity + 1;

        // Prevent quantity from exceeding available stock
        if (newQuantity > stock) {
            newQuantity = stock;
            alert('Cannot add more than the available stock');
        }

        quantityInput.value = newQuantity;
        const stockChange = newQuantity - currentQuantity;
        stock -= stockChange;  // Reduce stock based on quantity change
        updateStock(productId, stock);
        row.querySelector('.subtotal').textContent = (newQuantity * sellingPrice).toFixed(2);

    } else {
        // Add new row if product is not already in the table
        row = productTableBody.insertRow();
        row.dataset.productId = productId;
        row.innerHTML = `
            <td>${productName}</td>
            <td><input type="number" class="form-control quantity" value="1" min="1" max="${stock}" required></td>
            <td>${purchasePrice}</td>
            <td>${sellingPrice.toFixed(2)}</td>
            <td class="subtotal">${sellingPrice.toFixed(2)}</td>
            <td><button type="button" class="btn btn-danger remove-product-btn">Remove</button></td>`;

        attachRowEvents(row, productId, stock, sellingPrice);

        stock -= 1; // Reduce stock when product is added
        updateStock(productId, stock);

    }
    updateTotal();
    productTable.style.display = 'table';

    submitButton.style.display = 'inline-block';
});

// Updated row event listener for quantity change
function attachRowEvents(row, productId, stock, sellingPrice) {
    const quantityInput = row.querySelector('.quantity');
    quantityInput.addEventListener('input', function () {
        const previousQuantity = parseInt(quantityInput.dataset.lastValue) || 1;
        let currentQuantity = Math.max(1, Math.min(stock, parseInt(this.value) || 1));

        // Ensure that quantity doesn't exceed stock
        if (currentQuantity > stock) {
            currentQuantity = stock;
            alert('Cannot exceed available stock');
        }

        const stockChange = currentQuantity - previousQuantity;
        quantityInput.value = currentQuantity;
        quantityInput.dataset.lastValue = currentQuantity;
        stock -= stockChange; // Adjust stock based on quantity change
        updateStock(productId, stock);

        updateSubtotal(row, sellingPrice);
    });

    row.querySelector('.remove-product-btn').addEventListener('click', function () {
        const quantity = parseInt(row.querySelector('.quantity').value);
        stock += quantity; // Increase stock when removing product
        updateStock(productId, stock);
        row.remove();
        updateTotal();
        updateSubmitButtonVisibility();
    });
}

// Update stock function
function updateStock(productId, stock) {
    // Update the stock in the product stock map
    productStockMap[productId] = stock;

    // Update stock in the dropdown options
    Array.from(productNameDropdown.options).forEach(option => {
        if (option.value === productId) {
            option.dataset.stock = stock;
            option.textContent = `${option.textContent.split('(')[0].trim()} (${stock > 0 ? `${stock} in stock` : 'Out of stock'})`;
            option.disabled = stock <= 0; // Disable option if out of stock
        }
    });
}

// Update subtotal for each row
function updateSubtotal(row, sellingPrice) {
    const quantity = parseInt(row.querySelector('.quantity').value) || 0;
    row.querySelector('.subtotal').textContent = (quantity * sellingPrice).toFixed(2);
    updateTotal();
}

// Update total amount
function updateTotal() {
    let total = 0;
    productTableBody.querySelectorAll('.subtotal').forEach(cell => {
        total += parseFloat(cell.textContent);
    });
    totalInput.value = total.toFixed(2);

}

// Update submit button visibility
function updateSubmitButtonVisibility() {
    const rowCount = productTableBody.querySelectorAll('tr').length;
    submitButton.style.display = rowCount > 0 ? 'inline-block' : 'none';
}


 productForm.addEventListener('submit', function (e) {
    const products = [];
    productTableBody.querySelectorAll('tr').forEach(row => {
        const productId = row.dataset.productId;
        const quantity = parseInt(row.querySelector('.quantity').value);
        const purchasePrice = parseFloat(row.cells[2].textContent);
        const sellingPrice = parseFloat(row.cells[3].textContent);

        if (quantity > 0) {
            products.push({
                product_id: productId,
                quantity: quantity,
                purchase_price: purchasePrice,
                selling_price: sellingPrice
            });
        }
    });

    if (products.length === 0) {
        alert('No products selected!');
        e.preventDefault();
        return;
    }

    productsInput.value = JSON.stringify(products);
});


    // Transaction ID generation
    const transactionIdInput = document.getElementById('transaction-id');
    if (transactionIdInput) {
        transactionIdInput.value = `TXN-${Date.now()}-${Math.floor(Math.random() * 10000)}`;
    }


    // Payment method toggling
    const paymentMethodDropdown = document.getElementById('payment-method');
    if (paymentMethodDropdown) {
        paymentMethodDropdown.addEventListener('change', function () {
            const paymentMethod = this.value;
            document.getElementById('check-fields').style.display = paymentMethod === 'check' ? 'block' : 'none';
            document.getElementById('online-fields').style.display = paymentMethod === 'online' ? 'block' : 'none';
        });
    }

    // Validate payment amount
    const payAmountInput = document.querySelector('input[name="pay_amount"]');
    if (payAmountInput) {
        payAmountInput.addEventListener('input', function () {
            const total = parseFloat(totalInput.value);
            const payAmount = parseFloat(this.value);
            const errorMessage = document.getElementById('error-message');

            if (payAmount < total) {
                errorMessage.style.display = 'block';
            } else {
                errorMessage.style.display = 'none';
            }
        });
    }

    function validatePayAmount() {
    const totalAmount = parseFloat(document.getElementById('total').value); // Get the total amount
    const payAmountInput = document.querySelector('input[name="pay_amount"]'); // Get the pay amount field
    const errorMessage = document.getElementById('error-message'); // Get the error message element

    // Ensure pay amount is a number
    const payAmount = parseFloat(payAmountInput.value);

    // Show error if the pay amount exceeds the total or is not a valid number
    if (payAmount > totalAmount) {
        errorMessage.style.display = 'block'; // Show error
        payAmountInput.setCustomValidity('Amount cannot exceed total'); // Prevent form submission
    } else {
        errorMessage.style.display = 'none'; // Hide error
        payAmountInput.setCustomValidity(''); // Clear custom validity
    }
}

});

</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const transactionIdInput = document.getElementById('transaction-id');

        // Function to generate a unique transaction ID
        function generateTransactionId() {
            const timestamp = Date.now(); // Get current timestamp
            const randomNum = Math.floor(Math.random() * 10000); // Generate a random number
            return `TXN-${timestamp}-${randomNum}`; // Format: TXN-<timestamp>-<random>
        }

        // Set the transaction ID when the page loads
        transactionIdInput.value = generateTransactionId();
    });
</script>

<script>

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


  </script>

