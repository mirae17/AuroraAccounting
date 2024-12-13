@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Quotation Details</h2>

    <p><strong>Company:</strong> {{ $quotation->company->name }}</p>
    <p><strong>Customer:</strong> {{ $quotation->customer->name }}</p>
    <p><strong>Quotation Number:</strong> {{ $quotation->iQuoNo }}</p>
    <p><strong>Date:</strong> {{ $quotation->dQuodate }}</p>
    <p><strong>Subtotal:</strong> {{ $quotation->yQuoSubtotal }}</p>
    <p><strong>Total Payment:</strong> {{ $quotation->yQuoTotalPayment }}</p>

    <form action="{{ route('quotations.print', ['id' => $quotation->id, 'signature' => true]) }}" method="GET">
        <button class="btn btn-success">Print with Signature</button>
    </form>
    <form action="{{ route('quotations.print', ['id' => $quotation->id, 'signature' => false]) }}" method="GET">
        <button class="btn btn-primary">Print without Signature</button>
    </form>
</div>
@endsection