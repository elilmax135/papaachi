@extends('layouts.admin.app')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Add Box Button -->
        <button class="btn btn-warning mb-3" data-bs-toggle="modal" data-bs-target="#addBoxModal">Add Box</button>

        <!-- Box Table -->
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Manage Boxes</h3>
                <div>
                    <label for="itemsPerPage" class="me-2">Items per page:</label>
                    <select id="itemsPerPage" class="form-select d-inline-block custom-blue" style="width: auto;">
                        <option value="5" {{ $itemsPerPage == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ $itemsPerPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="15" {{ $itemsPerPage == 15 ? 'selected' : '' }}>15</option>
                        <option value="20" {{ $itemsPerPage == 20 ? 'selected' : '' }}>20</option>
                    </select>
                </div>
            </div>

            <table class="table table-hover table-bordered table-striped text-center shadow-sm">
                <thead class="table-brown">
                    <tr>

                        <th>Box Name</th>
                        <th>Image</th>
                        <th>Type</th>
                        <th>Size</th>
                        <th>Color</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Branch Name</th>
                        <th>Actions</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($data_boxes as $box)
                    <tr>

                        <td>{{ $box->box_name }}</td>
                        <td><img src="/boxImage/{{ $box->box_image }}" alt="Box Image" width="100" height="100"></td>
                        <td>{{ $box->box_type }}</td>
                        <td>{{ $box->size }}</td>
                        <td>{{ $box->color }}</td>
                        <td>${{ $box->price }}</td>
                        <td>{{ $box->quantity }}</td>
                        <td>{{ $box->branch_name ?? 'Not Assigned' }}</td>
                        <td>
                            <a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editBoxModal{{ $box->id }}">Edit</a>
                            <form action="{{url('/destroy_box',$box->id)}}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $data_boxes->links() }}
            </div>
        </div>

        <!-- Add Box Modal -->
        <div class="modal fade" id="addBoxModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{url('/box_store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Add Box</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <label for="box_name" class="col-form-label col-sm-4 text-end">Box Name:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="box_name" id="box_name" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="box_image" class="col-form-label col-sm-4 text-end">Box Image:</label>
                                <div class="col-sm-8">
                                    <input type="file" class="form-control" name="box_image" id="box_image" required>
                                    <br>
                                    <img id="boxImagePreview"   width="150" height="80">

                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="box_type" class="col-form-label col-sm-4 text-end">Box Type:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="box_type" id="box_type" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="color" class="col-form-label col-sm-4 text-end">Color:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="color" id="color" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="box_size" class="col-form-label col-sm-4 text-end">Size:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="box_size" id="box_size" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="price" class="col-form-label col-sm-4 text-end">Price:</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" name="price" id="price" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="quantity" class="col-form-label col-sm-4 text-end">Quantity:</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" name="quantity" id="quantity" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="branch_id" class="col-form-label col-sm-4 text-end">Branch:</label>
                            <div class="col-sm-8">
                                <select name="branch_id" id="branch_id" class="form-control" required>
                                    <option value="">Select Branch</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Edit Box Modal -->
@foreach($data_boxes as $box)
<div class="modal fade" id="editBoxModal{{ $box->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{url('/box_update',$box->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Edit Boxes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="row mb-3">
                    <label for="box_name" class="col-form-label col-sm-4 text-end">Box Name:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="box_name" value="{{ $box->box_name }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="old-image" class="col-form-label col-sm-4 text-end">Old Image:</label>
                    <div class="col-sm-8">
                        <img id="oldImagePreview" src="/boxImage/{{ $box->box_image }}" alt="Old Image" width="70" height="80">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="edit-image" class="col-form-label col-sm-4 text-end">New Image:</label>
                    <div class="col-sm-8">
                        <input type="file" class="form-control" name="imageedit" id="imageedit">
                        <br>
                        <img  alt="New Image Preview" style="display:none;" width="150" height="80">
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="box_type" class="col-form-label col-sm-4 text-end">Box Type:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="box_type" value="{{ $box->box_type}}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="color" class="col-form-label col-sm-4 text-end">Color:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="color" value="{{ $box->color }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="box_size" class="col-form-label col-sm-4 text-end">Size:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="box_size" value="{{ $box->box_size}}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="price" class="col-form-label col-sm-4 text-end">Price:</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" name="price" value="{{ $box->price }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="quantity" class="col-form-label col-sm-4 text-end">Quantity:</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" name="quantity" value="{{$box->quantity}}"required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="branch_id" class="col-form-label col-sm-4 text-end">Branch:</label>
                    <div class="col-sm-8">
                        <select name="branch_id" id="branch_id" class="form-control" required>
                            <option value="">Select Branch</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Box</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

@endforeach
<script>
    document.getElementById('itemsPerPage').addEventListener('change', function () {
        const itemsPerPage = this.value;
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('itemsPerPage', itemsPerPage);
        window.location.href = currentUrl.toString();
    });
</script>
<script>
    document.getElementById('box_image').addEventListener('change', function(event) {
        const fileInput = event.target;
        const previewImage = document.getElementById('boxImagePreview');

        // Ensure a file is selected
        if (fileInput.files && fileInput.files[0]) {
            const reader = new FileReader();

            // Define the callback function for when the file is loaded
            reader.onload = function(e) {
                previewImage.src = e.target.result; // Set the src to the image data
                previewImage.style.display = 'block'; // Show the image
            };

            reader.readAsDataURL(fileInput.files[0]); // Read the file
        } else {
            previewImage.style.display = 'none'; // Hide the image if no file is selected
            previewImage.src = '#'; // Reset the src
        }
    });

</script>


@endsection
