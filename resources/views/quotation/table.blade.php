

@section('content')
<style>
    .table thead th, .table td, .table th {
        text-align: center;
        vertical-align: middle;
    }
</style>

<div class="row mb-3 align-items-center">
    <div class="col-lg-6">
    <br>
        <h2>Supplier List</h2>
    </div>
</div>

<!-- Modal Create -->
<div class="modal fade" id="ModalCreate" tabindex="-1" role="dialog" aria-labelledby="ModalCreateLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalCreateLabel">Add Supplier</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('suppliers.store') }}" method="POST">
        @csrf
        <div class="modal-body">
            <!-- Code -->
            <div class="form-group mb-3">
                <label for="iSuppCode" class="form-label">SUPPLIER CODE</label>
                <input type="text" class="form-control" id="iSuppCode" maxlength="6" name="iSuppCode" required>
            </div>
            <!-- Description -->
            <div class="form-group mb-3">
                <label for="iSuppDesc" class="form-label">SUPPLIER DETAILS</label>
                <input type="text" class="form-control" id="iSuppDesc" maxlength="50"  name="iSuppDesc" required>
            </div>
            @if(Auth::user()->role === 'system admin')
                <div class="form-group mb-3">
                    <label for="company_id" class="form-label">Company</label>
                    <select class="form-control" id="company_id" name="company_id" required>
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
            <button type="submit" class="btn btn-primary me-2">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Display Success Message -->
@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

<button class="btn btn-success rounded-pill shadow-sm px-4" data-toggle="modal" data-target="#ModalCreate">

        <i class="fas fa-plus-circle"></i> Add Supplier
        </button>
<!-- Payment Records Table -->
<div class="card-body">

    
       
    <div id="datatable">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Supplier Code</th>
                    <th>Supplier Details</th>
                    @if(Auth::user()->role === 'system admin')
                    <th>Company Name</th>
                    @endif
                    <th>Action</th>
                </tr>
            </thead>
            <tbody >
                @foreach($suppliers as $supply)
                <tr>
                    <td>{{ $supply->iSuppCode }}</td>
                    <td>{{ $supply->iSuppDesc }}</td>
                    @if(Auth::user()->role === 'system admin')
                       <td>{{ $supply->company->description ?? 'N/A' }}</td>
                     @endif
                    <td>
                        <!-- Edit Button with Modal -->
                        <button class="btn btn-custom-edit btn-sm" data-toggle="modal" data-target="#ModalEdit{{ $supply->iSuppPk }}">
                            <i class="fa fa-edit"></i>
                        </button>
                        
                        <!-- Delete Form -->
                        <form action="{{ route('suppliers.destroy', $supply->iSuppPk) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-custom-delete btn-sm" onclick="return confirm('Are you sure?')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="ModalEdit{{ $supply->iSuppPk }}" tabindex="-1" role="dialog" aria-labelledby="ModalEditLabel{{ $supply->iSuppPk }}" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="ModalEditLabel{{ $supply->iSuppPk }}">Edit Payment Method</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form action="{{ route('suppliers.update', $supply->iSuppPk) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <!-- Code -->
                            <div class="form-group mb-3">
                                <label for="iSuppCode" class="form-label">SUPPLIER CODE</label>
                                <input type="text" class="form-control" name="iSuppCode" maxlength="6" value="{{ $supply->iSuppCode }}" required>
                            </div>
                            <!-- Description -->
                            <div class="form-group mb-3">
                                <label for="iSuppDesc" class="form-label">SUPPLIER DETAILS</label>
                                <input type="text" class="form-control" name="iSuppDesc" maxlength="50"  value="{{ $supply->iSuppDesc }}" required>
                            </div>
                            <div class="form-group mb-3">
                                @if(Auth::user()->role === 'system admin')
                                    <label for="company_id{{ $supply->iSuppPk }}" class="form-label">Company</label>
                                
                                        <select class="form-control" id="company_id{{ $supply->iSuppPk }}" name="company_id" required>
                                            @foreach($companies as $company)
                                                <option value="{{ $company->id }}" {{ $supply->company_id == $company->id ? 'selected' : '' }}>
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
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
