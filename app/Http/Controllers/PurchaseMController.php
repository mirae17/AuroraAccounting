<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\PaymentMethod;
use App\Models\Supplier;
use App\Models\Company;
use App\Models\PurchaseM;
use Barryvdh\DomPDF\Facade\Pdf;


class PurchaseMController extends Controller
{
    // Display sales records
    public function index(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user
        $selectedYear = session('selected_year', date('Y')); // Get selected year or default to current year
        $selectedCompanyId = session('selected_company_id', null);

        // Fetch the selected company details
        $selectedCompany = $selectedCompanyId ? Company::find($selectedCompanyId) : null;

        // Pass the description or a default value if no company is selected
        $selectedCompanyDescription = $selectedCompany ? $selectedCompany->description : 'All Companies';
    
        // System Admin can view all sales; others only see their company's sales
        $query = PurchaseM::with(['paymentMethod' => function($query) {
                $query->select('iPymtdPk', 'cPymtdDesc');
            }, 'supplier' => function($query) {
                $query->select('iSuppPk', 'iSuppCode', 'iSuppDesc');
            },
             'company'
            ]) ->whereYear('dpmasdate', $selectedYear);

            if ($selectedCompanyId) {
                $query->where('company_id', $selectedCompanyId);
            }
    
        if ($user->role !== 'system admin') {
            $query->where('company_id', $user->company_id); // Filter by company for regular users
        }
    

        $purchaseM = $query->get(); // Execute the query
        $totalPurchase = $query->sum('ypmaspayment'); // Calculate the total sales for the selected year
    
        return view('purchaseM.index', compact('purchaseM', 'totalPurchase', 'selectedYear','selectedCompanyDescription'));
    }

    // Show form to add a new 
    public function create()
    {
        $user = Auth::user();

        if ($user->role === 'system admin') {
            $paymentMethods = PaymentMethod::all(['iPymtdPk', 'cPymtdDesc']);
            $suppliers = Supplier::all(['iSuppPk', 'iSuppDesc']);
            $companies = Company::all(['id', 'description']); // Fetch all companies
        } else {
            $paymentMethods = PaymentMethod::where('company_id', $user->company_id)->get(['iPymtdPk', 'cPymtdDesc']);
            $suppliers = Supplier::where('company_id', $user->company_id)->get(['iSuppPk', 'iSuppDesc']);
            $companies = [];
        }
        return view('purchaseM.create', compact('paymentMethods', 'suppliers','companies'));
    }

  // Store 
  public function store(Request $request)
  {
    $user = Auth::user();
      $request->validate([
          'dpmasdate' => 'required|date',
          'ipmasSuppfk' => 'required|exists:suppliers,iSuppPk',
          'cpmascodeprod' => 'required|string|max:150',
          'ypmaspayment' => 'required|numeric',
          'ypmasdeposit' => 'required|numeric',
          'ipmasPymtdfk' => 'required|exists:payments,iPymtdPk',
          'ipmasinvoiceref' => 'required|string|max:50',
          'cpmasnotes' => 'required|string|max:150',
         'company_id' => Rule::requiredIf($user->role === 'system admin'),
      ]);



      $purchaseM= new PurchaseM();
        $purchaseM->dpmasdate = $request->dpmasdate;
        $purchaseM->ipmasSuppfk = $request->ipmasSuppfk;
        $purchaseM->cpmascodeprod = $request->cpmascodeprod;
        $purchaseM->ypmaspayment = $request->ypmaspayment;
        $purchaseM->cara_jualan = $request->ypmasdeposit == $request->ypmaspayment ? 'Cash' : 'Credit';
        $purchaseM->ypmasdeposit = $request->ypmasdeposit;
        $purchaseM->ipmasPymtdfk = $request->ipmasPymtdfk;
        $purchaseM->ipmasinvoiceref = $request->ipmasinvoiceref;
        $purchaseM->cpmasnotes = $request->cpmasnotes;
        if (Auth::user()->role === 'system admin') {
            $purchaseM->company_id = $request->company_id; // Admin selects company
        } else {
            $purchaseM->company_id = Auth::user()->company_id; // Regular user uses their company ID
        } // Set the user_id based on the authenticated user
        $purchaseM->save();
 
  
     
      return redirect()->route('purchaseM.index')->with('success', 'Purchase record added successfully.');
  }
  


