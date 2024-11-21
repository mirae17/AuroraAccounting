

@section('content')
<style>
    .table thead th, .table td, .table th {
        text-align: center;
        vertical-align: middle;
    }
</style>

<div class="row mb-3 align-items-center">
    <div class="col-lg-6">
        <h2>Expenses List</h2>
    </div>
</div>


<!-- Modal Create-->
<div class="modal fade" id="ModalCreate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Expenses</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('expenses.store') }}" method="POST">
      <div class="modal-body">
      
            @csrf

            <!--code -->
            <div class="form-group mb-3">
                <label for="cExpCode" class="form-label">EXPENSES CODE</label>
                <input type="text" class="form-control" id="cExpCode" name="cExpCode" required>
            </div>

            <!-- Description -->
            <div class="form-group mb-3">
                <label for="cExpDesc" class="form-label">EXPENSES DETAILS</label>
                <input type="text" class="form-control" id="cExpDesc" name="cExpDesc" required>
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
<div class="modal fade" id="ModalEdit{{ $expenses->iExpPk }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Expense</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('expenses.update', $expenses->iExpPk) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <!-- Code -->
          <div class="form-group mb-3">
            <label for="cExpCode{{ $expenses->iExpPk }}" class="form-label">EXPENSES CODE</label>
            <input type="text" class="form-control" id="cExpCode{{ $expenses->iExpPk }}" name="cExpCode" value="{{ $expenses->cExpCode }}" required>
          </div>

          <!-- Description -->
          <div class="form-group mb-3">
            <label for="cExpDesc{{ $expenses->iExpPk }}" class="form-label">EXPENSES DETAILS</label>
            <input type="text" class="form-control" id="cExpDesc{{ $expenses->iExpPk }}" name="cExpDesc" value="{{ $expenses->cExpDesc }}" required>
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

<!-- Expenses Records Table -->
 <div class="card-body">
  <div class="col-lg-6 d-flex">
      <button class="btn btn-success rounded-pill shadow-sm px-4" data-toggle="modal" data-target="#ModalCreate">

      <i class="fas fa-plus-circle"></i> Add Expenses
      </button>
  </div >
  
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
                <form action="{{ route('expenses.destroy', $expenses->iExpPk) }}" method="POST" style="display: inline-block;">
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

