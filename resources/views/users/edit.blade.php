@extends('layouts.template')

@section('content')
<style>
    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        max-width: 600px;
        width: 100%;
    }
    .btn-primary, .btn-secondary {
        padding: 0.5rem 1.5rem;
    }
    .form-group label {
        font-weight: 500;
        color: #555;
    }
    .form-control, .form-select {
        width: 100%;
        height: 38px;
        padding: 0.5rem;
        border-radius: 8px;
        border: 1px solid #ced4da;
        box-sizing: border-box;
        appearance: none;
    }
</style>

<div class="container">
    <div class="card">
        <h2 class="text-center mb-4">Edit User</h2>

        <!-- Edit User Form -->
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="name" class="form-label">User Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="role" class="form-label">Role:</label>
                <input type="text" class="form-control" id="role" name="role" value="{{ $user->role }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="company_code" class="form-label">Company Code:</label>
                <input type="text" class="form-control" id="company_code" maxlength="6" name="company_code" 
                    value="{{ $user->company->code ?? '' }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="company_description" class="form-label">Company Description:</label>
                <input type="text" class="form-control" id="company_description" maxlength="50" name="company_description" 
                    value="{{ $user->company->description ?? '' }}" required>
            </div>

            <div class="form-group mb-3">
                <label for="password" class="form-label">Password (Leave blank to keep current password):</label>
                <input type="password" class="form-control" id="password" minlength="8" name="password" placeholder="Enter new password">
            </div>

           
            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
</div>
@endsection
