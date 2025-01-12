@extends('layouts.admin.app')

@section('content')
<form id="product-form" method="POST" action="{{ url('/submit') }}">
    @csrf
    <div class="row">
        <!-- Supplier & Purchase Details -->
        <!-- Similar to your current form -->

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
                                <th>Selling Price</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Rows dynamically added -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const productCategoryDropdown = document.getElementById('product-category');
        const productNameDropdown = document.getElementById('product-name');
        const addProductBtn = document.getElementById('add-product-btn');
        const productTable = document.getElementById('product-table');
        const productTableBody = productTable.querySelector('tbody');

        // Get product data from backend
        const productData = {
            box: @json($productbox),
            flower: @json($productflower)
        };

        // Event: Change Product Category
        productCategoryDropdown.addEventListener('change', function () {
            const selectedCategory = this.value;

            // Clear and populate product name dropdown
            productNameDropdown.innerHTML = `
                <option value="" disabled selected>Select a product</option>
            `;
            if (productData[selectedCategory]) {
                productData[selectedCategory].forEach(product => {
                    productNameDropdown.innerHTML += `
                        <option value="${product.product_id}"
                                data-purchase-price="${product.price_purchase}"
                                data-selling-price="${product.price_selling}"
                                data-stock="${product.stock_quantity}">
                            ${product.product_name} (${product.stock_quantity} in stock)
                        </option>
                    `;
                });

                document.getElementById('product-selection').style.display = 'block';
                addProductBtn.style.display = 'inline-block';
            } else {
                document.getElementById('product-selection').style.display = 'none';
                addProductBtn.style.display = 'none';
            }
        });

        // Event: Add Product to Table
        addProductBtn.addEventListener('click', function () {
            const selectedOption = productNameDropdown.options[productNameDropdown.selectedIndex];
            if (!selectedOption || selectedOption.value === "") {
                alert("Please select a product.");
                return;
            }

            const productId = selectedOption.value;
            const stock = parseInt(selectedOption.dataset.stock, 10);
            const purchasePrice = parseFloat(selectedOption.dataset.purchasePrice);
            const sellingPrice = parseFloat(selectedOption.dataset.sellingPrice);
            const productName = selectedOption.textContent.split('(')[0].trim();

            if (stock > 0) {
                const existingRow = Array.from(productTableBody.rows).find(row => row.dataset.productId === productId);

                if (existingRow) {
                    const quantityInput = existingRow.querySelector('.quantity');
                    const currentQuantity = parseInt(quantityInput.value, 10);
                    const newQuantity = Math.min(currentQuantity + 1, stock);

                    if (newQuantity > currentQuantity) {
                        quantityInput.value = newQuantity;
                        updateSubtotal(existingRow, sellingPrice);
                        updateStock(selectedOption, currentQuantity, newQuantity);
                    } else {
                        alert('Not enough stock available');
                    }
                } else {
                    const row = productTableBody.insertRow();
                    row.dataset.productId = productId;
                    row.innerHTML = `
                        <td>${productName} (${stock} in stock)</td>
                        <td>
                            <input type="number" class="form-control quantity" value="1" min="1" max="${stock}" required>
                        </td>
                        <td>${purchasePrice}</td>
                        <td>${sellingPrice}</td>
                        <td class="subtotal">${(sellingPrice * 1).toFixed(2)}</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-product-btn">Remove</button>
                        </td>
                    `;
                    productTable.style.display = 'table';

                    const quantityInput = row.querySelector('.quantity');
                    quantityInput.addEventListener('input', function () {
                        const currentQuantity = parseInt(this.dataset.lastValue || 1, 10);
                        const newQuantity = parseInt(this.value, 10);

                        if (newQuantity <= stock) {
                            updateStock(selectedOption, currentQuantity, newQuantity);
                            this.dataset.lastValue = newQuantity;
                            updateSubtotal(row, sellingPrice);
                        } else {
                            this.value = currentQuantity;
                            alert('Not enough stock available');
                        }
                    });

                    row.querySelector('.remove-product-btn').addEventListener('click', function () {
                        const currentQuantity = parseInt(row.querySelector('.quantity').value, 10);
                        updateStock(selectedOption, currentQuantity, 0);
                        row.remove();
                    });

                    updateStock(selectedOption, 0, 1);
                }
            } else {
                alert('Product is out of stock');
            }
        });

        function updateStock(option, oldQuantity, newQuantity) {
            const stockChange = oldQuantity - newQuantity;
            const currentStock = parseInt(option.dataset.stock, 10);
            const newStock = currentStock - stockChange;
            option.dataset.stock = newStock;

            const row = Array.from(productTableBody.rows).find(row => row.dataset.productId === option.value);
            if (row) {
                row.querySelector('td').textContent = `${option.textContent.split('(')[0].trim()} (${newStock} in stock)`;
            }

            option.textContent = `${option.textContent.split('(')[0].trim()} (${newStock} in stock)`;
        }

        function updateSubtotal(row, sellingPrice) {
            const quantity = parseInt(row.querySelector('.quantity').value, 10);
            row.querySelector('.subtotal').textContent = (quantity * sellingPrice).toFixed(2);
        }
    });
</script>
