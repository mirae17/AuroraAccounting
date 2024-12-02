@extends('layouts.template')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4" style="max-width: 600px; width: 100%; border-radius: 15px;">

        <h2 class="text-center mb-4">Add New Expenses</h2>

        <form action="{{ route('expensesM.store') }}" method="POST">
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
                <input type="date" class="form-control" id="dexmasdate" name="dexmasdate" required>
            </div>

              <!-- Invoice Reference -->
              <div class="form-group mb-3">
                <label for="iexmasinvoiceref" class="form-label">Invoice Reference</label>
                <input type="text" class="form-control" id="iexmasinvoiceref" maxlength="50" name="iexmasinvoiceref" required>
            </div>

             <!-- Description -->
            <div class="form-group mb-3">
                <label for="cexmasExpfk" class="form-label">Description</label>
                <select id="cexmasExpfk" name="cexmasExpfk" class="form-select" required>
                <option value="">Select Description</option>
                    @foreach($expenses as $desc)
                        <option value="{{ $desc->iExpPk}}">{{ $desc->cExpDesc }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Total Payment -->
            <div class="form-group mb-3">
                <label for="yexmaspayment" class="form-label">Total Payment</label>
                <input type="number" step="0.01" class="form-control" id="yexmaspayment" name="yexmaspayment" required>
            </div>
           
            <!-- Payment Method -->
            <div class="form-group mb-3">
                <label for="iexmasPymtdfk" class="form-label">Payment Method</label>
                <select id="iexmasPymtdfk" name="iexmasPymtdfk" class="form-select" required>
                <option value="">Select Payment Method</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->iPymtdPk}}">{{ $method->cPymtdDesc }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Notes-->
            <div class="form-group mb-3">
                <label for="cexmasnotes" class="form-label">Notes</label>
                <input type="text" class="form-control" id="cexmasnotes" maxlength="150" name="cexmasnotes" required>
            </div>

    
                
            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary me-2">Save Expenses</button>
                <a href="{{ route('expensesM.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
</div>
<script>
 document.getElementById('company_id')?.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        
        const paymentMethods = JSON.parse(selectedOption.getAttribute('data-payment-methods')) || [];
        const expenses = JSON.parse(selectedOption.getAttribute('data-expenses')) || [];

        const paymentMethodSelect = document.getElementById('iexmasPymtdfk');
        paymentMethodSelect.innerHTML = '<option value="">Select Payment Method</option>';
        paymentMethods.forEach(method => {
            paymentMethodSelect.innerHTML += `<option value="${method.iPymtdPk}">${method.cPymtdDesc}</option>`;
        });

        const descSelect = document.getElementById('cexmasExpfk');
        descSelect.innerHTML = '<option value="">Select Description</option>';
        expenses.forEach(desc => {
            descSelect.innerHTML += `<option value="${desc.iExpPk}">${desc.cExpDesc}</option>`;
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
