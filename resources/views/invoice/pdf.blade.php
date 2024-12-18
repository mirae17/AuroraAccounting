<!DOCTYPE html>
<html>

<head>
    <title>Invoice</title>
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
                <p><strong>Name:</strong> {{ $invoice->customer->cCustDName }}</p>
                <p><strong>Company:</strong> {{ $invoice->customer->cCustDCompName }}</p>
                <p><strong>Address:</strong> {{ $invoice->customer->cCustDAddress }}</p>
                <p><strong>Phone:</strong> {{ $invoice->customer->cCustDCompOfficeNo }}</p>
                <p><strong>Email:</strong> {{ $invoice->customer->cCustDCompEmail }}</p>
            </div>
            <div>
                <h5>Invoice Details</h5>
                <p><strong>Order No:</strong> {{ $invoice->iInvcNo }}</p>
                <p><strong>Date:</strong> {{ $invoice->dInvcdate }}</p>
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
                @foreach($invoice->items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->cInvcItemProductCode }}</td>
                        <td>{{ $item->cInvcItemDescription }}</td>
                        <td>{{ $item->iInvcItemQuantity }}</td>
                        <td>{{ number_format($item->yInvcItemPriceUnit, 2) }}</td>
                        <td>{{ number_format($item->yInvcItemTotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="highlight">Subtotal</td>
                    <td>{{ number_format($invoice->yInvcSubtotal, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="5" class="highlight">Discount</td>
                    <td>{{ $invoice->iInvcDiscount }}%</td>
                </tr>
                <tr>
                    <td colspan="5" class="highlight">Tax</td>
                    <td>{{ $invoice->iInvcTax }}%</td>
                </tr>
                <tr>
                    <td colspan="5" class="highlight">Total</td>
                    <td>{{ number_format($invoice->yInvcTotalPayment, 2) }}</td>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>


    <!-- jQuery and Bootstrap JavaScript for modal functionality -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        AOS.init();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

</body>

</html>