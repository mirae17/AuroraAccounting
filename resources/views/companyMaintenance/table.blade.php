@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


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
            <h2>Company Details</h2>
        </div>
    </div>

    <!-- Display Success Message -->
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="col-lg-6 d-flex">
        @if(Auth::user()->role === 'system admin')
            <a href="{{ route('companyMaintenance.create') }}" class="btn btn-success rounded-pill shadow-sm px-4 mr-2">
                <i class="fas fa-plus-circle"></i> Add Company Details
            </a>
        @endif

        <a href="{{ route('companyMaintenance.pdf') }}" class="btn btn-info rounded-pill shadow-sm px-4 ">
            <i class="fas fa-file-pdf"></i> Save as PDF
        </a>
    </div>
    <!-- Sales Records Table -->
    <div class="card-body">


        <table id="example1" class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>Name</th>
                    <th>Registration No</th>
                    <th>Phone No</th>
                    <th>Email</th>
                    <th>Logo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($companyMaintenance as $company)
                    <tr>
                        <td>{{ $company->company->description ?? 'N/A' }}</td>
                        <td>{{ $company->iCompMainRegNo }}</td>
                        <td>{{ $company->iCompMainPhoneNo }}</td>
                        <td>{{ $company->iCompMainEmail }}</td>
                        <td>
                            @if($company->iCompMainLogo)
                                <img src="{{ asset('storage/' . $company->iCompMainLogo) }}" alt="Logo" width="50">
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('companyMaintenance.edit', $company->iCompMainPk) }}"
                                class="btn btn-custom-edit btn-sm">
                                <i class="fa fa-edit"></i></a>
                            <form action="{{ route('companyMaintenance.destroy', $company->iCompMainPk) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-custom-delete btn-sm"
                                    onclick="return confirm('Are you sure?')">
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