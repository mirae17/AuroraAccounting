@extends('layouts.template')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4" style="max-width: 600px; width: 100%; border-radius: 15px;">

        <h2 class="text-center mb-4">Add New Sale</h2>

        <form action="{{ route('sales.store') }}" method="POST">
            @csrf

            @if(Auth::user()->role === 'system admin')
                <div class="form-group mb-3">
                    <label for="company_id" class="form-label">Company</label>
                    <select class="form-control" id="company_id" name="company_id" required>
                        <option value="">Select Company</option>
                        @foreach($companies as $company)
                            <option 
                                value="{{ $company->id }}" 
                                data-payment-methods='@json($company->payments)' 
                                data-debtors='@json($company->debtors)'>
                                {{ $company->description }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @else
            <input type="hidden" name="company_id" value="{{ Auth::user()->company_id }}">
            @endif

            <!-- Date -->
            <div class="form-group mb-3">
                <label for="dsmasdate" class="form-label">Date</label>
                <input type="date" class="form-control" id="dsmasdate"  name="dsmasdate" required>
            </div>

            <!-- Description -->
            <div class="form-group mb-3">
                <label for="csmasdesc" class="form-label">Description</label>
                <input type="text" class="form-control" id="csmasdesc" maxlength="150" name="csmasdesc" required>
            </div>

            <!-- Deposit -->
            <div class="form-group mb-3">
                <label for="ysmasdeposit" class="form-label">Deposit/Full Payment</label>
                <input type="number" step="0.01" class="form-control" id="ysmasdeposit" name="ysmasdeposit" required>
            </div>

            <div class="form-group mb-3">
                <label for="ismasPymtdfk" class="form-label">Payment Method</label>
                <select id="ismasPymtdfk" name="ismasPymtdfk" class="form-select" required>
                    <option value="">Select Payment Method</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->iPymtdPk }}">{{ $method->cPymtdDesc }}</option>
                    @endforeach
                </select>
            </div>

             <div class="form-group mb-3">
                <label for="csmasDebtorfk" class="form-label">Debtor Code</label>
                <select id="csmasDebtorfk" name="csmasDebtorfk" class="form-select" required>
                    <option value="">Select Debtor</option>
                    @foreach($debtors as $debtor)
                        <option value="{{ $debtor->iDebtorPk }}">{{ $debtor->cDebtorCode }} - {{ $debtor->cDebtorDesc }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Invoice Reference -->
            <div class="form-group mb-3">
                <label for="ismasinvoiceref" class="form-label">Invoice Reference</label>
                <input type="text" class="form-control" id="ismasinvoiceref" maxlength="50" name="ismasinvoiceref" required>
            </div>

            <!-- Cara Jualan (Auto-detected) -->
            <div class="form-group mb-3">
                <label for="cara_jualan" class="form-label">Sale Method</label>
                <input type="text" class="form-control" id="cara_jualan" name="cara_jualan" readonly>
            </div>

            <!-- Total Payment -->
            <div class="form-group mb-3">
                <label for="ysmaspayment" class="form-label">Total Payment</label>
                <input type="number" step="0.01" class="form-control" id="ysmaspayment" name="ysmaspayment" required>
            </div>

            <div class="form-group  mb-3">
            <label for="ismasusersfk">Salesperson</label>
            <select id="ismasusersfk" name="ismasusersfk" class="form-control" required>
            <option value="">Select Salesperson</option>
                @foreach($employee as $emp)
                    <option value="{{ $emp->iEmpmasPk }}" {{ $emp->ismasusersfk == $emp->iEmpmasPk ? 'selected' : '' }}>{{ $emp->cEmpName }}</option>
                @endforeach
            </select>
            </div>
           
            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary me-2">Save Sale</button>
                <a href="{{ route('sales.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript to auto-detect Cara Jualan -->
<script>
    document.getElementById('ysmasdeposit').addEventListener('input', handleCaraJualan);
    document.getElementById('ysmaspayment').addEventListener('input', handleCaraJualan);

    function handleCaraJualan() {
        const deposit = parseFloat(document.getElementById('ysmasdeposit').value) || 0;
        const totalPayment = parseFloat(document.getElementById('ysmaspayment').value) || 0;
        const caraJualan = document.getElementById('cara_jualan');
        const debtorField = document.getElementById('csmasDebtorfk');

        if (deposit === totalPayment) {
            caraJualan.value = 'Cash';
            debtorField.value = ''; // Clear debtor field
            debtorField.disabled = true; // Disable debtor selection
        } else {
            caraJualan.value = 'Credit';
            debtorField.disabled = false; // Enable debtor selection
        }
    }

        document.getElementById('company_id')?.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        
        const paymentMethods = JSON.parse(selectedOption.getAttribute('data-payment-methods')) || [];
        const debtors = JSON.parse(selectedOption.getAttribute('data-debtors')) || [];

        const paymentMethodSelect = document.getElementById('ismasPymtdfk');
        paymentMethodSelect.innerHTML = '<option value="">Select Payment Method</option>';
        paymentMethods.forEach(method => {
            paymentMethodSelect.innerHTML += `<option value="${method.iPymtdPk}">${method.cPymtdDesc}</option>`;
        });

        const debtorSelect = document.getElementById('csmasDebtorfk');
        debtorSelect.innerHTML = '<option value="">Select Debtor</option>';
        debtors.forEach(debtor => {
            debtorSelect.innerHTML += `<option value="${debtor.iDebtorPk}">${debtor.cDebtorDesc}</option>`;
        });
    });


</script>


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
    .form-control, .form-select {
        width: 100%;
        height: 38px; /* Match input field height */
        padding: 0.5rem;
        border-radius: 8px;
        border: 1px solid #ced4da;
        box-sizing: border-box; /* Ensures padding doesn’t affect width */
        appearance: none; /* Removes default styling on some browsers */
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
