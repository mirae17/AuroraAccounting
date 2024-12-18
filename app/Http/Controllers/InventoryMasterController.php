<?php

namespace App\Http\Controllers;

use App\Models\InventoryMaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Inventory;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Supplier;
use App\Models\PaymentMethod;
use Barryvdh\DomPDF\Facade\Pdf;

class InventoryMasterController extends Controller
{
    // Display sales records
    public function index(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user
        $selectedYear = session('selected_year', date('Y')); // Get selected year or default to current year

        // System Admin can view all sales; others only see their company's sales
        $query = InventoryMaster::with([
            'paymentMethod' => function ($query) {
                $query->select('iPymtdPk', 'cPymtdDesc');
            },
            'supplier' => function ($query) {
                $query->select('iSuppPk', 'iSuppCode', 'iSuppDesc');
            },
            'employees' => function ($query) {
                $query->select('iEmpmasPk', 'cEmpName');
            },
            'inventories' => function ($query) {
                $query->select('iInvPK', 'cInvCode', 'cInvName', 'yInvPrice');
            },
            'company'
        ])->whereYear('dInvmasDate', $selectedYear);



        if ($user->role !== 'system admin') {
            $query->where('cInvmasCompfk', $user->company_id); // Filter by company for regular users
        }


        $inventoryM = $query->get(); // Execute the query
        $totalinventory = $query->count('iInvmasPk'); // Calculate the total sales for the selected year

        return view('inventoryM.index', compact('inventoryM', 'totalinventory', 'selectedYear'));
    }

    // Show form to add a new 
    public function create()
    {
        $user = Auth::user();

        if ($user->role === 'system admin') {
            $paymentMethods = PaymentMethod::all(['iPymtdPk', 'cPymtdDesc']);
            $inventories = Inventory::all(['iInvPK', 'cInvCode', 'cInvName', 'yInvPrice']);
            $employees = Employee::all(['iEmpmasPk', 'cEmpName']);
            $suppliers = Supplier::all(['iSuppPk', 'iSuppDesc']);
            $companies = Company::with(['payments', 'suppliers', 'employees', 'inventories'])->get(['id', 'description']);
        } else {
            $paymentMethods = PaymentMethod::where('company_id', $user->company_id)->get(['iPymtdPk', 'cPymtdDesc']);
            $suppliers = Supplier::where('company_id', $user->company_id)->get(['iSuppPk', 'iSuppDesc']);
            $inventories = Inventory::where('iInvComfk', $user->company_id)->get(['iInvPK', 'cInvCode', 'cInvName', 'yInvPrice']);
            $employees = Employee::where('company_id', $user->company_id)->get(['iEmpmasPk', 'cEmpName']);
            $companies = [];
        }
        return view('inventoryM.create', compact('paymentMethods', 'suppliers', 'companies', 'inventories', 'employees'));
    }

    // Store 
    public function store(Request $request)
    {
        $user = Auth::user();
        \Log::info('Submitted iInvmasInvPricefk: ' . $request->iInvmasInvPricefk);
        $request->validate([

            'dInvmasDate' => 'required|date',
            'cInvmasType' => 'required|string|max:50',
            'cInvmasInvCodefk' => 'required|exists:inventories,iInvPK',
            'cInvmasSuppfk' => 'required|exists:suppliers,iSuppPk',
            'iInvmasQuanIn' => 'required|numeric',
            'iInvmasQuanOut' => 'required|numeric',
            'iInvmasInvPricefk' => 'nullable|numeric|exists:inventories,iInvPK',
            'yInvmasDeposit' => 'required|numeric',
            'yInvmasPayment' => 'required|numeric',
            'cInvmasPymtdfk' => 'required|exists:payments,iPymtdPk',
            'cInvmasInvoice' => 'required|string|max:50',
            'cInvmasEmpfk' => 'required|exists:employees,iEmpmasPk',
            'cInvmasCreditorfk' => 'required|string|max:150',
            'cInvmasCompfk' => Rule::requiredIf($user->role === 'system admin'),
        ]);



        $inventoryM = new InventoryMaster();
        $inventoryM->dInvmasDate = $request->dInvmasDate;
        $inventoryM->cInvmasType = $request->cInvmasType;
        $inventoryM->cInvmasInvCodefk = $request->cInvmasInvCodefk;
        $inventoryM->cInvmasSuppfk = $request->cInvmasSuppfk;
        $inventoryM->iInvmasQuanIn = $request->iInvmasQuanIn;
        $inventoryM->iInvmasQuanOut = $request->iInvmasQuanOut;
        $inventoryM->yInvmasDeposit = $request->yInvmasDeposit;
        $inventoryM->yInvmasPayment = $request->yInvmasPayment;
        $inventoryM->cInvmasPymtdfk = $request->cInvmasPymtdfk;
        $inventoryM->cInvmasInvoice = $request->cInvmasInvoice;
        $inventoryM->cInvmasEmpfk = $request->cInvmasEmpfk;
        $inventoryM->cInvmasCreditorfk = $request->cInvmasCreditorfk;
        if (Auth::user()->role === 'system admin') {
            $inventoryM->cInvmasCompfk = $request->cInvmasCompfk; // Admin selects company
        } else {
            $inventoryM->cInvmasCompfk = Auth::user()->cInvmasCompfk; // Regular user uses their company ID
        } // Set the user_id based on the authenticated user

        $inventory = Inventory::findOrFail($request->cInvmasInvCodefk);
        $inventoryM->iInvmasInvPricefk = $inventory->yInvPrice;
        $inventoryM->save();



        return redirect()->route('inventoryM.index')->with('success', 'Inventory record added successfully.');
    }



