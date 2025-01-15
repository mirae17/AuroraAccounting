@extends('layouts.template')

@section('content')
<br>
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-5"
        style="max-width: 1200px; width: 100%; border-radius: 15px; background-color: #f9f9f9;">
        <!-- Company Information -->
        @if(isset($companyMaintenance))
            @if($companyMaintenance instanceof \Illuminate\Database\Eloquent\Collection)
                @foreach($companyMaintenance as $maintenance)
                    <div class="text-center mb-5">
                        <img id="company-logo" src="{{ asset('storage/' . $maintenance->iCompMainLogo) }}" alt="Company Logo"
                            class="mb-3" style="max-height: 100px;">
                        <h3 id="company-name" class="mb-1">{{ $maintenance->company->description }}</h3>
                        <p id="company-address" class="mb-1 text-muted">{{ $maintenance->iCompMainAddress }}</p>
                        <p id="company-contact" class="text-muted">
                            {{ $maintenance->iCompMainPhoneNo }} | {{ $maintenance->iCompMainEmail }}
                        </p>
                    </div>
                @endforeach
            @else
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
        @endif


        <!-- Details Section -->
        <div class="row mb-5">
            <!-- Customer Details -->
            <div class="col-md-6">
                <h5 class="text-primary mb-3">Bill To:</h5>
                <div id="customer-details">
                    <p><strong>Attention:</strong> {{ $quotation->customer->cCustDName }}</p>
                    <p><strong>Company:</strong> {{ $quotation->customer->cCustDCompName }}</p>
                    <p><strong>Address:</strong> {{ $quotation->customer->cCustDAddress }}</p>
                    <p><strong>Phone:</strong> {{ $quotation->customer->cCustDCompOfficeNo }}</p>
                    <p><strong>Email:</strong> {{ $quotation->customer->cCustDCompEmail }}</p>
                </div>
            </div>
            <!-- Quotation Details -->
            <div class="col-md-6 text-end">
                <h5 class="text-primary mb-3">Quotation Details:</h5>
                <p><strong>Quotation No:</strong> {{ $quotation->iQuoNo }}</p>
                <p><strong>Date:</strong> {{ $quotation->dQuodate }}</p>
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
                    @foreach($quotation->items as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->cQuoItemProductCode }}</td>
                            <td>{{ $item->cQuoItemDescription }}</td>
                            <td class="text-center">{{ $item->iQuoItemQuantity }}</td>
                            <td class="text-end">{{ number_format($item->yQuoItemPriceUnit, 2) }}</td>
                            <td class="text-end">{{ number_format($item->yQuoItemTotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-end"><strong>Subtotal:</strong></td>
                        <td class="text-end">{{ number_format($quotation->yQuoSubtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-end"><strong>Discount:</strong></td>
                        <td class="text-end">{{ $quotation->iQuoDiscount }}%</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-end"><strong>Tax:</strong></td>
                        <td class="text-end">{{ $quotation->iQuoTax }}%</td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-end"><strong>Shipping:</strong></td>
                        <td class="text-end">{{ number_format($quotation->iQuoShipping, 2) }}</td>
                    </tr>
                    <tr class="table-secondary">
                        <td colspan="5" class="text-end"><strong>Total:</strong></td>
                        <td class="text-end"><strong>{{ number_format($quotation->yQuoTotalPayment, 2) }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- Buttons -->
        <div class="text-center">
            <!-- Signature Option -->
            <form action="{{ route('quotations.pdf', ['quotation' => $quotation->iQuoPk]) }}" method="GET"
                class="d-inline">
                <label class="me-2"><strong>Include Signature:</strong></label>
                <select name="signature" class="form-select d-inline w-auto">
                    <option value="with">With Signature</option>
                    <option value="without">Without Signature</option>
                </select>
                <button type="submit" class="btn btn-primary ms-2">Save as PDF</button>
            </form>

            <a href="{{ route('quotations.index') }}" class="btn btn-secondary ms-2">Back</a>
        </div>
    </div>
</div>
<br>
@endsection