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
    <div class="card-body">
        <table id="example1" class="table table-bordered">
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
                        <td>{{ $quotation->customer->cCustDCompName ?? 'N/A' }}</td>
                        <td>{{ $quotation->customer->cCustDName ?? 'N/A'}}</td>
                        <td>{{ $quotation->dQuodate }}</td>
                        <td>{{ $quotation->yQuoTotalPayment }}</td>
                        <td>
                            <a href="{{ route('quotations.show', $quotation->iQuoPk) }}" class="btn btn-info btn-sm">
                                <i class="fa fa-eye"></i></a>
                            <a href="{{ route('quotations.edit', $quotation->iQuoPk) }}" class="btn btn-custom-edit btn-sm">
                                <i class="fa fa-edit"></i></a>
                            <form action="{{ route('quotations.destroy', $quotation->iQuoPk) }}" method="POST"
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