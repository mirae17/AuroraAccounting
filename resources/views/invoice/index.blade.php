@extends('layouts.template')

@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        /* Center align text in the table header */
        <style>

        /* Center align text in the table header */
        .table thead th {
            text-align: center;
            vertical-align: middle;
        }

        /* Left-align text columns */
        .text-left {
            text-align: left !important;
        }

        /* Right-align number columns */
        .text-right {
            text-align: right !important;
        }

        /* Custom styles for the card */
        .card-custom {
            background-color: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }

        .card-custom p {
            margin: 0;
            font-size: 16px;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="row mb-3 align-items-center">
        <div class="col-lg-6">
            <h2>Invoices List</h2>
        </div>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <a href="{{ route('invoice.create') }}" class="btn btn-success mb-3"> <i class="fas fa-plus-circle"></i> Add New
        Invoice</a>
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
                        <td class="text-right">{{ $invc->yInvcTotalPayment }}</td>
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

</body>

@endsection