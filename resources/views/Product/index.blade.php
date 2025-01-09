@extends('layouts.admin.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/product_css.css') }}">
<div class="row">
    <div class="col-12">
        <div class="container my-5">
              <div class="card shadow-lg rounded-lg">
                  <div class="card-header bg-light text-center custom-text-color">
                    <h2>Add Product</h2>
                  </div>
                 <div class="card-body p-5">

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-center mb-4">
                        <button id="showBoxFormBtn" class="btn btn-lg mx-2 shadow" style="background-color: #755b2b; color: white; font-size: 1.25rem; padding: 0.75rem 1.5rem;">
                            <i class="bi bi-box"></i> Add Box
                        </button>
                        <button id="showFlowerFormBtn" class="btn btn-lg mx-2 shadow" style="background-color: #6eec25e8; color: white; font-size: 1.25rem; padding: 0.75rem 1.5rem;">
                            <i class="bi bi-flower"></i> Add Flower
                        </button>
                    </div>

                    <!-- Box Form -->
                    <div>
                    <form id="boxForm" action="{{ url('/product_store/box') }}" method="POST" enctype="multipart/form-data" style="display: none;">
                        @csrf <!-- Include CSRF token for security -->
                        <h4 class="mb-3" style="color: #a7987e;">Add Box</h4>
                        <div class="mb-3">
                            <label for="box_name" class="form-label fw-bold" style="color: #755b2b;">Box Name</label>
                            <input type="text" class="form-control form-control-lg" id="box_name" name="box_name" placeholder="Enter Box Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="box_image" class="form-label fw-bold" style="color: #755b2b;">Box Image</label>
                            <input type="file" class="form-control form-control-lg" id="box_image" name="box_image">
                            <img id="boxImagePreview" class="mt-3" width="150" height="100" style="display: none; border: 1px solid #ddd;">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="box_type" class="form-label fw-bold" style="color: #755b2b;">Box Type</label>
                                <select class="form-control form-control-lg custom-select" id="box_type" name="box_type">
                                    <option value="" disabled selected>Select Box Type</option>
                                    @foreach($boxtype as $bts)
                                        <option value="{{ $bts->box_type_id }}">{{ $bts->box_type_name}}</option>
                                    @endforeach
                                </select></div>
                            <div class="col-md-6 mb-3">
                                <label for="box_color" class="form-label fw-bold" style="color: #755b2b;">Box Color</label>
                                <select class="form-control form-control-lg" id="box_color" name="box_color">
                                    <option value="" disabled selected>Select Box color</option>
                                    @foreach($bcolor as $color)
                                        <option value="{{ $color->box_color_id }}">{{ $color->box_color_name}}</option>
                                        @endforeach
                                </select>  </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="box_price_purchase" class="form-label fw-bold" style="color: #755b2b;">Purchase Price</label>
                                <input type="number" class="form-control form-control-lg" id="box_price_purchase" name="box_price_purchase" placeholder="Enter Purchase Price">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="box_price_selling" class="form-label fw-bold" style="color: #755b2b;">Selling Price</label>
                                <input type="number" class="form-control form-control-lg" id="box_price_selling" name="box_price_selling" placeholder="Enter Selling Price">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-lg mx-2 shadow" style="background-color: #755b2b; color: white; font-size: 1.25rem; padding: 0.75rem 1.5rem; width: 1000px;">
                            <i class="bi bi-save"></i> Save Box
                        </button>
                    </form>

                </div>

                    <!-- Flower Form -->
                    <div>
                    <form id="flowerForm" action="{{ url('/product_store/flower') }}" method="POST" enctype="multipart/form-data" style="display: none;">
                        @csrf <!-- Include CSRF token for security -->
                        <h4 class="mb-3" style="color: #70bd43e8;">Add Flower</h4>
                        <div class="mb-3">
                            <label for="flower_name" class="form-label fw-bold" style="color: #6eec25e8;">Flower Name</label>
                            <input type="text" class="form-control form-control-lg" id="flower_name" name="flower_name" placeholder="Enter Flower Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="flower_image" class="form-label fw-bold" style="color: #6eec25e8;">Flower Image</label>
                            <input type="file" class="form-control form-control-lg" id="flower_image" name="flower_image">
                            <img id="flowerImagePreview" class="mt-3" width="150" height="100" style="display: none; border: 1px solid #ddd;">
                        </div>
                        <div class="mb-3">
                            <label for="flower_color" class="form-label fw-bold" style="color: #6eec25e8;">Flower Color</label>
                            <select class="form-control form-control-lg" id="f_color_id" name="f_color_id">
                                <option value="" disabled selected>Select flower color</option>
                                @foreach($fcolor as $color)
                                    <option value="{{ $color->flower_color_id }}">{{ $color->flower_color_name}}</option>
                                    @endforeach
                            </select>  </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="flower_price_purchase" class="form-label fw-bold" style="color: #6eec25e8;">Purchase Price</label>
                                <input type="number" class="form-control form-control-lg" id="flower_price_purchase" name="flower_price_purchase" placeholder="Enter Purchase Price">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="flower_price_selling" class="form-label fw-bold" style="color: #6eec25e8;">Selling Price</label>
                                <input type="number" class="form-control form-control-lg" id="flower_price_selling" name="flower_price_selling" placeholder="Enter Selling Price">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-lg mx-2 shadow" style="background-color: #6eec25e8; color: white; font-size: 1.25rem; padding: 0.75rem 1.5rem; width: 1000px;">
                            <i class="bi bi-save"></i> Save Flower
                        </button>
                    </form>
                    </div>

                </div>

            </div>
        </div>
    </div>

        <script>
            // JavaScript to toggle forms
            document.getElementById('showBoxFormBtn').addEventListener('click', function () {
                document.getElementById('boxForm').style.display = 'block';
                document.getElementById('flowerForm').style.display = 'none';
            });

            document.getElementById('showFlowerFormBtn').addEventListener('click', function () {
                document.getElementById('flowerForm').style.display = 'block';
                document.getElementById('boxForm').style.display = 'none';
            });
        </script>


                    <script>
                    document.getElementById('box_image').addEventListener('change', function(event) {
                        const input = event.target;
                        const preview = document.getElementById('boxImagePreview');

                        // Check if a file was selected
                        if (input.files && input.files[0]) {
                            const reader = new FileReader();

                            // Load the image and display the preview
                            reader.onload = function(e) {
                                preview.src = e.target.result;
                                preview.style.display = 'block'; // Show the preview image
                            };

                            reader.readAsDataURL(input.files[0]); // Read the selected file
                        } else {
                            preview.style.display = 'none'; // Hide the preview if no file selected
                        }
                    });
                </script>
                    <script>
                        document.getElementById('flower_image').addEventListener('change', function(event) {
                            const input = event.target;
                            const preview = document.getElementById('flowerImagePreview');

                            // Check if a file was selected
                            if (input.files && input.files[0]) {
                                const reader = new FileReader();

                                // Load the image and display the preview
                                reader.onload = function(e) {
                                    preview.src = e.target.result;
                                    preview.style.display = 'block'; // Show the preview image
                                };

                                reader.readAsDataURL(input.files[0]); // Read the selected file
                            } else {
                                preview.style.display = 'none'; // Hide the preview if no file selected
                            }
                        });
                    </script>
                    <script>
                        $(document).ready(function(){
                            $('.custom-select').select2();
                        });
                    </script>

@endsection
