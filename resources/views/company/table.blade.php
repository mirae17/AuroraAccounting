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
        <h2>Debtor List</h2>
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
            
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="debtorTableBody">
        @foreach($companies as $comp)
        <tr>
            <td>{{ $comp->code }}</td>
            <td>{{ $comp->description }}</td>
           
            <td>
                <form action="{{ route('company.destroy', $comp->id) }}" method="POST" style="display: inline-block;">
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
