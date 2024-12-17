@extends('layouts.template')

@section('content')
<br>
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4" style="max-width: 2500px; width: 100%; border-radius: 15px;">
        <div class="text-center mb-4">
            <!-- Display Company Information -->
            @if(isset($companyMaintenance))
                <img id="company-logo" src="{{ asset('storage/' . $companyMaintenance->iCompMainLogo) }}" alt="Company Logo"
                    class="mb-3" style="max-height: 100px;">
                <h2 id="company-name">{{ $companyMaintenance->company->description }}</h2>
                <p id="company-address">{{ $companyMaintenance->iCompMainAddress }}</p>
                <p id="company-contact">{{ $companyMaintenance->iCompMainPhoneNo }} |
                    {{ $companyMaintenance->iCompMainEmail }}
                </p>
            @endif
        </div>

        <div class="row mb-4">
            <!-- Left: Customer Details -->
            <div class="col-md-6">
                <h5>Bill To:</h5>
                <div id="customer-details" class="mt-3">
                    <p><strong>Attention:</strong> {{ $quotation->customer->cCustDName }}</p>
                    <p><strong>Company:</strong> {{ $quotation->customer->cCustDCompName }}</p>
                    <p><strong>Address:</strong> {{ $quotation->customer->cCustDAddress }}</p>
                    <p><strong>Phone:</strong> {{ $quotation->customer->cCustDCompOfficeNo }}</p>
                    <p><strong>Email:</strong> {{ $quotation->customer->cCustDCompEmail }}</p>
                </div>
            </div>

            <!-- Right: Quotation Details -->
            <div class="col-md-6 text-end">
                <h5>Quotation Details</h5>
                <p><strong>Quotation No:</strong> {{ $quotation->iQuoNo }}</p>
                <p><strong>Date:</strong> {{ $quotation->dQuodate }}</p>
            </div>
        </div>

        <!-- Items Table -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
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
                    @foreach($quotation->items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->cQuoItemProductCode }}</td>
                            <td>{{ $item->cQuoItemDescription }}</td>
                            <td>{{ $item->iQuoItemQuantity }}</td>
                            <td>{{ number_format($item->yQuoItemPriceUnit, 2) }}</td>
                            <td>{{ number_format($item->yQuoItemTotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-end"><strong>Subtotal:</strong></td>
                        <td>{{ number_format($quotation->yQuoSubtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-end"><strong>Discount:</strong></td>
                        <td>{{ $quotation->iQuoDiscount }}%</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-end"><strong>Tax:</strong></td>
                        <td>{{ $quotation->iQuoTax }}%</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-end"><strong>Shipping:</strong></td>
                        <td>{{ number_format($quotation->iQuoShipping, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-end"><strong>Total:</strong></td>
                        <td>{{ number_format($quotation->yQuoTotalPayment, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>


    </div>
</div>
<br>

@endsection