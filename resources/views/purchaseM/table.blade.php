@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | DataTables</title>

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
<body>
<div class="row mb-3 align-items-center">
    <div class="col-lg-6">
        <h2>Purchase Records </h2>
    </div>

       

        <!-- Total Sales Display -->
        <div class="col-lg-6 d-flex justify-content-end">
        <!-- Total Expenses Card -->
        <div class="card-custom">
                    <p>Total Purchase: <strong>RM{{ number_format($totalPurchase, 2) }}</strong></p>
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

<div class="col-lg-6 d-flex">
       
        <a href="{{ route('purchaseM.create') }}" class="btn btn-success rounded-pill shadow-sm px-4 mr-2">
            <i class="fas fa-plus-circle"></i> Add Purchase
        </a>
        <a href="{{ route('purchaseM.pdf') }}" class="btn btn-info rounded-pill shadow-sm px-4 ">
            <i class="fas fa-file-pdf"></i> Save as PDF
        </a>
    </div>
<!-- Sales Records Table -->
<div class="card-body">

   
<table id="example1" class="table table-bordered table-striped">

    <thead>
        <tr>
            <th>Date</th>
            <th>Supplier</th>
            <th>Product Code</th>
            <th>Total Sale (RM)</th>
            <th>Sale Method</th>
            <th >Deposit/Full Payment (RM)</th>
            <th>Payment Method</th>
            <th>No Ref. Invoice/Receipt</th>
            <th>Notes</th>
            @if(Auth::user()->role === 'system admin')
            <th>Company Name</th>
            @endif
            <th>Action</th>
        </tr>
    </thead>
    <tbody >
        @foreach($purchaseM as $purchasesM)
        
        <tr >
            <td>{{ $purchasesM->dpmasdate }}</td>
            <td>{{ $purchasesM->supplier->iSuppCode ?? 'N/A' }}-{{ $purchasesM->supplier->iSuppDesc ?? 'N/A' }}</td>
            <td>{{ $purchasesM->cpmascodeprod }}</td>
            <td  class="text-right">{{ number_format($purchasesM->ypmaspayment, 2) }}</td>
            <td>{{ $purchasesM->ypmasdeposit == $purchasesM->ypmaspayment ? 'CASH' : 'CREDIT' }}</td>
            <td class="text-right">{{ number_format($purchasesM->ypmasdeposit, 2) }}</td>
            <td>{{ $purchasesM->paymentMethod->cPymtdDesc ?? 'N/A' }}</td>
            <td>{{ $purchasesM->ipmasinvoiceref ?? 'N/A' }}</td>
            <td>{{ $purchasesM-> cpmasnotes }}</td>
            @if(Auth::user()->role === 'system admin')
                <td>{{ $purchasesM->company->description ?? 'N/A' }}</td>
            @endif
            <td> <!-- Edit and Delete Icons -->
                <a href="{{ route('purchaseM.edit', $purchasesM->ipmaspk) }}" class="btn btn-custom-edit btn-sm">
                    <i class="fa fa-edit"></i>
                </a>
                <form action="{{ route('purchaseM.destroy', $purchasesM->ipmaspk) }}" method="POST" style="display: inline-block;">
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



