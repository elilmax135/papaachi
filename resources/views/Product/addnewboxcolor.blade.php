@extends('layouts.admin.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/product_css.css') }}">

<div class="row">
    <div class="col-12">

        <!-- Box Color Modal -->
        <div class="modal fade" id="boxColorModal" tabindex="-1" aria-labelledby="boxColorModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="boxColorModalLabel">Manage Box Colors</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('/box_color') }}" id="boxColorForm">
                            <label for="boxColorInput" class="form-label">Box Color</label>
                            <input type="text" class="form-control" id="box_color" name="box_color" placeholder="Enter box color">
                            <div class="d-flex justify-content-end mt-2">
                                <button type="submit" class="btn btn-primary" id="saveBoxColorBtn">Save Box Color</button>
                            </div>
                        </form>
                        <table class="table mt-1" id="boxColorTable">
                            <thead>
                                <tr>
                                    <th>Box Color</th>

                                </tr>
                            </thead>
                            <tbody id="boxColorList" style="max-height: 200px; overflow-y: scroll; display: block;">
                                @foreach ($bcolor as $color)
                                    <tr>
                                        <td>{{ $color->box_color_name }}</td>
                                        <td>
                                            <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editBoxColorModal{{ $color->box_color_id }}">Edit</a>
                                            <!-- Delete Form -->
                                            <form action="{{ url('/bcolordestroy', $color->box_color_id) }}" method="POST" style="display:inline;">
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
                            <ul class="pagination justify-content-center" id="boxColorPagination"></ul>
                        </nav>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Box Color Modal -->
        @foreach($bcolor as $color)
        <div class="modal fade" id="editBoxColorModal{{ $color->box_color_id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ url('/bcolorupdate', $color->box_color_id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Box Color</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="box_color" class="form-label">Box Color Name</label>
                                <input type="text" class="form-control" name="box_color" value="{{ $color->box_color_name }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Box Color</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>

<script>
    // JavaScript to trigger modal programmatically
    document.addEventListener('DOMContentLoaded', function () {
        // Example: Automatically show the modal on page load (if needed)
        const boxColorModal = new bootstrap.Modal(document.getElementById('boxColorModal'));
        // Uncomment the line below if you want the modal to open automatically
      boxColorModal.show();
    });
</script>
@endsection
