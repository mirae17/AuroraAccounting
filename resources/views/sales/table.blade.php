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
</style>
<body>
<div class="row mb-3 align-items-center">
    <div class="col-lg-6">
        <h2>Sales Records</h2>
    </div>
    <div class="col-lg-6 text-right d-flex justify-content-end align-items-center">
       

        <!-- Total Sales Display -->
        <div class="mr-3">
            <div class="small-card">
                <div class="card-body p-1">
                    <p>Total Sales: <strong>RM{{ number_format($totalSales, 2) }}</strong></p>
                    <p>Year: <strong>{{ date('Y') }}</strong></p>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Display Success Message -->
@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

<!-- Sales Records Table -->
<div class="card-body">
<div class="col-lg-6 d-flex">
        <a href="{{ route('sales.create') }}" id="rekodJualanButton" class="btn btn-success mr-2">Add Sales</a>
    </div>
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
            <td>{{ $sale->supplier->iSuppCode ?? 'N/A' }}-{{ $sale->supplier->iSuppDesc ?? 'N/A' }}</td>
            <td>{{ $sale->ismasinvoiceref ?? 'N/A' }}</td>
            <td>{{ $sale->ysmasdeposit == $sale->ysmaspayment ? 'CASH' : 'CREDIT' }}</td>
            <td  class="text-right">{{ number_format($sale->ysmaspayment, 2) }}</td>
            <td>{{ $sale->ismasusersfk ?? 'N/A' }}</td>
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



