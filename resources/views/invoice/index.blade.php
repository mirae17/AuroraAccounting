@extends('layouts.template')

@section('content')
<div class="container">
    <h2>Invoices List</h2>
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
                @forelse($invoice as $invc)
                    <tr>
                        <td>{{ $invc->id }}</td>
                        <td>{{ $invc->iInvcNo }}</td>
                        <td>{{ $invc->customer->cCustDCompName ?? 'N/A' }}</td>
                        <td>{{ $invc->customer->cCustDName ?? 'N/A'}}</td>
                        <td>{{ $invc->dInvcdate }}</td>
                        <td>{{ $invc->yInvcTotalPayment }}</td>
                        <td>
                            <a href="{{ route('invoice.show', $invc->iInvcPk) }}" class="btn btn-info btn-sm"><i
                                    class="fa fa-eye"></i></a>
                            <a href="{{ route('invoice.edit', $invc->iInvcPk) }}" class="btn btn-custom-edit btn-sm"> <i
                                    class="fa fa-edit"></i></a>
                            <form action="{{ route('invoice.destroy', $invc->iInvcPk) }}" method="POST"
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