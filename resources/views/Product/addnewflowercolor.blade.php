@extends('layouts.admin.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/product_css.css') }}">

<div class="row">
    <div class="col-12">

        <!-- Flower Color Modal -->
        <div class="modal fade" id="flowerColorModal" tabindex="-1" aria-labelledby="flowerColorModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="flowerColorModalLabel">Manage Flower Colors</h5>

                    </div>
                    <div class="modal-body">
                        <form action="{{ url('/flower_color') }}" id="flowerColorForm">
                            <label for="flowerColorInput" class="form-label">Flower Color</label>
                            <input type="text" class="form-control" name="fname" id="fname" placeholder="Enter flower color">
                            <button type="submit" class="btn btn-primary mt-2" id="saveBoxFlowerBtn">Save Flower Color</button>
                        </form>
                        <table class="table mt-3" id="flowerColorTable">
                            <thead>
                                <tr>
                                    <th>Flower Color</th>

                                </tr>
                            </thead>
                            <tbody id="flowerColorList" style="max-height: 200px; overflow-y: auto; display: block;">
                                @foreach ($fcolor as $color)
                                <tr>
                                    <td>{{ $color->flower_color_name }}</td>
                                    <td>
                                        <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editFlowerColorModal{{ $color->flower_color_id }}">Edit</a>
                                        <!-- Delete Form -->
                                        <form action="{{ url('/fcolordestroy', $color->flower_color_id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination justify-content-center" id="flowerColorPagination"></ul>
                        </nav>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ url('/AddNewThings') }}" class="btn btn-primary">
                            Close</a>

                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Flower Color Modal -->
        @foreach($fcolor as $color)
        <div class="modal fade" id="editFlowerColorModal{{ $color->flower_color_id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ url('/fcolorupdate', $color->flower_color_id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Flower Color</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="fname" class="form-label">Flower Color Name</label>
                                <input type="text" class="form-control" name="fname" id="fname" value="{{ $color->flower_color_name }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Flower Color</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Programmatically trigger the modal if required
    const flowerColorModal = new bootstrap.Modal(document.getElementById('flowerColorModal'));
    // Uncomment the line below to open the modal automatically on page load
  flowerColorModal.show();
});
</script>
@endsection
