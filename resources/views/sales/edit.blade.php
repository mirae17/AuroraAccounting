@extends('layouts.template_sales')

@section('content')
<div class="container">
    <h2>Edit Sale</h2>

    <form action="{{ route('sales.update', $sale->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="dsmasdate">Date</label>
            <input type="date" class="form-control" id="dsmasdate" name="dsmasdate" value="{{ $sale->dsmasdate }}" required>
        </div>

        <div class="form-group">
            <label for="csmasdesc">Description</label>
            <input type="text" class="form-control" id="csmasdesc" name="csmasdesc" value="{{ $sale->csmasdesc }}" required>
        </div>

        <div class="form-group">
            <label for="ysmasdeposit">Deposit</label>
            <input type="number" class="form-control" id="ysmasdeposit" name="ysmasdeposit" value="{{ $sale->ysmasdeposit }}" required>
        </div>

        <div class="form-group">
            <label for="ysmaspayment">Total Payment</label>
            <input type="number" class="form-control" id="ysmaspayment" name="ysmaspayment" value="{{ $sale->ysmaspayment }}" required>
        </div>

        <div class="form-group">
            <label for="ismasPymtdfk">Payment Method</label>
            <select id="ismasPymtdfk" name="ismasPymtdfk" class="form-control" required>
                @foreach($paymentMethods as $method)
                    <option value="{{ $method->iPymtdPk }}" {{ $sale->ismasPymtdfk == $method->iPymtdPk ? 'selected' : '' }}>{{ $method->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="ismasSuppfk">Supplier</label>
            <select id="ismasSuppfk" name="ismasSuppfk" class="form-control" required>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->iSuppPk }}" {{ $sale->ismasSuppfk == $supplier->iSuppPk ? 'selected' : '' }}>{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Sale</button>
    </form>
</div>
@endsection
