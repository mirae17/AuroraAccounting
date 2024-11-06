@extends('layouts.template_sales')

@section('content')
<div class="container">
    <h2>Sale Details</h2>
    <ul class="list-group">
        <li class="list-group-item"><strong>Date:</strong> {{ $sale->dsmasdate }}</li>
        <li class="list-group-item"><strong>Description:</strong> {{ $sale->csmasdesc }}</li>
        <li class="list-group-item"><strong>Deposit:</strong> RM{{ number_format($sale->ysmasdeposit, 2) }}</li>
        <li class="list-group-item"><strong>Total Payment:</strong> RM{{ number_format($sale->ysmaspayment, 2) }}</li>
        <li class="list-group-item"><strong>Payment Method:</strong> {{ $sale->ismasPymtdfk }}</li>
        <li class="list-group-item"><strong>Supplier:</strong> {{ $sale->ismasSuppfk }}</li>
    </ul>
</div>
@endsection
