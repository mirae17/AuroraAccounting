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
    <h1>Sales Records</h1>
    <p>Total Sales: RM{{ number_format($totalSales, 2) }}</p>
    <p>Year: {{ $selectedYear }}</p>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Deposit/Full Payment (RM)</th>
                <th>Payment Method</th>
                <th>Debtor Code</th>
                <th>No Ref. Invoice/Receipt</th>
                <th>Sale Method</th>
                <th>Total Sale (RM)</th>
                <th>Salesperson</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
            <tr>
                <td>{{ $sale->dsmasdate }}</td>
                <td>{{ $sale->csmasdesc }}</td>
                <td class="text-right">{{ number_format($sale->ysmasdeposit, 2) }}</td>
                <td>{{ $sale->paymentMethod->cPymtdDesc ?? 'N/A' }}</td>
                <td>{{ $sale->debtor->cDebtorCode ?? 'N/A' }}-{{ $sale->debtor->cDebtorDesc ?? 'N/A' }}</td>
                <td>{{ $sale->ismasinvoiceref ?? 'N/A' }}</td>
                <td>{{ $sale->ysmasdeposit == $sale->ysmaspayment ? 'CASH' : 'CREDIT' }}</td>
                <td class="text-right">{{ number_format($sale->ysmaspayment, 2) }}</td>
                <td>{{ $sale->ismasusersfk ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
