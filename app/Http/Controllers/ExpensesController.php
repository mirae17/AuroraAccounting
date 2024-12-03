<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Company;


class ExpensesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'system admin') {
           
            $expense = Expense::with('company')->get();
            $companies = Company::all(); // Get all companies
        } else {
           
            $expense = Expense::with('company')->where('company_id', $user->company_id)->get();
            $companies = []; 
        }

       
        return view('expenses.index', compact('expense', 'companies'));
    }

    /**
     * Store a newly created expense in the database.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'cExpCode' => 'required|max:6|unique:expenses,cExpCode',
            'cExpDesc' => 'required|max:50',
            'company_id' => $user->role === 'system admin' ? 'required|exists:companies,id' : 'nullable',
        ]);

       

        $expense = new Expense();
        $expense->cExpCode = $request->cExpCode;
        $expense->cExpDesc = $request->cExpDesc;
        $expense->company_id = Auth::user()->company_id; // Set the user_id based on the authenticated user
        if ($user->role === 'system admin') {
            $expense->company_id = $request->company_id;
        } else {
            $expense->company_id = $user->company_id;
        }
        $expense->save();

        return redirect()->route('expenses.index')->with('success', 'Expense created successfully.');
    }

    /**
     * Update the specified expense in the database.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $request->validate([
            'cExpCode' => 'required|string|max:6',
            'cExpDesc' => 'required|string|max:50',
            'company_id' => $user->role === 'system admin' ? 'required|exists:companies,id' : 'nullable',
        ]);
    
        $expense = Expense::findOrFail($id);
        $expense->cExpCode = $request->cExpCode;
        $expense->cExpDesc = $request->cExpDesc;
        
        if ($user->role === 'system admin') {
            $expense->company_id = $request->company_id;
        }
        $expense->save();
    
        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }
    

    /**
     * Remove the specified expense from the database.
     */
    public function destroy($iExpPk)
    {
        $expense = Expense::findOrFail($iExpPk);
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Expenses record deleted successfully.');
    }
}