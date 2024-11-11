@extends('layouts.template_sales')

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
                <label for="ismasSuppfk" >Debtor Code</label>
                <select id="ismasSuppfk" name="ismasSuppfk" class="form-control" required>
                    @foreach($suppliers as $supp)
                        <option value="{{ $supp->iSuppPk }}" {{ $sale->ismasPymtdfk == $supp->iSuppPk ? 'selected' : ''}}> -{{ $supp->iSuppDesc }} </option>
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


         <!-- Salesperson -->
         <div class="form-group mb-3">
                <label for="ismausersfk" >Salesperson</label>
                <input type="text" class="form-control" id="ismausersfk" name="ismausersfk" value="{{ $sale->ismasusersfk }}"  required>
            </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Update Sale</button>
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

@endsection
