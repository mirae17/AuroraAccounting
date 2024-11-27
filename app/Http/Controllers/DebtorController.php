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
        $companyId = $request->input('selected_company_id');

       if ($user->role === 'system admin') {
           
            $debtors = Debtor::with('company')->get();
            $companies = Company::all(); // Get all companies
        } else {
           
            $debtors = Debtor::with('company')->where('company_id', $user->company_id)->get();
            $companies = []; 
        }

        
        return view(' debtor.index', compact('debtors','companies'));
    }


    public function store(Request $request)
{
    $user = Auth::user();


    $request->validate([
        'cDebtorCode' => 'required|string|max:10',
        'cDebtorDesc' => 'required|string|max:50',
        'company_id' => $user->role === 'system admin' 
        ? 'required|exists:companies,id' 
        : 'required',
    ]);

    // Create a new Debtor
    $debtors = new Debtor();
    $debtors->cDebtorCode = $request->cDebtorCode;
    $debtors->cDebtorDesc = $request->cDebtorDesc;

    if ($user->role === 'system admin') {
        $debtors->company_id = $request->company_id;
    } else {
        $debtors->company_id = $user->company_id;
    }
   
    
    $debtors->save();

    // Redirect with success message
    return redirect()->route('debtor.index')->with('success', 'Debtor added successfully.');
}


    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $request->validate([
            'cDebtorCode' => 'required|string|max:10',
            'cDebtorDesc' => 'required|string|max:50',
            'company_id' => $user->role === 'system admin' ? 'required|exists:companies,id' : 'nullable',
        ]);

        $debtors = Debtor::findOrFail($id);
        $debtors->cDebtorCode = $request->cDebtorCode;
        $debtors->cDebtorDesc = $request->cDebtorDesc;
        if ($user->role === 'system admin') {
            $debtors->company_id = $request->company_id;
        }

    

        $debtors->save();

        return redirect()->route('debtor.index')
                         ->with('success', 'Debtor updated successfully.');
    }
    
  

    public function destroy($iDebtorPk)
    {
        $debtors = Debtor::findOrFail($iDebtorPk);
        $debtors ->delete();
        return redirect()->route('debtor.index')->with('success', 'Debtor deleted successfully.');
    }
}
