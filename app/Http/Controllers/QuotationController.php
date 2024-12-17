<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\CustomerDetail;
use App\Models\QuotationItem;
use App\Models\Quotation;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use App\Models\CompanyMaintenance;
use App\Models\Inventory;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;

class QuotationController extends Controller
{
    public function index()
    {

        $user = Auth::user();
        if ($user->role === 'system admin') {

            $quotations = Quotation::with('company', 'customer')->get();
            $companies = Company::all(); // Get all companies
        } else {
            $quotations = Quotation::with('company', 'customer')->where('iQuoComfk', $user->company_id)->get();
            $companies = [];

        }

        return view('quotations.index', compact('quotations'));
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
        $latestQuotation = Quotation::latest('iQuoPk')->first();
        $newQuotationNumber = $latestQuotation
            ? 'QT' . str_pad((int) substr($latestQuotation->iQuoNo, 3) + 1, 5, '0', STR_PAD_LEFT)
            : 'QT00001';

        return view('quotations.create', compact('companies', 'customers', 'companyMaintenance', 'newQuotationNumber', 'products', 'inventory'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        // Validate input data
        $validated = $request->validate([
            'iQuoComfk' => $user->role === 'system admin' ? 'required|exists:companies,id' : 'sometimes|exists:companies,id',
            'iQuoCustDfk' => 'required|exists:customer_details,iCustDPk', // Ensure customer exists
            'iQuoNo' => 'required|unique:quotations,iQuoNo', // Quotation number should be unique
            'dQuodate' => 'required|date',
            'iQuoTax' => 'nullable|numeric|min:0',
            'iQuoDiscount' => 'nullable|numeric|min:0',
            'iQuoShipping' => 'nullable|numeric|min:0',
            'yQuoSubtotal' => 'required|numeric|min:0',
            'yQuoTotalPayment' => 'required|numeric|min:0',
            'items' => 'required|array', // List of items to be added to the quotation
            'items.*.code' => 'required|string|max:255',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',

        ]);

        // Create the new quotation
        $quotation = Quotation::create([
            'iQuoComfk' => Auth::user()->role === 'system admin' ? $request->iQuoComfk : Auth::user()->company_id,
            'iQuoNo' => $request->input('iQuoNo'),
            'iQuoCustDfk' => $request->input('iQuoCustDfk'),
            'dQuodate' => $request->input('dQuodate'),
            'iQuoTax' => $request->input('iQuoTax', 0),
            'iQuoDiscount' => $request->input('iQuoDiscount', 0),
            'iQuoShipping' => $request->input('iQuoShipping', 0),
            'yQuoSubtotal' => $request->input('yQuoSubtotal'),
            'yQuoTotalPayment' => $request->input('yQuoTotalPayment'),
        ]);

        // Loop through the items and store them in the QuotationItem table

        foreach ($request->items as $item) {
            QuotationItem::create([
                'iQuoItemQuofk' => $quotation->iQuoPk, // Foreign key to the quotation table
                'cQuoItemProductCode' => $item['code'], // Map 'code' to database column
                'cQuoItemDescription' => $item['description'], // Map 'description' to database column
                'iQuoItemQuantity' => $item['quantity'], // Map 'quantity' to database column
                'yQuoItemPriceUnit' => $item['price'], // Map 'price' to database column
                'yQuoItemTotal' => $item['quantity'] * $item['price'], // Calculate total
            ]);
        }

        return redirect()->route('quotations.index', $quotation->iQuoPk)->with('success', 'Quotation created successfully!');
    }

    // Edit method to display the edit form for a specific quotation
    public function edit($quotation)
    {
        $quotation = Quotation::with('items')->findOrFail($quotation); // Fetch the quotation and related items
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

        return view('quotations.edit', compact('quotation', 'companies', 'customers', 'products', 'inventory'));
    }

    // Update method to handle the form submission and update the quotation
    public function update(Request $request, $quotation)
    {
        $user = Auth::user();
        // dd(request()->all());
        $validated = $request->validate([
            'iQuoCustDfk' => 'required|exists:customer_details,iCustDPk',
            'dQuodate' => 'required|date',
            'iQuoTax' => 'nullable|numeric|min:0',
            'iQuoDiscount' => 'nullable|numeric|min:0',
            'iQuoShipping' => 'nullable|numeric|min:0',
            'yQuoSubtotal' => 'required|numeric|min:0',
            'yQuoTotalPayment' => 'required|numeric|min:0',
            'items' => 'required|array',
            'items.*.cQuoItemProductCode' => 'required|string|max:255',
            'items.*.cQuoItemDescription' => 'required|string|max:255',
            'items.*.iQuoItemQuantity' => 'required|numeric|min:1',
            'items.*.yQuoItemPriceUnit' => 'required|numeric|min:0',
            'items.*.yQuoItemTotal' => 'required|numeric|min:0',
        ]);

        $quotation = Quotation::findOrFail($quotation);

        $quotation->update([
            'iQuoCustDfk' => $request->input('iQuoCustDfk'),
            'dQuodate' => $request->input('dQuodate'),
            'iQuoTax' => $request->input('iQuoTax', 0),
            'iQuoDiscount' => $request->input('iQuoDiscount', 0),
            'iQuoShipping' => $request->input('iQuoShipping', 0),
            'yQuoSubtotal' => $request->input('yQuoSubtotal'),
            'yQuoTotalPayment' => $request->input('yQuoTotalPayment'),
        ]);

        // Delete existing items and replace with updated items
        $quotation->items()->delete();
        foreach ($request->items as $item) {
            \Log::info('Creating Quotation Item', $item);
            QuotationItem::create([
                'iQuoItemQuofk' => $quotation->iQuoPk,
                'cQuoItemProductCode' => $item['cQuoItemProductCode'],
                'cQuoItemDescription' => $item['cQuoItemDescription'],
                'iQuoItemQuantity' => $item['iQuoItemQuantity'],
                'yQuoItemPriceUnit' => $item['yQuoItemPriceUnit'],
                'yQuoItemTotal' => $item['yQuoItemTotal'],
            ]);
        }

        return redirect()->route('quotations.index')->with('success', 'Quotation updated successfully!');
    }

    // Show method to display the details of a specific quotation
    public function show($id)
    {
        $user = auth()->user();
        // Fetch the quotation by ID with its related data
        $quotation = Quotation::with(['company', 'customer', 'items'])->findOrFail($id);
        $company = Company::select('id', 'description')->find($user->company_id);

        $companies = collect([$company]);

        // Fetch products and inventory based on the user's company ID
        $products = Product::where('iProComfk', $company->id)->get();
        $inventory = Inventory::where('iInvComfk', $company->id)->get();
        $companyMaintenance = CompanyMaintenance::with('company')->where('iCompMainName', $company->id)->first();

        // Pass the data to the show view
        return view('quotations.show', compact('quotation', 'products', 'inventory', 'companyMaintenance'));

    }

    // Delete method to remove a specific quotation
    public function destroy($iQuoPk)
    {
        $quotation = Quotation::findOrFail($iQuoPk);

        // Delete related items first to maintain integrity
        $quotation->items()->delete();

        // Delete the quotation
        $quotation->delete();

        return redirect()->route('quotations.index')->with('success', 'Quotation deleted successfully!');
    }


    public function pdf($id, Request $request)
    {
        // Fetch the quotation with related data
        $quotation = Quotation::with(['company', 'customer', 'items'])->findOrFail($id);

        // Determine whether to include a signature (from request input)
        $includeSignature = $request->has('include_signature') && $request->include_signature == 'yes';

        // Load the view for the PDF (e.g., quotations/pdf.blade.php)
        $pdf = Pdf::loadView('quotations.pdf', [
            'quotation' => $quotation,
            'includeSignature' => $includeSignature,
        ]);

        // Set filename for download
        $fileName = "Quotation-{$quotation->quotation_no}.pdf";

        // Return PDF as a download
        return $pdf->download($fileName);
    }

}
