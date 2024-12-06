@extends('layouts.template')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-5" style="max-width: 700px; width: 100%; border-radius: 12px;">

        <h2 class="text-center text-primary mb-5">Add Company Details</h2>

        <form action="{{ route('companyMaintenance.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group mb-3">
                <label for="iCompMainName" class="form-label">Company Name</label>
                <select class="form-select @error('iCompMainName') is-invalid @enderror" id="iCompMainName"
                    name="iCompMainName" required>
                    <option value="" disabled selected>Select Company</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id}}" {{ old('iCompMainName', $companyMaintenance->iCompMainName ?? '') == $company->id ? 'selected' : '' }}>
                            {{ $company->description}}
                        </option>
                    @endforeach
                </select>
                @error('iCompMainName')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="iCompMainRegNo">Company Registration No</label>
                <input type="text" class="form-control" id="iCompMainRegNo" name="iCompMainRegNo" required>
            </div>
            <div class="form-group">
                <label for="iCompMainPhoneNo">Company Phone No</label>
                <input type="text" class="form-control" id="iCompMainPhoneNo" name="iCompMainPhoneNo" required>
            </div>
            <div class="form-group">
                <label for="iCompMainEmail">Company Email</label>
                <input type="email" class="form-control" id="iCompMainEmail" name="iCompMainEmail" required>
            </div>
            <div class="form-group">
                <label for="iCompMainLogo">Company Logo</label>
                <input type="file" class="form-control" id="iCompMainLogo" name="iCompMainLogo">
            </div>
            <div class="form-group">
                <label for="iCompMainAddress">Company Address</label>
                <textarea class="form-control" id="iCompMainAddress" name="iCompMainAddress" rows="4"
                    required></textarea>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary ">Save Customer Details</button>
                <a href="{{ route('companyMaintenance.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>

<style>
    /* Centering the card within the viewport */
    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    /* Card styling */
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 2rem;
    }

    /* Form elements styling */
    .form-group label {
        font-weight: 500;
        color: #555;
    }

    /* Uniform height, width, and styling for input and select */
    .form-control,
    .form-select {
        width: 100%;
        height: 38px;
        /* Match input field height */
        padding: 0.5rem;
        border-radius: 8px;
        border: 1px solid #ced4da;
        box-sizing: border-box;
        /* Ensures padding doesn’t affect width */
        appearance: none;
        /* Removes default styling on some browsers */
    }

    /* Button styling */
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
</style>
@endsection