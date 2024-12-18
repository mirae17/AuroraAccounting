@extends('layouts.template')

@section('content')
<div class="container">
    <h2>Purchase Orders List</h2>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('purchaseOrder.create') }}" class="btn btn-success mb-3">Create New Purchase Order</a>
    <div class="card-body">
        <table id="example1" class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Purchase Order No</th>
                    <th>Company</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total Payment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchaseOrder as $PurchOrder)
                    <tr>
                        <td>{{ $PurchOrder->id }}</td>
                        <td>{{ $PurchOrder->iPurchOrderNo }}</td>
                        <td>{{ $PurchOrder->customer->cCustDCompName ?? 'N/A' }}</td>
                        <td>{{ $PurchOrder->customer->cCustDName ?? 'N/A'}}</td>
                        <td>{{ $PurchOrder->dPurchOrderdate }}</td>
                        <td>{{ $PurchOrder->yPurchOrderTotalPayment }}</td>
                        <td>
                            <a href="{{ route('purchaseOrder.show', $PurchOrder->iPurchOrderPk) }}"
                                class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                            <a href="{{ route('purchaseOrder.edit', $PurchOrder->iPurchOrderPk) }}"
                                class="btn btn-custom-edit btn-sm"> <i class="fa fa-edit"></i></a>
                            <form action="{{ route('purchaseOrder.destroy', $PurchOrder->iPurchOrderPk) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-custom-delete btn-sm"
                                    onclick="return confirm('Are you sure?')">
                                    <i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty

                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection