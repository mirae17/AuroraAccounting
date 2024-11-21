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
use App\Models\Debtor;
use Barryvdh\DomPDF\Facade\Pdf;


class SalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Display sales records
    public function index(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user
        $selectedYear = session('selected_year', date('Y')); // Get selected year or default to current year
    
        // System Admin can view all sales; others only see their company's sales
        $query = Sales::with(['paymentMethod' => function($query) {
                $query->select('iPymtdPk', 'cPymtdDesc');
            }, 'debtor' => function($query) {
                $query->select('iDebtorPk', 'cDebtorCode', 'cDebtorDesc');
            },
            'company'
            ])
            ->whereYear('dsmasdate', $selectedYear);
    
        if ($user->role !== 'system admin') {
            $query->where('company_id', $user->company_id); // Filter by company for regular users
        }
    
        $sales = $query->get(); // Execute the query
        $totalSales = $query->sum('ysmaspayment'); // Calculate the total sales for the selected year
    
        return view('sales.index', compact('sales', 'totalSales', 'selectedYear'));
    }

    // Show form to add a new sale
    public function create()
    {

        $user = Auth::user();

        if ($user->role === 'system admin') {
            $paymentMethods = PaymentMethod::all(['iPymtdPk', 'cPymtdDesc']);
            $debtor= Debtor::all(['iDebtorPk', 'cDebtorDesc']);
        } else {
            $paymentMethods = PaymentMethod::where('company_id', $user->company_id)->get(['iPymtdPk', 'cPymtdDesc']);
            $debtor= Debtor::where('company_id', $user->company_id)->get(['iDebtorPk', 'cDebtorDesc']);
        }
       
        return view('sales.create', compact('paymentMethods', 'debtor'));
    }

  // Store new sale
  public function store(Request $request)
{
    // Determine cara_jualan
    $caraJualan = $request->ysmasdeposit == $request->ysmaspayment ? 'Cash' : 'Credit';

    // Conditional validation
    $request->validate([
        'dsmasdate' => 'required|date',
        'csmasdesc' => 'required|string|max:150',
        'ysmasdeposit' => 'required|numeric',
        'ismasPymtdfk' => 'required|exists:payments,iPymtdPk',
        'ismasinvoiceref' => 'required|string|max:150',
        'ysmaspayment' => 'required|numeric',
        'ismasusersfk' => 'required|string|max:150',
        // Debtor validation based on cara_jualan
        'csmasDebtorfk' => $caraJualan === 'Credit' ? 'required|exists:debtor,iDebtorPk' : 'nullable',
    ]);

    // Save the sale
    $sale = new Sales();
    $sale->dsmasdate = $request->dsmasdate;
    $sale->csmasdesc = $request->csmasdesc;
    $sale->ysmasdeposit = $request->ysmasdeposit;
    $sale->ismasPymtdfk = $request->ismasPymtdfk;
    $sale->cara_jualan = $caraJualan;
    $sale->csmasDebtorfk = $caraJualan === 'Cash' ? null : $request->csmasDebtorfk; // Set to null if Cash
    $sale->ismasinvoiceref = $request->ismasinvoiceref;
    $sale->ysmaspayment = $request->ysmaspayment;
    $sale->ismasusersfk = $request->ismasusersfk;
    $sale->company_id = Auth::user()->company_id;
    $sale->save();

    return redirect()->route('sales.index')->with('success', 'Sale record added successfully.');
}

  


    // Show form to edit a sale
    public function edit($sale)
    {
       
        $user = Auth::user();
        $sale = Sales::findOrFail($sale);
        if ($user->role === 'system admin') {
            $paymentMethods = PaymentMethod::all(['iPymtdPk', 'cPymtdDesc']);
            $debtor= Debtor::all(['iDebtorPk', 'cDebtorDesc']);
        } else {
            $paymentMethods = PaymentMethod::where('company_id', $user->company_id)->get(['iPymtdPk', 'cPymtdDesc']);
            $debtor= Debtor::where('company_id', $user->company_id)->get(['iDebtorPk', 'cDebtorDesc']);
        }
        return view('sales.edit', compact('sale', 'paymentMethods', 'debtor'));
    }

    public function update(Request $request, $sale)
{
    $caraJualan = $request->ysmasdeposit == $request->ysmaspayment ? 'Cash' : 'Credit';

    $request->validate([
        'dsmasdate' => 'required|date',
        'csmasdesc' => 'required|string|max:150',
        'ysmasdeposit' => 'required|numeric',
        'ysmaspayment' => 'required|numeric',
        'ismasPymtdfk' => 'required|exists:payments,iPymtdPk',
        'csmasDebtorfk' => $caraJualan === 'Credit' ? 'required|exists:debtor,iDebtorPk' : 'nullable',
    ]);

    $sale = Sales::findOrFail($sale);

    $sale->update([
        'dsmasdate' => $request->dsmasdate,
        'csmasdesc' => $request->csmasdesc,
        'ysmasdeposit' => $request->ysmasdeposit,
        'ismasPymtdfk' => $request->ismasPymtdfk,
        'cara_jualan' => $caraJualan,
        'csmasDebtorfk' => $caraJualan === 'Cash' ? null : $request->csmasDebtorfk, // Set to null if Cash
        'ismasinvoiceref' => $request->ismasinvoiceref,
        'ysmaspayment' => $request->ysmaspayment,
    ]);

    return redirect()->route('sales.index')->with('success', 'Sale record updated successfully.');
}

    

    // Delete sale record
    public function destroy($ismaspk)
    {
        $sale = Sales::findOrFail($ismaspk);
        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Sale record deleted successfully.');
    }

    public function exportPDF()
    {
        $user = Auth::user(); // Get the authenticated user
        $selectedYear = session('selected_year', date('Y')); // Get selected year or default to current year
    
        // Filter data for system admins and regular users
        $query = Sales::with(['paymentMethod', 'debtor'])
            ->whereYear('dsmasdate', $selectedYear);
        if ($user->role !== 'system admin') {
            $query->where('company_id', $user->company_id);
        }
    
        $sales = $query->get(); // Fetch sales records
        $totalSales = $query->sum('ysmaspayment'); // Total sales for the year
    
        $pdf = PDF::loadView('sales.pdf', compact('sales', 'totalSales', 'selectedYear'))
        ->setPaper('a4', 'landscape');
        return $pdf->download('sales.pdf');
    }
    

}
