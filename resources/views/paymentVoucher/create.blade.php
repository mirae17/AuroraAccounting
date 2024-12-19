@extends('layouts.template')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4" style="max-width: 600px; width: 100%; border-radius: 15px;">
        <h2>Create Payment Voucher</h2>
        <form action="{{ route('paymentVoucher.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="dPymtVchrDate" class="form-label">Date</label>
                <input type="date" class="form-control" id="dPymtVchrDate" name="dPymtVchrDate" required>
            </div>
            <div class="form-group">
                <label>Payment Voucher No:</label>
                <input type="text" name="iPymtVchrNo" class="form-control" value="{{ $voucherNo }}" readonly>
            </div>
            <div class="form-group mb-3">
                <label for="iPymtVchrPymtdfk" class="form-label">Payment Method</label>
                <select id="iPymtVchrPymtdfk" name="iPymtVchrPymtdfk" class="form-select" required>
                    <option value="">Select Payment Method</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->iPymtdPk }}">{{ $method->cPymtdDesc }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="cPymtVchrDesc" class="form-label">Description</label>
                <input type="text" class="form-control" id="cPymtVchrDesc" name="cPymtVchrDesc" required>
            </div>

            <div class="mb-3">
                <label for="cPymtVchrNoAcc" class="form-label">Bank Account Number</label>
                <input type="text" class="form-control" id="cPymtVchrNoAcc" name="cPymtVchrNoAcc" required>
            </div>
            <div class="mb-3">
                <label for="cPymtVchrMethod" class="form-label">Payment Method</label>
                <input type="text" class="form-control" id="cPymtVchrMethod" name="cPymtVchrMethod" required>
            </div>
            <div class="mb-3">
                <label for="cPymtVchrName" class="form-label">Payee Name</label>
                <input type="text" class="form-control" id="cPymtVchrName" name="cPymtVchrName" required>
            </div>
            <div class="mb-3">
                <label for="yPymtVchrTotal" class="form-label">Total Amount</label>
                <input type="number" step="0.01" class="form-control" id="yPymtVchrTotal" name="yPymtVchrTotal"
                    required>
            </div>
            <div class="mb-3">
                <label for="cPymtVchrRefNo" class="form-label">Reference Number</label>
                <input type="text" class="form-control" id="cPymtVchrRefNo" name="cPymtVchrRefNo">
            </div>
            @if(Auth::user()->role === 'system admin')
                <div class="form-group mb-3">
                    <label for="iPymtVchrCompfk" class="form-label">Company</label>
                    <select class="form-control" id="iPymtVchrCompfk" name="iPymtVchrCompfk" required>
                        <option value="">Select Company</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" data-payment-methods='@json($company->payments)'>
                                {{ $company->description }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @else
                <input type="hidden" name="iPymtVchrCompfk" value="{{ Auth::user()->company_id }}">
            @endif
            <br>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('paymentVoucher.index') }}" class="btn btn-secondary">Back</a>
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
<script>
    document.getElementById('iPymtVchrCompfk')?.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];

        const paymentMethods = JSON.parse(selectedOption.getAttribute('data-payment-methods')) || [];

        const paymentMethodSelect = document.getElementById('iPymtVchrPymtdfk');
        paymentMethodSelect.innerHTML = '<option value="">Select Payment Method</option>';
        paymentMethods.forEach(method => {
            paymentMethodSelect.innerHTML += `<option value="${method.iPymtdPk}">${method.cPymtdDesc}</option>`;
        });

    });
</script>
@endsection