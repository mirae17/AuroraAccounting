@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Expenses Records</title>

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
        <h2>Expenses Records</h2>
    </div>
    <div class="col-lg-6 d-flex justify-content-end">
        <!-- Total Expenses Card -->
        <div class="card-custom">
            <p>Total Expenses:  <strong>RM{{ number_format($totalExpenses, 2) }}</strong></p>
            <p>Year: <strong>{{ $selectedYear }}</strong></p>
           
        </div>
    </div>
</div>

<!-- Display Success Message -->
@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif


<div class="col-lg-6 d-flex mb-3">
        <a href="{{ route('expensesM.create') }}" class="btn btn-success rounded-pill shadow-sm px-4 mr-2">
            <i class="fas fa-plus-circle"></i> Add Expenses
        </a>
        <a href="{{ route('expensesM.pdf') }}" class="btn btn-info rounded-pill shadow-sm px-4">
            <i class="fas fa-file-pdf"></i> Save as PDF
        </a>
    </div>
<!-- Sales Records Table -->
<div class="card-body">
   

    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Total Purchase (RM)</th>
                <th>Payment Method</th>
                <th>No Ref. Invoice/Receipt</th>
                <th>Notes</th> 
                @if(Auth::user()->role === 'system admin')
                <th>Company Name</th>
                @endif
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenseM as $expensesM)
            <tr>
                <td>{{ $expensesM->dexmasdate }}</td>
                <td>{{ $expensesM->expenses->cExpDesc ?? 'N/A' }}</td>
                <td class="text-right">{{ number_format($expensesM->yexmaspayment, 2) }}</td>
                <td>{{ $expensesM->paymentMethod->cPymtdDesc ?? 'N/A' }}</td>
                <td>{{ $expensesM->iexmasinvoiceref ?? 'N/A' }}</td>
                <td>{{ $expensesM->cexmasnotes }}</td>
                @if(Auth::user()->role === 'system admin')
                    <td>{{ $expensesM->company->description ?? 'N/A' }}</td>
                @endif
                <td>
                    <a href="{{ route('expensesM.edit', $expensesM->iexmaspk) }}" class="btn btn-custom-edit btn-sm">
                        <i class="fa fa-edit"></i>
                    </a>
                    <form action="{{ route('expensesM.destroy', $expensesM->iexmaspk) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-custom-delete btn-sm" onclick="return confirm('Are you sure?')">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
