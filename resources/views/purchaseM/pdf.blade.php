<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales PDF</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
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
</head>
<body>
    <h1>Purchase Records</h1>
    <p>Total Sales: RM{{ number_format($totalPurchase, 2) }}</p>
    <p>Year: {{ $selectedYear }}</p>

    <table>
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
            </tr>
        </thead>
        <tbody>
        @foreach($purchaseM as $purchasesM)
            <tr>
            <td>{{ $purchasesM->dpmasdate }}</td>
            <td>{{ $purchasesM->supplier->iSuppCode ?? 'N/A' }}-{{ $purchasesM->supplier->iSuppDesc ?? 'N/A' }}</td>
            <td>{{ $purchasesM->cpmascodeprod }}</td>
            <td  class="text-right">{{ number_format($purchasesM->ypmaspayment, 2) }}</td>
            <td>{{ $purchasesM->ypmasdeposit == $purchasesM->ypmaspayment ? 'CASH' : 'CREDIT' }}</td>
            <td class="text-right">{{ number_format($purchasesM->ypmasdeposit, 2) }}</td>
            <td>{{ $purchasesM->paymentMethod->cPymtdDesc ?? 'N/A' }}</td>
            <td>{{ $purchasesM->ipmasinvoiceref ?? 'N/A' }}</td>
            <td>{{ $purchasesM-> cpmasnotes }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
