<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\Company;
use App\Models\Debtor;

class CompanyController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'system admin') {
           
            $companies = Company::all(); // Get all companies
        } else {
           
           
            $companies = []; 
        }

       

        return view('company.index', compact('companies'));
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:10|unique:companies',
            'description' => 'required|string|max:255',
        ]);
    
        $company = Company::create($validatedData);
    
        return response()->json(['company' => $company], 201); // Return the created company as JSON
    }
    public function getRelatedData($companyId)
    {
        $paymentMethods = PaymentMethod::where('company_id', $companyId)->get(['iPymtdPk', 'cPymtdDesc']);
        $debtors = Debtor::where('company_id', $companyId)->get(['iDebtorPk', 'cDebtorCode', 'cDebtorDesc']);

        return response()->json([
            'paymentMethods' => $paymentMethods,
            'debtors' => $debtors,
        ]);
    }

    public function destroy($id)
    {
        $company= Company::findOrFail($id);
        $company->delete();
        return redirect()->route('company.index')->with('success', 'company deleted successfully.');
    }
}
