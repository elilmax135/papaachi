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
                            <label for="s_id" class="form-label">Select Service</label>
                            <select
                                id="s_id"
                                name="s_id"
                                class="form-control">
                                <option value="" disabled selected>Select Service</option>
                                @foreach ($service as $ser)
                                    <option value="{{$ser->service_id_uniq}}">{{$ser->service_name}}</option>
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


        </div>

        <!-- Product Selection -->
        <div class="col-12">
            <div class="card mb-3">

                <div class="card-header">
            </div>



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

                    <button id="add-product-btn" type="button" class="btn btn-primary mt-3" style="display: none;">Add Product</button>

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
    <div>

    </div>

    <button type="submit" id="submit-btn" class="btn btn-success" style="display: none;">Submit</button>
</form>

<div class="card-header">Location</div>
<div class="card-body">
      <div class="mb-3">
 <label for="location"><h3>Transfer Location</h3></label><br>

 <a href="{{ url('/openlocate') }}" id="navigate-link" class="btn btn-primary">Set The Location</a>

<div id="content"></div>

      </div>

 </div>
</div>
<div class="col-12">
    <div class="card-header">Locations</div>
    <div class="card-body">
        <form id="location-price-form" method="POST" action="{{ url('/update_total') }}">
            @csrf
            <div class="card-body">
                @if ($location)
                    <label>Location Total (Distance):</label>
                    <input type="text" id="distance_0" name="location_total" value="{{ $location->total_distance }}" class="form-control" readonly>

                    <div class="row">
                        <div class="col-md-4">
                            <label for="lesfiv_0">Enter Price For 5km:</label>
                            <input type="number" id="lesfiv_0" name="price_5km" min="0" value="0" class="form-control" placeholder="Enter Price For 5km">
                        </div>
                        <div class="col-md-4">
                            <label for="fiv_fifeen_0">Enter Price for 5-15km:</label>
                            <input type="number" id="fiv_fifeen_0" name="price_5_15km" min="0" value="0" class="form-control" placeholder="Enter price for 5-15km">
                        </div>
                        <div class="col-md-4">
                            <label for="r_0">Enter Price for more than 15Km:</label>
                            <input type="number" id="r_0" name="price_above_15km" min="0" value="0" class="form-control" placeholder="Enter Price for more than 15Km">
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-primary" onclick="calculateLocationPrice()">Calculate Price</button>
                    </div>

                    <div class="mt-3">
                        <label for="location_price">Calculated Price:</label>
                        <input type="number" id="location_price" name="location_price" class="form-control" readonly>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">Update Total</button>
                    </div>
                @else
                    <p>No location data found.</p>
                @endif
            </div>
        </form>
</div>

</div>

<!-- Payment Form -->
<form id="payment-form" method="POST" action="{{ url('/sellpayments') }}">
    @csrf
    <div class="col-12">
        <div class="card-header">Payment Details</div>
        <div class="card-body">
            <!-- Payment Method & Fields -->
            <div class="row mb-3">
                <div class="form-group mb-3">
                    <label>Total</label>
                    @if ($lastRecord)
                    <input type="number" id="payment_total" name="payment_total" value="{{ $lastRecord->total }}" class="form-control" readonly>
                    @endif

                </div>

                <div class="form-group mb-3">
                    <label>Sell ID</label>
                    @if ($lastRecord)
                    <input type="number" id="sell_id" name="sell_id" value="{{ $lastRecord->id }}" class="form-control" readonly>
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
                            @if ($lasttransect)
                            <input type="text" id="transection_id" name="transection_id" value="{{ $lasttransect->transaction_id }}" class="form-control" readonly>
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

    // Doctor confirmation image preview
    const doctorConfirmationInput = document.getElementById('doctor-confirmation');
    if (doctorConfirmationInput) {
        const previewContainer = document.createElement('div');
        previewContainer.style.marginTop = '10px';
        doctorConfirmationInput.parentNode.appendChild(previewContainer);


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
    document.addEventListener('DOMContentLoaded', function () {
        const doctorConfirmationInput = document.getElementById('doctor-confirmation');

        // Create an image preview container
        const previewContainer = document.createElement('div');
        previewContainer.style.marginTop = '10px';
        doctorConfirmationInput.parentNode.appendChild(previewContainer);

        doctorConfirmationInput.addEventListener('change', function (event) {
            // Clear any existing preview
            previewContainer.innerHTML = '';

            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Preview of uploaded image';
                    img.style.maxWidth = '200px'; // Set max width for the image preview
                    img.style.border = '1px solid #ddd';
                    img.style.padding = '5px';
                    previewContainer.appendChild(img);
                };

                reader.readAsDataURL(file);
            }
        });
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


  </script>

<script>
    function calculateLocationPrice() {
        const distance = parseFloat(document.getElementById('distance_0').value);
        const price5km = parseFloat(document.getElementById('lesfiv_0').value);
        const price5to15km = parseFloat(document.getElementById('fiv_fifeen_0').value);
        const priceAbove15km = parseFloat(document.getElementById('r_0').value);

        let totalPrice = 0;

if (distance <= 5) {
    // If distance is 5 km or less, multiply distance by price for 5 km
    totalPrice = distance * price5km;
} else if (distance > 5 && distance <= 15) {
    // For distance between 5 and 15 km, calculate for first 5 km and remaining distance
    totalPrice = (5 * price5km) + ((distance - 5) * price5to15km);
} else if (distance > 15) {
    // For distance greater than 15 km, calculate for first 15 km and remaining distance
    totalPrice = (5 * price5km) + (10 * price5to15km) + ((distance - 15) * priceAbove15km);
}


        document.getElementById('location_price').value = totalPrice;
    }
</script>
<script>
    document.getElementById('navigate-link').addEventListener('click', function(event) {
        event.preventDefault();

        let url = this.href;

        // Update the URL in the address bar
        window.history.pushState({ path: url }, '', url);

        // Perform the AJAX request to fetch the page
        fetch(url)
            .then(response => response.text())
            .then(data => {
                // Update the content dynamically
                document.getElementById('content').innerHTML = data;
            })
            .catch(error => console.log('Error loading the page:', error));
    });

    window.onpopstate = function(event) {
        console.log('Location changed: ', event.state);
        // Optionally reload or change the content when the user navigates via browser's back/forward button
    };
</script>
