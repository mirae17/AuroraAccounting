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

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4" style="max-width: 600px; width: 100%; border-radius: 15px;">
        <h2 class="text-center mb-4">Add Company and User</h2>

        <!-- Company Selection and Add Company Form -->
        <form id="companyForm">
            @csrf
            <div class="form-group mb-3">
                <label for="company_select" class="form-label">Select Existing Company:</label>
                <select name="company_id" id="company_select" class="form-select">
                    <option value="">-- Select a Company --</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->description }}</option>
                    @endforeach
                </select>
            </div>
            
            <div id="addCompanyFields">
                <div class="form-group mb-3">
                    <label for="code" class="form-label">Company Code:</label>
                    <input type="text" class="form-control" name="code" maxlength="6" id="code">
                </div>
                <div class="form-group mb-3">
                    <label for="description" class="form-label">Company Name:</label>
                    <input type="text" class="form-control" name="description" maxlength="50" id="description">
                </div>
                <button type="button" id="addCompanyBtn" class="btn btn-primary">Add Company</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary" id="backCompanyBtn">Back</a>
            </div>
        </form>

        <!-- Read-only Company Info after adding -->
        <div id="addedCompanyInfo" style="display: none;">
            <div class="form-group mb-3">
                <label for="readonly_code" class="form-label">Company Code:</label>
                <input type="text" class="form-control" id="readonly_code" readonly>
            </div>
            <div class="form-group mb-3">
                <label for="readonly_description" class="form-label">Description:</label>
                <input type="text" class="form-control" id="readonly_description" readonly>
            </div>
        </div>

         <!-- Success message and user form - initially hidden -->
         <div id="successMessage" class="alert alert-success mt-4" style="display: none;">Company added successfully!</div>
        
        <!-- User Form -->
        <form id="userForm" action="{{ route('users.store') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="company_id" id="company_id">

            <div class="form-group mb-3">
                <label for="name" class="form-label">User Name:</label>
                <input type="text" class="form-control" id="name" maxlength="255" name="name" required>
            </div>
            <div class="form-group mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" maxlength="255" name="email" required>
            </div>
            <div class="form-group mb-3">
                <label for="role" class="form-label">Role:</label>
                <input type="text" class="form-control" id="role" maxlength="255" name="role" required>
            </div>
            <div class="form-group mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" minlength="8" required>
            </div>
            <div class="form-group mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password:</label>
                <input type="password" class="form-control" id="confirm-password" minlength="8" name="password_confirmation" required>
                <small id="passwordError" class="text-danger" style="display: none;">Passwords do not match.</small>
            </div>
            <button type="button" class="btn btn-success" id="submitUserForm">Add User</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary" id="backUserBtn">Back</a>
        </form>
    </div>
</div>

<script>
    // Handle company selection or adding a new company
    document.getElementById('company_select').addEventListener('change', function () {
        const addCompanyFields = document.getElementById('addCompanyFields');
        const userForm = document.getElementById('userForm');

        if (this.value) {  // If an existing company is selected
            addCompanyFields.style.display = 'none';  // Hide add company fields
            document.getElementById('company_id').value = this.value;  // Set company ID for user form
            userForm.style.display = 'block';  // Show user form
        } else {
            addCompanyFields.style.display = 'block';  // Show add company fields
            userForm.style.display = 'none';  // Hide user form
            document.getElementById('company_id').value = ''; // Clear company ID in user form
        }
    });

    // Handle Add Company button click
    document.getElementById('addCompanyBtn').addEventListener('click', function () {
        const code = document.getElementById('code').value;
        const description = document.getElementById('description').value;

        fetch("{{ route('companies.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ code, description })
        })
        .then(response => response.json())
        .then(data => {
            if (data.company) {
                // Show success message
                document.getElementById('successMessage').style.display = 'block';

                // Populate hidden company ID field in the user form
                document.getElementById('company_id').value = data.company.id;

                // Hide company selection dropdown and add company fields
                document.getElementById('company_select').style.display = 'none';
                document.getElementById('addCompanyFields').style.display = 'none';

                // Populate read-only fields with added company information
                document.getElementById('readonly_code').value = data.company.code;
                document.getElementById('readonly_description').value = data.company.description;

                // Display read-only company info and user form
                document.getElementById('addedCompanyInfo').style.display = 'block';
                document.getElementById('userForm').style.display = 'block';
            } else {
                alert('Error adding company');
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Handle User Form Submission with Password Match Validation
    document.getElementById('submitUserForm').addEventListener('click', function () {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm-password').value;
        const passwordError = document.getElementById('passwordError');
        const companyId = document.getElementById('company_id').value;

        // Check if passwords match
        if (password !== confirmPassword) {
            passwordError.style.display = 'block';
            return;
        } else {
            passwordError.style.display = 'none';
        }

        // Ensure company ID is set
        if (!companyId) {
            alert('Please select or add a company before adding a user.');
            return;
        }

        // Submit the form if passwords match and company ID is set
        document.getElementById('userForm').submit();
    });
</script>


@endsection
