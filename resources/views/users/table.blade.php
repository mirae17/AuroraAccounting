@extends('layouts.template')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Add Company and User</title>
    <style>
    .table thead th, .table td, .table th {
        text-align: center;
        vertical-align: middle;
    }
</style>
</head>
<body>
 
<div class="container">
    <div class="row mb-3 align-items-center">
        <div class="col-lg-6">
            <h2>Manage Companies and Users</h2>
        </div>
    </div>

    <!-- Success Message Display -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <a href="{{ route('users.create') }}" class="btn btn-success rounded-pill shadow-sm px-4">
            <i class="fas fa-plus-circle"></i> Add Companies and Users
</a>
<!-- Payment Records Table -->
<div class="card-body">



<div id="datatable">
    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                 <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Company</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody >
            @foreach($users as $user)
            <tr style="text-transform: uppercase;" >
                <td >{{ $user->name }}</td>
                <td >{{ $user->email }}</td>
                <td >{{ $user->role }}</td>
                <td>{{ $user->company ? $user->company->description : 'N/A' }}</td>
                <td> <!-- Edit and Delete Icons -->
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-custom-edit btn-sm">
                    <i class="fa fa-edit"></i>
                </a>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-custom-delete btn-sm" onclick="return confirm('Are you sure?')">
                        <i class="fa fa-trash"></i>
                    </button>
                </form>
            </td>
            </tr>

            @endforeach
        </tbody>
    </table>
</div>
</div>

 
 
</div>

@endsection
