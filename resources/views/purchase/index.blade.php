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
                                        <th>Total</th>
                                        <th>Select Branch</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            <div class="text-end mt-3">
                                <p><strong>Total Price:</strong> <span id="total-price">0.00</span></p>
                                           </div>
                                    <!-- Generate Invoice Button -->


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
                    <button type="submit">Log</button>
                </form>

                @if(session('customer_name')) <!-- Check if customer name exists in the session -->
                <div class="card">
                    <div class="card-header">
                        Available Products
                    </div>
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
                                            <input type="number" name="quantity" value="0" min="0" class="quantity-input">
                                            <button type="submit" class="add-to-cart-button">To Cart</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <form action="{{ url('logout.customer') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Log Out</button>
                </form>
                @endif
            </div>
       </div>
  </div>
</div>
<script>
    document.querySelectorAll('.quantity-input').forEach(input => {
    input.addEventListener('input', function () {
        const max = parseInt(this.getAttribute('max'));
        const value = parseInt(this.value);

        if (value > max) {
            alert('Quantity exceeds available stock!');
            this.value = max; // Reset to max value
        }
    });
});
    </script>
<script>

</script>



@endsection
