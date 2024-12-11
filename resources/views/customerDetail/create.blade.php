@extends('layouts.template')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-5" style="max-width: 700px; width: 100%; border-radius: 12px;">

        <h2 class="text-center text-primary mb-5">Add Customer Details</h2>

        <form action="{{ route('customerDetail.store') }}" method="POST">
            @csrf

            <!-- Customer Details Section -->
            <div class="section-header">
                <h5>Customer Details</h5>
                <p class="text-muted mb-4">Please enter the customer information below</p>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="cCustDName">Customer Name</label>
                    <input type="text" class="form-control" id="cCustDName" name="cCustDName" placeholder="Enter name"
                        required>
                </div>
                <div class="form-group col-md-6">
                    <label for="cCustDPhoneNo">Phone No</label>
                    <input type="text" class="form-control" id="cCustDPhoneNo" name="cCustDPhoneNo"
                        placeholder="Enter phone number" required>
                </div>
            </div>

            <div class="form-group">
                <label for="cCustDAddress">Customer Address</label>
                <textarea class="form-control" id="cCustDAddress" name="cCustDAddress" placeholder="Enter address"
                    rows="3" required></textarea>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="cCustDCity">City</label>
                    <input type="text" class="form-control" id="cCustDCity" name="cCustDCity" placeholder="Enter city"
                        required>
                </div>
                <div class="form-group col-md-3">
                    <label for="cCustDPostcode">Postcode</label>
                    <input type="text" class="form-control" id="cCustDPostcode" name="cCustDPostcode"
                        placeholder="Enter postcode" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="cCustDState">State</label>
                    <input type="text" class="form-control" id="cCustDState" name="cCustDState"
                        placeholder="Enter state" required>
                </div>
            </div>

            <!-- Company Details Section -->
            <div class="section-header mt-5">
                <h5>Company Details</h5>
                <p class="text-muted mb-4">Please select the company associated with the customer</p>
            </div>

            <div class="form-group">

                <label for="cCustDCompName">Company Name</label>
                <input type="text" class="form-control" id="cCustDCompName" name="cCustDCompName"
                    placeholder="Enter company name" required>

            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="cCustDCompNo">Company Number</label>
                    <input type="text" class="form-control" id="cCustDCompNo" name="cCustDCompNo"
                        placeholder="Enter company number" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="cCustDCompOfficeNo">Office Number</label>
                    <input type="text" class="form-control" id="cCustDCompOfficeNo" name="cCustDCompOfficeNo"
                        placeholder="Enter office number" required>
                </div>
            </div>

            <div class="form-group">
                <label for="cCustDCompEmail">Company Email</label>
                <input type="email" class="form-control" id="cCustDCompEmail" name="cCustDCompEmail"
                    placeholder="Enter company email" required>
            </div>

            <div class="form-group">
                <label for="cCustDCompWebsite">Company Website</label>
                <input type="url" class="form-control" id="cCustDCompWebsite" name="cCustDCompWebsite"
                    placeholder="Enter company website" required>
            </div>

            <div class="form-group">
                @if(Auth::user()->role === 'system admin')
                    <label for="iCustDCompfk" class="form-label">Company</label>
                    <select class="form-control" id="iCustDCompfk" name="iCustDCompfk" required>
                        <option value="">Select a Company</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->description }}</option>
                        @endforeach
                    </select>
                @else
                    <input type="hidden" name="iCustDCompfk" value="{{ Auth::user()->company_id }}">
                @endif
            </div>
            <br>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary ">Save Customer Details</button>
                <a href="{{ route('customerDetail.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Custom Styling -->
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