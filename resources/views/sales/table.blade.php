@section('content')
<style>
    /* Center align text in the table header */
    .table thead th {
        text-align: center;
        vertical-align: middle;
    }

    /* Optional: Center-align text in the entire table if desired */
    .table td, .table th {
        text-align: center;
        vertical-align: middle;
    }
</style>

<div class="row mb-3 align-items-center">
    <div class="col-lg-6">
        <h2>Sales Records</h2>
    </div>
    <div class="col-lg-6 text-right d-flex justify-content-end align-items-center">
        <!-- Buttons for Data Management -->
        <button type="button" class="btn btn-danger mr-2" onclick="showDeleteConfirmation()">Buang Data</button>
        <a href="{{ route('sales.create') }}" id="rekodJualanButton" class="btn btn-success mr-2">Rekod Jualan</a>
        <a href="#" class="btn btn-primary mr-2">Jualan Stok</a>

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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Delete Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Please select the data you want to delete.</p>
                <p>Are you sure you want to delete the selected sales records?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="confirmDeleteButton" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Filter and Sorting Options -->
<div class="row mb-4">
    <div class="col-md-3">
        <label for="year">Year:</label>
        <select id="year" name="year" class="form-control">
            <option value="">Select Year</option>
            @for($y = date('Y'); $y >= 2000; $y--)
                <option value="{{ $y }}">{{ $y }}</option>
            @endfor
        </select>
    </div>
    <div class="col-md-3">
        <label for="month">Susun Bulan:</label>
        <select id="month" name="month" class="form-control">
            <option value="">Select Month</option>
            @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
            @endfor
        </select>
    </div>
    <div class="col-md-3">
        <label for="debtor_code">Penghutang:</label>
        <input type="text" id="debtor_code" name="debtor_code" class="form-control" placeholder="Enter Debtor Code">
    </div>
    <div class="col-md-3">
        <label for="invoice_ref">No Rujukan:</label>
        <input type="text" id="invoice_ref" name="invoice_ref" class="form-control" placeholder="Enter Invoice Reference">
    </div>
</div>

<!-- Clear and Sort Buttons -->
<div class="row mb-4">
    <div class="col-md-3 offset-md-9 d-flex justify-content-end">
        <button id="clearFilter" class="btn btn-secondary mr-2">Clear Filter</button>
        <button id="sortByDate" class="btn btn-info">Sort by Date</button>
    </div>
</div>

<!-- Display Success Message -->
@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

<!-- Sales Records Table -->
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Tarikh</th>
            <th>Perkara/Customer Detail</th>
            <th>Deposit/Bayaran Penuh (RM)</th>
            <th>Cara Bayaran</th>
            <th>Kod Penghutang</th>
            <th>No Ruj. Invoice/Receipt</th>
            <th>Cara Jualan</th>
            <th>Jumlah Jualan (RM)</th>
            <th>Salesperson</th>
        </tr>
    </thead>
    <tbody id="salesTableBody">
        @foreach($sales as $sale)
        <tr>
            <td>{{ $sale->dsmasdate }}</td>
            <td>{{ $sale->csmasdesc }}</td>
            <td>{{ number_format($sale->ysmasdeposit, 2) }}</td>
            <td>{{ $sale->ismasPymtdfk ?? 'N/A' }}</td>
            <td>{{ $sale->ismasSuppfk ?? 'N/A' }}</td>
            <td>{{ $sale->ismasinvoiceref ?? 'N/A' }}</td>
            <td>{{ $sale->ysmasdeposit == $sale->ysmaspayment ? 'Cash' : 'Credit' }}</td>
            <td>{{ number_format($sale->ysmaspayment, 2) }}</td>
            <td>{{ $sale->ismasusersfk ?? 'N/A' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Hidden Form for Deletion -->
<form id="deleteForm" action="{{ route('sales.destroy', ['sales_master' => 'all']) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
    function showDeleteConfirmation() {
        // Show the delete confirmation modal
        $('#deleteConfirmationModal').modal('show');
    }

    document.getElementById('confirmDeleteButton').addEventListener('click', function() {
        // Submit the form when user confirms delete
        document.getElementById('deleteForm').submit();
    });

    document.getElementById('clearFilter').addEventListener('click', function() {
        // Clear all filter values
        document.getElementById('year').value = '';
        document.getElementById('month').value = '';
        document.getElementById('debtor_code').value = '';
        document.getElementById('invoice_ref').value = '';
        
        // Refresh table or apply cleared filter
        applyFilters();
    });

    document.getElementById('sortByDate').addEventListener('click', function() {
        // Sort sales by date (can implement further logic based on backend requirements)
        applyFilters();
    });

    function applyFilters() {
        const year = document.getElementById('year').value;
        const month = document.getElementById('month').value;
        const debtorCode = document.getElementById('debtor_code').value;
        const invoiceRef = document.getElementById('invoice_ref').value;

        // Fetch data based on filters
        fetch(`/sales/filter?year=${year}&month=${month}&debtor_code=${debtorCode}&invoice_ref=${invoiceRef}`)
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('salesTableBody');
                tbody.innerHTML = ''; // Clear the existing rows

                data.sales.forEach(sale => {
                    let row = `
                        <tr>
                            <td>${sale.dsmasdate}</td>
                            <td>${sale.csmasdesc}</td>
                            <td>${parseFloat(sale.ysmasdeposit).toFixed(2)}</td>
                            <td>${sale.ismasPymtdfk ?? 'N/A'}</td>
                            <td>${sale.ismasSuppfk ?? 'N/A'}</td>
                            <td>${sale.ismasinvoiceref ?? 'N/A'}</td>
                            <td>${sale.saleType ?? ''}</td>
                            <td>${parseFloat(sale.ysmaspayment).toFixed(2)}</td>
                            <td>${sale.ismasusersfk ?? 'N/A'}</td>
                        </tr>
                    `;
                    tbody.insertAdjacentHTML('beforeend', row);
                });
            })
            .catch(error => console.error('Error fetching data:', error));
    }
</script>
@endpush
