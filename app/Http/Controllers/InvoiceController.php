<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\CustomerDetail;
use App\Models\CompanyMaintenance;
use App\Models\Company;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user(); // Get the logged-in user

        // Fetch customers
        $customers = CustomerDetail::all();

        if ($user->role === 'system admin') {
            // System admin: Fetch all companies, products, and inventory
            $companies = Company::select('id', 'description')->get();
            $products = Product::all();
            $inventory = Inventory::all();
            $companyMaintenance = null; // System admins don't have specific company maintenance
        } else {
            // Regular users: Fetch their associated company
            $company = Company::select('id', 'description')->find($user->company_id);

            if (!$company) {
                return redirect()->route('invoice.index')->with('error', 'Your company details are not available.');
            }

            $companies = collect([$company]); // Wrap single company in a collection

            // Fetch products and inventory based on the user's company ID
            $products = Product::where('iProComfk', $company->id)->get();
            $inventory = Inventory::where('iInvComfk', $company->id)->get();

            // Fetch company maintenance details
            $companyMaintenance = CompanyMaintenance::with('company')->where('iCompMainName', $company->id)->first();
        }

        // Generate a new invoice number
        $latestInvoice = Invoice::latest('iInvcPk')->first();
        $newInvoiceNumber = $latestInvoice
            ? 'INV' . str_pad((int) substr($latestInvoice->iInvcNo, 3) + 1, 5, '0', STR_PAD_LEFT)
            : 'INV00001';
        // Debugging

        return view('invoice.create', compact('companies', 'customers', 'companyMaintenance', 'newInvoiceNumber', 'products', 'inventory'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // Validate request data
        $request->validate([
            'iInvcComfk' => $user->role === 'system admin' ? 'required|exists:companies,id' : 'sometimes|exists:companies,id',
            'iInvcCustDfk' => 'required|exists:customer_details,iCustDPk',
            'iInvcNo' => 'required|unique:invoices',
            'dInvcdate' => 'required|date',
            'yInvcSubtotal' => 'required|numeric',
            'yInvcTotalPayment' => 'required|numeric',
            'iInvcDiscount' => 'required|numeric',
            'iInvcShipping' => 'required|numeric',
            'iInvcTax' => 'required|numeric',
        ]);


        $invoice = new Invoice();
        if ($user->role === 'system admin') {
            $invoice->iInvcComfk = $request->iInvcComfk;
        } else {
            $invoice->iInvcComfk = $user->company_id;
        }

        $invoice->iInvcCustDfk = $request->iInvcCustDfk;
        $invoice->iInvcNo = $request->iInvcNo;
        $invoice->dInvcdate = $request->dInvcdate;
        $invoice->yInvcSubtotal = $request->yInvcSubtotal;
        $invoice->iInvcDiscount = $request->iInvcDiscount;
        $invoice->iInvcTax = $request->iInvcTax;
        $invoice->iInvcShipping = $request->iInvcShipping;
        $invoice->yInvcTotalPayment = $request->yInvcTotalPayment;
        $invoice->save();


        return redirect()->route('invoice.index')->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