    // Show form to edit a sale
    public function edit($purchaseM)
    {
        $user = Auth::user();

        $inventoryM = InventoryMaster::findOrFail($purchaseM);

        $user = Auth::user();

        if ($user->role === 'system admin') {
            $paymentMethods = PaymentMethod::all(['iPymtdPk', 'cPymtdDesc']);
            $inventories = Inventory::all(['iInvPK', 'cInvCode', 'cInvName', 'yInvPrice']);
            $employees = Employee::all(['iEmpmasPk', 'cEmpName']);
            $suppliers = Supplier::all(['iSuppPk', 'iSuppDesc']);
            $companies = Company::with(['payments', 'suppliers', 'employees', 'inventories'])->get(['id', 'description']);
        } else {
            $paymentMethods = PaymentMethod::where('company_id', $user->company_id)->get(['iPymtdPk', 'cPymtdDesc']);
            $suppliers = Supplier::where('company_id', $user->company_id)->get(['iSuppPk', 'iSuppDesc']);
            $inventories = Inventory::where('iInvComfk', $user->company_id)->get(['iInvPK', 'cInvCode', 'cInvName', 'yInvPrice']);
            $employees = Employee::where('company_id', $user->company_id)->get(['iEmpmasPk', 'cEmpName']);
            $companies = [];
        }

        return view('inventoryM.edit', compact('inventoryM', 'paymentMethods', 'suppliers', 'companies', 'inventories', 'employees'));
    }

    public function update(Request $request, $inventoryM)
    {
        $user = Auth::user();

        $request->validate([
            'dInvmasDate' => 'required|date',
            'cInvmasType' => 'required|string|max:50',
            'cInvmasInvCodefk' => 'required|exists:inventories,iInvPK',
            'cInvmasSuppfk' => 'required|exists:suppliers,iSuppPk',
            'iInvmasQuanIn' => 'required|numeric',
            'iInvmasQuanOut' => 'required|numeric',
            'iInvmasInvPricefk' => 'nullable|integer|exists:inventories,iInvPK',
            'yInvmasDeposit' => 'required|numeric',
            'yInvmasPayment' => 'required|numeric',
            'cInvmasPymtdfk' => 'required|exists:payments,iPymtdPk',
            'cInvmasInvoice' => 'required|string|max:50',
            'cInvmasEmpfk' => 'required|exists:employees,iEmpmasPk',
            'cInvmasCreditorfk' => 'required|string|max:150',
            'cInvmasCompfk' => Rule::requiredIf($user->role === 'system admin'),
        ]);

        $inventoryM = InventoryMaster::findOrFail($inventoryM);

        // Ensure only the user's company_id is updated
        $inventoryM->update([
            'dInvmasDate' => $request->dInvmasDate,
            'cInvmasType' => $request->cInvmasType,
            'cInvmasSuppfk' => $request->cInvmasSuppfk,
            'cInvmasInvCodefk' => $request->cInvmasInvCodefk,
            'iInvmasQuanIn' => $request->iInvmasQuanIn,
            'iInvmasQuanOut' => $request->iInvmasQuanOut,
            'iInvmasInvPricefk' => $request->iInvmasInvPricefk,
            'yInvmasDeposit' => $request->yInvmasDeposit,
            'yInvmasPayment' => $request->yInvmasPayment,
            'cInvmasPymtdfk' => $request->cInvmasPymtdfk,
            'cInvmasInvoice' => $request->cInvmasInvoice,
            'cInvmasEmpfk' => $request->cInvmasEmpfk,
            'cInvmasCreditorfk' => $request->cInvmasCreditorfk,
            'cInvmasCompfk' => Auth::user()->role === 'system admin' ? $request->cInvmasCompfk : Auth::user()->cInvmasCompfk,
        ]);

        return redirect()->route('inventoryM.index')->with('success', 'Inventory record updated successfully.');
    }


    // Delete sale record
    public function destroy($iInvmasPk)
    {
        $inventoryM = InventoryMaster::findOrFail($iInvmasPk);
        $inventoryM->delete();

        return redirect()->route('inventoryM.index')->with('success', 'Inventory record deleted successfully.');
    }

    public function exportPDF()
    {
        $user = Auth::user(); // Get the authenticated user
        $selectedYear = session('selected_year', date('Y')); // Get selected year or default to current year

        // Filter data for system admins and regular users
        $query = InventoryMaster::with(['paymentMethod', 'supplier', 'employees', 'inventories'])
            ->whereYear('dInvmasDate', $selectedYear);
        if ($user->role !== 'system admin') {
            $query->where('cInvmasCompfk', $user->company_id);
        }

        $inventoryM = $query->get(); // Execute the query
        $totalinventory = $query->count('iInvmasPk'); //  Total sales for the year

        $pdf = PDF::loadView('inventoryM.pdf', compact('inventoryM', 'totalinventory', 'selectedYear'))
            ->setPaper('a4', 'landscape');
        return $pdf->download('InventoryMaster.pdf');
    }
}