    // Show form to edit a sale
    public function edit($purchaseM)
    {
        $user = Auth::user();

        $purchaseM = PurchaseM::findOrFail($purchaseM);

        if ($user->role === 'system admin') {
            $paymentMethods = PaymentMethod::all(['iPymtdPk', 'cPymtdDesc']);
            $suppliers = Supplier::all(['iSuppPk', 'iSuppDesc']);
            $companies = Company::all(['id', 'description']); // Fetch all companies
        } else {
            $paymentMethods = PaymentMethod::where('company_id', $user->company_id)->get(['iPymtdPk', 'cPymtdDesc']);
            $suppliers = Supplier::where('company_id', $user->company_id)->get(['iSuppPk', 'iSuppDesc']);
            $companies = []; // Fetch all companies
        }
 
        return view('purchaseM.edit', compact('purchaseM', 'paymentMethods', 'suppliers','companies'));
    }

    public function update(Request $request, $purchaseM)
    {
        $user = Auth::user();
        $request->validate([
            'dpmasdate' => 'required|date',
            'ipmasSuppfk' => 'required|exists:suppliers,iSuppPk',
            'cpmascodeprod' => 'required|string|max:150',
            'ypmaspayment' => 'required|numeric',
            'ypmasdeposit' => 'required|numeric',
            'ipmasPymtdfk' => 'required|exists:payments,iPymtdPk',
            'ipmasinvoiceref' => 'required|string|max:50',
            'cpmasnotes' => 'required|string|max:150',
        ]);
    
        $purchaseM = PurchaseM::findOrFail($purchaseM);
    
        // Ensure only the user's company_id is updated
        $purchaseM->update([
            'dpmasdate' => $request->dpmasdate,
            'ipmasSuppfk' => $request->ipmasSuppfk,
            'cpmascodeprod' => $request->cpmascodeprod,
            'ypmaspayment' => $request->ypmaspayment,
            'cara_jualan' => $request->ypmasdeposit == $request->ypmaspayment ? 'Cash' : 'Credit',
            'ypmasdeposit' => $request->ypmasdeposit,
            'ipmasPymtdfk' => $request->ipmasPymtdfk,
            'ipmasinvoiceref' => $request->ipmasinvoiceref,
            'cpmasnotes' =>$request->cpmasnotes,
            'company_id' => Auth::user()->role === 'system admin' ? $request->company_id : Auth::user()->company_id,
        ]);
    
        return redirect()->route('purchaseM.index')->with('success', 'Purchase record updated successfully.');
    }
    

    // Delete sale record
    public function destroy($ipmaspk)
    {
        $purchaseM = PurchaseM::findOrFail($ipmaspk);
        $purchaseM->delete();

        return redirect()->route('purchaseM.index')->with('success', 'Purchase record deleted successfully.');
    }

    public function exportPDF()
    {
        $user = Auth::user(); // Get the authenticated user
        $selectedYear = session('selected_year', date('Y')); // Get selected year or default to current year
    
        // Filter data for system admins and regular users
        $query = PurchaseM::with(['paymentMethod', 'supplier'])
            ->whereYear('dpmasdate', $selectedYear);
        if ($user->role !== 'system admin') {
            $query->where('company_id', $user->company_id);
        }
    
        $purchaseM = $query->get(); // Fetch sales records
        $totalPurchase = $query->sum('ypmaspayment'); // Total sales for the year
    
        $pdf = PDF::loadView('purchaseM.pdf', compact('purchaseM', 'totalPurchase', 'selectedYear'))
        ->setPaper('a4', 'landscape');
        return $pdf->download('purchase.pdf');
    }
    

}


