@extends('layouts.template')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4"
        style="max-width: 800px; width: 100%; border-radius: 12px; background-color: #f9f9f9;">
        <!-- Header Section -->
        <div class="text-center">
            <div class="profile-image mb-4">
                <img src="{{ asset('storage/' . $companyMaintenance->iCompMainLogo ?? 'path_to_default_logo_or_placeholder') }}"
                    alt="Company Logo" class="rounded-circle"
                    style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #007bff;">
            </div>
            <h3 class="text-primary">Edit Company Details</h3>
            <p class="text-muted">Update the information below for the company.</p>
        </div>

        <!-- Form Section -->
        <form action="{{ route('companyMaintenance.update', $companyMaintenance->iCompMainPk) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Company Select Field (Visible only for System Admin) -->
            @if(Auth::user()->role === 'system admin')
                <div class="form-group mb-3">
                    <label for="iCompMainName" class="form-label">Company Name</label>
                    <select class="form-select @error('iCompMainName') is-invalid @enderror" id="iCompMainName"
                        name="iCompMainName" required>
                        <option value="" disabled selected>Select Company</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ $companyMaintenance->iCompMainName == $company->id ? 'selected' : '' }}>
                                {{ $company->description }}
                            </option>
                        @endforeach
                    </select>
                    @error('iCompMainName')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            @else
                <!-- Hidden Company Field for Non-System Admin Users -->
                <input type="hidden" name="iCompMainName" value="{{ auth()->user()->company_id }}">
                <p>Company: {{ auth()->user()->company->description }}</p>
            @endif

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="iCompMainRegNo" class="form-label">Company Registration No</label>
                    <input type="text" class="form-control" id="iCompMainRegNo" name="iCompMainRegNo"
                        value="{{ $companyMaintenance->iCompMainRegNo }}" required>
                </div>
                <div class="col-md-6">
                    <label for="iCompMainPhoneNo" class="form-label">Company Phone No</label>
                    <input type="text" class="form-control" id="iCompMainPhoneNo" name="iCompMainPhoneNo"
                        value="{{ $companyMaintenance->iCompMainPhoneNo }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="iCompMainEmail" class="form-label">Company Email</label>
                    <input type="email" class="form-control" id="iCompMainEmail" name="iCompMainEmail"
                        value="{{ $companyMaintenance->iCompMainEmail }}" required>
                </div>
                <div class="col-md-6">
                    <label for="iCompMainLogo" class="form-label">Company Logo</label>
                    <input type="file" class="form-control" id="iCompMainLogo" name="iCompMainLogo">
                    @if($companyMaintenance->iCompMainLogo)
                        <img src="{{ asset('/storage' . $companyMaintenance->iCompMainLogo) }}" alt="Company Logo"
                            width="50" class="mt-2">
                    @endif
                </div>
            </div>

            <div class="mb-3">
                <label for="iCompMainAddress" class="form-label">Company Address</label>
                <textarea class="form-control" id="iCompMainAddress" name="iCompMainAddress" rows="4"
                    required>{{ $companyMaintenance->iCompMainAddress }}</textarea>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-save"></i> Update
                </button>
                <a href="{{ route('companyMaintenance.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>

<style>
    .container {
        background: linear-gradient(to top, #e3f2fd, #fff);
    }

    .card {
        border: none;
        border-radius: 15px;
    }

    .form-label {
        font-weight: 500;
    }

    .form-select {
        width: 100%;
        height: 45px;
        padding: 0.5rem 1rem;
        font-size: 16px;
        border: 1px solid #ced4da;
        border-radius: 8px;
        background-color: #f9f9f9;
        color: #495057;
        appearance: none;
        background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="gray" class="bi bi-caret-down-fill" viewBox="0 0 16 16"%3E%3Cpath d="M7.247 11.14l-4.796-5.481C2.013 5.253 2.482 4.5 3.206 4.5h9.588c.724 0 1.193.753.756 1.159l-4.796 5.481c-.57.652-1.514.652-2.084 0z"/%3E%3C/svg%3E');
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1rem;
    }

    .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
        outline: none;
    }

    .form-select option {
        color: #333;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        padding: 0.5rem 1.5rem;
    }

    .btn-secondary {
        background-color: #6c757d;
        border: none;
        padding: 0.5rem 1.5rem;
    }

    .profile-image img {
        border-radius: 50%;
        transition: transform 0.2s;
    }

    .profile-image img:hover {
        transform: scale(1.1);
    }
</style>

@endsection