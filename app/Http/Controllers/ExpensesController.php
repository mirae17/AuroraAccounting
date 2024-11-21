<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
        } else {
           
            $expense = Expense::with('company')->where('company_id', $user->company_id)->get();
        }

       
        return view('expenses.index', compact('expense'));
    }

    /**
     * Show the form for creating a new expense.
     */
    public function create()
    {
        return view('expenses.create');
    }

    /**
     * Store a newly created expense in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cExpCode' => 'required|max:6|unique:expenses,cExpCode',
            'cExpDesc' => 'required|max:50',
        ]);

       

        $expense = new Expense();
        $expense->cExpCode = $request->cExpCode;
        $expense->cExpDesc = $request->cExpDesc;
        $expense->company_id = Auth::user()->company_id; // Set the user_id based on the authenticated user
        $expense->save();

        return redirect()->route('expenses.index')->with('success', 'Expense created successfully.');
    }

    /**
     * Show the form for editing the specified expense.
     */
    public function edit(Expense $expense)
    {
        return view('expenses.edit', compact('expense'));
    }

    /**
     * Update the specified expense in the database.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'cExpCode' => 'required|string|max:6',
            'cExpDesc' => 'required|string|max:50',
        ]);
    
        $expense = Expense::findOrFail($id);
        $expense->cExpCode = $request->cExpCode;
        $expense->cExpDesc = $request->cExpDesc;
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
