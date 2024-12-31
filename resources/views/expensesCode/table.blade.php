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
    <h2>Expenses List</h2>
  </div>
</div>


<!-- Modal Create-->
<div class="modal fade" id="ModalCreate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Expenses</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('expensesCode.store') }}" method="POST">
        <div class="modal-body">

          @csrf

          <!--code -->
          <div class="form-group mb-3">
            <label for="cExpCode" class="form-label">EXPENSES CODE</label>
            <input type="text" class="form-control @error('expenses code') is-invalid @enderror" id="cExpCode"
              name="cExpCode" maxlength="6" required>
            @error('expenses code')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
          </div>

          <!-- Description -->
          <div class="form-group mb-3">
            <label for="cExpDesc" class="form-label">EXPENSES DETAILS</label>
            <input type="text" class="form-control" id="cExpDesc" maxlength="50" name="cExpDesc" required>
          </div>

          <div class="form-group mb-3">
            @if(Auth::user()->role === 'system admin')
        <label for="company_id" class="form-label">COMPANY</label>

        <select class="form-control" id="company_id" name="company_id" required>
          <option value="">Select a Company</option>
          @foreach($companies as $company)
        <option value="{{ $company->id }}">{{ $company->description }}</option>
      @endforeach
        </select>
      @else
    <input type="hidden" name="company_id" value="{{ Auth::user()->company_id }}">
  @endif
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary me-2">Save Expenses</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Edit Modal -->
@foreach($expense as $expenses)
  <div class="modal fade" id="ModalEdit{{ $expenses->iExpPk }}" tabindex="-1" role="dialog"
    aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title" id="editModalLabel">Edit Expense</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      </div>
      <form action="{{ route('expensesCode.update', $expenses->iExpPk) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-body">
        <!-- Code -->
        <div class="form-group mb-3">
        <label for="cExpCode{{ $expenses->iExpPk }}" class="form-label">EXPENSES CODE</label>
        <input type="text" class="form-control" id="cExpCode{{ $expenses->iExpPk }}" name="cExpCode" maxlength="6"
          value="{{ $expenses->cExpCode }}" required>
        </div>

        <!-- Description -->
        <div class="form-group mb-3">
        <label for="cExpDesc{{ $expenses->iExpPk }}" class="form-label">EXPENSES DETAILS</label>
        <input type="text" class="form-control" id="cExpDesc{{ $expenses->iExpPk }}" name="cExpDesc" maxlength="50"
          value="{{ $expenses->cExpDesc }}" required>
        </div>

        <div class="form-group mb-3">
        @if(Auth::user()->role === 'system admin')
      <label for="company_id{{ $expenses->iExpPk }}" class="form-label">COMPANY</label>

      <select class="form-control" id="company_id{{ $expenses->iExpPk}}" name="company_id" required>
        @foreach($companies as $company)
      <option value="{{ $company->id }}" {{ $expenses->company_id == $company->id ? 'selected' : '' }}>
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
        <button type="submit" class="btn btn-primary">Update Expense</button>
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

    <i class="fas fa-plus-circle"></i> Add Expenses
  </button>
</div>
<!-- Expenses Records Table -->
<div class="card-body">


  <table id="example1" class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>Expenses Code</th>
        <th>Expenses Details</th>
        @if(Auth::user()->role === 'system admin')
      <th>Company Name</th>
    @endif
        <th>Action</th>
      </tr>
    </thead>
    <tbody id="expensesTableBody">
      @foreach($expense as $expenses)
      <tr>
      <td>{{ $expenses->cExpCode }}</td>
      <td>{{ $expenses->cExpDesc }}</td>
      @if(Auth::user()->role === 'system admin')
      <td>{{ $expenses->company->description ?? 'N/A' }}</td>
    @endif

      <td>
        <!-- Edit and Delete Icons -->
        <a class="btn btn-custom-edit btn-sm" data-toggle="modal" data-target="#ModalEdit{{ $expenses->iExpPk }}">
        <i class="fa fa-edit"></i>
        </a>
        <form action="{{ route('expensesCode.destroy', $expenses->iExpPk) }}" method="POST"
        style="display: inline-block;">
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