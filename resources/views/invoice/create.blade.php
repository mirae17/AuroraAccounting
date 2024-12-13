@extends('layouts.template')

@section('content')
<br>
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow-lg p-4" style="max-width: 2500px; width: 100%; border-radius: 15px;">
        <div class="text-center mb-4">
            <form action="{{ route('invoice.store') }}" method="POST">
                @csrf
                @if(isset($companyMaintenance))
                    <!-- Display Company Information -->
                    <img id="company-logo" src="{{ asset('storage/' . $companyMaintenance->iCompMainLogo) }}"
                        alt="Company Logo" class="mb-3" style="max-height: 100px;">
                    <h2 id="company-name">{{ $companyMaintenance->company->description }}</h2>
                    <p id="company-address">{{ $companyMaintenance->iCompMainAddress }}</p>
                    <p id="company-contact">{{ $companyMaintenance->iCompMainPhoneNo }} |
                        {{ $companyMaintenance->iCompMainEmail }}
                    </p>

                    <!-- Pass the Company ID -->
                    <input type="hidden" name="iInvcComfk" value="{{ $companyMaintenance->company->id }}">
                @else
                    @if(Auth::user()->role === 'system admin')
                        <!-- System Admin: Allow company selection -->
                        <div class="form-group">
                            <label for="iInvcComfk">Select Company:</label>
                            <select name="iInvcComfk" id="iInvcComfk" class="form-control">
                                <option value="">-- Select a Company --</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->description }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <!-- Regular User: Auto-detect company -->
                        <input type="hidden" name="iInvcComfk" value="{{ Auth::user()->company_id }}">
                    @endif
                @endif

        </div>


        <div class="row mb-4">
            <!-- Left: Customer Details -->
            <div class="col-md-6">
                <h5>Bill To:</h5>
                <div class="form-group">
                    <label for="customer-select">Select Customer:</label>
                    <select name="iInvcCustDfk" id="customer-select" class="form-control"
                        onchange="displayCustomerDetails(this)">
                        <option value="">-- Select Customer --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->iCustDPk }}" data-name="{{ $customer->cCustDName }}"
                                data-company="{{ $customer->cCustDCompName }}" data-address="{{ $customer->cCustDAddress }}"
                                data-phone="{{ $customer->cCustDCompOfficeNo }}"
                                data-email="{{ $customer->cCustDCompEmail }}">
                                {{ $customer->cCustDName }} ({{ $customer->cCustDCompName }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div id="customer-details" class="mt-3" style="display: none;">
                    <p><strong>Attention:</strong> <span id="customer-name"></span></p>
                    <p><strong>Company:</strong> <span id="customer-company"></span></p>
                    <p><strong>Address:</strong> <span id="customer-address"></span></p>
                    <p><strong>Phone:</strong> <span id="customer-phone"></span></p>
                    <p><strong>Email:</strong> <span id="customer-email"></span></p>
                </div>
            </div>

            <!-- Right: Invctation Details -->
            <div class="col-md-6 text-end">
                <h5>Invoice Details</h5>
                <div class="form-group">
                    <label for="iInvcNo">Invoice No:</label>
                    <input type="text" name="iInvcNo" id="iInvcNo" class="form-control" value="{{ $newInvoiceNumber}}"
                        readonly>
                </div>
                <div class="form-group">
                    <label for="quotation-date">Date:</label>
                    <input type="date" name="dInvcdate" id="quotation-date" class="form-control"
                        value="{{ date('Y-m-d') }}" required>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Items Table -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Select</th>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Price per Unit</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="items-table">

                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6"></td>
                        <td><strong>Subtotal: <span id="subtotal">0.00</span></strong></td>
                        <input type="hidden" name="yInvcSubtotal" id="hidden-subtotal" value="0">
                    </tr>
                </tfoot>
            </table>
            <button type="button" class="btn btn-primary" onclick="addItem()">+ Add Item</button>
        </div>


        <!-- Discount, Tax, and Shipping -->
        <div class="row">
            <div class="col-md-6"></div> <!-- Empty column for alignment -->
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="discount" class="form-label">Discount (%):</label>
                    <input type="number" id="discount" name="iInvcDiscount" class="form-control"
                        oninput="calculateTotal()" placeholder="Enter Discount">
                </div>
                <div class="form-group mb-3">
                    <label for="tax" class="form-label">Tax (%):</label>
                    <input type="number" id="additional-tax" name="iInvcTax" class="form-control"
                        oninput="calculateTotal()" placeholder="Enter Tax">
                </div>
                <div class="form-group mb-3">
                    <label for="shipping" class="form-label">Shipping:</label>
                    <input type="number" id="shipping" name="iInvcShipping" class="form-control"
                        oninput="calculateTotal()" placeholder="Enter Shipping Cost">
                </div>
                <!-- Total -->
                <div class="row mt-3">
                    <div class="col-md-12 text-end">
                        <h4>Total Amount: <span id="total-amount">0.00</span></h4>
                        <input type="hidden" name="yInvcTotalPayment" id="hidden-total-amount" value="0.00">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="itemModalLabel">Select Product or Inventory</h5>

                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-tabs" id="itemTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="products-tab" data-bs-toggle="tab"
                                    data-bs-target="#products" type="button" role="tab" aria-controls="products"
                                    aria-selected="true">
                                    Products
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="inventory-tab" data-bs-toggle="tab"
                                    data-bs-target="#inventory" type="button" role="tab" aria-controls="inventory"
                                    aria-selected="false">
                                    Inventory
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content" id="itemTabContent">
                            <!-- Products Tab -->
                            <div class="tab-pane fade show active" id="products" role="tabpanel"
                                aria-labelledby="products-tab">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product Code</th>
                                            <th>Description</th>
                                            <th>Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($products as $product)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $product->cProCode }}</td>
                                                <td>{{ $product->cProName }}</td>
                                                <td>{{ $product->yProPrice }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-success btn-sm"
                                                        onclick="addItemToTable('{{ $product->cProCode }}', '{{ $product->cProName }}', {{ $product->yProPrice }})">
                                                        Add
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Inventory Tab -->
                            <div class="tab-pane fade" id="inventory" role="tabpanel" aria-labelledby="inventory-tab">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Item Code</th>
                                            <th>Description</th>
                                            <th>Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($inventory as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->cInvCode}}</td>
                                                <td>{{ $item->cInvName }}</td>
                                                <td>{{ $item->yInvPrice }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-success btn-sm"
                                                        onclick="addItemToTable('{{ $item->cInvCode }}', '{{ $item->cInvName }}', {{ $item->yInvPrice }})">
                                                        Add
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-success">Save Invoice</button>
        </div>

        </form>
    </div>
</div>
<br>
@include('quotations.script')

<style>
    .nav-link.active {
        background-color: #007bff;
        /* Customize with your preferred color */
        color: white;
        font-weight: bold;
    }
</style>
@endsection