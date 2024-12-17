@extends('layouts.template')

@section('content')
<div class="container">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">Create Quotation</h2>

        <form action="{{ route('quotations.store') }}" method="POST" id="quotation-form">
            @csrf

            <!-- Step 1: Customer & Quotation Details -->
            <div id="step-1">
                <h4>Step 1: Customer & Quotation Details</h4>
                <div class="row">
                    <!-- Customer Details -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="customer-select">Select Customer:</label>
                            <select name="iQuoCustDfk" id="customer-select" class="form-control"
                                onchange="displayCustomerDetails(this)" required>
                                <option value="">-- Select Customer --</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->iCustDPk }}" data-name="{{ $customer->cCustDName }}"
                                        data-company="{{ $customer->cCustDCompName }}"
                                        data-address="{{ $customer->cCustDAddress }}"
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

                    <!-- Quotation Details -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Quotation No:</label>
                            <input type="text" name="iQuoNo" class="form-control" value="{{ $newQuotationNumber }}"
                                readonly>
                        </div>
                        <div class="form-group">
                            <label>Quotation Date:</label>
                            <input type="date" name="dQuodate" class="form-control" value="{{ date('Y-m-d') }}"
                                required>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary mt-3" onclick="nextStep(2)">Next Step</button>
            </div>

            <!-- Step 2: Quotation Items -->
            <div id="step-2" style="display: none;">
                <h4>Step 2: Add Quotation Items</h4>

                <!-- Items Table -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="items-table"></tbody>
                </table>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#itemModal">+ Add
                    Item</button>

                <!-- Next & Back Buttons -->
                <button type="button" class="btn btn-secondary mt-3" onclick="prevStep(1)">Back</button>
                <button type="button" class="btn btn-primary mt-3" onclick="nextStep(3)">Next Step</button>
            </div>

            <!-- Step 3: Summary & Submission -->
            <!-- Step 3: Summary & Submission -->
            <div id="step-3" style="display: none;">
                <h4>Step 3: Summary</h4>

                <!-- Company Details -->
                <div class="card mb-3">
                    <div class="card-header">Company Details</div>
                    <div class="card-body">
                        <p><strong>Logged-in User:</strong> {{ auth()->user()->name }}</p>
                        <p><strong>Company:</strong> {{ auth()->user()->description }}</p>
                        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                    </div>
                </div>

                <!-- Customer Details -->
                <div class="card mb-3">
                    <div class="card-header">Customer Details</div>
                    <div class="card-body">
                        <p><strong>Attention:</strong> <span id="summary-customer-name"></span></p>
                        <p><strong>Company:</strong> <span id="summary-customer-company"></span></p>
                        <p><strong>Address:</strong> <span id="summary-customer-address"></span></p>
                        <p><strong>Phone:</strong> <span id="summary-customer-phone"></span></p>
                        <p><strong>Email:</strong> <span id="summary-customer-email"></span></p>
                    </div>
                </div>

                <!-- Quotation Details -->
                <div class="card mb-3">
                    <div class="card-header">Quotation Details</div>
                    <div class="card-body">
                        <p><strong>Quotation No:</strong> {{ $newQuotationNumber }}</p>
                        <p><strong>Quotation Date:</strong> {{ date('Y-m-d') }}</p>
                    </div>
                </div>

                <!-- Items List -->
                <div class="card mb-3">
                    <div class="card-header">Items</div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="summary-items-table"></tbody>
                        </table>
                    </div>
                </div>

                <!-- Totals -->
                <div class="card">
                    <div class="card-header">Totals</div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-6"><strong>Subtotal:</strong></div>
                            <div class="col-md-6 text-right" name="yQuoSubtotal ">RM <span
                                    id="summary-subtotal">0.00</span></div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6"><strong>Tax (%) :</strong></div>
                            <div class="col-md-6 text-right">
                                <input type="number" id="iQuoTax" name="iQuoTax" class="form-control" min="0" value="0"
                                    oninput="calculateFinalTotal()">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6"><strong>Discount (%) :</strong></div>
                            <div class="col-md-6 text-right">
                                <input type="number" id="iQuoDiscount" name="iQuoDiscount" class="form-control" min="0"
                                    value="0" oninput="calculateFinalTotal()">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6"><strong>Shipping (RM):</strong></div>
                            <div class="col-md-6 text-right">
                                <input type="number" id="iQuoShipping" name="iQuoShipping" class="form-control" min="0"
                                    value="0" oninput="calculateFinalTotal()">
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6"><strong>Total:</strong></div>
                            <div class="col-md-6 text-right" id="yQuoTotalPayment" name="yQuoTotalPayment"><strong>RM
                                    <span id="final-total">0.00</span></strong>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Hidden inputs for subtotal and total -->
                <input type="hidden" name="yQuoSubtotal" id="hidden-subtotal">
                <input type="hidden" name="yQuoTotalPayment" id="hidden-total-payment">

                <!-- Navigation Buttons -->
                <button type="button" class="btn btn-secondary mt-3" onclick="prevStep(2)">Back</button>
                <button type="submit" class="btn btn-success mt-3">Submit Quotation</button>
            </div>

        </form>
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
                        <button class="nav-link" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory"
                            type="button" role="tab" aria-controls="inventory" aria-selected="false">
                            Inventory
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="itemTabContent">
                    <!-- Products Tab -->
                    <div class="tab-pane fade show active" id="products" role="tabpanel" aria-labelledby="products-tab">
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
<script>
    // Call `updateSummary` when navigating to Step 3
    function nextStep(step) {
        document.getElementById(`step-${step - 1}`).style.display = 'none';
        document.getElementById(`step-${step}`).style.display = 'block';

        if (step === 3) {
            updateSummary();
        }
    }
    function prevStep(step) {
        document.getElementById(`step-${step + 1}`).style.display = 'none';
        document.getElementById(`step-${step}`).style.display = 'block';
    }

    // Add Items to Table
    function addItemToTable(code, name, price) {
        let table = document.getElementById('items-table');
        let rowIndex = table.rows.length; // Row index for unique input names
        let row = `
        <tr>
            <td>${rowIndex + 1}</td>
            <td>${code}</td>
            <td>${name}</td>
            <td>
                <input type="number" name="items[${rowIndex}][quantity]" class="form-control" value="1" min="1" 
                    oninput="updateTotal(this, ${price})">
            </td>
            <td>
                ${price.toFixed(2)}
                <input type="hidden" name="items[${rowIndex}][price]" value="${price}">
            </td>
            <td>
                <span class="item-total">${price.toFixed(2)}</span>
                <input type="hidden" name="items[${rowIndex}][total]" value="${price}">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">Remove</button>
            </td>
            <!-- Hidden Inputs for Code and Description -->
            <input type="hidden" name="items[${rowIndex}][code]" value="${code}">
            <input type="hidden" name="items[${rowIndex}][description]" value="${name}">
        </tr>
    `;
        table.insertAdjacentHTML('beforeend', row);
        calculateSubtotal();
    }


    // Update Totals
    function updateTotal(input, price) {
        let quantity = input.value;
        let totalCell = input.closest('tr').querySelector('.item-total');
        totalCell.textContent = (quantity * price).toFixed(2);
        calculateSubtotal();
    }

    function calculateSubtotal() {
        let total = 0;
        document.querySelectorAll('.item-total').forEach(cell => {
            total += parseFloat(cell.textContent);
        });
        document.getElementById('hidden-subtotal').value = total;
    }

    function removeItem(button) {
        button.closest('tr').remove();
        calculateSubtotal();
    }
    function displayCustomerDetails(select) {
        const selectedOption = select.options[select.selectedIndex];

        if (selectedOption.value) {
            document.getElementById("customer-details").style.display = "block";
            document.getElementById("customer-name").innerText = selectedOption.getAttribute("data-name");
            document.getElementById("customer-company").innerText = selectedOption.getAttribute("data-company");
            document.getElementById("customer-address").innerText = selectedOption.getAttribute("data-address");
            document.getElementById("customer-phone").innerText = selectedOption.getAttribute("data-phone");
            document.getElementById("customer-email").innerText = selectedOption.getAttribute("data-email");
        } else {
            document.getElementById("customer-details").style.display = "none";
            clearCustomerDetails();
        }
    }

    function clearCustomerDetails() {
        document.getElementById("customer-name").innerText = "";
        document.getElementById("customer-company").innerText = "";
        document.getElementById("customer-address").innerText = "";
        document.getElementById("customer-phone").innerText = "";
        document.getElementById("customer-email").innerText = "";
    }
    // Update Summary Customer Details
    function updateSummary() {
        // Update Customer Details
        document.getElementById('summary-customer-name').innerText = document.getElementById('customer-name').innerText;
        document.getElementById('summary-customer-company').innerText = document.getElementById('customer-company').innerText;
        document.getElementById('summary-customer-address').innerText = document.getElementById('customer-address').innerText;
        document.getElementById('summary-customer-phone').innerText = document.getElementById('customer-phone').innerText;
        document.getElementById('summary-customer-email').innerText = document.getElementById('customer-email').innerText;

        // Clear previous summary table rows
        const summaryTable = document.getElementById('summary-items-table');
        summaryTable.innerHTML = '';

        let subtotal = 0;

        // Rebuild summary table dynamically without inputs
        document.querySelectorAll('#items-table tr').forEach((row, index) => {
            const cells = row.querySelectorAll('td');

            if (cells.length) {
                const code = cells[1].innerText;
                const description = cells[2].innerText;
                const quantity = row.querySelector('input').value;
                const price = parseFloat(cells[4].innerText);
                const total = parseFloat(quantity) * price;

                subtotal += total;

                // Add row to summary table
                const newRow = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${code}</td>
                    <td>${description}</td>
                    <td>${quantity}</td>
                    <td>${price.toFixed(2)}</td>
                    <td>${total.toFixed(2)}</td>
                </tr>
            `;
                summaryTable.insertAdjacentHTML('beforeend', newRow);
            }
        });

        // Update hidden inputs and display
        document.getElementById('summary-subtotal').innerText = subtotal.toFixed(2);
        document.getElementById('hidden-subtotal').value = subtotal;

        calculateFinalTotal();
    }


    // Auto-Calculate Totals (Subtotal, Tax, Discount, Shipping)
    function calculateFinalTotal() {
        let subtotal = 0;
        document.querySelectorAll('.item-total').forEach(cell => {
            subtotal += parseFloat(cell.textContent);
        });

        const taxRate = parseFloat(document.getElementById('iQuoTax').value) || 0;
        const discountRate = parseFloat(document.getElementById('iQuoDiscount').value) || 0;
        const shipping = parseFloat(document.getElementById('iQuoShipping').value) || 0;

        const taxAmount = (subtotal * taxRate) / 100;
        const discountAmount = (subtotal * discountRate) / 100;

        const finalTotal = subtotal + taxAmount - discountAmount + shipping;

        // Update display values
        document.getElementById('summary-subtotal').innerText = subtotal.toFixed(2);
        document.getElementById('final-total').innerText = finalTotal.toFixed(2);

        // Update hidden input values
        document.getElementById('hidden-subtotal').value = subtotal.toFixed(2);
        document.getElementById('hidden-total-payment').value = finalTotal.toFixed(2);
    }
</script>
@endsection