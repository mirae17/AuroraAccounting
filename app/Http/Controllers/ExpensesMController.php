<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Sales;
use App\Models\PaymentMethod;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Company;
use App\Models\ExpensesM;
use App\Models\Expense;
use Barryvdh\DomPDF\Facade\Pdf;

class ExpensesMController extends Controller
{
   // Display sales records
   public function index(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user
        $selectedYear = session('selected_year', date('Y')); // Get selected year or default to current year

        // Build the query
        $query = ExpensesM::with([
            'paymentMethod' => function ($query) {
                $query->select('iPymtdPk', 'cPymtdDesc');
            },
            'expenses' => function ($query) {
                $query->select('iExpPk', 'cExpDesc');
            },
            'company' // Include the company relationship
        ])->whereYear('dexmasdate', $selectedYear);

        // Restrict data for regular users
        if ($user->role !== 'system admin') {
            $query->where('company_id', $user->company_id);
        }

        $expenseM = $query->get();
        $totalExpenses = $query->sum('yexmaspayment'); // Total expenses for the year

        return view('expensesM.index', compact('expenseM', 'totalExpenses', 'selectedYear'));
    }


   // Show form to add a new sale
   public function create()
   {
        $user = Auth::user(); // Get the authenticated user

        if ($user->role === 'system admin') {
            // System admin sees all payment methods and expenses
            $paymentMethods = PaymentMethod::all(['iPymtdPk', 'cPymtdDesc']);
            $expenses = Expense::all(['iExpPk', 'cExpDesc']);
        } else {
            // Regular users only see payment methods and expenses for their company
            $paymentMethods = PaymentMethod::where('company_id', $user->company_id)->get(['iPymtdPk', 'cPymtdDesc']);
            $expenses = Expense::where('company_id', $user->company_id)->get(['iExpPk', 'cExpDesc']);
        }

        return view('expensesM.create', compact('paymentMethods', 'expenses'));
   }

 // Store new sale
 public function store(Request $request)
 {
     $request->validate([
         'dexmasdate' => 'required|date',
         'cexmasExpfk' => 'required|exists:expenses,iExpPk',
         'yexmaspayment' => 'required|numeric',
         'iexmasPymtdfk' => 'required|exists:payments,iPymtdPk',
         'iexmasinvoiceref' => 'required|string|max:150',
         'cexmasnotes' => 'required|string|max:150',  

     ]);
 


        $expenseM= new ExpensesM();
        $expenseM->dexmasdate = $request->dexmasdate;
        $expenseM->cexmasExpfk = $request->cexmasExpfk;
        $expenseM->yexmaspayment = $request->yexmaspayment;
        $expenseM->iexmasPymtdfk = $request->iexmasPymtdfk;
        $expenseM->cexmasnotes = $request->cexmasnotes;
        $expenseM->company_id = Auth::user()->company_id; // Set the user_id based on the authenticated user
        $expenseM->save();
 
   
 
     return redirect()->route('expensesM.index')->with('success', 'Expenses record added successfully.');
 }
 


   // Show form to edit a sale
   public function edit($expenseM)
    {
        $user = Auth::user();

        $expenseM = ExpensesM::findOrFail($expenseM);

        if ($user->role === 'system admin') {
            $paymentMethods = PaymentMethod::all(['iPymtdPk', 'cPymtdDesc']);
            $expenses = Expense::all(['iExpPk', 'cExpDesc']);
        } else {
            $paymentMethods = PaymentMethod::where('company_id', $user->company_id)->get(['iPymtdPk', 'cPymtdDesc']);
            $expenses = Expense::where('company_id', $user->company_id)->get(['iExpPk', 'cExpDesc']);
        }

        return view('expensesM.edit', compact('expenseM', 'paymentMethods', 'expenses'));
    }


   public function update(Request $request, $expenseM)
   {
       $request->validate([
        'dexmasdate' => 'required|date',
        'cexmasExpfk' => 'required|exists:expenses,iExpPk',
        'yexmaspayment' => 'required|numeric',
        'iexmasPymtdfk' => 'required|exists:payments,iPymtdPk',
        'iexmasinvoiceref' => 'required|string|max:150',
        'cexmasnotes' => 'required|string|max:150', 
       ]);
   
       $expenseM = ExpensesM::findOrFail($expenseM);
   
       // Ensure only the user's company_id is updated
       
      $expenseM->update([
        'dexmasdate' => $request->dexmasdate,
        'cexmasExpfk' => $request->cexmasExpfk,
        'yexmaspayment' => $request->yexmaspayment,
        'iexmasPymtdfk' => $request->iexmasPymtdfk,
        'iexmasinvoiceref' => $request->iexmasinvoiceref,
        'cexmasnotes' => $request->cexmasnotes, 
      ]);
     
       return redirect()->route('expensesM.index')->with('success', 'Expenses record updated successfully.');
   }
   

   // Delete sale record
   public function destroy($iexmaspk)
   {
       $expenseM = ExpensesM::findOrFail($iexmaspk);
       $expenseM->delete();

       return redirect()->route('expensesM.index')->with('success', 'Expenses record deleted successfully.');
   }

   public function exportPDF()
   {
       $user = Auth::user(); // Get the authenticated user
       $selectedYear = session('selected_year', date('Y')); // Get selected year or default to current year
   
       // Filter data for system admins and regular users
       $query = ExpensesM::with(['paymentMethod', 'expenses'])
           ->whereYear('dexmasdate', $selectedYear);
       if ($user->role !== 'system admin') {
           $query->where('company_id', $user->company_id);
       }
   
       $expenseM = $query->get(); // Fetch sales records
       $totalExpenses = $query->sum('yexmaspayment'); // Total sales for the year
   
       $pdf = PDF::loadView('expensesM.pdf', compact('expenseM', 'totalExpenses', 'selectedYear'))
       ->setPaper('a4', 'landscape');
       return $pdf->download('expensesM.pdf');
   }

 
}
