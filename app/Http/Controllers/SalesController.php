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
use App\Models\Employee;


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
        $selectedYear = session('selected_year', date('Y'));
            
    
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
            $query->where('company_id', $user->company_id); 
            $companies = []; // Filter by company for regular users
        }
    
        $sales = $query->get(); // Execute the query
        $totalSales = $query->sum('ysmasdeposit'); // Calculate the total sales for the selected year
    
        return view('sales.index', compact('sales', 'totalSales', 'selectedYear'));
    }

    // Show form to add a new sale
    public function create()
    {
        $user = Auth::user();

        if ($user->role === 'system admin') {
            $paymentMethods = PaymentMethod::all(['iPymtdPk', 'cPymtdDesc']);
            $debtors = Debtor::all(['iDebtorPk', 'cDebtorDesc']);
            $employee = Employee::all(['iEmpmasPk', 'cEmpName']);
            $companies = Company::all(['id', 'description']); // Fetch all companies
        } else {
            $paymentMethods = PaymentMethod::where('company_id', $user->company_id)->get(['iPymtdPk', 'cPymtdDesc']);
            $debtors = Debtor::where('company_id', $user->company_id)->get(['iDebtorPk', 'cDebtorDesc']);
            $employee = Employee::where('company_id', $user->company_id)->get(['iEmpmasPk', 'cEmpName']);
            $companies = [];
        }

        return view('sales.create', compact('paymentMethods', 'debtors','employee' ,'companies'));
    }


  // Store new sale
  public function store(Request $request)
{
    $user = Auth::user();

    // Validate the input fields
    $request->validate([
        'dsmasdate' => 'required|date',
        'csmasdesc' => 'required|string|max:150',
        'ysmasdeposit' => 'required|numeric',
        'ismasPymtdfk' => 'required|exists:payments,iPymtdPk',
        'ysmaspayment' => 'required|numeric',
        'ismasinvoiceref' => 'required|string|max:50',
        'ismasusersfk' => 'required|exists:employees,iEmpmasPk',
        'csmasDebtorfk' => 'nullable|exists:debtor,iDebtorPk',
        'company_id' => Rule::requiredIf($user->role === 'system admin'),
    ]);

    $caraJualan = $request->ysmasdeposit == $request->ysmaspayment ? 'Cash' : 'Credit';

    // Save the sale record
    $sale = new Sales();
    $sale->dsmasdate = $request->dsmasdate;
    $sale->csmasdesc = $request->csmasdesc;
    $sale->ysmasdeposit = $request->ysmasdeposit;
    $sale->ismasPymtdfk = $request->ismasPymtdfk;
    $sale->cara_jualan = $caraJualan;
    $sale->ismasinvoiceref =$request->ismasinvoiceref;
    $sale->csmasDebtorfk = $caraJualan === 'Cash' ? null : $request->csmasDebtorfk;
    $sale->ysmaspayment= $request->ysmaspayment;
    $sale->ismasusersfk = $request->ismasusersfk;
    if (Auth::user()->role === 'system admin') {
        $sale->company_id = $request->company_id; // Admin selects company
    } else {
        $sale->company_id = Auth::user()->company_id; // Regular user uses their company ID
    } 
    $sale->save();

    return redirect()->route('sales.index')->with('success', 'Sale record added successfully.');
}


    public function edit($sale)
    {
        $user = Auth::user();
        $sale = Sales::findOrFail($sale);
       

        if ($user->role === 'system admin') {
            $paymentMethods = PaymentMethod::all(['iPymtdPk', 'cPymtdDesc']);
            $debtors = Debtor::all(['iDebtorPk', 'cDebtorDesc']);
            $employee = Employee::where('company_id', )->get(['iEmpmasPk', 'cEmpName']);
            $companies = Company::all(['id', 'description']); // Fetch all companies
        } else {
            $paymentMethods = PaymentMethod::where('company_id', $user->company_id)->get(['iPymtdPk', 'cPymtdDesc']);
            $debtors = Debtor::where('company_id', $user->company_id)->get(['iDebtorPk', 'cDebtorDesc']);
            $employee = Employee::where('company_id', $user->company_id)->get(['iEmpmasPk', 'cEmpName']);
            $companies = []; // No company selection for regular users
        }

        return view('sales.edit', compact('sale', 'paymentMethods', 'debtors','employee', 'companies'));
    }

    public function update(Request $request, $sale)
    {
        $user = Auth::user(); 
        $caraJualan = $request->ysmasdeposit == $request->ysmaspayment ? 'Cash' : 'Credit';

        $request->validate([
            
            'dsmasdate' => 'required|date',
            'csmasdesc' => 'required|string|max:150',
            'ysmasdeposit' => 'required|numeric',
            'ismasPymtdfk' => 'required|exists:payments,iPymtdPk',
            'ysmaspayment' => 'required|numeric',
            'ismasinvoiceref' => 'required|string|max:50',
            'ismasusersfk' => 'required|string|max:150',
            'csmasDebtorfk' => 'nullable|exists:debtor,iDebtorPk',
            'company_id' => Rule::requiredIf($user->role === 'system admin') ,// Require company_id only for System Admin
 
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
            'ismasusersfk' => $request->ismasusersfk,
            'company_id' => Auth::user()->role === 'system admin' ? $request->company_id : Auth::user()->company_id,
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
    public function getDebtors($companyId)
    {
        $debtors = Debtor::where('company_id', $companyId)->get();
        return response()->json(['debtors' => $debtors]);
    }
    
    public function getPaymentMethods($companyId)
    {
        $paymentMethods = PaymentMethod::where('company_id', $companyId)->get();
        return response()->json(['paymentMethods' => $paymentMethods]);
    }
    

}
