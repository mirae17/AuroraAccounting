@extends('layouts.template')

@section('content')
<div class="container">
    <h2>Quotations List</h2>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('quotations.create') }}" class="btn btn-success mb-3">Create New Quotation</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Quotation No</th>
                <th>Company</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Total Payment</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($quotations as $quotation)
                <tr>
                    <td>{{ $quotation->id }}</td>
                    <td>{{ $quotation->iQuoNo }}</td>
                    <td>{{ $quotation->company->name }}</td>
                    <td>{{ $quotation->customer->name }}</td>
                    <td>{{ $quotation->dQuodate }}</td>
                    <td>{{ $quotation->yQuoTotalPayment }}</td>
                    <td>
                        <a href="{{ route('quotations.show', $quotation->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('quotations.edit', $quotation->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <form action="{{ route('quotations.destroy', $quotation->id) }}" method="POST"
                            style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No quotations found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection