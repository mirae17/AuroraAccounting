@extends('layouts.template')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4" style="max-width: 600px; width: 100%; border-radius: 15px;">
    <h2  class="text-center mb-4">Edit Purchase</h2>

    <form action="{{ route('purchaseM.update', $purchaseM->ipmaspk) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group mb-3">
            <label for="dpmasdate">Date</label>
            <input type="date" class="form-control" id="dpmasdate" name="dpmasdate" value="{{ $purchaseM->dpmasdate }}" required>
        </div>

           <!-- Invoice Reference -->
           <div class="form-group mb-3">
            <label for="ipmasinvoiceref" >Invoice Reference</label>
            <input type="text" class="form-control" id="ipmasinvoiceref" name="ipmasinvoiceref" value="{{ $purchaseM->ipmasinvoiceref }}" required>
        </div>

          <!-- Kod Penghutang -->
          <div class="form-group mb-3">
                <label for="ipmasSuppfk" >Supplier</label>
                <select id="ipmasSuppfk" name="ipmasSuppfk" class="form-control" required>
                    @foreach($suppliers as $supp)
                        <option value="{{ $supp->iSuppPk }}" {{ $purchaseM->ipmasPymtdfk == $supp->iSuppPk ? 'selected' : ''}}> -{{ $supp->iSuppDesc }} </option>
                    @endforeach
                </select>
         </div>

          <!-- Notes -->
            <div class="form-group mb-3">
                <label for="cpmascodeprod" >Notes</label>
                <input type="text" class="form-control" id="cpmascodeprod" name="cpmascodeprod" value="{{ $purchaseM->cpmascodeprod }}"  required>
            </div>

         <div class="form-group  mb-3">
            <label for="ypmaspayment">Total Payment</label>
            <input type="number" class="form-control" id="ypmaspayment" name="ypmaspayment" value="{{ $purchaseM->ypmaspayment }}" required>
        </div>

        <div class="form-group  mb-3">
            <label for="ypmasdeposit">Deposit/Full Payment</label>
            <input type="number" class="form-control" id="ypmasdeposit" name="ypmasdeposit" value="{{ $purchaseM->ypmasdeposit }}" required>
        </div>

        <!-- Cara Jualan (Auto-detected) -->
        <div class="form-group mb-3">
            <label for="cara_jualan" >Sale Method</label>
            <input type="text" class="form-control" id="cara_jualan" name="cara_jualan" readonly>
        </div>
        
        <div class="form-group  mb-3">
            <label for="ipmasPymtdfk">Payment Method</label>
            <select id="ipmasPymtdfk" name="ipmasPymtdfk" class="form-control" required>
                @foreach($paymentMethods as $method)
                    <option value="{{ $method->iPymtdPk }}" {{ $purchaseM->ipmasPymtdfk == $method->iPymtdPk ? 'selected' : '' }}>{{ $method->cPymtdDesc }}</option>
                @endforeach
            </select>
        </div>

         <!-- Notes -->
         <div class="form-group mb-3">
                <label for="cpmasnotes" >Notes</label>
                <input type="text" class="form-control" id="cpmasnotes" name="cpmasnotes" value="{{ $purchaseM->cpmasnotes }}"  required>
            </div>

            <div class="form-group mb-3">
            @if(Auth::user()->role === 'system admin')
                <label for="company_id{{ $purchaseM->ipmaspk }}" class="form-label">Company</label>
            
                    <select class="form-control" id="company_id{{ $purchaseM->ipmaspk }}" name="company_id" required>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{  $purchaseM->company_id == $company->id ? 'selected' : '' }}>
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
</script>

@endsection
