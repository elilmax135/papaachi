@extends('layouts.admin.app')

@section('content')
<div class="row">
    <div class="col-12">

         <!-- Box Table -->


                <h3>Manage Boxes</h3>
                <div>



            <div>
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Product Name</th>
                            <th>Product Image</th>
                            <th>Product Type</th>
                            <th>Product Box Type</th>
                            <th>Product Color</th>
                            <th>Price Purchase</th>
                            <th>Price Selling</th>
                            <th>Act</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($CombinedProducts as $product)
                        <tr>
                            <!-- Product Name -->
                            <td>{{ $product->product_name }}</td>

                            <!-- Product Image -->
                            <td>
                                @if ($product->product_type === 'box')
                                    <img src="/boxImage/{{ $product->product_image }}" alt="Box Image" width="150" height="150">
                                @elseif ($product->product_type === 'flower')
                                    <img src="/flowerImage/{{ $product->product_image }}" alt="Flower Image" width="150" height="150">
                                @else
                                    Unknown
                                @endif
                            </td>

                            <!-- Product Type -->
                            <td>{{ ucfirst($product->product_type) }}</td>

                            <!-- Product Box Type -->
                            <td>
                                @if ($product->product_type === 'box')
                                    {{ $product->box_type_name }}  <!-- Assuming 'boxType' is the relationship method -->
                                @elseif ($product->product_type === 'flower')
                                    {{ $product->product_boxtype_id  }}  <!-- Assuming 'flowerType' is the relationship method -->
                                @endif
                            </td>

                            <!-- Product Color -->
                            <td>{{ $product->color_name }}</td>  <!-- Assuming 'color' is the relationship method -->

                            <!-- Product Prices -->
                            <td>{{ number_format($product->price_purchase, 2) }}</td>
                            <td>{{ number_format($product->price_selling, 2) }}</td>

                            <!-- Actions (Edit and Delete) -->
                            <td>
                                <!-- Edit Link -->
                                <a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editproModal{{ $product->product_id }}">Edit</a>

                                <!-- Delete Form -->
                                <form action="{{url('/destroyproduct', $product->product_id)}}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
                <!-- Pagination -->




                            <!-- Edit Modal -->
       <!-- Modal for editing product -->
       @foreach ($CombinedProducts as $product)
<div class="modal fade" id="editproModal{{ $product->product_id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Product - {{ $product->product_name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ url('product.update', $product->product_id) }}" method="POST" enctype="multipart/form-data">
                    @csrf


                    <div class="mb-3">
                        <label for="product_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" value="{{ $product->product_name }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="product_type" class="form-label">Product Type</label>
                        <select class="form-control" name="product_type" id="product_type" required>
                            <option value="box" {{ $product->product_type === 'box' ? 'selected' : '' }}>Box</option>
                            <option value="flower" {{ $product->product_type === 'flower' ? 'selected' : '' }}>Flower</option>
                        </select>
                    </div>

                    <!-- Product Image (Upload or Display) -->
                    <div class="mb-3">
                        <label for="product_image" class="form-label">Product Image</label>
                        <div>
                            @if ($product->product_type === 'box')
                                <img src="/boxImage/{{ $product->product_image }}" alt="Box Image" width="150" height="150">
                            @elseif ($product->product_type === 'flower')
                                <img src="/flowerImage/{{ $product->product_image }}" alt="Flower Image" width="150" height="150">
                            @endif
                        </div>
                        <input type="file" class="form-control mt-2" id="product_image" name="product_image">
                    </div>

                    <div class="mb-3">
                        <label for="product_boxtype_id" class="form-label">Box Type</label>
                        <input type="text" class="form-control" id="product_boxtype_id" name="product_boxtype_id" value="{{ $product->product_boxtype_id }}">
                    </div>

                    <div class="mb-3">
                        <label for="color_id" class="form-label">Color</label>
                        <input type="text" class="form-control" id="color_id" name="color_id" value="{{ $product->color_id }}">
                    </div>

                    <div class="mb-3">
                        <label for="price_purchase" class="form-label">Price Purchase</label>
                        <input type="number" step="0.01" class="form-control" id="price_purchase" name="price_purchase" value="{{ $product->price_purchase }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="price_selling" class="form-label">Price Selling</label>
                        <input type="number" step="0.01" class="form-control" id="price_selling" name="price_selling" value="{{ $product->price_selling }}" required>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
            <!-- Delete Modal -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="deleteForm" method="POST" action="">
                            @csrf
                            @method('DELETE')
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Delete Product</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete <strong id="deleteProductName"></strong>?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>


                    <!-- Add Bootstrap JS -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

            <script>
                // Edit Modal: Populate data
                document.getElementById('editModal').addEventListener('show.bs.modal', function (event) {
                    let button = event.relatedTarget;
                    let id = button.getAttribute('data-id');
                    let name = button.getAttribute('data-name');
                    let type = button.getAttribute('data-type');

                    let modal = this;
                    modal.querySelector('#editProductId').value = id;
                    modal.querySelector('#editProductName').value = name;
                    modal.querySelector('#editProductType').value = type;
                    modal.querySelector('#editForm').action = `/products/${id}`;
                });

                // Delete Modal: Populate data
                document.getElementById('deleteModal').addEventListener('show.bs.modal', function (event) {
                    let button = event.relatedTarget;
                    let id = button.getAttribute('data-id');
                    let name = button.getAttribute('data-name');

                    let modal = this;
                    modal.querySelector('#deleteProductName').textContent = name;
                    modal.querySelector('#deleteForm').action = `/products/${id}`;
                });
            </script>
    </div>
</div>
@endsection
