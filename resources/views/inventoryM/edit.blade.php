@extends('layouts.template')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4" style="max-width: 600px; width: 100%; border-radius: 15px;">
        <h2 class="text-center mb-4">Edit Inventory Master</h2>

        <form action="{{ route('inventoryM.update', $inventoryM->iInvmasPk) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="cInvmasType" class="form-label">Inventory Type</label>
                <select id="cInvmasType" name="cInvmasType" class="form-control" required>
                        <option value="Raw Material" {{ old('cInvmasType', $inv->cInvmasType ?? '') == 'Raw Material' ? 'selected' : '' }}>Raw Material</option>
                        <option value="Stock" {{ old('cInvmasType', $inv->cInvmasType ?? '') == 'Stock' ? 'selected' : '' }}>
                            Stock
                
                </select>
            </div>

            @if(Auth::user()->role === 'system admin')
                <div class="form-group mb-3">
                    <label for="cInvmasCompfk" class="form-label">Company</label>
                    <select class="form-control" id="cInvmasCompfk" name="cInvmasCompfk" required>
                        <option value="">Select Company</option>
                        @foreach($companies as $company)
                        <option value="{{ $company->id }}" 
                            data-payment-methods='{{ json_encode($company->payments) }}'
                            data-suppliers='{{ json_encode($company->suppliers) }}'
                            data-employees='{{ json_encode($company->employees) }}'
                            data-inventories='{{ json_encode($company->inventories) }}'
                            {{ $inventoryM->cInvmasCompfk == $company->id ? 'selected' : '' }}>
                            {{ $company->description }}
                        </option>
                        @endforeach
                    </select>
                </div>
            @else
                <input type="hidden" name="cInvmasCompfk" value="{{ Auth::user()->cInvmasCompfk }}">
            @endif

            <!-- Date -->
            <div class="form-group mb-3">
                <label for="dInvmasDate" class="form-label">Date</label>
                <input type="date" class="form-control" id="dInvmasDate" name="dInvmasDate"
                    value="{{ $inventoryM->dInvmasDate }}" required>
            </div>

            <!-- Code -->
            <div class="form-group mb-3">
                <label for="cInvmasInvCodefk" class="form-label">Product/Service Code & Name</label>
                <select id="cInvmasInvCodefk" name="cInvmasInvCodefk" class="form-control" required>
                    @foreach($inventories as $inv)
                        <option value="{{ $inv->iInvPK }}" {{ $inventoryM->cInvmasInvCodefk == $inv->iInvPK ? 'selected' : '' }}>
                            {{ $inv->cInvCode }}-{{ $inv->cInvName}}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- supplier-->
            <div class="form-group mb-3">
                <label for="cInvmasSuppfk" class="form-label">Supplier</label>
                <select id="cInvmasSuppfk" name="cInvmasSuppfk" class="form-control" required>
                    @foreach($suppliers as $supp)
                        <option value="{{ $supp->iSuppPk }}" {{ $inventoryM->cInvmasSuppfk == $supp->iSuppPk ? 'selected' : '' }}>
                            {{ $supp->iSuppCode }} - {{ $supp->iSuppDesc}}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- quantity in -->
            <div class="form-group mb-3">
                <label for="iInvmasQuanIn" class="form-label">Quantity(IN)</label>
                <input type="number" step="0.01" class="form-control" id="iInvmasQuanIn" step="1"
                    value="{{ $inventoryM->iInvmasQuanIn }}" name="iInvmasQuanIn" required>
            </div>

            <!-- quantity out -->
            <div class="form-group mb-3">
                <label for="iInvmasQuanOut" class="form-label">Quantity(OUT)</label>
                <input type="number" step="0.01" class="form-control" id="iInvmasQuanOut" step="1"
                    value="{{ $inventoryM->iInvmasQuanOut }}" name="iInvmasQuanOut" required>
            </div>

            <!-- Deposit -->
            <div class="form-group mb-3">
                <label for="yInvmasDeposit" class="form-label">Deposit Purchase(RM)</label>
                <input type="number" step="0.01" class="form-control" id="yInvmasDeposit"
                    value="{{ $inventoryM->yInvmasDeposit }}" step="0.01" name="yInvmasDeposit" required>
            </div>

            <!-- Total Payment -->
            <div class="form-group mb-3">
                <label for="yInvmasPayment" class="form-label">Total Payment</label>
                <input type="number" step="0.01" class="form-control" id="yInvmasPayment"
                    value="{{ $inventoryM->yInvmasPayment }}" step="0.01" name="yInvmasPayment" required>
            </div>

            <!-- Payment Method -->
            <div class="form-group mb-3">
                <label for="cInvmasPymtdfk" class="form-label">Payment Method</label>
                <select id="cInvmasPymtdfk" name="cInvmasPymtdfk" class="form-control" required>
                    <option value="">Select Payment Method</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->iPymtdPk }}" {{ $inventoryM->cInvmasPymtdfk == $method->iPymtdPk ? 'selected' : '' }}>
                            {{ $method->cPymtdDesc }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Invoice Reference -->
            <div class="form-group mb-3">
                <label for="cInvmasInvoice" class="form-label">Invoice Reference</label>
                <input type="text" class="form-control" id="cInvmasInvoice" maxlength="50"
                    value="{{ $inventoryM->cInvmasInvoice }}" name="cInvmasInvoice" required>
            </div>

            <!-- Staff In Charge -->
            <div class="form-group mb-3">
                <label for="cInvmasEmpfk" class="form-label">Staff In Charge</label>
                <select id="cInvmasEmpfk" name="cInvmasEmpfk" class="form-control" required>
                    <option value="">Select Staff In Charge</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->iEmpmasPk}}" {{$inventoryM->cInvmasEmpfk == $emp->iEmpmasPk ? 'selected' : '' }}>
                            {{ $emp->cEmpName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Debtor -->
            <div class="form-group mb-3">
                <label for="cInvmasCreditorfk" class="form-label">Creditor</label>
                <input type="text" class="form-control" id="cInvmasCreditorfk" maxlength="50"
                    value="{{ $inventoryM->cInvmasCreditorfk }}" name="cInvmasCreditorfk" required>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary me-2">Save Inventory</button>
                <a href="{{ route('inventoryM.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
</div>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<!-- JavaScript to auto-detect Cara Jualan -->
<script>
    document.getElementById('cInvmasCompfk')?.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];

        // Parse data from the selected option
        const paymentMethods = JSON.parse(selectedOption.getAttribute('data-payment-methods') || '[]');
        const suppliers = JSON.parse(selectedOption.getAttribute('data-suppliers') || '[]');
        const employees = JSON.parse(selectedOption.getAttribute('data-employees') || '[]');
        const inventories = JSON.parse(selectedOption.getAttribute('data-inventories') || '[]');

        // Populate Payment Methods
        const paymentMethodSelect = document.getElementById('cInvmasPymtdfk');
        paymentMethodSelect.innerHTML = '<option value="">Select Payment Method</option>';
        paymentMethods.forEach(method => {
            paymentMethodSelect.innerHTML += `<option value="${method.iPymtdPk}">${method.cPymtdDesc}</option>`;
        });

        // Populate Suppliers
        const suppSelect = document.getElementById('cInvmasSuppfk');
        suppSelect.innerHTML = '<option value="">Select Supplier</option>';
        suppliers.forEach(supp => {
            suppSelect.innerHTML += `<option value="${supp.iSuppPk}">${supp.iSuppDesc}</option>`;
        });

        // Populate Employees
        const empSelect = document.getElementById('cInvmasEmpfk');
        empSelect.innerHTML = '<option value="">Select Staff In Charge</option>';
        employees.forEach(emp => {
            empSelect.innerHTML += `<option value="${emp.iEmpmasPk}">${emp.cEmpName}</option>`;
        });

        const inventoriesSelect = document.getElementById('cInvmasInvCodefk');
        inventoriesSelect.innerHTML = '<option value="">Select Product/Service Code & Name</option>';
        inventories.forEach(inv => {
            inventoriesSelect.innerHTML += `<option value="${inv.iInvPK}">${inv.cInvCode}-${inv.cInvName}</option>`;
        });
    });
</script>

@endsection