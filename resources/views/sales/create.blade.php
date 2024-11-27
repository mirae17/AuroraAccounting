@extends('layouts.template')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4" style="max-width: 600px; width: 100%; border-radius: 15px;">

        <h2 class="text-center mb-4">Add New Sale</h2>

        <form action="{{ route('sales.store') }}" method="POST">
            @csrf

            <!-- Date -->
            <div class="form-group mb-3">
                <label for="dsmasdate" class="form-label">Date</label>
                <input type="date" class="form-control" id="dsmasdate" name="dsmasdate" required>
            </div>

            <!-- Description -->
            <div class="form-group mb-3">
                <label for="csmasdesc" class="form-label">Description</label>
                <input type="text" class="form-control" id="csmasdesc" name="csmasdesc" required>
            </div>

            <!-- Deposit -->
            <div class="form-group mb-3">
                <label for="ysmasdeposit" class="form-label">Deposit/Full Payment</label>
                <input type="number" step="0.01" class="form-control" id="ysmasdeposit" name="ysmasdeposit" required>
            </div>

            <!-- Payment Method -->
            <div class="form-group mb-3">
                <label for="ismasPymtdfk" class="form-label">Payment Method</label>
                <select id="ismasPymtdfk" name="ismasPymtdfk" class="form-select" required>
                <option value="">Select Payment Method</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->iPymtdPk}}">{{ $method->cPymtdDesc }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Kod Penghutang -->
            <div class="form-group mb-3">
                <label for="csmasDebtorfk" class="form-label">Debtor Code</label>
                <select id="csmasDebtorfk" name="csmasDebtorfk" class="form-select" required>
                <option value="">Select Penghutang</option>
                    @foreach($debtor as $debt)
                        <option value="{{ $debt->iDebtorPk }}">{{ $debt->cDebtorCode }} - {{ $debt->cDebtorDesc }} </option>
                    @endforeach
                </select>
            </div>

            <!-- Invoice Reference -->
            <div class="form-group mb-3">
                <label for="ismasinvoiceref" class="form-label">Invoice Reference</label>
                <input type="text" class="form-control" id="ismasinvoiceref" name="ismasinvoiceref" required>
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

            <!-- Salesperson -->
            <div class="form-group mb-3">
                <label for="ismasusersfk" class="form-label">Salesperson</label>
                <input type="text" class="form-control" id="ismasusersfk" name="ismasusersfk" required>
            </div>

            @if(Auth::user()->role === 'system admin')
                <div class="form-group mb-3">
                    <label for="company_id" class="form-label">Company</label>
                    <select class="form-control" id="company_id" name="company_id" required>
                        <option value="">Select Company</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->description }}</option>
                        @endforeach
                    </select>
                </div>
            @else
                <input type="hidden" name="company_id" value="{{ Auth::user()->company_id }}">
            @endif

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

    // When company is selected, update debtor and payment method
// When company is selected, update debtor and payment method
$('#company_id').change(function () {
    var companyId = $(this).val();

    // Get debtors related to the selected company
    $.ajax({
        url: '/get-debtors/' + companyId,  // Correct URL
        method: 'GET',
        success: function (data) {
            // Clear previous options
            $('#csmasDebtorfk').empty();
            $('#csmasDebtorfk').append('<option value="">Select Debtor</option>');  // Add placeholder option

            // Add new options
            data.debtors.forEach(function (debtor) {
                $('#csmasDebtorfk').append('<option value="' + debtor.id + '">' + debtor.name + '</option>');
            });
        }
    });

    // Get payment methods related to the selected company
    $.ajax({
        url: '/get-payment-methods/' + companyId,  // Correct URL
        method: 'GET',
        success: function (data) {
            // Clear previous options
            $('#ismasPymtdfk').empty();
            $('#ismasPymtdfk').append('<option value="">Select Payment Method</option>');  // Add placeholder option

            // Add new options
            data.paymentMethods.forEach(function (paymentMethod) {
                $('#ismasPymtdfk').append('<option value="' + paymentMethod.id + '">' + paymentMethod.name + '</option>');
            });
        }
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
