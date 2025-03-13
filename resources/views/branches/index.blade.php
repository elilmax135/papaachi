@extends('layouts.admin.app')

@section('content')
<div class="row">

    <div class="card">

        <div class="card-body">
                <div class="col-12">
<!-- Add Branch Button -->
<button class="btn btn-warning mb-3 float-end" data-bs-toggle="modal" data-bs-target="#addBranchModal">Add Branch</button>

<!-- Branch Table -->
<table class="table table-bordered table-striped" id="branchtable">
    <thead>
    <tr>
        <th>Branch Name</th>
        <th>Address</th>
        <th>Incharge</th>
        <th>Contact No</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data_branch as $branch)
        <tr>
            <td>{{ $branch->branch_name }}</td>
            <td>{{ $branch->address }}</td>
            <td>{{ $branch->incharge }}</td>
            <td>{{ $branch->contact_no }}</td>
            <td>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{ $branch->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-cogs"></i> Actions
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $branch->id }}">
                        <li>
                            <a class="dropdown-item text-warning" href="#" data-bs-toggle="modal" data-bs-target="#editBranchModal{{ $branch->id }}">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </li>
                        <li>
                            <form action="{{ url('/destroy', $branch->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
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
        </div>
<!-- Add Branch Modal -->
<div class="modal fade" id="addBranchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{url('/store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="branch_name" class="form-label">Branch Name</label>
                        <input type="text" class="form-control" name="branch_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" name="address" required>
                    </div>
                    <div class="mb-3">
                        <label for="incharge" class="form-label">Incharge</label>
                        <input type="text" class="form-control" name="incharge">
                    </div>
                    <div class="mb-3">
                        <label for="contact_no" class="form-label">Contact No</label>
                        <input type="text" class="form-control" name="contact_no" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
 </div><!-- Edit Branch Modal -->
 <!-- Edit Branch Modal -->
@foreach($data_branch as $branch)
<div class="modal fade" id="editBranchModal{{ $branch->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{url('/update',$branch->id)}}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Edit Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="branch_name" class="form-label">Branch Name</label>
                        <input type="text" class="form-control" name="branch_name" value="{{ $branch->branch_name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" name="address" value="{{ $branch->address }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="incharge" class="form-label">Incharge</label>
                        <input type="text" class="form-control" name="incharge" value="{{ $branch->incharge }}">
                    </div>
                    <div class="mb-3">
                        <label for="contact_no" class="form-label">Contact No</label>
                        <input type="text" class="form-control" name="contact_no" value="{{ $branch->contact_no }}" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Branch</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

@endforeach



 </div>

 </div>



 </div>


 <script>
    $(document).ready(function() {
        $('#branchTable').DataTable(); // Initialize DataTable
    });
</script>
@endsection
