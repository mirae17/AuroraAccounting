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
    <div class="card-body">
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
                        <td>{{ $recpt->iRecptNo }}</td>
                        <td>{{ $recpt->customer->cCustDCompName ?? 'N/A' }}</td>
                        <td>{{ $recpt->customer->cCustDName ?? 'N/A'}}</td>
                        <td>{{ $recpt->dRecptdate }}</td>
                        <td>{{ $recpt->yRecptTotalPayment }}</td>
                        <td>
                            <a href="{{ route('receipt.show', $recpt->iRecptPk) }}" class="btn btn-info btn-sm">
                                <i class="fa fa-eye"></i></a>
                            <a href="{{ route('receipt.edit', $recpt->iRecptPk) }}" class="btn btn-custom-edit btn-sm"> <i
                                    class="fa fa-edit"></i></a>
                            <form action="{{ route('receipt.destroy', $recpt->iRecptPk) }}" method="POST"
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