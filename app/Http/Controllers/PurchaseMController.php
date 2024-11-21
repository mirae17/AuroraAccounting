<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Sales;
use App\Models\PaymentMethod;
use App\Models\Supplier;
use App\Models\User;
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
    
        // System Admin can view all sales; others only see their company's sales
        $query = PurchaseM::with(['paymentMethod' => function($query) {
                $query->select('iPymtdPk', 'cPymtdDesc');
            }, 'supplier' => function($query) {
                $query->select('iSuppPk', 'iSuppCode', 'iSuppDesc');
            },
             'company'
            ]) ->whereYear('dpmasdate', $selectedYear);
    
        if ($user->role !== 'system admin') {
            $query->where('company_id', $user->company_id); // Filter by company for regular users
        }
    

        $purchaseM = $query->get(); // Execute the query
        $totalPurchase = $query->sum('ypmaspayment'); // Calculate the total sales for the selected year
    
        return view('purchaseM.index', compact('purchaseM', 'totalPurchase', 'selectedYear'));
    }

    // Show form to add a new 
    public function create()
    {
        $user = Auth::user();

        if ($user->role === 'system admin') {
            $paymentMethods = PaymentMethod::all(['iPymtdPk', 'cPymtdDesc']);
            $suppliers = Supplier::all(['iSuppPk', 'iSuppDesc']);
        } else {
            $paymentMethods = PaymentMethod::where('company_id', $user->company_id)->get(['iPymtdPk', 'cPymtdDesc']);
            $suppliers = Supplier::where('company_id', $user->company_id)->get(['iSuppPk', 'iSuppDesc']);
        }
        return view('purchaseM.create', compact('paymentMethods', 'suppliers'));
    }

  // Store 
  public function store(Request $request)
  {
      $request->validate([
          'dpmasdate' => 'required|date',
          'ipmasSuppfk' => 'required|exists:suppliers,iSuppPk',
          'cpmascodeprod' => 'required|string|max:150',
          'ypmaspayment' => 'required|numeric',
          'ypmasdeposit' => 'required|numeric',
          'ipmasPymtdfk' => 'required|exists:payments,iPymtdPk',
          'ipmasinvoiceref' => 'required|string|max:150',
          'cpmasnotes' => 'required|string|max:150',
   
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
        $purchaseM->company_id = Auth::user()->company_id; // Set the user_id based on the authenticated user
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
        } else {
            $paymentMethods = PaymentMethod::where('company_id', $user->company_id)->get(['iPymtdPk', 'cPymtdDesc']);
            $suppliers = Supplier::where('company_id', $user->company_id)->get(['iSuppPk', 'iSuppDesc']);
        }
 
        return view('purchaseM.edit', compact('purchaseM', 'paymentMethods', 'suppliers'));
    }

    public function update(Request $request, $purchaseM)
    {
        $request->validate([
            'dpmasdate' => 'required|date',
            'ipmasSuppfk' => 'required|exists:suppliers,iSuppPk',
            'cpmascodeprod' => 'required|string|max:150',
            'ypmaspayment' => 'required|numeric',
            'ypmasdeposit' => 'required|numeric',
            'ipmasPymtdfk' => 'required|exists:payments,iPymtdPk',
            'ipmasinvoiceref' => 'required|string|max:150',
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
        return $pdf->download('pruchase.pdf');
    }
    

}


