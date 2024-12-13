<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\CompanyMaintenance;
use App\Models\CustomerDetail;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;

class ReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'system admin') {

            $receipt = Receipt::with('company', 'customer', 'companyMaintenance')->get();
            $companies = Company::all(); // Get all companies
        } else {
            $receipt = Receipt::with('company', 'customer')->where('iRecptComfk', $user->company_id)->get();
            $companies = [];


        }

        return view('receipt.index', compact('receipt', 'companies'));
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
                return redirect()->route('receipts.index')->with('error', 'Your company details are not available.');
            }

            $companies = collect([$company]); // Wrap single company in a collection

            // Fetch products and inventory based on the user's company ID
            $products = Product::where('iProComfk', $company->id)->get();
            $inventory = Inventory::where('iInvComfk', $company->id)->get();

            // Fetch company maintenance details
            $companyMaintenance = CompanyMaintenance::with('company')->where('iCompMainName', $company->id)->first();
        }

        // Generate a new invoice number
        $latestReceipt = Receipt::latest('iRecptPk')->first();
        $newpurchaseOrderNumber = $latestReceipt ? 'RCPT' . str_pad((int) substr($latestReceipt->iRecptNo, 3) + 1, 5, '0', STR_PAD_LEFT)
            : 'RCPT00001';

        return view('receipt.create', compact('companies', 'customers', 'companyMaintenance', 'newReceiptNumber', 'products', 'inventory'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // Validate request data
        $request->validate([
            'iRecptComfk' => $user->role === 'system admin' ? 'required|exists:companies,id' : 'sometimes|exists:companies,id',
            'iRecptCustDfk' => 'required|exists:customer_details,iCustDPk',
            'iRecptNo' => 'required|unique:receipts',
            'dRecptdate' => 'required|date',
            'yRecptSubtotal' => 'required|numeric',
            'yRecptTotalPayment' => 'required|numeric',
            'iRecptDiscount' => 'required|numeric',
            'iRecptShipping' => 'required|numeric',
            'iRecptTax' => 'required|numeric',
        ]);


        $receipt = new Receipt();
        if ($user->role === 'system admin') {
            $receipt->iRecptComfk = $request->iRecptComfk;
        } else {
            $receipt->iRecptComfk = $user->company_id;
        }

        $receipt->iRecptCustDfk = $request->iRecptCustDfk;
        $receipt->iRecptNo = $request->iRecptNo;
        $receipt->dRecptdate = $request->dRecptdate;
        $receipt->yRecptSubtotal = $request->yRecptSubtotal;
        $receipt->iRecptDiscount = $request->iRecptDiscount;
        $receipt->iRecptTax = $request->iRecptTax;
        $receipt->iRecptShipping = $request->iRecptShipping;
        $receipt->yRecptTotalPayment = $request->yRecptTotalPayment;
        $receipt->save();


        return redirect()->route('receipt.index')->with('success', 'Receipt created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Receipt $receipt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Receipt $receipt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Receipt $receipt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Receipt $receipt)
    {
        //
    }
}
