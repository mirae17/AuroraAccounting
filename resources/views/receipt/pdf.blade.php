<!DOCTYPE html>
<html>

<head>
    <title>Receipt</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Additional Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- DataTables -->
    @vite(['resources/js/app.js', 'resources/sass/app.scss'])
    <style>
        * {
            font-size: 12px !important;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            margin: 0 auto;
            max-width: 800px;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 24px;
            color: #007bff;
            margin: 0;
        }

        .header p {
            font-size: 14px;
            margin: 0;
        }

        .details-section {
            display: flex;
            justify-content: space-between;
            margin-top: 5px;
        }

        .details-section div {
            width: 48%;
        }

        .details-section h5 {
            background-color: #007bff;
            color: #fff;
            padding: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            font-size: 14px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tfoot td {
            font-weight: bold;
        }

        .highlight {
            color: #007bff;
        }

        .footer {
            margin-top: 15px;
            text-align: left;
            font-size: 14px;
        }


        .note {
            text-align: center;
            font-size: 12px;
            color: gray;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="text-center mb-5">
                <img id="company-logo" src="{{ public_path('storage/' . $companyMaintenance->iCompMainLogo) }}"
                    alt="Company Logo" class="mb-3" style="max-height: 100px;">
                <h3 id="company-name" class="mb-1">{{ $companyMaintenance->company->description }}</h3>
                <p id="company-address" class="mb-1 text-muted">{{ $companyMaintenance->iCompMainAddress }}</p>
                <p id="company-contact" class="text-muted">
                    {{ $companyMaintenance->iCompMainPhoneNo }} | {{ $companyMaintenance->iCompMainEmail }}
                </p>
            </div>
        </div>

        <!-- Details Section -->
        <div class="details-section">
            <!-- Bill To -->
            <div>
                <h5>Bill To</h5>
                <p><strong>Name:</strong> {{ $receipt->customer->cCustDName }}</p>
                <p><strong>Company:</strong> {{ $receipt->customer->cCustDCompName }}</p>
                <p><strong>Address:</strong> {{ $receipt->customer->cCustDAddress }}</p>
                <p><strong>Phone:</strong> {{ $receipt->customer->cCustDCompOfficeNo }}</p>
                <p><strong>Email:</strong> {{ $receipt->customer->cCustDCompEmail }}</p>
            </div>
            <div>
                <h5>Receipt Details</h5>
                <p><strong>Order No:</strong> {{ $receipt->iRecptNo }}</p>
                <p><strong>Date:</strong> {{ $receipt->dRecptdate }}</p>
            </div>
        </div>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Price per Unit</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receipt->items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->cRecptItemProductCode }}</td>
                        <td>{{ $item->cRecptItemDescription }}</td>
                        <td>{{ $item->iRecptItemQuantity }}</td>
                        <td>{{ number_format($item->yRecptItemPriceUnit, 2) }}</td>
                        <td>{{ number_format($item->yRecptItemTotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="highlight">Subtotal</td>
                    <td>{{ number_format($receipt->yRecptSubtotal, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="5" class="highlight">Discount</td>
                    <td>{{ $receipt->iRecptDiscount }}%</td>
                </tr>
                <tr>
                    <td colspan="5" class="highlight">Tax</td>
                    <td>{{ $receipt->iRecptTax }}%</td>
                </tr>
                <tr>
                    <td colspan="5" class="highlight">Total</td>
                    <td>{{ number_format($receipt->yRecptTotalPayment, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Footer -->
        <div class="footer">
            @if($signatureOption === 'with')
                <div>
                    <p>Authorized Signature</p>
                    <p>______________________</p>
                </div>
            @else
                <p class="note">This is a computer-generated document. No signature is required.</p>
            @endif
        </div>


    </div>
</body>

</html>