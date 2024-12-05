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
        <h2>Inventory List</h2>
    </div>
</div>


<!-- Modal Create-->
<div class="modal fade" id="ModalCreate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Inventory</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('inventory.store') }}" method="POST">
                <div class="modal-body">

                    @csrf

                    <!--code -->
                    <div class="form-group mb-3">
                        <label for="cInvCode" class="form-label">INVENTORY CODE</label>
                        <input type="text" class="form-control " id="cInvCode" name="cInvCode" maxlength="6" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="cInvName" class="form-label">INVENTORY NAME</label>
                        <input type="text" class="form-control " id="cInvName" name="cInvName" maxlength="100" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="cInvType" class="form-label">INVENTORY TYPE</label>
                        <input type="text" class="form-control" id="cInvType" name="cInvType" maxlength="50" required>
                    </div>

                    <!-- Description -->
                    <div class="form-group mb-3">
                        <label for="iInvUom" class="form-label">UNIT OF MEASURE</label>
                        <input type="text" class="form-control" id="iInvUom" maxlength="10" name="iInvUom" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="yInvPrice" class="form-label">PRICE PER UNIT</label>
                        <input type="number" class="form-control" id="yProPrice" name="yInvPrice" step="0.01" required>
                    </div>
                    <div class="form-group mb-3">
                        @if(Auth::user()->role === 'system admin')
                            <label for="iInvComfk" class="form-label">Company</label>

                            <select class="form-control" id="iInvComfk" name="iInvComfk" required>
                                <option value="">Select a Company</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->description }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="hidden" name="iInvComfk" value="{{ Auth::user()->iInvComfk }}">
                        @endif
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary me-2">Save Inventory</button>
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
<div class="col-lg-6 d-flex">
    <button class="btn btn-success rounded-pill shadow-sm px-4" data-toggle="modal" data-target="#ModalCreate">

        <i class="fas fa-plus-circle"></i> Add Inventory
    </button>
</div>
<!-- Inventorys Records Table -->
<div class="card-body">


    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Inventory Code</th>
                <th>Inventory Name</th>
                <th>Inventory Type</th>
                <th>Unit Of Measure</th>
                <th>Price Per Unit</th>
                @if(Auth::user()->role === 'system admin')
                    <th>Company Name</th>
                @endif
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="inventoryTableBody">
            @foreach($inventory as $item)
                <tr>
                    <td>{{ $item->cInvCode }}</td>
                    <td>{{ $item->cInvName }}</td>
                    <td>{{ $item->cInvType }}</td>
                    <td>{{ $item->iInvUom }}</td>
                    <td>{{$item->yInvPrice}}</td>
                    @if(Auth::user()->role === 'system admin')
                        <td>{{ $item->company->description ?? 'N/A' }}</td>
                    @endif

                    <td>
                        <!-- Edit Button with Modal -->
                        <button class="btn btn-custom-edit btn-sm" data-toggle="modal"
                            data-target="#ModalEdit{{ $item->iInvPK}}">
                            <i class="fa fa-edit"></i>
                        </button>

                        <form action="{{ route('inventory.destroy', $item->iInvPK) }}" method="POST"
                            style="display: inline-block;">
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
</div>

<!-- Edit Modal -->
<div class="modal fade" id="ModalEdit{{ $item->iInvPK}}" tabindex="-1" role="dialog"
    aria-labelledby="editModalLabel{{ $item->iInvPK}}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $item->iInvPK}}">Edit Inventory</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('inventory.update', $item->iInvPK) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">

                    <!-- Inventory Code -->
                    <div class="form-group mb-3">
                        <label for="cInvCode{{ $item->iInvPK}}" class="form-label">INVENTORY CODE</label>
                        <input type="text" class="form-control" id="cInvCode{{ $item->iInvPK}}" name="cInvCode"
                            value="{{ $item->cInvCode }}" maxlength="6" required>
                    </div>

                    <!-- Inventory Name -->
                    <div class="form-group mb-3">
                        <label for="cInvName{{ $item->iInvPK}}" class="form-label">INVENTORY NAME</label>
                        <input type="text" class="form-control" id="cInvName{{ $item->iInvPK}}" name="cInvName"
                            value="{{ $item->cInvName }}" maxlength="100" required>
                    </div>

                    <!-- Inventory Type -->
                    <div class="form-group mb-3">
                        <label for="cInvType{{ $item->iInvPK}}" class="form-label">INVENTORY TYPE</label>
                        <input type="text" class="form-control" id="cInvType{{ $item->iInvPK}}" name="cInvType"
                            value="{{ $item->cInvType }}" maxlength="50" required>
                    </div>

                    <!-- Unit of Measure -->
                    <div class="form-group mb-3">
                        <label for="iInvUom{{ $item->iInvPK}}" class="form-label">UNIT OF MEASURE</label>
                        <input type="text" class="form-control" id="iInvUom{{ $item->iInvPK}}" name="iInvUom"
                            value="{{ $item->iInvUom }}" maxlength="10" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="yInvPrice{{ $item->iInvPK }}" class="form-label">PRICE PER UNIT</label>
                        <input type="number" class="form-control" id="yProPrice{{ $item->iInvPK }}" name="yInvPrice"
                            value="{{ $item->yInvPrice }}" step="0.01" required>
                    </div>
                    <!-- Company -->
                    @if(Auth::user()->role === 'system admin')
                        <div class="form-group mb-3">
                            <label for="iInvComfk{{ $item->iInvPK}}" class="form-label">Company</label>
                            <select class="form-control" id="iInvComfk{{ $item->iInvPK}}" name="iInvComfk" required>
                                <option value="">Select a Company</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ $item->iInvComfk == $company->id ? 'selected' : '' }}>
                                        {{ $company->description }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <input type="hidden" name="iInvComfk" value="{{ Auth::user()->iInvComfk }}">
                    @endif

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection