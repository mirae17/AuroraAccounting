@extends('layouts.template')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Edit Invoice</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('invoice.update', $invoice->iInvcPk) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- General Details -->
                        <fieldset class="border p-3 mb-4">
                            <legend class="float-none w-auto px-2">Invoice Details</legend>

                            <div class="row g-3">
                                <!-- Invoice Number -->
                                <div class="col-md-6">
                                    <label for="iInvcNo" class="form-label">Invoice Number</label>
                                    <input type="text" id="iInvcNo" name="iInvcNo" class="form-control" 
                                        value="{{ $invoice->iInvcNo }}" readonly>
                                </div>

                                <!-- Invoice Date -->
                                <div class="col-md-6">
                                    <label for="dInvcdate" class="form-label">Invoice Date</label>
                                    <input type="date" id="dInvcdate" name="dInvcdate" class="form-control" 
                                        value="{{ $invoice->dInvcdate }}" required>
                                </div>

                                <!-- Customer Details -->
                                <div class="col-md-12">
                                    <label for="iInvcCustDfk" class="form-label">Customer</label>
                                    <select id="iInvcCustDfk" name="iInvcCustDfk" class="form-select" required>
                                        <option value="">-- Select Customer --</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->iCustDPk }}" 
                                                {{ $invoice->iInvcCustDfk == $customer->iCustDPk ? 'selected' : '' }}>
                                                {{ $customer->cCustDName }} ({{ $customer->cCustDCompName }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </fieldset>

                        <!-- Items Section -->
                        <fieldset class="border p-3 mb-4">
                            <legend class="float-none w-auto px-2">Item Details</legend>
                            <div id="item-container">
                                @foreach ($invoice->items as $index => $item)
                                    <div class="row g-3 mb-3 item-row" data-index="{{ $index }}">
                                        <div class="col-md-3">
                                            <input type="text" name="items[{{ $index }}][cInvcItemProductCode]" 
                                                class="form-control" value="{{ $item->cInvcItemProductCode }}" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="items[{{ $index }}][cInvcItemDescription]" 
                                                class="form-control" value="{{ $item->cInvcItemDescription }}" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" name="items[{{ $index }}][iInvcItemQuantity]" 
                                                class="form-control quantity" value="{{ $item->iInvcItemQuantity }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" name="items[{{ $index }}][yInvcItemPriceUnit]" 
                                                class="form-control price" value="{{ $item->yInvcItemPriceUnit }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" name="items[{{ $index }}][yInvcItemTotal]" 
                                                class="form-control total" value="{{ $item->yInvcItemTotal }}" readonly>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#itemModal">
                                Add Item
                            </button>
                        </fieldset>

                        <!-- Financial Details -->
                        <fieldset class="border p-3 mb-4">
                            <legend class="float-none w-auto px-2">Financial Details</legend>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="yInvcSubtotal" class="form-label">Subtotal</label>
                                    <input type="number" id="yInvcSubtotal" name="yInvcSubtotal" class="form-control" 
                                        value="{{ $invoice->yInvcSubtotal }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="iInvcDiscount" class="form-label">Discount (%)</label>
                                    <input type="number" id="iInvcDiscount" name="iInvcDiscount" class="form-control" 
                                        value="{{ $invoice->iInvcDiscount }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="iInvcTax" class="form-label">Tax (%)</label>
                                    <input type="number" id="iInvcTax" name="iInvcTax" class="form-control" 
                                        value="{{ $invoice->iInvcTax }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="iInvcShipping" class="form-label">Shipping</label>
                                    <input type="number" id="iInvcShipping" name="iInvcShipping" class="form-control" 
                                        value="{{ $invoice->iInvcShipping }}">
                                </div>
                                <div class="col-md-12">
                                    <label for="yInvcTotalPayment" class="form-label">Total Amount</label>
                                    <input type="number" id="yInvcTotalPayment" name="yInvcTotalPayment" class="form-control" 
                                        value="{{ $invoice->yInvcTotalPayment }}" readonly>
                                </div>
                            </div>
                        </fieldset>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success me-2">Update Invoice</button>
                            <a href="{{ route('invoice.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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

<!-- Modal for Selecting Items -->
<div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itemModalLabel">Select Product or Inventory</h5>

            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="itemTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="products-tab" data-bs-toggle="tab"
                            data-bs-target="#products" type="button" role="tab" aria-controls="products"
                            aria-selected="true">
                            Products
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory"
                            type="button" role="tab" aria-controls="inventory" aria-selected="false">
                            Inventory
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="itemTabContent">
                    <!-- Products Tab -->
                    <div class="tab-pane fade show active" id="products" role="tabpanel" aria-labelledby="products-tab">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Code</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $product->cProCode }}</td>
                                        <td>{{ $product->cProName }}</td>
                                        <td>{{ $product->yProPrice }}</td>
                                        <td>
                                            <button type="button" class="btn btn-success btn-sm"
                                                onclick="addItemToTable('{{ $product->cProCode }}', '{{ $product->cProName }}', {{ $product->yProPrice }})">
                                                Add
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Inventory Tab -->
                    <div class="tab-pane fade" id="inventory" role="tabpanel" aria-labelledby="inventory-tab">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item Code</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inventory as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->cInvCode}}</td>
                                        <td>{{ $item->cInvName }}</td>
                                        <td>{{ $item->yInvPrice }}</td>
                                        <td>
                                            <button type="button" class="btn btn-success btn-sm"
                                                onclick="addItemToTable('{{ $item->cInvCode }}', '{{ $item->cInvName }}', {{ $item->yInvPrice }})">
                                                Add
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const itemContainer = document.getElementById('item-container');

    // Add Item to the Items Container
    window.addItemToTable = function (itemCode, description, pricePerUnit) {
        const index = itemContainer.querySelectorAll('.item-row').length;

        // Create a new row
        const row = document.createElement('div');
        row.classList.add('row', 'mb-3', 'item-row');
        row.dataset.index = index;

        row.innerHTML = `
        <div class="col-md-3">
            <input type="text" name="items[${index}][cInvcItemProductCode]" class="form-control" value="${itemCode}" placeholder="Item Code" readonly>
        </div>
        <div class="col-md-3">
            <input type="text" name="items[${index}][cInvcItemDescription]" class="form-control" value="${description}" placeholder="Description" readonly>
        </div>
        <div class="col-md-2">
            <input type="number" name="items[${index}][iInvcItemQuantity]" class="form-control quantity" value="1" placeholder="Quantity" required>
        </div>
        <div class="col-md-2">
            <input type="number" name="items[${index}][yInvcItemPriceUnit]" class="form-control price" value="${pricePerUnit}" placeholder="Price Per Unit" required>
        </div>
        <div class="col-md-2">
            <input type="number" name="items[${index}][yInvcItemTotal]" class="form-control total" value="${pricePerUnit}" placeholder="Total" readonly>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
        </div>
    `;

        itemContainer.appendChild(row);
        calculateTotals();
    };

    // Calculate Totals
    function calculateTotals() {
        let subtotal = 0;

        itemContainer.querySelectorAll('.item-row').forEach(row => {
            const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
            const price = parseFloat(row.querySelector('.price').value) || 0;
            const totalField = row.querySelector('.total');
            const total = quantity * price;

            totalField.value = total.toFixed(2);
            subtotal += total;
        });

        // Update subtotal and total payment
        document.getElementById('yInvcSubtotal').value = subtotal.toFixed(2);

        const discount = parseFloat(document.getElementById('iInvcDiscount').value) || 0;
        const tax = parseFloat(document.getElementById('iInvcTax').value) || 0;
        const shipping = parseFloat(document.getElementById('iInvcShipping').value) || 0;

        const totalPayment = subtotal - (subtotal * discount / 100) + (subtotal * tax / 100) + shipping;
        document.getElementById('yInvcTotalPayment').value = totalPayment.toFixed(2);
    }

    // Recalculate totals on input change
    itemContainer.addEventListener('input', function (e) {
        if (e.target.classList.contains('quantity') || e.target.classList.contains('price')) {
            calculateTotals();
        }
    });

    // Recalculate totals when discount, tax, or shipping fields change
    document.getElementById('iInvcDiscount').addEventListener('input', calculateTotals);
    document.getElementById('iInvcTax').addEventListener('input', calculateTotals);
    document.getElementById('iInvcShipping').addEventListener('input', calculateTotals);

    // Remove item row
    itemContainer.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('.item-row').remove();
            calculateTotals();
        }
    });
});

</script>
@endsection