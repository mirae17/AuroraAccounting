@extends('layouts.template')

@section('content')
<div class="container">
    <h2>Receipt List</h2>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('receipt.create') }}" class="btn btn-success mb-3">Create New Receipt</a>

    <table id="example1" class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Receipt No</th>
                <th>Company</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Total Payment</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($receipt as $recpt)
                <tr>
                    <td>{{ $recpt->id }}</td>
                    <td>{{ $recpt->iQuoNo }}</td>
                    <td>{{ $recpt->company->name }}</td>
                    <td>{{ $recpt->customer->name }}</td>
                    <td>{{ $recpt->dQuodate }}</td>
                    <td>{{ $recpt->yQuoTotalPayment }}</td>
                    <td>
                        <a href="{{ route('receipts.show', $recpt->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('receipts.edit', $recpt->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <form action="{{ route('receipts.destroy', $recpt->id) }}" method="POST"
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