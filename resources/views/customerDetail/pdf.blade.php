<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
            word-wrap: break-word;
            overflow: hidden;
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
    <h1>Customer Record</h1>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>No HP</th>
                <th>Address</th>
                <th>City</th>
                <th>Postcode</th>
                <th>State</th>
                <th>Company Code/Name</th>
                <th>Company HP No</th>
                <th>Company Office No</th>
                <th>Company Email</th>
                <th>Website</th>

            </tr>
        </thead>
        <tbody>
            @foreach($customerDetail as $customer)

                <tr>
                    <td>{{$customer->cCustDName}}</td>
                    <td>{{$customer->cCustDPhoneNo}}</td>
                    <td>{{$customer->cCustDAddress}}</td>
                    <td>{{$customer->cCustDCity}}</td>
                    <td>{{$customer->cCustDState}}</td>
                    <td>{{$customer->cCustDPostcode}}</td>
                    <td>{{$customer->company->code ?? 'N/A'}}-{{$customer->company->description ?? 'N/A'}} </td>
                    <td>{{$customer->cCustDCompNo }}</td>
                    <td>{{$customer->cCustDCompOfficeNo }}</td>
                    <td>{{$customer->cCustDCompEmail}}</td>
                    <td>{{$customer->cCustDCompWebsite}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>