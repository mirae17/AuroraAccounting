@extends('layouts.template')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4" style="max-width: 600px; width: 100%; border-radius: 15px;">
        <h2 class="text-center mb-4">Edit Sale</h2>

        <form action="{{ route('sales.update', $sale->ismaspk) }}" method="POST">
            @csrf
            @method('PUT')

            @if(Auth::user()->role === 'system admin')
                <!-- Company Dropdown -->
                <div class="form-group mb-3">
                    <label for="company_id" class="form-label">Company</label>
                    <select class="form-control" id="company_id" name="company_id" required>
                        <option value="">Select Company</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" 
                                data-payment-methods='@json($company->payments)'
                                data-debtors='@json($company->debtors)'
                                {{ $sale->company_id == $company->id ? 'selected' : '' }}>
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
                <label for="dsmasdate">Date</label>
                <input type="date" class="form-control" id="dsmasdate" name="dsmasdate" value="{{ $sale->dsmasdate }}" required>
            </div>

            <!-- Description -->
            <div class="form-group mb-3">
                <label for="csmasdesc">Description</label>
                <input type="text" class="form-control" id="csmasdesc" maxlength="100" name="csmasdesc" value="{{ $sale->csmasdesc }}" required>
            </div>

            <!-- Deposit -->
            <div class="form-group mb-3">
                <label for="ysmasdeposit">Deposit/Full Payment</label>
                <input type="number" step="0.01" class="form-control" id="ysmasdeposit" name="ysmasdeposit" value="{{ $sale->ysmasdeposit }}" required>
            </div>

            <!-- Payment Method -->
            <div class="form-group mb-3">
                <label for="ismasPymtdfk" class="form-label">Payment Method</label>
                <select id="ismasPymtdfk" name="ismasPymtdfk" class="form-control" required>
                    <option value="">Select Payment Method</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->iPymtdPk }}" {{ $sale->ismasPymtdfk == $method->iPymtdPk ? 'selected' : '' }}>
                            {{ $method->cPymtdDesc }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Debtor Code -->
            <div class="form-group mb-3">
                <label for="csmasDebtorfk" class="form-label">Debtor Code</label>
                <select id="csmasDebtorfk" name="csmasDebtorfk" class="form-control" required>
                    <option value="">Select Debtor</option>
                    @foreach($debtors as $debtor)
                        <option value="{{ $debtor->iDebtorPk }}" {{ $sale->csmasDebtorfk == $debtor->iDebtorPk ? 'selected' : '' }}>
                            {{ $debtor->cDebtorCode }} - {{ $debtor->cDebtorDesc }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Invoice Reference -->
            <div class="form-group mb-3">
                <label for="ismasinvoiceref">Invoice Reference</label>
                <input type="text" class="form-control" id="ismasinvoiceref" maxlength="50" name="ismasinvoiceref" value="{{ $sale->ismasinvoiceref }}" required>
            </div>

            <!-- Sale Method (Cara Jualan) -->
            <div class="form-group mb-3">
                <label for="cara_jualan">Sale Method</label>
                <input type="text" class="form-control" id="cara_jualan" name="cara_jualan" value="{{ $sale->cara_jualan }}" readonly>
            </div>

            <!-- Total Payment -->
            <div class="form-group mb-3">
                <label for="ysmaspayment">Total Payment</label>
                <input type="number" step="0.01" class="form-control" id="ysmaspayment" name="ysmaspayment" value="{{ $sale->ysmaspayment }}" required>
            </div>

            <div class="form-group  mb-3">
            <label for="ismasusersfk">Salesperson</label>
            <select id="ismasusersfk" name="ismasusersfk" class="form-control" required>
                @foreach($employee as $emp)
                    <option value="{{ $emp->iEmpmasPk }}" {{ $sale->ismasusersfk == $emp->iEmpmasPk ? 'selected' : '' }}>{{ $emp->cEmpName }}</option>
                @endforeach
            </select>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Update Sale</button>
                <a href="{{ route('sales.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript -->
<script>
    // Handle Company Change
    document.getElementById('company_id')?.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const paymentMethods = JSON.parse(selectedOption.getAttribute('data-payment-methods')) || [];
        const debtors = JSON.parse(selectedOption.getAttribute('data-debtors')) || [];

        // Populate Payment Method
        const paymentMethodSelect = document.getElementById('ismasPymtdfk');
        paymentMethodSelect.innerHTML = '<option value="">Select Payment Method</option>';
        paymentMethods.forEach(method => {
            paymentMethodSelect.innerHTML += `<option value="${method.iPymtdPk}">${method.cPymtdDesc}</option>`;
        });

        // Populate Debtor Code
        const debtorSelect = document.getElementById('csmasDebtorfk');
        debtorSelect.innerHTML = '<option value="">Select Debtor</option>';
        debtors.forEach(debtor => {
            debtorSelect.innerHTML += `<option value="${debtor.iDebtorPk}">${debtor.cDebtorDesc}</option>`;
        });
    });

    // Handle Sale Method (Cara Jualan)
    document.getElementById('ysmasdeposit').addEventListener('input', handleCaraJualan);
    document.getElementById('ysmaspayment').addEventListener('input', handleCaraJualan);

    function handleCaraJualan() {
        const deposit = parseFloat(document.getElementById('ysmasdeposit').value) || 0;
        const totalPayment = parseFloat(document.getElementById('ysmaspayment').value) || 0;
        const caraJualan = document.getElementById('cara_jualan');
        const debtorField = document.getElementById('csmasDebtorfk');

        if (deposit === totalPayment) {
            caraJualan.value = 'Cash';
            debtorField.value = ''; // Clear debtor
            debtorField.disabled = true; // Disable dropdown
        } else {
            caraJualan.value = 'Credit';
            debtorField.disabled = false; // Enable dropdown
        }
    }

    // Trigger functions to initialize values
    handleCaraJualan();
</script>

@endsection
