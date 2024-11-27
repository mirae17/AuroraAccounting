@section('content')
<style>
    .table thead th, .table td, .table th {
        text-align: center;
        vertical-align: middle;
    }
</style>

<div class="row mb-3 align-items-center">
    <div class="col-lg-6">
        <h2>Employee List</h2>
    </div>
</div>

<!-- Modal Create-->
<div class="modal fade" id="ModalCreate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Employee</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('employees.store') }}" method="POST">
      <div class="modal-body">
            @csrf
            <!-- Employee Code -->
            <div class="form-group mb-3">
                <label for="cEmpNo" class="form-label">EMPLOYEE NUMBER</label>
                <input type="text" class="form-control" id="cEmpNo" name="cEmpNo" required>
            </div>
            <!-- Description -->
            <div class="form-group mb-3">
                <label for="cEmpName" class="form-label">EMPLOYEE NAME</label>
                <input type="text" class="form-control" id="cEmpName" name="cEmpName" required>
            </div>
            <div class="form-group mb-3">
               @if(Auth::user()->role === 'system admin')
            <label for="company_id" class="form-label">Company</label>
          
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
        <button type="submit" class="btn btn-primary">Save Employee</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
@foreach($employees as $emp)
<div class="modal fade" id="ModalEdit{{ $emp->iEmpmasPk }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Employee</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('employees.update', $emp->iEmpmasPk) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <!-- Code -->
          <div class="form-group mb-3">
            <label for="cEmpNo{{ $emp->iEmpmasPK }}" class="form-label">EMPLOYEE NUMBER</label>
            <input type="text" class="form-control" id="cEmpNo{{ $emp->iEmpmasPK }}" name="cEmpNo" value="{{ $emp->cEmpNo }}" required>
          </div>
          <!-- Description -->
          <div class="form-group mb-3">
            <label for="cEmpName{{ $emp->iEmpmasPK }}" class="form-label">EMPLOYEE NAME</label>
            <input type="text" class="form-control" id="cEmpName{{ $emp->iEmpmasPK }}" name="cEmpName" value="{{ $emp->cEmpName }}" required>
          </div>

          <div class="form-group mb-3">
          @if(Auth::user()->role === 'system admin')
            <label for="company_id{{ $emp->iEmpmasPK }}" class="form-label">Company</label>
           
                <select class="form-control" id="company_id{{ $emp->iEmpmasPK }}" name="company_id" required>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ $emp->company_id == $company->id ? 'selected' : '' }}>
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
          <button type="submit" class="btn btn-primary">Update Employee</button>
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

<!-- Employee Records Table -->
<div class="card-body">
  <div class="col-lg-6 d-flex">
      <button class="btn btn-success rounded-pill shadow-sm px-4" data-toggle="modal" data-target="#ModalCreate">
      <i class="fas fa-plus-circle"></i> Add Employee
      </button>
  </div>
  
  <table id="example1" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Employee Number</th>
            <th>Employee Name</th>
            @if(Auth::user()->role === 'system admin')
            <th>Company Name</th>
            @endif
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="empTableBody">
        @foreach($employees as $emp)
        <tr>
            <td>{{ $emp->cEmpNo }}</td>
            <td>{{ $emp->cEmpName }}</td>
           
            @if(Auth::user()->role === 'system admin')
                <td>{{ $emp->company->description ?? 'N/A' }}</td>
            @endif
           
            <td>
            <a class="btn btn-custom-edit btn-sm" data-toggle="modal" data-target="#ModalEdit{{ $emp->iEmpmasPk }}">
                    <i class="fa fa-edit"></i>
                </a>
                <form action="{{ route('employees.destroy', $emp->iEmpmasPk) }}" method="POST" style="display: inline-block;">
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
