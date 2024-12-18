<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use App\Models\CustomerDetail;
use App\Models\CompanyMaintenance;
use App\Models\Company;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index()
    {


        $user = Auth::user();

        if ($user->role === 'system admin') {

            $invoice = Invoice::with('company', 'customer', 'companyMaintenance')->get();
            $companies = Company::all(); // Get all companies
        } else {
            $invoice = Invoice::with('company', 'customer')->where('iInvcComfk', $user->company_id)->get();
            $companies = [];


        }

        return view('invoice.index', compact('invoice', 'companies'));
    }
    public function create()
    {
        $user = Auth::user();

        if ($user->role === 'system admin') {
            $companies = Company::select('id', 'description')->get();
            $products = Product::all();
            $inventory = Inventory::all();
            $companyMaintenance = null;
            $customers = CustomerDetail::all(); // Fetch all companies
        } else {
            $products = Product::where('iProComfk', $user->company_id)->get(['iProPk', 'cProName', 'cProCode', 'yProPrice']);
            $customers = CustomerDetail::where('iCustDCompfk', $user->company_id)->get(['iCustDPk', 'cCustDName', 'cCustDAddress', 'cCustDCompName', 'cCustDCompOfficeNo', 'cCustDCompEmail']);
            $inventory = Inventory::where('iInvComfk', $user->company_id)->get(['iInvPK', 'cInvName', 'cInvCode', 'yInvPrice']);
            $companyMaintenance = CompanyMaintenance::where('iCompMainName', $user->company_id)->get(['iCompMainPk', 'iCompMainAddress', 'iCompMainPhoneNo', 'iCompMainEmail', 'iCompMainLogo']);
            $companies = [];
        }
        $latestInvoice = Invoice::latest('iInvcPk')->first();
        $newInvoiceNumber = $latestInvoice
            ? 'INV' . str_pad((int) substr($latestInvoice->iInvcNo, 3) + 1, 5, '0', STR_PAD_LEFT)
            : 'INV00001';

        return view('invoice.create', compact('companies', 'customers', 'companyMaintenance', 'newInvoiceNumber', 'products', 'inventory'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        // Validate input data
        $validated = $request->validate([
            'iInvcComfk' => $user->role === 'system admin' ? 'required|exists:companies,id' : 'sometimes|exists:companies,id',
            'iInvcCustDfk' => 'required|exists:customer_details,iCustDPk', // Ensure customer exists
            'iInvcNo' => 'required|unique:invoices,iInvcNo', // Invoice number should be unique
            'dInvcdate' => 'required|date',
            'iInvcTax' => 'nullable|numeric|min:0',
            'iInvcDiscount' => 'nullable|numeric|min:0',
            'iInvcShipping' => 'nullable|numeric|min:0',
            'yInvcSubtotal' => 'required|numeric|min:0',
            'yInvcTotalPayment' => 'required|numeric|min:0',
            'items' => 'required|array', // List of items to be added to the quotation
            'items.*.code' => 'required|string|max:255',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',

        ]);

        // Create the new quotation
        $invoice = Invoice::create([
            'iInvcComfk' => Auth::user()->role === 'system admin' ? $request->iInvcComfk : Auth::user()->company_id,
            'iInvcNo' => $request->input('iInvcNo'),
            'iInvcCustDfk' => $request->input('iInvcCustDfk'),
            'dInvcdate' => $request->input('dInvcdate'),
            'iInvcTax' => $request->input('iInvcTax', 0),
            'iInvcDiscount' => $request->input('iInvcDiscount', 0),
            'iInvcShipping' => $request->input('iInvcShipping', 0),
            'yInvcSubtotal' => $request->input('yInvcSubtotal'),
            'yInvcTotalPayment' => $request->input('yInvcTotalPayment'),
        ]);

        // Loop through the items and store them in the InvoiceItem table

        foreach ($request->items as $item) {
            InvoiceItem::create([
                'iInvcItemInvcfk' => $invoice->iInvcPk, // Foreign key to the quotation table
                'cInvcItemProductCode' => $item['code'], // Map 'code' to database column
                'cInvcItemDescription' => $item['description'], // Map 'description' to database column
                'iInvcItemQuantity' => $item['quantity'], // Map 'quantity' to database column
                'yInvcItemPriceUnit' => $item['price'], // Map 'price' to database column
                'yInvcItemTotal' => $item['quantity'] * $item['price'], // Calculate total
            ]);
        }

        return redirect()->route('quotations.index', $invoice->iInvcPk)->with('success', 'Invoice created successfully!');
    }

    // Edit method to display the edit form for a specific quotation
    public function edit($invoice)
    {
        $invoice = Invoice::with('items')->findOrFail($invoice); // Fetch the quotation and related items
        $user = Auth::user();

        if ($user->role === 'system admin') {
            $companies = Company::select('id', 'description')->get();
            $customers = CustomerDetail::all();
            $products = Product::all();
            $inventory = Inventory::all();
        } else {
            $companies = [];
            $customers = CustomerDetail::where('iCustDCompfk', $user->company_id)->get();
            $products = Product::where('iProComfk', $user->company_id)->get();
            $inventory = Inventory::where('iInvComfk', $user->company_id)->get();
        }

        return view('invoice.edit', compact('invoice', 'companies', 'customers', 'products', 'inventory'));
    }

    // Update method to handle the form submission and update the quotation
    public function update(Request $request, $invoice)
    {
        $user = Auth::user();
        // dd(request()->all());
        $validated = $request->validate([
            'iInvcCustDfk' => 'required|exists:customer_details,iCustDPk',
            'dInvcdate' => 'required|date',
            'iInvcTax' => 'nullable|numeric|min:0',
            'iInvcDiscount' => 'nullable|numeric|min:0',
            'iInvcShipping' => 'nullable|numeric|min:0',
            'yInvcSubtotal' => 'required|numeric|min:0',
            'yInvcTotalPayment' => 'required|numeric|min:0',
            'items' => 'required|array',
            'items.*.cInvcItemProductCode' => 'required|string|max:255',
            'items.*.cInvcItemDescription' => 'required|string|max:255',
            'items.*.iInvcItemQuantity' => 'required|numeric|min:1',
            'items.*.yInvcItemPriceUnit' => 'required|numeric|min:0',
            'items.*.yInvcItemTotal' => 'required|numeric|min:0',
        ]);

        $invoice = InvoiceItem::findOrFail($invoice);

        $invoice->update([
            'iInvcCustDfk' => $request->input('iInvcCustDfk'),
            'dInvcdate' => $request->input('dInvcdate'),
            'iInvcTax' => $request->input('iInvcTax', 0),
            'iInvcDiscount' => $request->input('iInvcDiscount', 0),
            'iInvcShipping' => $request->input('iInvcShipping', 0),
            'yInvcSubtotal' => $request->input('yInvcSubtotal'),
            'yInvcTotalPayment' => $request->input('yInvcTotalPayment'),
        ]);

        // Delete existing items and replace with updated items
        $invoice->items()->delete();

        foreach ($request->items as $item) {
            \Log::info('Creating Invctation Item', $item);
            InvoiceItem::create([
                'iInvcItemInvcfk' => $invoice->iInvcPk,
                'cInvcItemProductCode' => $item['cInvcItemProductCode'],
                'cInvcItemDescription' => $item['cInvcItemDescription'],
                'iInvcItemQuantity' => $item['iInvcItemQuantity'],
                'yInvcItemPriceUnit' => $item['yInvcItemPriceUnit'],
                'yInvcItemTotal' => $item['yInvcItemTotal'],
            ]);
        }

        return redirect()->route('invoice.index')->with('success', 'Invoice updated successfully!');
    }

    // Show method to display the details of a specific quotation
    public function show($invoice)
    {
        $user = auth()->user();
        // Fetch the quotation by ID with its related data
        $invoice = Invoice::with(['company', 'customer', 'items'])->findOrFail($invoice);
        $company = Company::select('id', 'description')->find($user->company_id);

        $companies = collect([$company]);

        // Fetch products and inventory based on the user's company ID
        $products = Product::where('iProComfk', $company->id)->get();
        $inventory = Inventory::where('iInvComfk', $company->id)->get();
        $companyMaintenance = CompanyMaintenance::with('company')->where('iCompMainName', $company->id)->first();

        // Pass the data to the show view
        return view('invoice.show', compact('invoice', 'products', 'inventory', 'companyMaintenance'));

    }

    // Delete method to remove a specific quotation
    public function destroy($iInvcPk)
    {
        $invoice = Invoice::findOrFail($iInvcPk);

        // Delete related items first to maintain integrity
        $invoice->items()->delete();

        // Delete the quotation
        $invoice->delete();

        return redirect()->route('invoice.index')->with('success', 'Invoice deleted successfully!');
    }

    public function generatePDF($invoice)
    {
        $user = auth()->user();
        // Fetch the invoice by ID with its related data
        $invoice = Invoice::with(['company', 'customer', 'items'])->findOrFail($invoice);
        $company = Company::select('id', 'description')->find($user->company_id);

        $companies = collect([$company]);

        // Fetch products and inventory based on the user's company ID
        $products = Product::where('iProComfk', $company->id)->get();
        $inventory = Inventory::where('iInvComfk', $company->id)->get();
        $companyMaintenance = CompanyMaintenance::with('company')->where('iCompMainName', $company->id)->first();

        $signatureOption = request('signature', 'with');

        $data = [
            'invoice' => $invoice,
            'companies' => $companies,
            'products' => $products,
            'inventory' => $inventory,
            'companyMaintenance' => $companyMaintenance,
            'signatureOption' => $signatureOption,
        ];

        $pdf = PDF::loadView('invoice.pdf', $data);

        return $pdf->download('Invoice_' . $invoice->iInvcNo . '.pdf');
    }
}
