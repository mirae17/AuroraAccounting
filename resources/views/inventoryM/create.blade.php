@extends('layouts.template')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4" style="max-width: 600px; width: 100%; border-radius: 15px;">

        <h2 class="text-center mb-4">Add New Inventory</h2>

        <form action="{{ route('inventoryM.store') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label for="cInvmasType" class="form-label">Inventory Type</label>
                <select id="cInvmasType" name="cInvmasType" class="form-select" required>

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
                            <option value="{{ $company->id }}" data-payment-methods="{{ json_encode($company->payments) }}"
                                data-suppliers="{{ json_encode($company->suppliers) }}"
                                data-employees="{{ json_encode($company->employees) }}"
                                data-inventory="{{ json_encode($company->inventories) }}">
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
                <label for="dInvmasDate" class="form-label">Date</label>
                <input type="date" class="form-control" id="dInvmasDate" name="dInvmasDate" required>
            </div>

            <!-- Code -->
            <div class="form-group mb-3">
                <label for="cInvmasInvCodefk" class="form-label">Product/Service Code & Name</label>
                <select id="cInvmasInvCodefk" name="cInvmasInvCodefk" class="form-select" required>
                    <option value="">Select Product/Service Code & Name</option>
                    @foreach($inventories as $inv)
                        <option value="{{ $inv->iInvPK }}" data-price="{{ $inv->yInvPrice }}">
                            {{ $inv->cInvCode }} - {{ $inv->cInvName }}
                        </option>
                    @endforeach
                </select>
            </div>


            <!-- supplier-->
            <div class="form-group mb-3">
                <label for="cInvmasSuppfk" class="form-label">Supplier</label>
                <select id="cInvmasSuppfk" name="cInvmasSuppfk" class="form-select" required>
                    <option value="">Select Supplier</option>
                    @foreach($suppliers as $supp)
                        <option value="{{ $supp->iSuppPk }}">{{ $supp->iSuppCode }} - {{ $supp->iSuppDesc }} </option>
                    @endforeach
                </select>
            </div>

            <!-- quantity in -->
            <div class="form-group mb-3">
                <label for="iInvmasQuanIn" class="form-label">Quantity(IN)</label>
                <input type="number" step="0.01" class="form-control" id="iInvmasQuanIn" step="1" name="iInvmasQuanIn"
                    required>
            </div>

            <!-- quantity out -->
            <div class="form-group mb-3">
                <label for="iInvmasQuanOut" class="form-label">Quantity(OUT)</label>
                <input type="number" step="0.01" class="form-control" id="iInvmasQuanOut" step="1" name="iInvmasQuanOut"
                    required>
            </div>

            <div class="form-group mb-3">
                <label for="iInvmasInvPricefk" class="form-label">Price Per Unit</label>
                <input type="number" class="form-control" id="iInvmasInvPricefk" name="iInvmasInvPricefk" step="1"
                    readonly>
            </div>

            <!-- Deposit -->
            <div class="form-group mb-3">
                <label for="yInvmasDeposit" class="form-label">Deposit Purchase(RM)</label>
                <input type="number" step="0.01" class="form-control" id="yInvmasDeposit" step="0.01"
                    name="yInvmasDeposit" required>
            </div>

            <!-- Total Payment -->
            <div class="form-group mb-3">
                <label for="yInvmasPayment" class="form-label">Total Payment</label>
                <input type="text" name="yInvmasPayment" id="yInvmasPayment" class="form-control"
                    placeholder="Total Amount" readonly>
            </div>

            <!-- Payment Method -->
            <div class="form-group mb-3">
                <label for="cInvmasPymtdfk" class="form-label">Payment Method</label>
                <select id="cInvmasPymtdfk" name="cInvmasPymtdfk" class="form-select" required>
                    <option value="">Select Payment Method</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->iPymtdPk}}">{{ $method->cPymtdDesc }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Invoice Reference -->
            <div class="form-group mb-3">
                <label for="cInvmasInvoice" class="form-label">Invoice Reference</label>
                <input type="text" class="form-control" id="cInvmasInvoice" maxlength="50" name="cInvmasInvoice"
                    required>
            </div>

            <!-- Staff In Charge -->
            <div class="form-group mb-3">
                <label for="cInvmasEmpfk" class="form-label">Staff In Charge</label>
                <select id="cInvmasEmpfk" name="cInvmasEmpfk" class="form-select" required>
                    <option value="">Select Staff In Charge</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->iEmpmasPk}}">{{ $emp->cEmpName }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Debtor -->
            <div class="form-group mb-3">
                <label for="cInvmasCreditorfk" class="form-label">Creditor</label>
                <input type="text" class="form-control" id="cInvmasCreditorfk" maxlength="50" name="cInvmasCreditorfk"
                    required>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary me-2">Save Purchase</button>
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
        const inventories = JSON.parse(selectedOption.getAttribute('data-inventory') || '[]');

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


    // Populate price per unit when product/service is selected
    document.getElementById('cInvmasInvCodefk')?.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const pricePerUnit = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        if (pricePerUnit === 0) {
            alert('Price is missing for the selected item.');
        }
        // Update the price per unit field
        document.getElementById('iInvmasInvPricefk').value = pricePerUnit.toFixed(2);

        // Recalculate total payment
        calculateTotalPayment();
    });

    // Recalculate total payment on quantity input change
    document.getElementById('iInvmasQuanIn')?.addEventListener('input', calculateTotalPayment);

    function calculateTotalPayment() {
        const quantity = parseFloat(document.getElementById('iInvmasQuanIn').value) || 0;
        const pricePerUnit = parseFloat(document.getElementById('iInvmasInvPricefk').value) || 0;
        const totalPayment = quantity * pricePerUnit;

        // Update the total payment field
        document.getElementById('yInvmasPayment').value = totalPayment.toFixed(2);
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
    .form-control,
    .form-select {
        width: 100%;
        height: 38px;
        /* Match input field height */
        padding: 0.5rem;
        border-radius: 8px;
        border: 1px solid #ced4da;
        box-sizing: border-box;
        /* Ensures padding doesn’t affect width */
        appearance: none;
        /* Removes default styling on some browsers */
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