@extends('layouts.template')

@section('content')
<div class="container">
    <h2>Purchase Order List</h2>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('purchaseOrder.create') }}" class="btn btn-success mb-3">Create New Purchase Order</a>

    <table class="table table-bordered">
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
            @forelse($purchaseOrder as $purchaseOrders)
                <tr>
                    <td>{{ $purchaseOrders->id }}</td>
                    <td>{{ $purchaseOrders->iQuoNo }}</td>
                    <td>{{ $purchaseOrders->company->name }}</td>
                    <td>{{ $purchaseOrders->customer->name }}</td>
                    <td>{{ $purchaseOrders->dQuodate }}</td>
                    <td>{{ $purchaseorders->yQuoTotalPayment }}</td>
                    <td>
                        <a href="{{ route('purchaseOrder.show', $purchaseorders->id) }}"
                            class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('purchaseOrder.edit', $purchaseorders->id) }}"
                            class="btn btn-primary btn-sm">Edit</a>
                        <form action="{{ route('purchaseOrder.destroy', $purchaseorders->id) }}" method="POST"
                            style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty

            @endforelse
        </tbody>
    </table>
</div>

@endsection