@extends('layouts.app')

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
        <h2>Payment Method Code {{ $selectedCompanyDescription }}</h2>
    </div>
</div>




<!-- Modal Create -->
<div class="modal fade" id="ModalCreate" tabindex="-1" role="dialog" aria-labelledby="ModalCreateLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalCreateLabel">Add Payment Method</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('payments.store') }}" method="POST">
        @csrf
        <div class="modal-body">
            <!-- Code -->
            <div class="form-group mb-3">
                <label for="cPymtdCode" class="form-label">PAYMENT METHOD CODE</label>
                <input  type="text" class="form-control @error('payment code') is-invalid @enderror"  id="cPymtdCode" name="cPymtdCode" maxlength="6" required>
                @error('payment code')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <!-- Description -->
            <div class="form-group mb-3">
                <label for="cPymtdDesc" class="form-label">PAYMENT METHOD DETAILS</label>
                <input type="text" class="form-control @error('cPymtdDesc') is-invalid @enderror"  id="cPymtdDesc" maxlength="50" name="cPymtdDesc" required>
                @error('cPymtdDesc')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            @if(Auth::user()->role === 'system admin')
                @if($selectedCompany)
                    <input type="hidden" name="company_id" value="{{ $selectedCompany->id }}">
                    <div class="form-group mb-3">
                        <label for="company_id" class="form-label">Company</label>
                        <input type="text" class="form-control" value="{{ $selectedCompany->description }}" readonly>
                    </div>
                @endif
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

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<div class="col-lg-6 d-flex">
        <button class="btn btn-success rounded-pill shadow-sm px-4" data-toggle="modal" data-target="#ModalCreate">

        <i class="fas fa-plus-circle"></i> Add Payment Method
        </button>
    </div>

<!-- Payment Records Table -->
<div class="card-body">
    
    <div id="datatable">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Payment Code</th>
                    <th>Payment Details</th>
                    @if(Auth::user()->role === 'system admin')
                    <th>Company Name</th>
                    @endif
                    <th>Action</th>
                </tr>
            </thead>
            <tbody >
                @foreach($paymentMethods as $method)
                <tr>
                    <td>{{ $method->cPymtdCode }}</td>
                    <td>{{ $method->cPymtdDesc }}</td>
                    @if(Auth::user()->role === 'system admin')
                     <td>{{ $method->company->description ?? 'N/A' }}</td>
                     @endif
                    <td>
                        <!-- Edit Button with Modal -->
                        <button class="btn btn-custom-edit btn-sm" data-toggle="modal" data-target="#ModalEdit{{ $method->iPymtdPk }}">
                            <i class="fa fa-edit"></i>
                        </button>
                        
                        <!-- Delete Form -->
                        <form action="{{ route('payments.destroy', $method->iPymtdPk) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-custom-delete btn-sm" onclick="return confirm('Are you sure?')">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Modal Edit -->
                <div class="modal fade" id="ModalEdit{{ $method->iPymtdPk }}" tabindex="-1" role="dialog" aria-labelledby="ModalEditLabel{{ $method->iPymtdPk }}" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="ModalEditLabel{{ $method->iPymtdPk }}">Edit Payment Method</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form action="{{ route('payments.update', $method->iPymtdPk) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <!-- Code -->
                            <div class="form-group mb-3">
                                <label for="cPymtdCode" class="form-label">PAYMENT METHOD CODE</label>
                                <input type="text" class="form-control" name="cPymtdCode" maxlength="6" value="{{ $method->cPymtdCode }}" required>
                            </div>
                            <!-- Description -->
                            <div class="form-group mb-3">
                                <label for="cPymtdDesc" class="form-label">PAYMENT METHOD DETAILS</label>
                                <input type="text" class="form-control" name="cPymtdDesc" value="{{ $method->cPymtdDesc }}" required>
                            </div>

                            @if(Auth::user()->role === 'system admin')
                            @if($selectedCompany)
                                <input type="hidden" name="company_id" value="{{ $selectedCompany->id }}">
                                <div class="form-group mb-3">
                                    <label for="company_id" class="form-label">Company</label>
                                    <input type="text" class="form-control" value="{{ $selectedCompany->description }}" readonly>
                                </div>
                              @endif
                            @endif

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
<script>
    document.querySelectorAll('.delete-button').forEach(button => {
    button.addEventListener('click', function (e) {
        e.preventDefault();
        const form = this.closest('form');
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});

    </script>
@endsection
