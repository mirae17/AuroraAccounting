@section('content')
<style>
  .table thead th,
  .table td,
  .table th {
    text-align: center;
    vertical-align: middle;
  }
</style>

<div class="row mb-3 align-items-center">

  <div class="col-lg-6">

    <br>
    <h2>Debtor List</h2>
  </div>
</div>

<!-- Modal Create-->
<div class="modal fade" id="ModalCreate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Debtor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('debtor.store') }}" method="POST">
        <div class="modal-body">
          @csrf
          <!-- Debtor Code -->
          <div class="form-group mb-3">
            <label for="cDebtorCode" class="form-label">DEBTOR CODE</label>
            <input type="text" class="form-control" maxlength="6" id="cDebtorCode" name="cDebtorCode" required>
          </div>
          <!-- Description -->
          <div class="form-group mb-3">
            <label for="cDebtorDesc" class="form-label">DEBTOR DETAILS</label>
            <input type="text" class="form-control" id="cDebtorDesc" maxlength="50" name="cDebtorDesc" required>
          </div>
          @if(Auth::user()->role === 'system admin')
        <div class="form-group mb-3">
        <label for="company_id" class="form-label">COMPANY</label>
        <select name="company_id" id="company_id" class="form-control" required>
          <option value="">Select Company</option>
          @foreach($companies as $company)
        <option value="{{ $company->id }}">{{ $company->description }}</option>
      @endforeach
        </select>
        </div>
      @else
      <input type="hidden" name="company_id" value="{{ Auth::user()->company_id }}">
    @endif
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Debtor</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
@foreach($debtors as $debtor)
  <div class="modal fade" id="ModalEdit{{ $debtor->iDebtorPk }}" tabindex="-1" role="dialog"
    aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title" id="editModalLabel">Edit Debtor</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      </div>
      <form action="{{ route('debtor.update', $debtor->iDebtorPk) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-body">
        <!-- Code -->
        <div class="form-group mb-3">
        <label for="cDebtorCode{{ $debtor->iDebtorPk }}" class="form-label">DEBTOR CODE</label>
        <input type="text" class="form-control" id="cDebtorCode{{ $debtor->iDebtorPk }}" maxlength="6"
          name="cDebtorCode" value="{{ $debtor->cDebtorCode }}" required>
        </div>
        <!-- Description -->
        <div class="form-group mb-3">
        <label for="cDebtorDesc{{ $debtor->iDebtorPk }}" class="form-label">DEBTOR DETAILS</label>
        <input type="text" class="form-control" id="cDebtorDesc{{ $debtor->iDebtorPk }}" maxlength="50"
          name="cDebtorDesc" value="{{ $debtor->cDebtorDesc }}" required>
        </div>

        <div class="form-group mb-3">
        @if(Auth::user()->role === 'system admin')
      <label for="company_id{{ $debtor->iDebtorPk }}" class="form-label">COMPANY</label>

      <select class="form-control" id="company_id{{ $debtor->iDebtorPk }}" name="company_id" required>
        @foreach($companies as $company)
      <option value="{{ $company->id }}" {{ $debtor->company_id == $company->id ? 'selected' : '' }}>
      {{ $company->description }}
      </option>
    @endforeach
      </select>
    @else
    <input type="hidden" name="company_id" value="{{ Auth::user()->company_id }}">
  @endif
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Update Debtor</button>
      </div>
      </form>
    </div>
    </div>
  </div>
@endforeach

<!-- Display Success Message -->
@if ($message = Session::get('success'))
  <div class="alert alert-success">
    <p>{{ $message }}</p>
  </div>
@endif
<div class="col-lg-6 d-flex">
  <button class="btn btn-success rounded-pill shadow-sm px-4" data-toggle="modal" data-target="#ModalCreate">
    <i class="fas fa-plus-circle"></i> Add Debtor
  </button>
</div>
<!-- Debtor Records Table -->
<div class="card-body">


  <table id="example1" class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>Debtor Code</th>
        <th>Debtor Details</th>
        @if(Auth::user()->role === 'system admin')
      <th>Company Name</th>
    @endif
        <th>Action</th>
      </tr>
    </thead>
    <tbody id="debtorTableBody">
      @foreach($debtors as $debtor)
      <tr>
      <td>{{ $debtor->cDebtorCode }}</td>
      <td>{{ $debtor->cDebtorDesc }}</td>

      @if(Auth::user()->role === 'system admin')
      <td>{{ $debtor->company->description ?? 'N/A' }}</td>
    @endif

      <td>
        <!-- Edit and Delete Icons -->
        <a class="btn btn-custom-edit btn-sm" data-toggle="modal" data-target="#ModalEdit{{ $debtor->iDebtorPk }}">
        <i class="fa fa-edit"></i>
        </a>
        <form action="{{ route('debtor.destroy', $debtor->iDebtorPk) }}" method="POST" style="display: inline-block;">
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
</div>

@endsection