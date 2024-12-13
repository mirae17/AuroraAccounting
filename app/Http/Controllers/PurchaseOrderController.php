<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\CompanyMaintenance;
use App\Models\CustomerDetail;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'system admin') {

            $purchaseOrder = PurchaseOrder::with('company', 'customer', 'companyMaintenance')->get();
            $companies = Company::all(); // Get all companies
        } else {
            $purchaseOrder = PurchaseOrder::with('company', 'customer')->where('iPurchOrderPk', $user->company_id)->get();
            $companies = [];


        }

        return view('purchaseOrder.index', compact('purchaseOrder', 'companies'));
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
        $latestpurchaseOrder = PurchaseOrder::latest('iPurchOrderPk')->first();
        $newpurchaseOrderNumber = $latestpurchaseOrder ? 'PO' . str_pad((int) substr($latestpurchaseOrder->iPurchOrderNo, 3) + 1, 5, '0', STR_PAD_LEFT)
            : 'PO00001';
        // Debugging

        return view('purchaseOrder.create', compact('companies', 'customers', 'companyMaintenance', 'newpurchaseOrderNumber', 'products', 'inventory'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        //
    }
}
