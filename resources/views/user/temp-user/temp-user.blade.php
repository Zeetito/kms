@extends('layouts.app')

{{-- @section('title', $zone->name) --}}
<script>
    // const today = new Date().toISOString().split('T')[0]; // Format: YYYY-MM-DD
    document.title = "Temp Users"
</script>


@section('content')
<div class="container my-5">
    <h2 class="mb-4">Temp Users</h2>

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
                <th>Contact</th>
                <th>Info</th>
                <th>Date</th>

                {{-- <th>Zone</th> --}}
                {{-- <th>Actions</th> --}}
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
                
            @endphp
            @foreach($users as $user)
            <tr>
                <td>{{ $i++ }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->contact }}</td>
                <td>{{ $user->info }}</td>
                <td>{{ $user->created_at }}</td>
    
                {{-- <td> --}}
                    {{-- Edit Button --}}
                    {{-- <button class="btn btn-sm btn-primary edit-user-btn" data-bs-toggle="modal" data-bs-target="#editUserModal"
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
                    </button> --}}

                    {{-- @if($attendance_session != null)
                        {{-- Check attendance Button --}}
                        {{-- <button class=" btn-sm check-attendance-btn" data-bs-toggle="modal" data-bs-target="#checkAttendanceModal"
                            data-id="{{ $user->id }}"
                            data-name="{{ $user->fullname }}">
                            <i class="bi bi-check-circle-fill"></i>
                        </button> --}}
                    {{-- @endif --}} 

                    

                    {{-- Delete Button --}}
                    {{-- <button class="btn btn-sm btn-danger delete-user-btn" data-bs-toggle="modal" data-bs-target="#deleteUserModal"
                        data-id="{{ $user->id }}">
                        <i class="bi bi-trash-fill"></i>
                    </button> --}}
                {{-- </td> --}}
            </tr>
            @endforeach
        </tbody>
    </table>
</div>



@endsection


