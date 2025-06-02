@extends('layouts.app')

{{-- @section('title', $zone->name) --}}
<script>
    const today = new Date().toISOString().split('T')[0]; // Format: YYYY-MM-DD
    document.title = "{{ $zone->name }} Members ";
</script>

@section('content')
<div class="container my-5">
    <h2 class="mb-4">{{ $zone->name }} Members</h2>

    <!-- Add User Button -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="bi bi-person-plus-fill"></i> Add User
    </button>

    {{-- Add Temp User Button - When Attendance is in session --}}
    {{-- @if($attendance_session != null)
        <button class="btn btn-success mb-3 float-end" data-bs-toggle="modal" data-bs-target="#addTempUserModal">
            <i class="bi bi-person-plus-fill"></i> ...Temp User
        </button>
    @endif --}}

    <!-- Users Table -->
    <table id="usersTable" class="table table-bordered table-striped">
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

            @foreach($zone->users() as $user)
                @php
                    // $i = 1;
                    $has_cutom_residence = $residenceNote = $user->residence_note()['is_custom'] == true ? true : false;
                    $residence_note = $user->residence_note();
                    
                @endphp
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ $user->fullname }}</td>
                <td>{{ $user->gender == 'm' ? 'Male' : 'Female' }}</td>
                <td>{{ $user->active_contact }}</td>
                <td>{{ $user->residence_note() ? $user->residence_note()['name'] ?? $user->residence_note()['custom_name']  : "N/A" }}</td>
                <td>{{ $user->email ?? "N/A" }}</td>
                <td>{{ $user->is_baptised == 1 ? 'Yes' : 'No' ?? "N/A" }}</td>
                <td>
                    {{-- Edit Button --}}
                    <button class="btn btn-sm btn-primary edit-user-btn" 
                        data-bs-toggle="modal" data-bs-target="#editUserModal"
                        data-id="{{ $user->id }}"
                        data-firstname="{{ $user->firstname }}"
                        data-lastname="{{ $user->lastname }}"
                        data-gender="{{ $user->gender }}"
                        data-baptised="{{ $user->is_baptised }}"
                        data-contact="{{ $user->active_contact }}"
                        data-email="{{ $user->email }}"
                        data-is_custom_residence="{{ $has_cutom_residence ? '1' : '0' }}"
                        data-residence="{{ $user->residence()->id ?? '' }}"
                        {{-- data-is_custom_residence="{{ $user->residence_note() && !empty($user->residence_note()['is_custom']) ? '1' : '0' }}" --}}
                        data-custom_residence_name="{{ $residence_note['custom_name'] ?? '' }}"
                        data-custom_residence_description="{{ $residence_note['custom_description'] ?? '' }}"
                            {{-- //////////////// --}}
                            data-status="{{ $user->status ?? null}}"
                            data-year="{{ $user->year ?? null}}"
                            data-program="{{ $user->program_id ?? null }}"
                            data-occupation="{{ $user->occupation  ?? null}}"
                            data-occupation_description="{{ $user->occupation_description ?? null}}"
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
                    <button class="btn btn-sm btn-danger delete-user-btn" data-bs-toggle="modal" data-bs-target="#deleteUserModal"
                        data-id="{{ $user->id }}">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Row for submit attendance and Go to Attendance page --}}
    {{-- <div class="row my-4">
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

    </div> --}}

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
                        <div class="mb-3" id="main_residence">

                            <label for="residence" class="form-label h6">
                                Residence
                            </label>


                            <select class="form-select" id="residence" name="residence" >
                                <option value="">Select Residence</option>
                                @foreach($zone->residences as $residence)
                                <option value="{{ $residence->id }}">{{ $residence->name }}</option>
                                @endforeach
                            </select>

                            
                        </div>

                        {{-- Custom residence Radio Button --}}
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_custom_residence" id="is_custom_residence" value="0">
                            <label class="form-check-label" for="is_custom_residence">
                                Can't Find Residence?
                            </label>
                        </div>

                        <br>

                        <!-- Custom Residence Details -->
                        <div id="custom_residence_details" style="display:none;">
                            <div class="mb-3">
                                <label for="custom_residence_name" class="form-label h6">Custom Residence Name</label>
                                <input type="text" class="form-control" id="custom_residence_name" name="custom_residence_name" placeholder="Enter Residence Name">
                            </div>

                            <input type="hidden" id="custom_residence_zone" name="custom_residence_zone" value="{{ $zone->id }}">

                            <div class="mb-3">
                                <label for="custom_residence_description" class="form-label h6">Custom Residence Description</label>
                                <input type="text" class="form-control" id="custom_residence_description" name="custom_residence_description" placeholder="How can one locate the Residence?">
                            </div>
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


