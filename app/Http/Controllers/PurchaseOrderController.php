<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\CompanyMaintenance;
use App\Models\CustomerDetail;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'system admin') {

            $purchaseOrder = PurchaseOrder::with('company', 'customer', 'companyMaintenance')->get();
            $companies = Company::all(); // Get all companies
        } else {
            $purchaseOrder = PurchaseOrder::with('company', 'customer')->where('iPurchOrderComfk', $user->company_id)->get();
            $companies = [];


        }

        return view('purchaseOrder.index', compact('purchaseOrder', 'companies'));
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
        $latestpurchaseOrder = PurchaseOrder::latest('iPurchOrderPk')->first();
        $newpurchaseOrderNumber = $latestpurchaseOrder ? 'PO' . str_pad((int) substr($latestpurchaseOrder->iPurchOrderNo, 3) + 1, 5, '0', STR_PAD_LEFT)
            : 'PO00001';
        return view('purchaseOrder.create', compact('companies', 'customers', 'companyMaintenance', 'newpurchaseOrderNumber', 'products', 'inventory'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        // Validate input data
        $validated = $request->validate([
            'iPurchOrderComfk' => $user->role === 'system admin' ? 'required|exists:companies,id' : 'sometimes|exists:companies,id',
            'iPurchOrderCustDfk' => 'required|exists:customer_details,iCustDPk', // Ensure customer exists
            'iPurchOrderNo' => 'required|unique:purchase_orders,iPurchOrderNo', // PurchOrdertation number should be unique
            'dPurchOrderdate' => 'required|date',
            'iPurchOrderTax' => 'nullable|numeric|min:0',
            'iPurchOrderDiscount' => 'nullable|numeric|min:0',
            'iPurchOrderShipping' => 'nullable|numeric|min:0',
            'yPurchOrderSubtotal' => 'required|numeric|min:0',
            'yPurchOrderTotalPayment' => 'required|numeric|min:0',
            'items' => 'required|array', // List of items to be added to the purchaseOrder
            'items.*.code' => 'required|string|max:255',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',

        ]);

        // Create the new purchaseOrder
        $purchaseOrder = PurchaseOrder::create([
            'iPurchOrderComfk' => Auth::user()->role === 'system admin' ? $request->iPurchOrderComfk : Auth::user()->company_id,
            'iPurchOrderNo' => $request->input('iPurchOrderNo'),
            'iPurchOrderCustDfk' => $request->input('iPurchOrderCustDfk'),
            'dPurchOrderdate' => $request->input('dPurchOrderdate'),
            'iPurchOrderTax' => $request->input('iPurchOrderTax', 0),
            'iPurchOrderDiscount' => $request->input('iPurchOrderDiscount', 0),
            'iPurchOrderShipping' => $request->input('iPurchOrderShipping', 0),
            'yPurchOrderSubtotal' => $request->input('yPurchOrderSubtotal'),
            'yPurchOrderTotalPayment' => $request->input('yPurchOrderTotalPayment'),
        ]);

        // Loop through the items and store them in the PurchaseOrderItem table

        foreach ($request->items as $item) {
            PurchaseOrderItem::create([
                'iPurchOrderItemPurchOrderfk' => $purchaseOrder->iPurchOrderPk, // Foreign key to the purchaseOrder table
                'cPurchOrderItemProductCode' => $item['code'], // Map 'code' to database column
                'cPurchOrderItemDescription' => $item['description'], // Map 'description' to database column
                'iPurchOrderItemQuantity' => $item['quantity'], // Map 'quantity' to database column
                'yPurchOrderItemPriceUnit' => $item['price'], // Map 'price' to database column
                'yPurchOrderItemTotal' => $item['quantity'] * $item['price'], // Calculate total
            ]);
        }

        return redirect()->route('purchaseOrder.index', $purchaseOrder->iPurchOrderPk)->with('success', 'PurchaseOrder created successfully!');
    }

    // Edit method to display the edit form for a specific purchaseOrder
    public function edit($purchaseOrder)
    {
        $purchaseOrder = PurchaseOrder::with('items')->findOrFail($purchaseOrder); // Fetch the purchaseOrder and related items
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

        return view('purchaseOrder.edit', compact('purchaseOrder', 'companies', 'customers', 'products', 'inventory'));
    }

    // Update method to handle the form submission and update the purchaseOrder
    public function update(Request $request, $purchaseOrder)
    {
        $user = Auth::user();
        // dd(request()->all());
        $validated = $request->validate([
            'iPurchOrderCustDfk' => 'required|exists:customer_details,iCustDPk',
            'dPurchOrderdate' => 'required|date',
            'iPurchOrderTax' => 'nullable|numeric|min:0',
            'iPurchOrderDiscount' => 'nullable|numeric|min:0',
            'iPurchOrderShipping' => 'nullable|numeric|min:0',
            'yPurchOrderSubtotal' => 'required|numeric|min:0',
            'yPurchOrderTotalPayment' => 'required|numeric|min:0',
            'items' => 'required|array',
            'items.*.cPurchOrderItemProductCode' => 'required|string|max:255',
            'items.*.cPurchOrderItemDescription' => 'required|string|max:255',
            'items.*.iPurchOrderItemQuantity' => 'required|numeric|min:1',
            'items.*.yPurchOrderItemPriceUnit' => 'required|numeric|min:0',
            'items.*.yPurchOrderItemTotal' => 'required|numeric|min:0',
        ]);

        $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrder);

        $purchaseOrder->update([
            'iPurchOrderCustDfk' => $request->input('iPurchOrderCustDfk'),
            'dPurchOrderdate' => $request->input('dPurchOrderdate'),
            'iPurchOrderTax' => $request->input('iPurchOrderTax', 0),
            'iPurchOrderDiscount' => $request->input('iPurchOrderDiscount', 0),
            'iPurchOrderShipping' => $request->input('iPurchOrderShipping', 0),
            'yPurchOrderSubtotal' => $request->input('yPurchOrderSubtotal'),
            'yPurchOrderTotalPayment' => $request->input('yPurchOrderTotalPayment'),
        ]);

        // Delete existing items and replace with updated items
        $purchaseOrder->items()->delete();
        foreach ($request->items as $item) {
            \Log::info('Creating PurchaseOrder Item', $item);
            PurchaseOrderItem::create([
                'iPurchOrderItemPurchOrderfk' => $purchaseOrder->iPurchOrderPk,
                'cPurchOrderItemProductCode' => $item['cPurchOrderItemProductCode'],
                'cPurchOrderItemDescription' => $item['cPurchOrderItemDescription'],
                'iPurchOrderItemQuantity' => $item['iPurchOrderItemQuantity'],
                'yPurchOrderItemPriceUnit' => $item['yPurchOrderItemPriceUnit'],
                'yPurchOrderItemTotal' => $item['yPurchOrderItemTotal'],
            ]);
        }

        return redirect()->route('purchaseOrder.index')->with('success', 'PurchaseOrder updated successfully!');
    }

    // Show method to display the details of a specific purchaseOrder
    public function show($purchaseOrder)
    {
        $user = auth()->user();
        // Fetch the purchaseOrder by ID with its related data
        $purchaseOrder = PurchaseOrder::with(['company', 'customer', 'items'])->findOrFail($purchaseOrder);
        if ($user->role === 'system admin') {
            $companies = Company::select('id', 'description')->get();
            $customers = CustomerDetail::all();
            $products = Product::all();
            $inventory = Inventory::all();
            $companyMaintenance = CompanyMaintenance::with('company')->get();
        } else {
            $companies = [];
            $customers = CustomerDetail::where('iCustDCompfk', $user->company_id)->get();
            $products = Product::where('iProComfk', $user->company_id)->get();
            $inventory = Inventory::where('iInvComfk', $user->company_id)->get();
            $companyMaintenance = CompanyMaintenance::with('company')->where('iCompMainName', $user->company_id)->first();
        }

        return view('purchaseOrder.show', compact('purchaseOrder', 'products', 'inventory', 'companyMaintenance'));

    }

    // Delete method to remove a specific purchaseOrder
    public function destroy($iPurchOrderPk)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($iPurchOrderPk);

        // Delete related items first to maintain integrity
        $purchaseOrder->items()->delete();

        // Delete the purchaseOrder
        $purchaseOrder->delete();

        return redirect()->route('purchaseOrder.index')->with('success', 'Purchase Order deleted successfully!');
    }

    public function generatePDF($purchaseOrder)
    {
        $user = auth()->user();
        // Fetch the purchaseOrder by ID with its related data
        $purchaseOrder = PurchaseOrder::with(['company', 'customer', 'items'])->findOrFail($purchaseOrder);
        $company = Company::select('id', 'description')->find($user->company_id);

        $companies = collect([$company]);

        // Fetch products and inventory based on the user's company ID
        $products = Product::where('iProComfk', $company->id)->get();
        $inventory = Inventory::where('iInvComfk', $company->id)->get();
        $companyMaintenance = CompanyMaintenance::with('company')->where('iCompMainName', $company->id)->first();

        $signatureOption = request('signature', 'with');

        $data = [
            'purchaseOrder' => $purchaseOrder,
            'companies' => $companies,
            'products' => $products,
            'inventory' => $inventory,
            'companyMaintenance' => $companyMaintenance,
            'signatureOption' => $signatureOption,
        ];

        $pdf = PDF::loadView('purchaseOrder.pdf', $data);

        return $pdf->download('Purchase Order_' . $purchaseOrder->iPurchOrderNo . '.pdf');
    }

}
