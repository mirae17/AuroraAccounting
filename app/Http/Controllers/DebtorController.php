<?php

namespace App\Http\Controllers;

use App\Models\Debtor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Company;

class DebtorController extends Controller
{
    public function index()
    {

        $user = Auth::user();

       if ($user->role === 'system admin') {
           
            $debtors = Debtor::with('company')->get();
        } else {
           
            $debtors = Debtor::with('company')->where('company_id', $user->company_id)->get();
        }

        
        return view(' debtor.index', compact('debtors'));
    }

    public function create()
    {
        return view('debtor.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cDebtorCode' => 'required|string|max:10',
            'cDebtorDesc' => 'required|string|max:100',
        ]);

        $debtors= new Debtor();
        $debtors->cDebtorCode = $request->cDebtorCode;
        $debtors->cDebtorDesc = $request->cDebtorDesc;
        $debtors->company_id = Auth::user()->company_id; // Set the user_id based on the authenticated user
        $debtors->save();

        return redirect()->route('debtor.index')
                         ->with('success', 'Debtor added successfully.');
    }

    public function edit(Debtor $debtors)
    {
        return view('debtor.edit', compact('debtors'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'cDebtorCode' => 'required|string|max:10',
            'cDebtorDesc' => 'required|string|max:100',
        ]);

        $debtors = Debtor::findOrFail($id);
        $debtors->update([
            'cDebtorCode' => $request->cDebtorCode,
            'cDebtorDesc' => $request->cDebtorDesc,
            
        ]);

        return redirect()->route('payments.index')
                         ->with('success', 'Debtor updated successfully.');
    }
    
  

    public function destroy($iDebtorPk)
    {
        $debtors = Debtor::findOrFail($iDebtorPk);
        $debtors ->delete();
        return redirect()->route('debtor.index')->with('success', 'Debtor deleted successfully.');
    }
}
