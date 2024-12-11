<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\CustomerDetail;
use App\Models\Quotation;
use App\Models\Company;
use App\Models\CompanyMaintenance;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\QuotationItem;
use Barryvdh\DomPDF\Facade\Pdf;

class QuotationController extends Controller
{
    public function index()
    {
        $quotations = Quotation::with('company', 'customer', 'companyMaintenance')->get();
        return view('quotations.index', compact('quotations'));
    }

    /**
     * Show the form for creating a new quotation.
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
                return redirect()->route('quotations.index')->with('error', 'Your company details are not available.');
            }

            $companies = collect([$company]); // Wrap single company in a collection

            // Fetch products and inventory based on the user's company ID
            $products = Product::where('iProComfk', $company->id)->get();
            $inventory = Inventory::where('iInvComfk', $company->id)->get();

            // Fetch company maintenance details
            $companyMaintenance = CompanyMaintenance::with('company')->where('iCompMainName', $company->id)->first();
        }

        // Generate a new quotation number
        $latestQuotation = Quotation::latest()->first();
        $newQuotationNumber = $latestQuotation ? 'QT' . str_pad($latestQuotation->id + 1, 5, '0', STR_PAD_LEFT) : 'QT00001';
        // Debugging

        return view('quotations.create', compact('companies', 'customers', 'companyMaintenance', 'newQuotationNumber', 'products', 'inventory'));
    }


    public function store(Request $request)
    {
        $user = auth()->user();

        dd($request->items); // Check the structure of the items data


        $request->validate([
            'iQuoComfk' => 'required|exists:companies,id',
            'iQuoCustDfk' => 'required|exists:customer_details,iCustDPk',
            'iQuoNo' => 'required|unique:quotations',
            'dQuodate' => 'required|date',
            'yQuoSubtotal' => 'required|numeric',
            'yQuoTotalPayment' => 'required|numeric',
            'iQuoDiscount' => 'required|numeric',
            'iQuoShipping' => 'required|numeric',
            'iQuoTax' => 'required|numeric',
            'items' => 'required|array',
            'items.*.iQuoItemQuofk' => 'required|exists:quotation,iQuoPk',
            'items.*.cQuoItemProductCode' => 'required|string|max:255',
            'items.*.cQuoItemDescription' => 'required|string|max:255',
            'items.*.yQuoItemPriceUnit' => 'required|numeric',
            'items.*.iQuoItemQuantity' => 'required|integer',
            'items.*.yQuoItemTotal' => 'required|numeric',
        ]);

        $quotation = new Quotation();
        if ($user->role === 'system admin') {
            $quotation->iQuoComfk = $request->iQuoComfk;
        } else {
            $quotation->iQuoComfk = $user->company_id;
        }
        $quotation->iQuoCustDfk = $request->iQuoCustDfk;
        $quotation->iQuoNo = $request->iQuoNo;
        $quotation->dQuodate = $request->dQuodate;
        $quotation->yQuoSubtotal = $request->yQuoSubtotal;
        $quotation->iQuoDiscount = $request->iQuoDiscount;
        $quotation->iQuoTax = $request->iQuoTax;
        $quotation->iQuoShipping = $request->iQuoShipping;
        $quotation->yQuoTotalPayment = $request->yQuoTotalPayment;
        $quotation->save();


        foreach ($request->items as $item) {
            $quotationItem = new QuotationItem();
            $quotationItem->iQuoItemQuofk = $quotation->iQuoComfk; // Assuming you have a foreign key
            $quotationItem->cQuoItemProductCode = $item['cQuoItemProductCode'];
            $quotationItem->cQuoItemDescription = $item['cQuoItemDescription'];
            $quotationItem->iQuoItemQuantity = $item['iQuoItemQuantity'];
            $quotationItem->yQuoItemPriceUnit = $item['yQuoItemPriceUnit'];
            $quotationItem->yQuoItemTotal = $item['yQuoItemTotal'];
            $quotationItem->save();

        }

        return redirect()->route('quotations.index')->with('success', 'Quotation created successfully.');
    }

    /**
     * Display the specified quotation.
     */
    public function show($id)
    {
        $quotation = Quotation::with('company', 'customer')->findOrFail($id);
        return view('quotations.show', compact('quotation'));
    }

    /**
     * Show the form for editing the specified quotation.
     */
    public function edit($id)
    {
        $quotation = Quotation::findOrFail($id);
        $companies = Company::all();
        $customers = CustomerDetail::all();
        return view('quotations.edit', compact('quotation', 'companies', 'customers'));
    }

    /**
     * Update the specified quotation in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'iQuoComfk' => 'required|exists:companies,id',
            'iQuoCustDfk' => 'required|exists:customer_details,iCustDPk',
            'iQuoNo' => 'required|unique:quotations,iQuoNo,' . $id,
            'dQuodate' => 'required|date',
            'yQuoSubtotal' => 'required|numeric',
            'yQuoTotalPayment' => 'required|numeric',
        ]);

        $quotation = Quotation::findOrFail($id);
        $quotation->update($request->all());
        return redirect()->route('quotations.index')->with('success', 'Quotation updated successfully.');
    }

    /**
     * Remove the specified quotation from storage.
     */
    public function destroy($id)
    {
        $quotation = Quotation::findOrFail($id);
        $quotation->delete();
        return redirect()->route('quotations.index')->with('success', 'Quotation deleted successfully.');
    }

    public function print($id, Request $request)
    {
        $quotation = Quotation::with('company', 'customer')->findOrFail($id);
        $withSignature = $request->query('signature', false);

        // Logic to generate PDF with/without signature
        $pdf = PDF::loadView('quotations.print', compact('quotation', 'withSignature'));
        return $pdf->download('quotation-' . $quotation->iQuoNo . '.pdf');
    }

}