<!-- Temp User Modal -->
<div class="modal fade" id="addTempUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('add.temp.user') }}" class="modal-content" id="addTempUserForm">
            @csrf
            {{-- @method('PUT') --}}
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Add a Temporary User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {{-- Name --}}
                <div class="mb-3">
                <label>Full Name</label>
                    <input type="text" name="name" id="temp_name" class="form-control" required>
                </div>
                {{-- Contact --}}
                <div class="mb-3">
                    <label>Contact</label>
                    <input type="text" name="contact" id="temp_contact" class="form-control" required>
                </div>
                {{-- info --}}
                <div class="mb-3">
                    <label>Info</label>
                    <input type="text" name="info" id="temp_info" class="form-control" required>
                </div>
                {{-- Zone Id --}}
                <input type="text" name="zone_id" value = "{{ $zone->id }}" id="zone_id" class="form-control" required hidden>

               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
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
                <div class="mb-3" id="edit-main_residence">

                    <label for="edit-residence" class="form-label h6">
                        Residence
                    </label>


                    <select class="form-select" id="edit-residence" name="edit-residence" >
                        @foreach($zone->residences as $residence)
                        <option value="{{ $residence->id }}">{{ $residence->name }}</option>
                        @endforeach
                    </select>

                    
                </div>
                @php
                    $residenceNote = $user->residence_note();
                @endphp

                {{-- Custom residence Radio Button --}}
                <div class="form-check">
                <input class="form-check-input" type="checkbox" name="edit-is_custom_residence" id="edit-is_custom_residence"  {{ $residenceNote && $residenceNote['is_custom'] == true ? 'checked' : '' }}>
                    <label class="form-check-label" for="edit-is_custom_residence">
                        Can't Find Residence?
                    </label>
                </div>


                <br>

                {{-- Custom Residence Details --}}
                <div id="edit-custom_residence_details" style="display: {{ ($residenceNote && $residenceNote['is_custom'] == true) ? 'block' : 'none' }};">


                    {{-- Residence Name --}}
                    <div class="mb-3">
                        <label for="edit-custom_residence_name" class="form-label h6">Custom Residence Name</label>
                        <input type="text" class="form-control" id="edit-custom_residence_name" name="edit-custom_residence_name" placeholder="Enter Residence Name" required>
                    </div>

                    {{-- custom residence zone --}}
                        <input type="text" class="form-control" id="edit-custom_residence_zone" name="edit-custom_residence_zone" placeholder="Enter Residence Zone" value="{{$zone->id}}"  hidden>

                    {{-- Residence Description --}}
                    <div class="mb-3">
                        <label for="edit-custom_residence_description" class="form-label h6">Custom Residence Description</label>
                        <input type="text" class="form-control" id="edit-custom_residence_description" name="edit-custom_residence_description" placeholder="How can one locate the Residence ?" required>
                    </div>
            </div>

            {{-- Status Dropdown --}}
                    {{-- <div class="mb-3">
                        <label for="edit-status" class="form-label h6">Status</label>
                        <select class="form-select" name="edit-status" id="edit-status" required>
                            <option value="">Select</option>
                            <option value="student">Student</option>
                            <option value="other">Other</option>
                        </select>
                    </div> --}}

                    {{-- Student Fields --}}
                    <div id="student-fields" style="display: none;">
                        <div class="mb-3">
                            <label for="edit-year" class="form-label h6">Year</label>
                            <select class="form-select" name="edit-year" id="edit-year">
                                <option value="">Select</option>
                                @for($i = 1; $i <= 6; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edit-program" class="form-label h6">Program</label>
                            <select class="form-select" name="edit-program" id="edit-program">
                                <option value="">Select a program</option>
                                @foreach(App\Models\Program::all() as $program)
                                    <option value="{{ $program->id }}">{{ $program->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    {{-- Other Fields --}}
                    <div id="other-fields" style="display: none;">
                        <div class="mb-3">
                            <label for="edit-occupation" class="form-label h6">Occupation</label>
                            <select class="form-select" name="edit-occupation" id="edit-occupation">
                                <option value="">Select</option>
                                <option value="N.S">N.S</option>
                                <option value="Worker">Worker</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edit-occupation_description" class="form-label h6">Occupation Description</label>
                            <input type="text" class="form-control" name="edit-occupation_description" id="edit-occupation_description" placeholder="Describe occupation">
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


