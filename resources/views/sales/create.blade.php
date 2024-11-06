@extends('layouts.template_sales')

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
                <label for="csmasdesc" class="form-label">Perkara/Customer Details</label>
                <input type="text" class="form-control" id="csmasdesc" name="csmasdesc" required>
            </div>

            <!-- Deposit -->
            <div class="form-group mb-3">
                <label for="ysmasdeposit" class="form-label">Deposit/Bayaran Penuh</label>
                <input type="number" step="0.01" class="form-control" id="ysmasdeposit" name="ysmasdeposit" required>
            </div>

            <!-- Payment Method -->
            <div class="form-group mb-3">
                <label for="ismasPymtdfk" class="form-label">Cara Bayaran</label>
                <select id="ismasPymtdfk" name="ismasPymtdfk" class="form-select" required>
                <option value="">Select Payment Method</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->iPymtdPk}}">{{ $method->cPymtdDesc }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Kod Penghutang -->
            <div class="form-group mb-3">
                <label for="ismasSuppfk" class="form-label">Kod Penghutang</label>
                <select id="ismasSuppfk" name="ismasSuppfk" class="form-select" required>
                <option value="">Select Penghutang</option>
                    @foreach($suppliers as $supp)
                        <option value="{{ $supp->iSuppPk }}">{{ $supp->iSuppCode }} - {{ $supp->iSuppDesc }} </option>
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
                <label for="cara_jualan" class="form-label">Cara Jualan</label>
                <input type="text" class="form-control" id="cara_jualan" name="cara_jualan" readonly>
            </div>

            <!-- Total Payment -->
            <div class="form-group mb-3">
                <label for="ysmaspayment" class="form-label">Total Payment</label>
                <input type="number" step="0.01" class="form-control" id="ysmaspayment" name="ysmaspayment" required>
            </div>

            <!-- Salesperson -->
            <div class="form-group mb-3">
                <label for="ismausersfk" class="form-label">Salesperson</label>
                <input type="text" class="form-control" id="ismausersfk" name="ismausersfk" required>
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
    document.getElementById('ysmasdeposit').addEventListener('input', detectCaraJualan);
    document.getElementById('ysmaspayment').addEventListener('input', detectCaraJualan);

    function detectCaraJualan() {
        const deposit = parseFloat(document.getElementById('ysmasdeposit').value) || 0;
        const totalPayment = parseFloat(document.getElementById('ysmaspayment').value) || 0;
        const caraJualan = document.getElementById('cara_jualan');

        // Determine Cara Jualan based on deposit and total payment
        if (deposit === totalPayment) {
            caraJualan.value = 'Cash';
        } else {
            caraJualan.value = 'Credit';
        }
    }
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
