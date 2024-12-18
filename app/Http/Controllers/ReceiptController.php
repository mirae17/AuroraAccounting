<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\ReceiptItem;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\CompanyMaintenance;
use App\Models\CustomerDetail;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptController extends Controller
{
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
        $latestReceipt = Receipt::latest('iRecptPk')->first();
        $newReceiptNumber = $latestReceipt ? 'RCPT' . str_pad((int) substr($latestReceipt->iRecptNo, 3) + 1, 5, '0', STR_PAD_LEFT)
            : 'RCPT00001';

        return view('receipt.create', compact('companies', 'customers', 'companyMaintenance', 'newReceiptNumber', 'products', 'inventory'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        // Validate input data
        $validated = $request->validate([
            'iRecptComfk' => $user->role === 'system admin' ? 'required|exists:companies,id' : 'sometimes|exists:companies,id',
            'iRecptCustDfk' => 'required|exists:customer_details,iCustDPk', // Ensure customer exists
            'iRecptNo' => 'required|unique:receipts,iRecptNo', // Recpttation number should be unique
            'dRecptdate' => 'required|date',
            'iRecptTax' => 'nullable|numeric|min:0',
            'iRecptDiscount' => 'nullable|numeric|min:0',
            'iRecptShipping' => 'nullable|numeric|min:0',
            'yRecptSubtotal' => 'required|numeric|min:0',
            'yRecptTotalPayment' => 'required|numeric|min:0',
            'items' => 'required|array', // List of items to be added to the receipt
            'items.*.code' => 'required|string|max:255',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',

        ]);

        // Create the new receipt
        $receipt = Receipt::create([
            'iRecptComfk' => Auth::user()->role === 'system admin' ? $request->iRecptComfk : Auth::user()->company_id,
            'iRecptNo' => $request->input('iRecptNo'),
            'iRecptCustDfk' => $request->input('iRecptCustDfk'),
            'dRecptdate' => $request->input('dRecptdate'),
            'iRecptTax' => $request->input('iRecptTax', 0),
            'iRecptDiscount' => $request->input('iRecptDiscount', 0),
            'iRecptShipping' => $request->input('iRecptShipping', 0),
            'yRecptSubtotal' => $request->input('yRecptSubtotal'),
            'yRecptTotalPayment' => $request->input('yRecptTotalPayment'),
        ]);

        // Loop through the items and store them in the ReceiptItem table

        foreach ($request->items as $item) {
            ReceiptItem::create([
                'iRecptItemRecptfk' => $receipt->iRecptPk, // Foreign key to the receipt table
                'cRecptItemProductCode' => $item['code'], // Map 'code' to database column
                'cRecptItemDescription' => $item['description'], // Map 'description' to database column
                'iRecptItemQuantity' => $item['quantity'], // Map 'quantity' to database column
                'yRecptItemPriceUnit' => $item['price'], // Map 'price' to database column
                'yRecptItemTotal' => $item['quantity'] * $item['price'], // Calculate total
            ]);
        }

        return redirect()->route('receipt.index', $receipt->iRecptPk)->with('success', 'Receipt created successfully!');
    }

    // Edit method to display the edit form for a specific receipt
    public function edit($receipt)
    {
        $receipt = Receipt::with('items')->findOrFail($receipt); // Fetch the receipt and related items
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

        return view('receipt.edit', compact('receipt', 'companies', 'customers', 'products', 'inventory'));
    }

    // Update method to handle the form submission and update the receipt
    public function update(Request $request, $receipt)
    {
        $user = Auth::user();
        // dd(request()->all());
        $validated = $request->validate([
            'iRecptCustDfk' => 'required|exists:customer_details,iCustDPk',
            'dRecptdate' => 'required|date',
            'iRecptTax' => 'nullable|numeric|min:0',
            'iRecptDiscount' => 'nullable|numeric|min:0',
            'iRecptShipping' => 'nullable|numeric|min:0',
            'yRecptSubtotal' => 'required|numeric|min:0',
            'yRecptTotalPayment' => 'required|numeric|min:0',
            'items' => 'required|array',
            'items.*.cRecptItemProductCode' => 'required|string|max:255',
            'items.*.cRecptItemDescription' => 'required|string|max:255',
            'items.*.iRecptItemQuantity' => 'required|numeric|min:1',
            'items.*.yRecptItemPriceUnit' => 'required|numeric|min:0',
            'items.*.yRecptItemTotal' => 'required|numeric|min:0',
        ]);

        $receipt = Receipt::findOrFail($receipt);

        $receipt->update([
            'iRecptCustDfk' => $request->input('iRecptCustDfk'),
            'dRecptdate' => $request->input('dRecptdate'),
            'iRecptTax' => $request->input('iRecptTax', 0),
            'iRecptDiscount' => $request->input('iRecptDiscount', 0),
            'iRecptShipping' => $request->input('iRecptShipping', 0),
            'yRecptSubtotal' => $request->input('yRecptSubtotal'),
            'yRecptTotalPayment' => $request->input('yRecptTotalPayment'),
        ]);

        // Delete existing items and replace with updated items
        $receipt->items()->delete();
        foreach ($request->items as $item) {
            \Log::info('Creating Receipt Item', $item);
            ReceiptItem::create([
                'iRecptItemRecptfk' => $receipt->iRecptPk,
                'cRecptItemProductCode' => $item['cRecptItemProductCode'],
                'cRecptItemDescription' => $item['cRecptItemDescription'],
                'iRecptItemQuantity' => $item['iRecptItemQuantity'],
                'yRecptItemPriceUnit' => $item['yRecptItemPriceUnit'],
                'yRecptItemTotal' => $item['yRecptItemTotal'],
            ]);
        }

        return redirect()->route('receipt.index')->with('success', 'Receipt updated successfully!');
    }

    // Show method to display the details of a specific receipt
    public function show($id)
    {
        $user = auth()->user();
        // Fetch the receipt by ID with its related data
        $receipt = Receipt::with(['company', 'customer', 'items'])->findOrFail($id);
        $company = Company::select('id', 'description')->find($user->company_id);

        $companies = collect([$company]);

        // Fetch products and inventory based on the user's company ID
        $products = Product::where('iProComfk', $company->id)->get();
        $inventory = Inventory::where('iInvComfk', $company->id)->get();
        $companyMaintenance = CompanyMaintenance::with('company')->where('iCompMainName', $company->id)->first();

        // Pass the data to the show view
        return view('receipt.show', compact('receipt', 'products', 'inventory', 'companyMaintenance'));

    }

    // Delete method to remove a specific receipt
    public function destroy($iRecptPk)
    {
        $receipt = Receipt::findOrFail($iRecptPk);

        // Delete related items first to maintain integrity
        $receipt->items()->delete();

        // Delete the receipt
        $receipt->delete();

        return redirect()->route('receipt.index')->with('success', 'Receipt deleted successfully!');
    }


    public function generatePDF($receipt)
    {
        $user = auth()->user();
        // Fetch the receipt by ID with its related data
        $receipt = Receipt::with(['company', 'customer', 'items'])->findOrFail($receipt);
        $company = Company::select('id', 'description')->find($user->company_id);

        $companies = collect([$company]);

        // Fetch products and inventory based on the user's company ID
        $products = Product::where('iProComfk', $company->id)->get();
        $inventory = Inventory::where('iInvComfk', $company->id)->get();
        $companyMaintenance = CompanyMaintenance::with('company')->where('iCompMainName', $company->id)->first();

        $signatureOption = request('signature', 'with');

        $data = [
            'receipt' => $receipt,
            'companies' => $companies,
            'products' => $products,
            'inventory' => $inventory,
            'companyMaintenance' => $companyMaintenance,
            'signatureOption' => $signatureOption,
        ];

        $pdf = PDF::loadView('receipt.pdf', $data);

        return $pdf->download('Receipt_' . ($receipt->iRecptNo) . '.pdf');
    }
}
