<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory PDF</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
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
    <h1>Inventory Records</h1>
    <p>Total Inventory:{{ number_format($totalinventory) }}</p>
    <p>Year: {{ $selectedYear }}</p>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Product/Service Code & Name</th>
                <th>Supplier</th>
                <th>Quantity (IN)</th>
                <th>Quantity (OUT)</th>
                <th>Deposit Purchase (RM)</th>
                <th>Total Purchase (RM)</th>
                <th>Payment Method</th>
                <th>No Ref. Invoice/Receipt</th>
                <th>Staff In Charge</th>
                <th>Debtor</th>
                @if(Auth::user()->role === 'system admin')
                    <th>Company Name</th>
                @endif

            </tr>
        </thead>
        <tbody>
            @foreach($inventoryM as $inventories)
                <tr>
                    <td>{{$inventories->dInvmasDate}}</td>
                    <td>{{$inventories->inventories->cInvCode ?? 'N/A'}}-{{$inventories->inventories->cInvName ?? 'N/A'}}
                    </td>
                    <td>{{$inventories->cInvmasType}}</td>
                    <td>{{$inventories->supplier->iSuppDesc ?? 'N/A'}}</td>
                    <td>{{$inventories->iInvmasQuanIn}}</td>
                    <td>{{$inventories->iInvmasQuanOut}}</td>
                    <td class="text-right">{{number_format($inventories->yInvmasDeposit, 2)}}</td>
                    <td class="text-right">{{number_format($inventories->yInvmasPayment, 2)}}</td>
                    <td>{{$inventories->paymentMethod->cPymtdDesc ?? 'N/A' }}</td>
                    <td>{{$inventories->cInvmasInvoice}}</td>
                    <td>{{$inventories->employee->cEmpName ?? 'N/A'}}</td>
                    @if(Auth::user()->role === 'system admin')
                        <td>{{ $inventories->company->description ?? 'N/A' }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>