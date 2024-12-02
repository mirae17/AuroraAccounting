@extends('layouts.template')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4" style="max-width: 600px; width: 100%; border-radius: 15px;">
        <h2 class="text-center mb-4">Edit Sale</h2>

        <form action="{{ route('expensesM.update', $expenseM->iexmaspk) }}" method="POST">
            @csrf
            @method('PUT')

          
            @if(Auth::user()->role === 'system admin')
                <div class="form-group mb-3">
                    <label for="company_id" class="form-label">Company</label>
                    <select class="form-control" id="company_id" name="company_id" required>
                        <option value="">Select Company</option>
                        @foreach($companies as $company)
                            <option 
                                value="{{ $company->id }}" 
                                data-payment-methods='@json($company->payments)' 
                                data-expenses='@json($company->expenses)'>
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
                <label for="dexmasdate" class="form-label">Date</label>
                <input type="date" class="form-control" id="dexmasdate" value="{{ $expenseM->dexmasdate }}" name="dexmasdate" required>
            </div>

            <!-- Invoice Reference -->
            <div class="form-group mb-3">
                <label for="iexmasinvoiceref" class="form-label">Invoice Reference</label>
                <input type="text" class="form-control" id="iexmasinvoiceref" value="{{ $expenseM->iexmasinvoiceref }}" name="iexmasinvoiceref" required>
            </div>

          <!-- Expense Dropdown -->
        <div class="form-group mb-3">
            <label for="cexmasExpfk">Expense</label>
            <select id="cexmasExpfk" name="cexmasExpfk" class="form-control" required>
                <option value="">Select Expense</option>
                @foreach($expenseM->company->expenses ?? [] as $expense)
                    <option value="{{ $expense->iExpPk }}" {{ $expenseM->cexmasExpfk == $expense->iExpPk ? 'selected' : '' }}>
                        {{ $expense->cExpDesc }}
                    </option>
                @endforeach
            </select>
        </div>

            <!-- Total Payment -->
            <div class="form-group mb-3">
                <label for="yexmaspayment" class="form-label">Total Payment</label>
                <input type="number" step="0.01" class="form-control" id="yexmaspayment" value="{{ $expenseM->yexmaspayment }}" name="yexmaspayment" required>
            </div>

           <!-- Payment Methods Dropdown -->
        <div class="form-group mb-3">
            <label for="iexmasPymtdfk">Payment Method</label>
            <select id="iexmasPymtdfk" name="iexmasPymtdfk" class="form-control" required>
                <option value="">Select Payment Method</option>
                @foreach($expenseM->company->payments ?? [] as $method)
                    <option value="{{ $method->iPymtdPk }}" {{ $expenseM->iexmasPymtdfk == $method->iPymtdPk ? 'selected' : '' }}>
                        {{ $method->cPymtdDesc }}
                    </option>
                @endforeach
            </select>
        </div>

            <!-- Notes -->
            <div class="form-group mb-3">
                <label for="cexmasnotes" class="form-label">Notes</label>
                <input type="text" class="form-control" id="cexmasnotes" value="{{ $expenseM->cexmasnotes }}" name="cexmasnotes" required>
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
  
   document.getElementById('company_id').addEventListener('change', function () {
    const selectedOption = this.options[this.selectedIndex];
    
    const paymentMethods = JSON.parse(selectedOption.getAttribute('data-payment-methods')) || [];
    const expenses = JSON.parse(selectedOption.getAttribute('data-expenses')) || [];

    // Update Payment Methods Dropdown
    const paymentMethodSelect = document.getElementById('iexmasPymtdfk');
    paymentMethodSelect.innerHTML = '<option value="">Select Payment Method</option>';
    paymentMethods.forEach(method => {
        paymentMethodSelect.innerHTML += `<option value="${method.iPymtdPk}">${method.cPymtdDesc}</option>`;
    });

    // Update Expenses Dropdown
    const expenseSelect = document.getElementById('iexpfk');
    expenseSelect.innerHTML = '<option value="">Select Expense</option>';
    expenses.forEach(expense => {
        expenseSelect.innerHTML += `<option value="${expense.iExpPk}">${expense.cExpDesc}</option>`;
    });
});

</script>


@endsection
