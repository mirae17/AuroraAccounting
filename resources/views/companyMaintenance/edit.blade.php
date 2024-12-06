@extends('layouts.template')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4" style="max-width: 600px; width: 100%; border-radius: 15px;">
        <h2 class="text-center mb-4">Edit Purchase</h2>

        <form action="{{ route('companyMaintenance.update', $companyMaintenance->iCompMainPk) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="iCompMainName">Company Name</label>
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
                <input type="text" class="form-control" id="iCompMainRegNo" name="iCompMainRegNo"
                    value="{{ $companyMaintenance->iCompMainRegNo }}" required>
            </div>
            <div class="form-group">
                <label for="iCompMainPhoneNo">Company Phone No</label>
                <input type="text" class="form-control" id="iCompMainPhoneNo" name="iCompMainPhoneNo"
                    value="{{ $companyMaintenance->iCompMainPhoneNo }}" required>
            </div>
            <div class="form-group">
                <label for="iCompMainEmail">Company Email</label>
                <input type="email" class="form-control" id="iCompMainEmail" name="iCompMainEmail"
                    value="{{ $companyMaintenance->iCompMainEmail }}" required>
            </div>
            <div class="form-group">
                <label for="iCompMainLogo">Company Logo</label>
                <input type="file" class="form-control" id="iCompMainLogo" name="iCompMainLogo">
                @if($companyMaintenance->iCompMainLogo)
                    <img src="{{ asset('storage/' . $companyMaintenance->iCompMainLogo) }}" alt="Company Logo" width="50"
                        class="mt-2">
                @endif
            </div>
            <div class="form-group">
                <label for="iCompMainAddress">Company Address</label>
                <textarea class="form-control" id="iCompMainAddress" name="iCompMainAddress" rows="4"
                    required>{{ $companyMaintenance->iCompMainAddress }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary ">Update Company Maintenance</button>
            <a href="{{ route('companyMaintenance.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </form>
    </div>
</div>

<style>
    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 3rem;
    }

    .section-header h5 {
        font-size: 1.25rem;
        font-weight: bold;
        color: #007bff;
    }

    .section-header p {
        font-size: 0.9rem;
        color: #6c757d;
    }

    .form-group label {
        font-weight: 500;
        color: #555;
    }

    .form-control,
    .form-select {
        height: 45px;
        padding: 0.75rem;
        border-radius: 8px;
        border: 1px solid #ced4da;
        box-sizing: border-box;
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        padding: 0.75rem;
        font-size: 1.1rem;
        border-radius: 8px;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .form-row {
        margin-bottom: 1.5rem;
    }

    .form-row .form-group {
        padding-right: 15px;
    }

    .form-row .form-group:last-child {
        padding-right: 0;
    }
</style>

@endsection