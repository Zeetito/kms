@extends('layouts.app')

{{-- @section('title', $zone->name) --}}
<script>
    const today = new Date().toISOString().split('T')[0]; // Format: YYYY-MM-DD
    document.title = "{{ $zone->name }} Absentees - " + today + " ";
</script>


@section('content')
<div class="container my-5">
    <h2 class="mb-4">{{ $zone->name }} Absentees</h2>

    {{-- <!-- Add User Button -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="bi bi-person-plus-fill"></i> Add User
    </button> --}}

    <!-- Users Table -->
    <table id="TablePrint" class="table table-bordered table-striped">

        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Active Contact</th>
                <th>Residence</th>
                <th>Email</th>
                <th>Baptised</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
                
            @endphp
            @foreach($zone->absentees() as $user)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ $user->fullname }}</td>
                <td>{{ $user->gender == 'm' ? 'Male' : 'Female' }}</td>
                <td>{{ $user->active_contact }}</td>
                <td>{{ $user->residence()->name ?? "N/A" }}</td>
                <td>{{ $user->email ?? "N/A" }}</td>
                <td>{{ $user->is_baptised == 1 ? 'Yes' : 'No' ?? "N/A" }}</td>
                <td>
                    {{-- Edit Button --}}
                    <button class="btn btn-sm btn-primary edit-user-btn" data-bs-toggle="modal" data-bs-target="#editUserModal"
                        data-id="{{ $user->id }}"
                        data-firstname="{{ $user->firstname }}"
                        data-lastname="{{ $user->lastname }}"
                        data-gender="{{ $user->gender }}"
                        data-baptised="{{ $user->is_baptised }}"
                        data-contact="{{ $user->active_contact }}"
                        data-email="{{ $user->email }}"
                        data-residence="{{ $user->residence()->id ?? null}}"
                        >
                        <i class="bi bi-pencil-fill"></i>
                    </button>

                    @if($attendance_session != null)
                        {{-- Check attendance Button --}}
                        <button class=" btn-sm check-attendance-btn" data-bs-toggle="modal" data-bs-target="#checkAttendanceModal"
                            data-id="{{ $user->id }}"
                            data-name="{{ $user->fullname }}">
                            <i class="bi bi-check-circle-fill"></i>
                        </button>
                    @endif

                    

                    {{-- Delete Button --}}
                    {{-- <button class="btn btn-sm btn-danger delete-user-btn" data-bs-toggle="modal" data-bs-target="#deleteUserModal"
                        data-id="{{ $user->id }}">
                        <i class="bi bi-trash-fill"></i>
                    </button> --}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Row for submit attendance and Go to Attendance page --}}
<div class="row my-4">
    @if($attendance_session != null)
        <div class="col-md-6">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#submitAttendanceModal">
                <i class="bi bi-check-circle-fill"></i> Submit Attendance
            </button>
        </div>
        <div class="col-md-6">
            <a href="{{route('active_attendance_session')}}">
                <button class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#goToAttendanceModal">
                    <i class="bi bi-check-circle-fill"></i> Go to Attendance
                </button>
            </a>
        </div>
    @endif

</div>

  <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="addUserModalLabel">Add New User - {{$zone->name}}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('add.zone.user') }}" method="POST">
                    @csrf
                    <div class="modal-body">

                        {{-- First Name --}}
                        <div class="mb-3">
                            <label for="firstname" class="form-label h6">FirstName</label>
                            <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter Firstname" required>
                        </div>

                        {{-- Lastname --}}
                        <div class="mb-3">
                            <label for="lastname" class="form-label h6">Lastname</label>
                            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter Lastname" required>
                        </div>

                        {{-- Gender --}}
                        <div class="mb-3">
                            <label for="gender" class="form-label h6">Gender</label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="m">Male</option>
                                <option value="f">Female</option>
                            </select>
                        </div>

                        {{-- Baptismal Status --}}
                        <div class="mb-3">
                            <label for="is_baptised" class="form-label h6">Is Baptised?</label>
                            <select class="form-select" id="is_baptised" name="is_baptised" required>
                                <option value="">Select</option>
                                <option value=1>Yes</option>
                                <option value=0>No</option>
                            </select>
                        </div>

                        {{-- Active Contact --}}
                        <div class="mb-3">
                            <label for="active_contact" class="form-label h6 ">Active Contact</label>
                            <input type="active_contact" class="form-control" id="active_contact" name="active_contact" placeholder="Active Contact" required>
                        </div>
                        
                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label h6">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" >
                        </div>

                        {{-- <div class="mb-3"> --}}
                            {{-- <label for="password" class="form-label h6">Password</label> --}}
                            <input type="password" class="form-control" id="password" name="password" value="password" placeholder="Enter password" required hidden>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" value="password" placeholder="Enter password" required hidden>
                        {{-- </div> --}}


                        {{-- Residence --}}
                        <div class="mb-3">
                            <label for="residence" class="form-label h6">Residence</label>
                            <select class="form-select" id="residence" name="residence" required>
                                @foreach($zone->residences as $residence)
                                <option value="{{ $residence->id }}">{{ $residence->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>




<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" class="modal-content" id="editUserForm">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {{-- Firstname --}}
                <div class="mb-3">
                    <label>FirstName</label>
                    <input type="text" name="firstname" id="edit-firstname" class="form-control" required>
                </div>
                {{-- Lastname --}}
                <div class="mb-3">
                    <label>LastName</label>
                    <input type="text" name="lastname" id="edit-lastname" class="form-control" required>
                </div>
                {{-- Gender --}}
                <div class="mb-3">
                    <label for="gender" class="form-label h6">Gender</label>
                    <select class="form-select" id="edit-gender" name="gender" required>
                        <option value="m">Male</option>
                        <option value="f">Female</option>
                    </select>
                </div>

                {{-- Baptismal Status --}}
                 <div class="mb-3">
                    <label for="is_baptised" class="form-label h6">Is Baptised?</label>
                    <select class="form-select" id="edit-is_baptised" name="is_baptised" required>
                        <option value="">Select</option>
                        <option value=1>Yes</option>
                        <option value=0>No</option>
                    </select>
                </div>

                 {{-- Active Contact --}}
                <div class="mb-3">
                    <label for="active_contact" class="form-label h6 ">Active Contact</label>
                    <input type="text" class="form-control" id="edit-active_contact" name="active_contact" placeholder="Active Contact" required>
                </div>

                <input type="hidden" name="user_id" id="edit-user-id">

                {{-- Email --}}
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" id="edit-email" class="form-control" >
                </div>

                {{-- Residence --}}
                <div class="mb-3">
                    <label for="edit-residence" class="form-label h6">Residence</label>
                    <select class="form-select" id="edit-residence" name="residence" required>
                        @foreach($zone->residences as $residence)
                            <option value="{{ $residence->id }}">{{ $residence->name }}</option>
                        @endforeach
                    </select>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update User</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" class="modal-content" id="deleteUserForm">
            @csrf
            @method('DELETE')
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Delete User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this user?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete</button>
            </div>
        </form>
    </div>
</div>

<!-- Check User Attendance -->
<div class="modal fade" id="checkAttendanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" class="modal-content" id="checkAttendanceForm">
            @csrf
            @method('DELETE')
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Check User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p>Are you sure you want to mark <strong id="attendance-user-name" class="text-primary"></strong> present?</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="markAttendanceBtn" class="btn btn-success">Mark Present</button>

            </div>
        </form>
    </div>
</div>

@endsection


