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
            <h2>Payment Voucher Records </h2>
        </div>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </div>
    <a href="{{ route('paymentVoucher.create') }}" class="btn btn-success mb-3"><i class="fas fa-plus-circle"></i> Add
        New Payment Voucher</a>
    <div class="card-body">
        <table id="example1" class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Payment Voucher No</th>
                    <th>Description</th>
                    <th>Bank Account Number</th>
                    <th>Bank Name</th>
                    <th>Payment Method</th>
                    <th>Reference No</th>
                    <th>Total Payment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paymentVouchers as $PymtVchr)
                    <tr>
                        <td>{{ $PymtVchr->dPymtVchrDate }}</td>
                        <td>{{ $PymtVchr->cPymtVchrNo}}</td>
                        <td>{{ $PymtVchr->cPymtVchrDesc  }}</td>
                        <td>{{ $PymtVchr->cPymtVchrDesc  }}</td>
                        <td>{{ $PymtVchr->cPymtVchrNoAcc}}</td>
                        <td>{{ $PymtVchr->paymentMethod->cPymtdDesc ?? 'N/A'  }}</td>
                        <td>{{ $PymtVchr->cPymtVchrRefNo}}</td>
                        <td class="text-right">{{ $PymtVchr->yPymtVchrTotal }}</td>
                        <td>
                            <a href="{{ route('paymentVoucher.edit', $PymtVchr->iPymtVchrPk) }}"
                                class="btn btn-custom-edit btn-sm"> <i class="fa fa-edit"></i></a>
                            <form action="{{ route('paymentVoucher.destroy', $PymtVchr->iPymtVchrPk) }}" method="POST"
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