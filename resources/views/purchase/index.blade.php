@extends('layouts.admin.app')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="row">
            <!-- First Column -->
            <div class="col-md-6">

                <div class="card">
                    <div class="card-header">
                        Selected Products
                    </div>
                    <div class="card-body">
                        <!-- Purchase Details Form -->
                      <table class="table table-bordered" id="selected-products-table">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Purchase Price</th>
                                        <th>Quantity</th>
                                        <th>subtotal</th>
                                        <th>Select Branch</th>
                                        <th>Action</th>
                                    </tr>

                                </thead>
                                <tbody>
                              @foreach ($stockproduct as $item)


                              <tr>
                                <!-- Product Name as input -->
                                <td>
                                    <input type="text" name="product_name[]" value="{{$item->product_name}}" class="form-control" readonly>
                                </td>

                                <!-- Purchase Price as input -->
                                <td>
                                    <input type="number" name="price_purchase[]" value="{{$item->price_purchase}}" class="form-control" step="0.01" readonly>
                                </td>

                                <!-- Quantity as input -->
                                <td>
                                    <input type="number" name="quantity[]" value="{{$item->quantity}}" class="form-control" min="0" readonly>
                                </td>
                                <td>
                                    <input type="text" name="subtotal[]" value="{{ $item->price_purchase * $item->quantity }}" class="form-control subtotal" readonly>
                                </td>


                                <!-- Total Price (readonly) -->
                                <td>
                                    <select name="branch_id[]" class="form-control">
                                        <option value="" disabled selected></option>
                                        @foreach($br as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                        @endforeach
                                    </select>
                                </td>

                                <!-- Remove Button -->
                                <td>
                                <form action="{{ url('/cartremove', $item->id) }}" method="POST" class="remove-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Remove</button>
                                </form>
                            </td>
                            </tr>
                               @endforeach
                                </tbody>
                            </table>
                            <div class="text-end mt-3">
                                <p><strong>Total Price:</strong> <span id="total-price">0.00</span></p>
                                <button type="button" class="btn btn-primary" id="generate-invoice">Generate Invoice</button>
                            </div>

                            <!-- Payment Details Modal -->
                            <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="paymentModalLabel">Payment Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="payment-form">
                                                <div class="mb-3">
                                                    <label for="payment-method" class="form-label">Payment Method</label>
                                                    <select class="form-control" id="payment-method" required>
                                                        <option value="" disabled selected>Select Payment Method</option>
                                                        <option value="cash">Cash</option>
                                                        <option value="card">Card</option>
                                                        <option value="online">Online</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="amount-total" class="form-label">Total</label>
                                                    <input type="number" class="form-control" id="amount-total" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="amount-paid" class="form-label">Amount Paid</label>
                                                    <input type="number" class="form-control" id="amount-paid" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="balance-due" class="form-label">Balance Due</label>
                                                    <input type="number" class="form-control" id="balance-due" readonly>
                                                </div>
                                                <button type="button" class="btn btn-primary" id="confirm-payment">Confirm Payment</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Invoice Modal -->
                           <!-- Invoice Modal -->
                        <div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="invoiceModalLabel">Invoice</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Product Name</th>
                                                    <th>Purchase Price</th>
                                                    <th>Quantity</th>
                                                    <th>Subtotal</th>
                                                    <th>Branch Name</th> <!-- New column -->
                                                </tr>
                                            </thead>
                                            <tbody id="invoice-body"></tbody>
                                        </table>
                                        <div class="text-end mt-3">
                                            <p><strong>Final Total:</strong> <span id="final-total">0.00</span></p>
                                            <p><strong>Payment Method:</strong> <span id="invoice-payment-method"></span></p>
                                            <p><strong>Total:</strong> <span id="invoice-amount-total"></span></p>
                                            <p><strong>Amount Paid:</strong> <span id="invoice-amount-paid"></span></p>
                                            <p><strong>Balance Due:</strong> <span id="invoice-balance-due"></span></p>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ url('/invoice/store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="invoice_data" id="invoice-data">
                                            <input type="hidden" name="payment_data" id="payment-data">
                                            <button type="submit" class="btn btn-success">Save Invoice</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Second Column -->
                    <!-- Second Column -->
            <div class="col-md-6">
                <form action="{{ url('/customer') }}" method="POST" class="customer-form">
                    @csrf
                    <label>Name</label>
                    <input type="text" name="name" class="form-control">
                    <button type="submit" class="btn btn-success">Log</button>
                </form>

                @if(session('customer_name')) <!-- Check if customer name exists in the session -->
                <div class="card">
                    <div class="card-header">
                        Available Products
                    </div>

                    <form action="{{ url('logout.customer') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">New Purchase</button>
                    </form>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product id</th>
                                    <th>Product Name</th>
                                    <th>Purchase Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product as $product)
                                <tr>
                                    <td>
                                        <input type="text" name="dis_product_id" class="form-control" value="{{ $product->product_id }}" readonly>
                                        <input type="hidden" name="a_product_id[]" value="{{ $product->product_id }}">
                                    </td>
                                    <td>
                                        <input type="text" name="dis_product_name" class="form-control" value="{{ $product->product_name }}" readonly>
                                        <input type="hidden" name="a_product_name[]" value="{{ $product->product_name }}">
                                    </td>
                                    <td>
                                        <input type="number" name="dis_purchase_price" class="form-control" value="{{ $product->price_purchase }}" readonly>
                                        <input type="hidden" name="a_purchase_price[]" value="{{ $product->price_purchase }}">
                                    </td>
                                    <td>
                                        <form action="{{ url('/cart') }}" method="POST" class="add-to-cart-form">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                                            <input type="number" name="quantity" value="0" min="0" class="form-control">
                                            <button type="submit" class="btn btn-success">To Cart</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                @endif
            </div>
       </div>
  </div>
</div>

<script>

document.addEventListener('DOMContentLoaded', function () {
    const selectedProductsTable = document.querySelector('#selected-products-table tbody');
    const totalPriceElement = document.getElementById('total-price');
    const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
    const paymentMethodInput = document.getElementById('payment-method');
    const amountPaidInput = document.getElementById('amount-paid');
    const balanceDueInput = document.getElementById('balance-due');
    const confirmPaymentButton = document.getElementById('confirm-payment');
    const invoiceModal = new bootstrap.Modal(document.getElementById('invoiceModal'));
    const invoiceBody = document.getElementById('invoice-body');
    const finalTotalElement = document.getElementById('final-total');
    let finalTotal = 0;

    // Function to calculate totals
    function calculateTotal() {
        finalTotal = 0;
        const rows = selectedProductsTable.querySelectorAll('tr');
        rows.forEach(row => {
            const price = parseFloat(row.querySelector('input[name="price_purchase[]"]').value) || 0;
            const quantity = parseInt(row.querySelector('input[name="quantity[]"]').value) || 0;
            const subtotalField = row.querySelector('input[name="subtotal[]"]');
            const subtotal = price * quantity;

            subtotalField.value = subtotal.toFixed(2);
            finalTotal += subtotal;
        });

        totalPriceElement.textContent = finalTotal.toFixed(2);
    }

    // Listen for quantity changes
    selectedProductsTable.addEventListener('input', (e) => {
        if (e.target.name === 'quantity[]') {
            calculateTotal();
        }
    });

    // Generate Invoice
   // Generate Invoice
document.getElementById('generate-invoice').addEventListener('click', function () {
    calculateTotal();

    if (finalTotal === 0) {
        alert('No products selected or quantities set to zero.');
        return;
    }

    // Populate invoice modal
    invoiceBody.innerHTML = '';
    const rows = selectedProductsTable.querySelectorAll('tr');
    rows.forEach(row => {
        const productName = row.querySelector('input[name="product_name[]"]').value;
        const price = parseFloat(row.querySelector('input[name="price_purchase[]"]').value) || 0;
        const quantity = parseInt(row.querySelector('input[name="quantity[]"]').value) || 0;
        const subtotal = parseFloat(row.querySelector('input[name="subtotal[]"]').value) || 0;
        const branchSelect = row.querySelector('select[name="branch_id[]"]');
        const branchName = branchSelect ? branchSelect.options[branchSelect.selectedIndex].text : '';

        if (quantity > 0) {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${productName}</td>
                <td>${price.toFixed(2)}</td>
                <td>${quantity}</td>
                <td>${subtotal.toFixed(2)}</td>
                <td>${branchName}</td> <!-- Add branch name -->
            `;
            invoiceBody.appendChild(tr);
        }
    });

    // Update payment modal details
    finalTotalElement.textContent = finalTotal.toFixed(2);
    balanceDueInput.value = finalTotal.toFixed(2);
    document.getElementById('amount-total').value = finalTotal.toFixed(2);

    paymentModal.show();
});

    // Confirm Payment
    confirmPaymentButton.addEventListener('click', function () {
    const paymentMethod = paymentMethodInput.value;
    const amountPaid = parseFloat(amountPaidInput.value) || 0; // Allow 0 as a valid amount paid

    if (!paymentMethod) {
        alert('Please select a payment method.');
        return;
    }

    if (amountPaid < 0) {
        alert('Amount paid cannot be negative.');
        return;
    }

    const balanceDue = finalTotal - amountPaid;

    if (balanceDue < 0) {
        alert('Amount paid exceeds the total price. Please adjust the payment.');
        return;
    }

    // Update invoice modal payment details
    document.getElementById('invoice-payment-method').textContent = paymentMethod;
    document.getElementById('invoice-amount-total').textContent = finalTotal.toFixed(2);
    document.getElementById('invoice-amount-paid').textContent = amountPaid.toFixed(2);
    document.getElementById('invoice-balance-due').textContent = balanceDue.toFixed(2);

    // Store payment data for submission
    document.getElementById('payment-data').value = JSON.stringify({
        paymentMethod,
        amountPaid,
        balanceDue,
    });

    // Show invoice modal
    paymentModal.hide();
    invoiceModal.show();
});

    // Initial total calculation
    calculateTotal();
});

</script>




@endsection
