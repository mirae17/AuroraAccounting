<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    /**
     * Display a listing of the expenses.
     */
    public function index()
    {
        $expense = Expense::all();
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

        Expense::create($request->all());

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
