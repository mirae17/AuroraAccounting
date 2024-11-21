<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Company;

class PaymentMethodController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    

    public function index()
    {

        $user = Auth::user();

        if ($user->role === 'system admin') {
           
            $paymentMethods = PaymentMethod::with('company')->get();
        } else {
           
            $paymentMethods = PaymentMethod::with('company')->where('company_id', $user->company_id)->get();
        }

        
        return view('payments.index', compact('paymentMethods'));
    }

    public function create()
    {
        return view('payments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cPymtdCode' => 'required|string|max:10',
            'cPymtdDesc' => 'required|string|max:100',
        ]);
        


         // Create a new asset
         $paymentMethod = new PaymentMethod();
         $paymentMethod->cPymtdCode = $request->cPymtdCode;
         $paymentMethod->cPymtdDesc = $request->cPymtdDesc;
         $paymentMethod->company_id = Auth::user()->company_id; // Set the user_id based on the authenticated user
         $paymentMethod->save();

     

        return redirect()->route('payments.index')
                         ->with('success', 'Payment method added successfully.');
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('payments.edit', compact('paymentMethod'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'cPymtdCode' => 'required|string|max:10',
            'cPymtdDesc' => 'required|string|max:100',
        ]);

        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->update([
            'cPymtdCode' => $request->cPymtdCode,
            'cPymtdDesc' => $request->cPymtdDesc,
        ]);

        return redirect()->route('payments.index')
                         ->with('success', 'Payment method updated successfully.');
    }
    
  

    public function destroy($iPymtdPk)
    {
        $paymentMethod= PaymentMethod::findOrFail($iPymtdPk);
        $paymentMethod->delete();
        return redirect()->route('payments.index')->with('success', 'Payment method deleted successfully.');
    }
}
