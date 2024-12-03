

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
        <h2>Product List</h2>
    </div>
</div>


<!-- Modal Create-->
<div class="modal fade" id="ModalCreate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('product.store') }}" method="POST">
      <div class="modal-body">
      
            @csrf

            <!--code -->
            <div class="form-group mb-3">
                <label for="cProCode" class="form-label">PRODUCT/SERVICE CODE</label>
                <input type="text" class="form-control " id="cProCode" name="cProCode" maxlength="6" required>
            </div>
            <div class="form-group mb-3">
                <label for="cProName" class="form-label">PRODUCT/SERVICE NAME</label>
                <input type="text" class="form-control " id="cProName" name="cProName" maxlength="100" required>
            </div>
            <div class="form-group mb-3">
                <label for="cProType" class="form-label">PRODUCT/SERVICE TYPE</label>
                <input type="text" class="form-control" id="cProType" name="cProType" maxlength="50" required>
            </div>
            
            <!-- Description -->
            <div class="form-group mb-3">
                <label for="iProUom" class="form-label">UNIT OF MEASURE</label>
                <input type="text" class="form-control" id="iProUom" maxlength="10" name="iProUom" required>
            </div>

            <div class="form-group mb-3">
                <label for="yProPrice" class="form-label">PRICE</label>
                <input type="number" class="form-control" id="yProPrice" name="yProPrice"  step="0.01" required>
            </div>

            <div class="form-group mb-3">
               @if(Auth::user()->role === 'system admin')
            <label for="iProComfk" class="form-label">COMPANY</label>
          
                <select class="form-control" id="iProComfk" name="iProComfk" required>
                    <option value="">Select a Company</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->description }}</option>
                    @endforeach
                </select>
            @else
                <input type="hidden" name="iProComfk" value="{{ Auth::user()->iProComfk }}">
            @endif
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary me-2">Save Product</button>
      </div>
      </form>
    </div>
  </div>
</div>
     
   

@foreach($product as $products)
    <!-- Edit Modal -->
    <div class="modal fade" id="ModalEdit{{ $products->iProPk }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('product.update', $products->iProPk) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <!-- Product Code -->
                        <div class="form-group mb-3">
                            <label for="cProCode{{ $products->iProPk }}" class="form-label">PRODUCT/SERVICE CODE</label>
                            <input type="text" class="form-control" id="cProCode{{ $products->iProPk }}" name="cProCode" value="{{ $products->cProCode }}" maxlength="6" required>
                        </div>
                        <!-- Product Name -->
                        <div class="form-group mb-3">
                            <label for="cProName{{ $products->iProPk }}" class="form-label">PRODUCT/SERVICE NAME</label>
                            <input type="text" class="form-control" id="cProName{{ $products->iProPk }}" name="cProName" value="{{ $products->cProName }}" maxlength="100" required>
                        </div>
                        <!-- Product Type -->
                        <div class="form-group mb-3">
                            <label for="cProType{{ $products->iProPk }}" class="form-label">PRODUCT/SERVICE TYPE</label>
                            <input type="text" class="form-control" id="cProType{{ $products->iProPk }}" name="cProType" value="{{ $products->cProType }}" maxlength="50" required>
                        </div>
                        <!-- Unit of Measure -->
                        <div class="form-group mb-3">
                            <label for="iProUom{{ $products->iProPk }}" class="form-label">UNIT OF MEASURE</label>
                            <input type="text" class="form-control" id="iProUom{{ $products->iProPk }}" name="iProUom" value="{{ $products->iProUom }}" maxlength="10" required>
                        </div>
                        <!-- Product Price -->
                        <div class="form-group mb-3">
                            <label for="yProPrice{{ $products->iProPk }}" class="form-label">PRICE</label>
                            <input type="number" class="form-control" id="yProPrice{{ $products->iProPk }}" name="yProPrice" value="{{ $products->yProPrice }}" step="0.01" required>
                        </div>
                        <!-- Company Dropdown -->
                        @if(Auth::user()->role === 'system admin')
                            <div class="form-group mb-3">
                                <label for="iProComfk{{ $products->iProPk }}" class="form-label">COMPANY</label>
                                <select class="form-control" id="iProComfk{{ $products->iProPk }}" name="iProComfk" required>
                                    <option value="">Select a Company</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ $products->iProComfk == $company->id ? 'selected' : '' }}>
                                            {{ $company->description }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="iProComfk" value="{{ $products->iProComfk }}">
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
@endforeach

<!-- Display Success Message -->
@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif
<div class="col-lg-6 d-flex">
      <button class="btn btn-success rounded-pill shadow-sm px-4" data-toggle="modal" data-target="#ModalCreate">

      <i class="fas fa-plus-circle"></i> Add Product
      </button>
  </div >
<!-- Product Records Table -->
 <div class="card-body">
 
  
<table id="example1" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Product Code</th>
            <th>Product Name</th>
            <th>Product Type</th>
            <th>Unit Of Measure</th>
            <th>Product Price</th>
            @if(Auth::user()->role === 'system admin')
            <th>Company Name</th>
            @endif
            <th>Action</th>
        </tr>
    </thead>
    <tbody >
        @foreach($product as $products)
        <tr>
            <td>{{ $products->cProCode }}</td>
            <td>{{ $products->cProName }}</td>
            <td>{{ $products->cProType }}</td>
            <td>{{ $products->iProUom }}</td>
            <td>{{ $products->yProPrice }}</td>
            @if(Auth::user()->role === 'system admin')
                <td>{{ $products->company->description ?? 'N/A' }}</td>
            @endif
          
            <td>
                        <!-- Edit Button with Modal -->
                        <button class="btn btn-custom-edit btn-sm" data-toggle="modal" data-target="#ModalEdit{{ $products->iProPk}}">
                            <i class="fa fa-edit"></i>
                        </button>
                        
                        <form action="{{ route('product.destroy', $products->iProPk) }}" method="POST" style="display: inline-block;">
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

