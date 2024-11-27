@extends('layouts.template')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4" style="max-width: 600px; width: 100%; border-radius: 15px;">
    <h2  class="text-center mb-4">Edit Sale</h2>

    <form action="{{ route('sales.update', $sale->ismaspk) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label for="dsmasdate">Date</label>
            <input type="date" class="form-control" id="dsmasdate" name="dsmasdate" value="{{ $sale->dsmasdate }}" required>
        </div>

        <div class="form-group  mb-3">
            <label for="csmasdesc">Description</label>
            <input type="text" class="form-control" id="csmasdesc" name="csmasdesc" value="{{ $sale->csmasdesc }}" required>
        </div>

        <div class="form-group  mb-3">
            <label for="ysmasdeposit">Deposit/Full Payment</label>
            <input type="number" class="form-control" id="ysmasdeposit" name="ysmasdeposit" value="{{ $sale->ysmasdeposit }}" required>
        </div>

        
        <div class="form-group  mb-3">
            <label for="ismasPymtdfk">Payment Method</label>
            <select id="ismasPymtdfk" name="ismasPymtdfk" class="form-control" required>
                @foreach($paymentMethods as $method)
                    <option value="{{ $method->iPymtdPk }}" {{ $sale->ismasPymtdfk == $method->iPymtdPk ? 'selected' : '' }}>{{ $method->cPymtdDesc }}</option>
                @endforeach
            </select>
        </div>

        <!-- Kod Penghutang -->
        <div class="form-group mb-3">
                <label for="csmasDebtorfk" >Debtor Code</label>
                <select id="csmasDebtorfk" name="csmasDebtorfk" class="form-control" required>
                    @foreach($debtor as $debt)
                        <option value="{{ $debt->iDebtorPk }}" {{ $sale->csmasDebtorfk == $debt->cDebtorPk ? 'selected' : ''}}> -{{ $debt->cDebtorDesc }} </option>
                    @endforeach
                </select>
         </div>

        <!-- Invoice Reference -->
        <div class="form-group mb-3">
            <label for="ismasinvoiceref" >Invoice Reference</label>
            <input type="text" class="form-control" id="ismasinvoiceref" name="ismasinvoiceref" value="{{ $sale->ismasinvoiceref }}" required>
        </div>

        
        <!-- Cara Jualan (Auto-detected) -->
        <div class="form-group mb-3">
            <label for="cara_jualan" >Sale Method</label>
            <input type="text" class="form-control" id="cara_jualan" name="cara_jualan" readonly>
        </div>

        <div class="form-group  mb-3">
            <label for="ysmaspayment">Total Payment</label>
            <input type="number" class="form-control" id="ysmaspayment" name="ysmaspayment" value="{{ $sale->ysmaspayment }}" required>
        </div>

            <div class="form-group  mb-3">
            <label for="ismasusersfk">Salesperson</label>
            <select id="ismasusersfk" name="ismasusersfk" class="form-control" required>
                @foreach($employee as $emp)
                    <option value="{{ $emp->iEmpmasPk }}" {{ $sale->ismasusersfk == $emp->iEmpmasPk ? 'selected' : '' }}>{{ $emp->cEmpName }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            @if(Auth::user()->role === 'system admin')
                <label for="company_id{{ $sale->ismaspk }}" class="form-label">Company</label>
            
                    <select class="form-control" id="company_id{{ $sale->ismaspk }}" name="company_id" required>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{  $sale->company_id == $company->id ? 'selected' : '' }}>
                                {{ $company->description }}
                            </option>
                        @endforeach
                    </select>
                @else
                    <input type="hidden" name="company_id" value="{{ Auth::user()->company_id }}">
                @endif
         </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Update Sale</button>
            <a href="{{ route('sales.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>
</div>

<!-- JavaScript to auto-detect Cara Jualan -->
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
</script>

@endsection
