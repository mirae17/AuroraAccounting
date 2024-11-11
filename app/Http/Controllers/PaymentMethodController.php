<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::all();
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

        PaymentMethod::create([
            'cPymtdCode' => $request->cPymtdCode,
            'cPymtdDesc' => $request->cPymtdDesc,
        ]);

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
