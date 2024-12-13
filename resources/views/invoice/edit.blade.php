@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Quotation</h2>
    <form action="{{ route('quotations.update', $quotation->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="iQuoComfk">Company</label>
            <select name="iQuoComfk" class="form-control" required>
                <option value="">Select Company</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ $quotation->iQuoComfk == $company->id ? 'selected' : '' }}>
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="iQuoCustDfk">Customer</label>
            <select name="iQuoCustDfk" class="form-control" required>
                <option value="">Select Customer</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->iCustDPk }}" {{ $quotation->iQuoCustDfk == $customer->iCustDPk ? 'selected' : '' }}>
                        {{ $customer->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="iQuoNo">Quotation Number</label>
            <input type="text" name="iQuoNo" class="form-control" value="{{ $quotation->iQuoNo }}" required>
        </div>

        <div class="form-group">
            <label for="dQuodate">Quotation Date</label>
            <input type="date" name="dQuodate" class="form-control" value="{{ $quotation->dQuodate }}" required>
        </div>

        <div class="form-group">
            <label for="yQuoSubtotal">Subtotal</label>
            <input type="number" step="0.01" name="yQuoSubtotal" class="form-control"
                value="{{ $quotation->yQuoSubtotal }}" required>
        </div>

        <div class="form-group">
            <label for="yQuoTotalPayment">Total Payment</label>
            <input type="number" step="0.01" name="yQuoTotalPayment" class="form-control"
                value="{{ $quotation->yQuoTotalPayment }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection