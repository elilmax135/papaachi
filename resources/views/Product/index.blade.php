@extends('layouts.admin.app')

@section('content')
<!-- Box Form -->
<form id="box-form" method="POST" action="{{ url('/product_store/box') }}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h4>Add Box</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="box_name">Box Name</label>
                                <input type="text" id="box_name" name="box_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="box_color">Box Color</label>
                                <select id="box_color" name="box_color" class="form-control" required>
                                    <option value="" disabled selected>Select Box Color</option>
                                    @foreach ($bcolor as $color)
                                    <option value="{{$color->box_color_id}}">{{$color->box_color_name}}</option>
                                    @endforeach


                                    <!-- Add more options as needed -->
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="box_type">Box Type</label>
                                            <select id="box_type" name="box_type" class="form-control" required>
                                                <option value="" disabled selected>Select Box Type</option>
                                                @foreach ($boxtype as $type)
                                                <option value="{{$type->box_type_id}}">{{$type->box_type_name}}</option>
                                                @endforeach


                                                <!-- Add more options as needed -->
                                            </select>
                                        </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="box_price_purchase">Purchase Price</label>
                                <input type="number" id="box_price_purchase" name="box_price_purchase" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="box_price_selling">Selling Price</label>
                                <input type="number" id="box_price_selling" name="box_price_selling" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="box_image">Box Image</label>
                                <input type="file" id="box_image" name="box_image" class="form-control" accept="image/*" onchange="previewImage(event, 'box_image_preview')" required>
                            </div>
                        </div>
                    </div>
                    <!-- Image Preview -->
                    <div class="form-group">
                        <img id="box_image_preview" src="" alt="Box Image Preview" style="max-width: 200px; display: none;">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Box</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Flower Form -->
<form id="flower-form" method="POST" action="{{ url('/product_store/flower') }}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h4>Add Flower</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="flower_name">Flower Name</label>
                                <input type="text" id="flower_name" name="flower_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                      <label for="box_color">flower Color</label>
                                <select id="f_color_id" name="f_color_id" class="form-control" required>
                                    <option value="" disabled selected>Select Box Color</option>
                                    @foreach ($fcolor as $color)
                                    <option value="{{$color->flower_color_id}}">{{$color->flower_color_name}}</option>
                                    @endforeach


                                    <!-- Add more options as needed -->
                                </select>    </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="flower_price_purchase">Purchase Price</label>
                                <input type="number" id="flower_price_purchase" name="flower_price_purchase" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="flower_price_selling">Selling Price</label>
                                <input type="number" id="flower_price_selling" name="flower_price_selling" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="flower_image">Flower Image</label>
                                <input type="file" id="flower_image" name="flower_image" class="form-control" accept="image/*" onchange="previewImage(event, 'flower_image_preview')" required>
                            </div>
                        </div>
                    </div>
                    <!-- Image Preview -->
                    <div class="form-group">
                        <img id="flower_image_preview" src="" alt="Flower Image Preview" style="max-width: 200px; display: none;">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Flower</button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

<script>
// Image Preview Function
function previewImage(event, previewId) {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function() {
        const preview = document.getElementById(previewId);
        preview.src = reader.result;
        preview.style.display = 'block';
    };

    if (file) {
        reader.readAsDataURL(file);
    }
}
</script>
