@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | DataTables</title>

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
<body>
<div class="row mb-3 align-items-center">
    <div class="col-lg-6">
        <h2>Sales Records ({{ $selectedCompanyDescription}})</h2>
    </div>



       

        <!-- Total Sales Display -->
        <div class="col-lg-6 d-flex justify-content-end">
        <!-- Total Expenses Card -->
        <div class="card-custom">
                    <p>Total Sales: <strong>RM{{ number_format($totalSales, 2) }}</strong></p>
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
<div class="col-lg-6 d-flex ">
        <a href="{{ route('sales.create') }}" class="btn btn-success rounded-pill shadow-sm px-4 mr-2">
            <i class="fas fa-plus-circle"></i> Add Sales
        </a>
        <a href="{{ route('sales.pdf') }}" class="btn btn-info rounded-pill shadow-sm px-4 ">
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
            <th >Deposit/Full Payment (RM)</th>
            <th>Payment Method</th>
            <th>Debtor Code</th>
            <th>No Ref. Invoice/Receipt</th>
            <th>Sale Method</th>
            <th>Total Sale (RM)</th>
            <th>Salesperson</th>
            @if(Auth::user()->role === 'system admin')
            <th>Company Name</th>
            @endif
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="salesTableBody">
        @foreach($sales as $sale)
        <tr >
            <td>{{ $sale->dsmasdate }}</td>
            <td>{{ $sale->csmasdesc }}</td>
            <td class="text-right">{{ number_format($sale->ysmasdeposit, 2) }}</td>
            <td>{{ $sale->paymentMethod->cPymtdDesc ?? 'N/A' }}</td>
            <td>{{ $sale->debtor->cDebtorCode ?? 'N/A' }}-{{ $sale->debtor->cDebtorDesc ?? 'N/A' }}</td>
            <td>{{ $sale->ismasinvoiceref ?? 'N/A' }}</td>
            <td>{{ $sale->ysmasdeposit == $sale->ysmaspayment ? 'CASH' : 'CREDIT' }}</td>
            <td  class="text-right">{{ number_format($sale->ysmaspayment, 2) }}</td>
            <td>{{ $sale->employee->cEmpName ?? 'N/A'  }}</td>
            @if(Auth::user()->role === 'system admin')
                <td>{{ $sale->company->description ?? 'N/A' }}</td>
            @endif
            <td> <!-- Edit and Delete Icons -->
                <a href="{{ route('sales.edit', $sale->ismaspk) }}" class="btn btn-custom-edit btn-sm">
                    <i class="fa fa-edit"></i>
                </a>
                <form action="{{ route('sales.destroy', $sale->ismaspk) }}" method="POST" style="display: inline-block;">
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



