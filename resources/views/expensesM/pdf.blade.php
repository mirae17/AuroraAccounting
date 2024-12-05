<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
    <h1>Expenses Records</h1>
    <p>Total Expenses: RM{{ number_format($totalExpenses, 2) }}</p>
    <p>Year:{{ $selectedYear }}</p>


    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Total Purchase (RM)</th>
                <th>Payment Method</th>
                <th>No Ref. Invoice/Receipt</th>
                <th>Notes</th>
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
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>