<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use Illuminate\Database\QueryException;

class PaymentMethodController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        $user = Auth::user();
        $selectedCompanyId = session('selected_company_id', null);

        // Fetch the selected company details
        $selectedCompany = $selectedCompanyId ? Company::find($selectedCompanyId) : null;

        // Pass the description or a default value if no company is selected
        $selectedCompanyDescription = $selectedCompany ? $selectedCompany->description : 'All Companies';

        if ($user->role === 'system admin') {
            if ($selectedCompanyId) {
                // Filter payment methods by the selected company for system admin
                $paymentMethods = PaymentMethod::with('company')
                    ->where('company_id', $selectedCompanyId)
                    ->get();
            } else {
                // If no company is selected, show all payment methods
                $paymentMethods = PaymentMethod::with('company')->get();
            }
            $companies = Company::all(); // Fetch all companies for selection
        } else {
            // For regular users, filter by their company
            $paymentMethods = PaymentMethod::with('company')
                ->where('company_id', $user->company_id)
                ->get();
            $companies = []; // Regular users don't need to select a company
        }

        return view('payments.index', compact('paymentMethods', 'companies', 'selectedCompany','selectedCompanyDescription'));
    }


    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'cPymtdCode' => 'required|string|max:6',
            'cPymtdDesc' => 'required|string|max:50',
            'company_id' => $user->role === 'system admin' ? 'required|exists:companies,id' : 'nullable',
        ]);

        $paymentMethod = new PaymentMethod();
        $paymentMethod->cPymtdCode = $request->cPymtdCode;
        $paymentMethod->cPymtdDesc = $request->cPymtdDesc;

        if ($user->role === 'system admin') {
            $paymentMethod->company_id = $request->company_id;
        } else {
            $paymentMethod->company_id = $user->company_id;
        }

        $paymentMethod->save();

        return redirect()->route('payments.index')
                         ->with('success', 'Payment method added successfully.');
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $request->validate([
            'cPymtdCode' => 'required|string|max:10',
            'cPymtdDesc' => 'required|string|max:100',
            'company_id' => $user->role === 'system admin' ? 'required|exists:companies,id' : 'nullable',
        ]);

        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->cPymtdCode = $request->cPymtdCode;
        $paymentMethod->cPymtdDesc = $request->cPymtdDesc;

        if ($user->role === 'system admin') {
            $paymentMethod->company_id = $request->company_id;
        }

        $paymentMethod->save();

        return redirect()->route('payments.index')
                         ->with('success', 'Payment method updated successfully.');
    }

    public function destroy($iPymtdPk)
{
    try {
        $paymentMethod = PaymentMethod::findOrFail($iPymtdPk);
        $paymentMethod->delete();

        return response()->json([
            'success' => true,
            'message' => 'Payment method deleted successfully.'
        ]);
    } catch (QueryException $e) {
        if ($e->getCode() === '23000') {
            // Foreign key constraint violation
            return response()->json([
                'success' => false,
                'message' => 'This payment method is in use in Sales, Purchases, or Expenses and cannot be deleted.'
            ]);
        }

        // Other types of exceptions
        return response()->json([
            'success' => false,
            'message' => 'An unexpected error occurred. Please try again later.'
        ]);
    }
}



                         
    }
