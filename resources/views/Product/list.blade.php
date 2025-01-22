@extends('layouts.admin.app')

@section('content')
<div class="row">
    <div class="col-12">

        <!-- Box Table -->
        <h3>Manage Boxes</h3>
        <div>
            <div>
                <table class="table table-bordered table-striped" id="producttable">
                    <thead>
                        <tr>
                            <th>Product Name</th>

                            <th>Product Type</th>
                            <th>Product Box Type</th>
                            <th>Product Color</th>
                            <th>Price Purchase</th>
                            <th>Price Selling</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($CombinedProducts as $product)
                        <tr>
                            <td>{{ $product->product_name }}</td>



                            <td>{{ ucfirst($product->product_type) }}</td>

                            <td>
                                @if ($product->product_type === 'box')
                                    {{ $product->box_type_name }}
                                @elseif ($product->product_type === 'flower')
                                    {{ $product->product_boxtype_id  }}
                                @endif
                            </td>
                            <td>
                                @if ($product->product_type === 'box')
                                    {{ $product->color_name }}
                                @elseif ($product->product_type === 'flower')
                                    {{ $product->flower_color_name }}
                                @endif
                            </td>

                            <td>{{ number_format($product->price_purchase, 2) }}</td>
                            <td>{{ number_format($product->price_selling, 2) }}</td>

                            <td>
                                <!-- Action Dropdown -->
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{ $product->product_id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-cogs"></i>Actions
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $product->product_id }}">
                                        <li>
                                            <!-- Edit Link -->
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editproModal{{ $product->product_id }}">    <i class="fas fa-pen"></i> Edit</a>
                                        </li>
                                        <li>
                                            <!-- Delete Form -->
                                            <form action="{{url('/destroyproduct', $product->product_id)}}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="dropdown-item" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i>Delete</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Edit Modals -->
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
                                        <select class="form-control" id="product_boxtype_id" name="product_boxtype_id">
                                            <option value="">Select Box Type</option>
                                            @foreach ($btype as $boxType)
                                                <option value="{{ $boxType->box_type_id }}" {{ $product->product_boxtype_id == $boxType->box_type_id ? 'selected' : '' }}>
                                                    {{ $boxType->box_type_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="color_id" class="form-label">Color</label>
                                        @if ($product->product_type === 'box')
                                            <select class="form-control" id="color_id" name="color_id">
                                                <option value="">Select Color</option>
                                                @foreach ($bcolor as $color)
                                                    <option value="{{ $color->box_color_id }}" {{ $product->color_id == $color->box_color_id ? 'selected' : '' }}>
                                                        {{ $color->box_color_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @elseif ($product->product_type === 'flower')
                                            <select class="form-control" id="color_id" name="color_id">
                                                <option value="">Select Color</option>
                                                @foreach ($fcolor as $color)
                                                    <option value="{{ $color->flower_color_id }}" {{ $product->color_id == $color->flower_color_id ? 'selected' : '' }}>
                                                        {{ $color->flower_color_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
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
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#producttable').DataTable(); // Initialize DataTable
        });
    </script>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

@endsection
