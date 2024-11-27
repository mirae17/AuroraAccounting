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
        $selectedCompanyId = session('selected_company_id', null);

        // Fetch the selected company details
        $selectedCompany = $selectedCompanyId ? Company::find($selectedCompanyId) : null;

        // Pass the description or a default value if no company is selected
        $selectedCompanyDescription = $selectedCompany ? $selectedCompany->description : 'All Companies';
            
        
    
        // System Admin can view all sales; others only see their company's sales
        $query = Sales::with(['paymentMethod' => function($query) {
                $query->select('iPymtdPk', 'cPymtdDesc');
            }, 'debtor' => function($query) {
                $query->select('iDebtorPk', 'cDebtorCode', 'cDebtorDesc');
            },
            'employee' => function($query) {
                $query->select('iEmpmasPk', 'cEmpNo', 'cEmpName');
            },
            'company'
            ])
            ->whereYear('dsmasdate', $selectedYear);
            // Apply company filter if a company is selected
            if ($selectedCompanyId) {
                $query->where('company_id', $selectedCompanyId);
            }
    
        if ($user->role !== 'system admin') {
            $query->where('company_id', $user->company_id); 
            $companies = []; // Filter by company for regular users
        }
    
        $sales = $query->get(); // Execute the query
        $totalSales = $query->sum('ysmasdeposit'); // Calculate the total sales for the selected year
    
        return view('sales.index', compact('sales', 'totalSales', 'selectedYear','selectedCompanyDescription'));
    }

    // Show form to add a new sale
    public function create()
    {
        $user = Auth::user();
        $selectedCompanyId = session('selected_company_id', null);

        if ($user->role === 'system admin') {
            $paymentMethods = $selectedCompanyId
                ? PaymentMethod::where('company_id', $selectedCompanyId)->get(['iPymtdPk', 'cPymtdDesc'])
                : PaymentMethod::all(['iPymtdPk', 'cPymtdDesc']);
            $debtor = $selectedCompanyId
                ? Debtor::where('company_id', $selectedCompanyId)->get(['iDebtorPk', 'cDebtorDesc'])
                : Debtor::all(['iDebtorPk', 'cDebtorDesc']);
            $employee = $selectedCompanyId
                ? Employee::where('company_id', $selectedCompanyId)->get(['iEmpmasPk', 'cEmpName'])
                : Employee::all(['iEmpmasPk', 'cEmpName']);
            $companies = $selectedCompanyId
                ? Company::where('id', $selectedCompanyId)->get(['id', 'description']) // Fetch only the selected company
                : [];
        } else {
            $paymentMethods = PaymentMethod::where('company_id', $user->company_id)->get(['iPymtdPk', 'cPymtdDesc']);
            $debtor = Debtor::where('company_id', $user->company_id)->get(['iDebtorPk', 'cDebtorDesc']);
            $employee = Employee::where('company_id', $user->company_id)->get(['iEmpmasPk', 'cEmpName']);
            $companies = []; // No company selection for regular users
        }

        return view('sales.create', compact('paymentMethods', 'debtor', 'employee', 'companies'));
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
        'ismasusersfk' => 'required|exists:employees,iEmpmasPk',
        'csmasDebtorfk' => 'nullable|exists:debtors,iDebtorPk',
        'company_id' => Rule::requiredIf($user->role === 'system admin') ,// Require company_id only for System Admin
    ]);

    $caraJualan = $request->ysmasdeposit == $request->ysmaspayment ? 'Cash' : 'Credit';

    // Save the sale record
    $sale = new Sales();
    $sale->dsmasdate = $request->dsmasdate;
    $sale->csmasdesc = $request->csmasdesc;
    $sale->ysmasdeposit = $request->ysmasdeposit;
    $sale->ismasPymtdfk = $request->ismasPymtdfk;
    $sale->cara_jualan = $caraJualan;
    $sale->csmasDebtorfk = $caraJualan === 'Cash' ? null : $request->csmasDebtorfk;
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
        $selectedCompanyId = $sale->company_id; // Get the company associated with the sale

        if ($user->role === 'system admin') {
            $paymentMethods = PaymentMethod::where('company_id', $selectedCompanyId)->get(['iPymtdPk', 'cPymtdDesc']);
            $debtor = Debtor::where('company_id', $selectedCompanyId)->get(['iDebtorPk', 'cDebtorDesc']);
            $employee = Employee::where('company_id', $selectedCompanyId)->get(['iEmpmasPk', 'cEmpName']);
            $companies = Company::where('id', $selectedCompanyId)->get(['id', 'description']); // Fetch only the selected company
        } else {
            $paymentMethods = PaymentMethod::where('company_id', $user->company_id)->get(['iPymtdPk', 'cPymtdDesc']);
            $debtor = Debtor::where('company_id', $user->company_id)->get(['iDebtorPk', 'cDebtorDesc']);
            $employee = Employee::where('company_id', $user->company_id)->get(['iEmpmasPk', 'cEmpName']);
            $companies = []; // No company selection for regular users
        }

        return view('sales.edit', compact('sale', 'paymentMethods', 'debtor', 'employee', 'companies'));
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
            'ismasusersfk'=> 'required|exists:employees,iEmpmasPk',
            
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
