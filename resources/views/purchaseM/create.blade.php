@extends('layouts.template')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4" style="max-width: 600px; width: 100%; border-radius: 15px;">

        <h2 class="text-center mb-4">Add New Purchase</h2>

        <form action="{{ route('purchaseM.store') }}" method="POST">
            @csrf

            @if(Auth::user()->role === 'system admin')
                <div class="form-group mb-3">
                    <label for="company_id" class="form-label">Company</label>
                    <select class="form-control" id="company_id" name="company_id" required>
                        <option value="">Select Company</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" data-payment-methods='@json($company->payments)'
                                data-suppliers='@json($company->suppliers)'>
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
                <label for="dpmasdate" class="form-label">Date</label>
                <input type="date" class="form-control" id="dpmasdate" name="dpmasdate" required>
            </div>

            <!-- Invoice Reference -->
            <div class="form-group mb-3">
                <label for="ipmasinvoiceref" class="form-label">Invoice Reference</label>
                <input type="text" class="form-control" id="ipmasinvoiceref" maxlength="50" name="ipmasinvoiceref"
                    required>
            </div>

            <!-- Supplier -->
            <div class="form-group mb-3">
                <label for="ipmasSuppfk" class="form-label">Supplier</label>
                <select id="ipmasSuppfk" name="ipmasSuppfk" class="form-select" required>
                    <option value="">Select Supplier</option>
                    @foreach($suppliers as $supp)
                        <option value="{{ $supp->iSuppPk }}">{{ $supp->iSuppCode }} - {{ $supp->iSuppDesc }} </option>
                    @endforeach
                </select>
            </div>

            <!-- Description -->
            <div class="form-group mb-3">
                <label for="cpmascodeprod" class="form-label">Product Code</label>
                <input type="text" class="form-control" id="cpmascodeprod" maxlength="50" name="cpmascodeprod" required>
            </div>

            <!-- Total Payment -->
            <div class="form-group mb-3">
                <label for="ypmaspayment" class="form-label">Total Payment</label>
                <input type="number" step="0.01" class="form-control" id="ypmaspayment" step="0.01" name="ypmaspayment"
                    required>
            </div>

            <!-- Cara Jualan (Auto-detected) -->
            <div class="form-group mb-3">
                <label for="cara_jualan" class="form-label">Sale Method</label>
                <input type="text" class="form-control" id="cara_jualan" name="cara_jualan" readonly>
            </div>

            <!-- Deposit -->
            <div class="form-group mb-3">
                <label for="ypmasdeposit" class="form-label">Deposit/Full Payment</label>
                <input type="number" step="0.01" class="form-control" id="ypmasdeposit" step="0.01" name="ypmasdeposit"
                    required>
            </div>

            <!-- Payment Method -->
            <div class="form-group mb-3">
                <label for="ipmasPymtdfk" class="form-label">Payment Method</label>
                <select id="ipmasPymtdfk" name="ipmasPymtdfk" class="form-select" required>
                    <option value="">Select Payment Method</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->iPymtdPk}}">{{ $method->cPymtdDesc }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Notes -->
            <div class="form-group mb-3">
                <label for="cpmasnotes" class="form-label">Notes</label>
                <input type="text" class="form-control" id="cpmasnotes" maxlength="150" name="cpmasnotes" required>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary me-2">Save Purchase</button>
                <a href="{{ route('purchaseM.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript to auto-detect Cara Jualan -->
<script>
    document.getElementById('ypmasdeposit').addEventListener('input', detectCaraJualan);
    document.getElementById('ypmaspayment').addEventListener('input', detectCaraJualan);

    function detectCaraJualan() {
        const deposit = parseFloat(document.getElementById('ypmasdeposit').value) || 0;
        const totalPayment = parseFloat(document.getElementById('ypmaspayment').value) || 0;
        const caraJualan = document.getElementById('cara_jualan');

        // Determine Cara Jualan based on deposit and total payment
        if (deposit === totalPayment) {
            caraJualan.value = 'Cash';
        } else {
            caraJualan.value = 'Credit';
        }
    }

    document.getElementById('company_id')?.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];

        const paymentMethods = JSON.parse(selectedOption.getAttribute('data-payment-methods')) || [];
        const suppliers = JSON.parse(selectedOption.getAttribute('data-suppliers')) || [];

        const paymentMethodSelect = document.getElementById('ipmasPymtdfk');
        paymentMethodSelect.innerHTML = '<option value="">Select Payment Method</option>';
        paymentMethods.forEach(method => {
            paymentMethodSelect.innerHTML += `<option value="${method.iPymtdPk}">${method.cPymtdDesc}</option>`;
        });

        const suppSelect = document.getElementById('ipmasSuppfk');
        suppSelect.innerHTML = '<option value="">Select Supplier</option>';
        suppliers.forEach(supp => {
            suppSelect.innerHTML += `<option value="${supp.iSuppPk}">${supp.iSuppDesc}</option>`;
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