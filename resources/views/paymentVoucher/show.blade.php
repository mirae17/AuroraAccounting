@extends('layouts.template')

@section('content')
<br>
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-5"
        style="max-width: 1200px; width: 100%; border-radius: 15px; background-color: #f9f9f9;">
        <!-- Company Information -->
        @if(isset($companyMaintenance))
            <div class="text-center mb-5">
                <img id="company-logo" src="{{ asset('storage/' . $companyMaintenance->iCompMainLogo) }}" alt="Company Logo"
                    class="mb-3" style="max-height: 100px;">
                <h3 id="company-name" class="mb-1">{{ $companyMaintenance->company->description }}</h3>
                <p id="company-address" class="mb-1 text-muted">{{ $companyMaintenance->iCompMainAddress }}</p>
                <p id="company-contact" class="text-muted">
                    {{ $companyMaintenance->iCompMainPhoneNo }} | {{ $companyMaintenance->iCompMainEmail }}
                </p>
            </div>
        @endif

        <!-- Details Section -->
        <div class="row mb-5">
            <!-- Customer Details -->
            <div class="col-md-6">
                <h5 class="text-primary mb-3">Bill To:</h5>
                <div id="customer-details">
                    <p><strong>Attention:</strong> {{ $purchaseOrder->customer->cCustDName }}</p>
                    <p><strong>Company:</strong> {{ $purchaseOrder->customer->cCustDCompName }}</p>
                    <p><strong>Address:</strong> {{ $purchaseOrder->customer->cCustDAddress }}</p>
                    <p><strong>Phone:</strong> {{ $purchaseOrder->customer->cCustDCompOfficeNo }}</p>
                    <p><strong>Email:</strong> {{ $purchaseOrder->customer->cCustDCompEmail }}</p>
                </div>
            </div>
            <!-- Purchase Order Details -->
            <div class="col-md-6 text-end">
                <h5 class="text-primary mb-3">Purchase Order Details:</h5>
                <p><strong>Purchase Order No:</strong> {{ $purchaseOrder->iPurchOrderNo }}</p>
                <p><strong>Date:</strong> {{ $purchaseOrder->dPurchOrderdate }}</p>
            </div>
        </div>

        <!-- Items Table -->
        <div class="table-responsive mb-5">
            <table class="table table-striped table-bordered">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Price per Unit</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchaseOrder->items as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->cPurchOrderItemProductCode }}</td>
                            <td>{{ $item->cPurchOrderItemDescription }}</td>
                            <td class="text-center">{{ $item->iPurchOrderItemQuantity }}</td>
                            <td class="text-end">{{ number_format($item->yPurchOrderItemPriceUnit, 2) }}</td>
                            <td class="text-end">{{ number_format($item->yPurchOrderItemTotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-end"><strong>Subtotal:</strong></td>
                        <td class="text-end">{{ number_format($purchaseOrder->yPurchOrderSubtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-end"><strong>Discount:</strong></td>
                        <td class="text-end">{{ $purchaseOrder->iPurchOrderDiscount }}%</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-end"><strong>Tax:</strong></td>
                        <td class="text-end">{{ $purchaseOrder->iPurchOrderTax }}%</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-end"><strong>Shipping:</strong></td>
                        <td class="text-end">{{ number_format($purchaseOrder->iPurchOrderShipping, 2) }}</td>
                    </tr>
                    <tr class="table-secondary">
                        <td colspan="5" class="text-end"><strong>Total:</strong></td>
                        <td class="text-end">
                            <strong>{{ number_format($purchaseOrder->yPurchOrderTotalPayment, 2) }}</strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- Buttons -->
        <div class="text-center">
            <!-- Signature Option -->
            <form action="{{ route('purchaseOrder.pdf', ['purchaseOrder' => $purchaseOrder->iPurchOrderPk]) }}"
                method="GET" class="d-inline">
                <label class="me-2"><strong>Include Signature:</strong></label>
                <select name="signature" class="form-select d-inline w-auto">
                    <option value="with">With Signature</option>
                    <option value="without">Without Signature</option>
                </select>
                <button type="submit" class="btn btn-primary ms-2">Save as PDF</button>
            </form>

            <a href="{{ route('purchaseOrder.index') }}" class="btn btn-secondary ms-2">Back</a>
        </div>
    </div>
</div>
<br>
@endsection