@extends('layouts.template')

@section('content')
<div class="container">
    <h2>Invoice List</h2>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('invoice.create') }}" class="btn btn-success mb-3">Create New Invoice</a>
    <div class="card-body">
        <table id="example1" class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Invoice No</th>
                    <th>Company</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total Payment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoice as $inv)
                    <tr>
                        <td>{{ $inv->iInvcPk }}</td>
                        <td>{{ $inv->iInvcNo }}</td>
                        <td>{{ $inv->customer->cCustDCompName ?? 'N/A' }}</td>
                        <td>{{ $inv->customer->cCustDName ?? 'N/A'}}</td>
                        <td>{{ $inv->dInvcdate }}</td>
                        <td>{{ $inv->yInvcTotalPayment }}</td>
                        <td>
                            <a href="{{ route('invoice.show', $inv->iInvcPk) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('invoice.edit', $inv->iInvcPk) }}" class="btn btn-primary btn-sm">Edit</a>
                            <form action="{{ route('invoice.destroy', $inv->iInvcPk) }}" method="POST"
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
                        <td colspan="7" class="text-center">No invoice found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection