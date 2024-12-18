@extends('layouts.template')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Edit Receipt</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('receipt.update', $receipt->iRecptPk) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- General Details -->
                        <fieldset class="border p-3 mb-4">
                            <legend class="float-none w-auto px-2">Receipt Details</legend>

                            <div class="row g-3">
                                <!-- Receipt Number -->
                                <div class="col-md-6">
                                    <label for="iRecptNo" class="form-label">Receipt Number</label>
                                    <input type="text" id="iRecptNo" name="iRecptNo" class="form-control" 
                                        value="{{ $receipt->iRecptNo }}" readonly>
                                </div>

                                <!-- Receipt Date -->
                                <div class="col-md-6">
                                    <label for="dRecptdate" class="form-label">Receipt Date</label>
                                    <input type="date" id="dRecptdate" name="dRecptdate" class="form-control" 
                                        value="{{ $receipt->dRecptdate }}" required>
                                </div>

                                <!-- Customer Details -->
                                <div class="col-md-12">
                                    <label for="iRecptCustDfk" class="form-label">Customer</label>
                                    <select id="iRecptCustDfk" name="iRecptCustDfk" class="form-select" required>
                                        <option value="">-- Select Customer --</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->iCustDPk }}" 
                                                {{ $receipt->iRecptCustDfk == $customer->iCustDPk ? 'selected' : '' }}>
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
                                @foreach ($receipt->items as $index => $item)
                                    <div class="row g-3 mb-3 item-row" data-index="{{ $index }}">
                                        <div class="col-md-3">
                                            <input type="text" name="items[{{ $index }}][cRecptItemProductCode]" 
                                                class="form-control" value="{{ $item->cRecptItemProductCode }}" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="items[{{ $index }}][cRecptItemDescription]" 
                                                class="form-control" value="{{ $item->cRecptItemDescription }}" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" name="items[{{ $index }}][iRecptItemQuantity]" 
                                                class="form-control quantity" value="{{ $item->iRecptItemQuantity }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" name="items[{{ $index }}][yRecptItemPriceUnit]" 
                                                class="form-control price" value="{{ $item->yRecptItemPriceUnit }}" required>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" name="items[{{ $index }}][yRecptItemTotal]" 
                                                class="form-control total" value="{{ $item->yRecptItemTotal }}" readonly>
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
                                    <label for="yRecptSubtotal" class="form-label">Subtotal</label>
                                    <input type="number" id="yRecptSubtotal" name="yRecptSubtotal" class="form-control" 
                                        value="{{ $receipt->yRecptSubtotal }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="iRecptDiscount" class="form-label">Discount (%)</label>
                                    <input type="number" id="iRecptDiscount" name="iRecptDiscount" class="form-control" 
                                        value="{{ $receipt->iRecptDiscount }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="iRecptTax" class="form-label">Tax (%)</label>
                                    <input type="number" id="iRecptTax" name="iRecptTax" class="form-control" 
                                        value="{{ $receipt->iRecptTax }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="iRecptShipping" class="form-label">Shipping</label>
                                    <input type="number" id="iRecptShipping" name="iRecptShipping" class="form-control" 
                                        value="{{ $receipt->iRecptShipping }}">
                                </div>
                                <div class="col-md-12">
                                    <label for="yRecptTotalPayment" class="form-label">Total Amount</label>
                                    <input type="number" id="yRecptTotalPayment" name="yRecptTotalPayment" class="form-control" 
                                        value="{{ $receipt->yRecptTotalPayment }}" readonly>
                                </div>
                            </div>
                        </fieldset>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success me-2">Update Receipt</button>
                            <a href="{{ route('receipt.index') }}" class="btn btn-secondary">Cancel</a>
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
            <input type="text" name="items[${index}][cRecptItemProductCode]" class="form-control" value="${itemCode}" placeholder="Item Code" readonly>
        </div>
        <div class="col-md-3">
            <input type="text" name="items[${index}][cRecptItemDescription]" class="form-control" value="${description}" placeholder="Description" readonly>
        </div>
        <div class="col-md-2">
            <input type="number" name="items[${index}][iRecptItemQuantity]" class="form-control quantity" value="1" placeholder="Quantity" required>
        </div>
        <div class="col-md-2">
            <input type="number" name="items[${index}][yRecptItemPriceUnit]" class="form-control price" value="${pricePerUnit}" placeholder="Price Per Unit" required>
        </div>
        <div class="col-md-2">
            <input type="number" name="items[${index}][yRecptItemTotal]" class="form-control total" value="${pricePerUnit}" placeholder="Total" readonly>
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
        document.getElementById('yRecptSubtotal').value = subtotal.toFixed(2);

        const discount = parseFloat(document.getElementById('iRecptDiscount').value) || 0;
        const tax = parseFloat(document.getElementById('iRecptTax').value) || 0;
        const shipping = parseFloat(document.getElementById('iRecptShipping').value) || 0;

        const totalPayment = subtotal - (subtotal * discount / 100) + (subtotal * tax / 100) + shipping;
        document.getElementById('yRecptTotalPayment').value = totalPayment.toFixed(2);
    }

    // Recalculate totals on input change
    itemContainer.addEventListener('input', function (e) {
        if (e.target.classList.contains('quantity') || e.target.classList.contains('price')) {
            calculateTotals();
        }
    });

    // Recalculate totals when discount, tax, or shipping fields change
    document.getElementById('iRecptDiscount').addEventListener('input', calculateTotals);
    document.getElementById('iRecptTax').addEventListener('input', calculateTotals);
    document.getElementById('iRecptShipping').addEventListener('input', calculateTotals);

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