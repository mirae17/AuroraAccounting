@extends('layouts.template')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4" style="max-width: 600px; width: 100%; border-radius: 15px;">
    <h2  class="text-center mb-4">Edit Sale</h2>

    <form action="{{ route('expensesM.update', $expenseM->iexmaspk) }}" method="POST">
        @csrf
        @method('PUT')


        <!-- Date -->
        <div class="form-group mb-3">
                <label for="dexmasdate" class="form-label">Date</label>
                <input type="date" class="form-control" id="dexmasdate" value="{{  $expenseM->dexmasdate }}"  name="dexmasdate" required>
            </div>

              <!-- Invoice Reference -->
              <div class="form-group mb-3">
                <label for="iexmasinvoiceref" class="form-label">Invoice Reference</label>
                <input type="text" class="form-control" id="iexmasinvoiceref" value="{{  $expenseM->iexmasinvoiceref }}"  name="iexmasinvoiceref" required>
            </div>

             <!-- Description -->
            <div class="form-group mb-3">
                <label for="cexmasExpfk" >Description</label>
                <select id="cexmasExpfk" name="cexmasExpfk" class="form-control" required>
                    @foreach($expenses as $desc)
                        <option value="{{ $desc->iExpPk}}">{{ $desc->cExpDesc == $desc->iExpPk ? 'selected' : ''  }}{{ $desc->cExpDesc }}</option>
                    @endforeach
                </select>
            </div>
            
            

            <!-- Total Payment -->
            <div class="form-group mb-3">
                <label for="yexmaspayment" class="form-label">Total Payment</label>
                <input type="number" step="0.01" class="form-control" id="yexmaspayment" value="{{  $expenseM->yexmaspayment }}"  name="yexmaspayment" required>
            </div>
           
            <!-- Payment Method -->
            <div class="form-group mb-3">
                <label for="iexmasPymtdfk" >Payment Method</label>
                <select id="iexmasPymtdfk" name="iexmasPymtdfk" class="form-control" required>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->iPymtdPk}}">{{ $method->cPymtdDesc== $method->iPymtdPk ? 'selected' : '' }}{{ $method->cPymtdDesc }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Notes-->
            <div class="form-group mb-3">
                <label for="cexmasnotes" class="form-label">Notes</label>
                <input type="text" class="form-control" id="cexmasnotes" value="{{  $expenseM->cexmasnotes }}"  name="cexmasnotes" required>
            </div>

            <div class="form-group mb-3">
            @if(Auth::user()->role === 'system admin')
                <label for="company_id{{  $expenseM->iexmaspk}}" class="form-label">Company</label>
            
                    <select class="form-control" id="company_id{{  $expenseM->iexmaspk}}" name="company_id" required>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{  $expenseM->company_id == $company->id ? 'selected' : '' }}>
                                {{ $company->description }}
                            </option>
                        @endforeach
                    </select>
                @else
                    <input type="hidden" name="company_id" value="{{ Auth::user()->company_id }}">
                @endif
         </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Update Expenses</button>
            <a href="{{ route('expensesM.index') }}" class="btn btn-secondary">Back</a>
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
