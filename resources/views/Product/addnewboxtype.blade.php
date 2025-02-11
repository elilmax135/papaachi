@extends('layouts.admin.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/product_css.css') }}">

<div class="row">
    <div class="col-12">

        <!-- Box Type Modal -->
        <div class="modal fade" id="boxTypeModal" tabindex="-1" aria-labelledby="boxTypeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="boxTypeModalLabel">Manage Box Types</h5>

                    </div>
                    <div class="modal-body">
                        <form action="{{ url('/box_type') }}" id="boxTypeForm">
                            <label for="boxTypeInput" class="form-label">Box Type</label>
                            <input type="text" class="form-control" id="btype" name="btype" placeholder="Enter box type">
                            <button type="submit" class="btn btn-primary mt-2" id="saveBoxTypeBtn">Save Box Type</button>
                        </form>
                        <table class="table mt-3" id="boxTypeTable">
                            <thead>
                                <tr>
                                    <th scope="col">Box Type</th>

                                </tr>
                            </thead>
                            <tbody id="boxTypeList" style="max-height: 200px; overflow-y: scroll; display: block;">
                                @foreach ($boxtype as $btype)
                                <tr>
                                    <td>{{ $btype->box_type_name }}</td>
                                    <td>
                                        <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editBoxTypeModal{{ $btype->box_type_id }}">Edit</a>
                                        <!-- Delete Form -->
                                        <form action="{{ url('/btypedestroy', $btype->box_type_id) }}" method="POST" style="display:inline;">
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
                            <ul class="pagination justify-content-center" id="boxTypePagination"></ul>
                        </nav>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ url('/AddNewThings') }}" class="btn btn-primary">
                            Close</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Box Type Modal -->
        @foreach($boxtype as $btype)
        <div class="modal fade" id="editBoxTypeModal{{ $btype->box_type_id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ url('/btypeupdate', $btype->box_type_id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Box Type</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="branch_name" class="form-label">Box Type Name</label>
                                <input type="text" class="form-control" name="btype" id="btype" value="{{ $btype->box_type_name }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Box Type</button>
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
        // Example: Automatically open the modal programmatically if required
        const boxTypeModal = new bootstrap.Modal(document.getElementById('boxTypeModal'));
        // Uncomment the following line if the modal should open on page load
         boxTypeModal.show();
    });
</script>
@endsection
