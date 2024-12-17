@extends('layouts.template')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Edit Quotation</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('quotations.update', $quotation->iQuoPk) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Quotation Number -->
                        <div class="form-group">
                            <label for="iQuoNo">Quotation Number</label>
                            <input type="text" id="iQuoNo" name="iQuoNo" class="form-control"
                                value="{{ $quotation->iQuoNo }}" readonly>
                        </div>

                        <!-- Quotation Date -->
                        <div class="form-group">
                            <label for="dQuodate">Quotation Date</label>
                            <input type="date" id="dQuodate" name="dQuodate" class="form-control"
                                value="{{ $quotation->dQuodate }}" required>
                        </div>

                        <!-- Customer Details -->
                        <div class="form-group">
                            <label for="iQuoCustDfk">Customer</label>
                            <select id="iQuoCustDfk" name="iQuoCustDfk" class="form-control" required>
                                <option value="">-- Select Customer --</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->iCustDPk}}" {{ $quotation->iQuoCustDfk == $customer->iCustDPk ? 'selected' : '' }}>
                                        {{ $customer->cCustDName }} ({{ $customer->cCustDCompName }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Item Details -->
                        <div id="item-container">
                            <label>Items</label>
                            @foreach ($quotation->items as $index => $item)
                                <div class="row mb-3 item-row" data-index="{{ $index }}">
                                    <div class="col-md-3">
                                        <input type="text" name="items[{{ $index }}][cQuoItemProductCode]"
                                            class="form-control" value="{{ $item->cQuoItemProductCode }}"
                                            placeholder="Item Code" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="items[{{ $index }}][cQuoItemDescription]"
                                            class="form-control" value="{{ $item->cQuoItemDescription }}"
                                            placeholder="Description" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="items[{{ $index }}][iQuoItemQuantity]"
                                            class="form-control quantity" value="{{ $item->iQuoItemQuantity }}"
                                            placeholder="Quantity" required>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="items[{{ $index }}][yQuoItemPriceUnit]"
                                            class="form-control price" value="{{ $item->yQuoItemPriceUnit }}"
                                            placeholder="Price Per Unit" required>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="items[{{ $index }}][yQuoItemTotal]"
                                            class="form-control total" value="{{ $item->yQuoItemTotal }}"
                                            placeholder="Total" readonly>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" data-toggle="modal" data-target="#itemModal"
                            class="btn btn-secondary mb-3">Add Item</button>

                        <!-- Totals -->
                        <div class="form-group">
                            <label for="yQuoSubtotal">Subtotal</label>
                            <input type="number" id="yQuoSubtotal" name="yQuoSubtotal" class="form-control"
                                value="{{ $quotation->yQuoSubtotal }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="iQuoDiscount">Discount (%)</label>
                            <input type="number" id="iQuoDiscount" name="iQuoDiscount" class="form-control"
                                value="{{ $quotation->iQuoDiscount }}">
                        </div>

                        <div class="form-group">
                            <label for="iQuoTax">Tax (%)</label>
                            <input type="number" id="iQuoTax" name="iQuoTax" class="form-control"
                                value="{{ $quotation->iQuoTax }}">
                        </div>
                        <div class="form-group">
                            <label for="iQuoTax">Shipping</label>
                            <input type="number" id="iQuoShipping" name="iQuoShipping" class="form-control"
                                value="{{ $quotation->iQuoShipping }}">
                        </div>

                        <div class="form-group">
                            <label for="yQuoTotalPayment">Total Amount</label>
                            <input type="number" id="yQuoTotalPayment" name="yQuoTotalPayment" class="form-control"
                                value="{{ $quotation->yQuoTotalPayment }}" readonly>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Quotation</button>
                        <a href="{{ route('quotations.index') }}" class="btn btn-secondary">Cancel</a>
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
                <input type="text" name="items[${index}][cQuoItemProductCode]" class="form-control" value="${itemCode}" placeholder="Item Code" readonly>
            </div>
            <div class="col-md-3">
                <input type="text" name="items[${index}][cQuoItemDescription]" class="form-control" value="${description}" placeholder="Description" readonly>
            </div>
            <div class="col-md-2">
                <input type="number" name="items[${index}][iQuoItemQuantity]" class="form-control quantity" value="1" placeholder="Quantity" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="items[${index}][yQuoItemPriceUnit]" class="form-control price" value="${pricePerUnit}" placeholder="Price Per Unit" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="items[${index}][yQuoItemTotal]" class="form-control total" value="${pricePerUnit}" placeholder="Total" readonly>
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
            document.getElementById('yQuoSubtotal').value = subtotal.toFixed(2);

            const discount = parseFloat(document.getElementById('iQuoDiscount').value) || 0;
            const tax = parseFloat(document.getElementById('iQuoTax').value) || 0;

            const totalPayment = subtotal - (subtotal * discount / 100) + (subtotal * tax / 100);
            document.getElementById('yQuoTotalPayment').value = totalPayment.toFixed(2);
        }

        // Recalculate totals on input change
        itemContainer.addEventListener('input', function (e) {
            if (e.target.classList.contains('quantity') || e.target.classList.contains('price')) {
                calculateTotals();
            }
        });

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