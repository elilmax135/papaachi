@extends('layouts.admin.app')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Add Staff Button -->
        <button class="btn btn-warning mb-3" data-bs-toggle="modal" data-bs-target="#addBranchModal">Add Staff</button>

        <!-- Branch Table -->
        <table class="table table-bordered table-striped" id="stafftable">
            <thead class="thead-blue">
                <tr>
                    <th>Staff Name</th>
                    <th>NIC</th>
                    <th>Mobile</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($staff as $st)
                <tr>
                    <td>{{$st->full_name}}</td>
                    <td>{{$st->nic}}</td>
                    <td>{{$st->mobile}}</td>
                    <td>
                        <!-- Dropdown Button -->
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton-{{$st->id}}" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-cogs"></i> Actions
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton-{{$st->id}}">
                                <li>
                                    <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editBranchModal{{$st->id}}">
                                        <i class="fas fa-pen"></i> Edit
                                    </a>
                                </li>
                                <li>
                                    <form action="{{url('/deletestaff', $st->id)}}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="dropdown-item text-danger" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>

                </tr>

                <!-- Edit Staff Modal -->
                <div class="modal fade" id="editBranchModal{{$st->id}}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{url('/updatestaff', $st->id)}}" method="POST">
                                @csrf

                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Staff</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="staff_name" class="form-label">Staff Name</label>
                                        <input type="text" class="form-control" name="staff_name" value="{{ $st->full_name }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="nic" class="form-label">NIC</label>
                                        <input type="text" class="form-control" name="nic" value="{{ $st->nic }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="mobile" class="form-label">Contact No</label>
                                        <input type="text" class="form-control" name="mobile" value="{{ $st->mobile }}" required>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Update Staff</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add Staff Modal -->
<div class="modal fade" id="addBranchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{url('/addstaff')}}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Staff</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="staff_name" class="form-label">Staff Name</label>
                        <input type="text" class="form-control" name="staff_name" required>
                    </div>

                    <div class="mb-3">
                        <label for="nic" class="form-label">NIC</label>
                        <input type="text" class="form-control" name="nic" required>
                    </div>

                    <div class="mb-3">
                        <label for="mobile" class="form-label">Contact No</label>
                        <input type="text" class="form-control" name="mobile" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Staff</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

<script>
    function validateMobile(input) {
        const value = input.value;
        const errorElement = document.getElementById('mobile-error');

        // Check if the value length is exactly 10 digits
        if (value.length === 10) {
            errorElement.style.display = 'none'; // Hide error message
            input.classList.remove('is-invalid'); // Remove invalid styling
        } else {
            errorElement.style.display = 'block'; // Show error message
            input.classList.add('is-invalid'); // Add invalid styling
        }
    }
</script>
<script>
    $(document).ready(function() {
        $('#stafftable').DataTable(); // Initialize DataTable
    });
</script>
