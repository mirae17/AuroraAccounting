<?php

namespace App\Http\Controllers;

use App\Models\PaymentVoucher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
use App\Models\PaymentMethod;
use Illuminate\Validation\Rule;

class PaymentVoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentVouchers = PaymentVoucher::all();
        $user = Auth::user();

        if ($user->role === 'system admin') {

            $paymentVouchers = PaymentVoucher::with('company', 'paymentMethod')->get();
            $companies = Company::all(); // Get all companies
        } else {

            $paymentVouchers = PaymentVoucher::with('company')->where('iProComfk', $user->company_id)->get();
            $companies = [];
        }
        return view('paymentVoucher.index', compact('paymentVouchers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->role === 'system admin') {
            $paymentMethods = PaymentMethod::all(['iPymtdPk', 'cPymtdDesc']);
            $companies = Company::all(['id', 'description']); // Fetch all companies
        } else {
            $paymentMethods = PaymentMethod::where('company_id', $user->company_id)->get(['iPymtdPk', 'cPymtdDesc']);
            $companies = [];
        }

        $lastVoucher = PaymentVoucher::latest('iPymtVchrPk')->first();
        $voucherNo = $lastVoucher ? 'VCHR-' . str_pad((int) substr($lastVoucher->iPymtVchrPk, 3) + 1, 5, '0', STR_PAD_LEFT)
            : 'VCHR-00001';

        return view('paymentVoucher.create', compact('paymentMethods', 'companies', 'voucherNo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'iPymtVchrPymtdfk' => 'required|exists:payments,iPymtdPk',
            'cPymtVchrDesc' => 'required|string|max:255',
            'dPymtVchrDate' => 'required|date',
            'cPymtVchrNoAcc' => 'required|string|max:50',
            'cPymtVchrMethod' => 'required|string|max:50',
            'cPymtVchrName' => 'required|string|max:100',
            'yPymtVchrTotal' => 'required|numeric',
            'cPymtVchrRefNo' => 'nullable|string|max:50',
            'iPymtVchrCompfk' => Rule::requiredIf($user->role === 'system admin'),
        ]);
        $lastVoucher = PaymentVoucher::latest('iPymtVchrPk')->first();
        $voucherNo = $lastVoucher ? 'VCHR-' . str_pad($lastVoucher->iPymtVchrPk + 1, 5, '0', STR_PAD_LEFT) : 'VCHR-00001';

        PaymentVoucher::create([
            'iPymtVchrPymtdfk' => $request->iPymtVchrPymtdfk,
            'cPymtVchrNo' => $voucherNo,
            'cPymtVchrDesc' => $request->cPymtVchrDesc,
            'dPymtVchrDate' => $request->dPymtVchrDate,
            'cPymtVchrNoAcc' => $request->cPymtVchrNoAcc,
            'cPymtVchrMethod' => $request->cPymtVchrMethod,
            'cPymtVchrName' => $request->cPymtVchrName,
            'yPymtVchrTotal' => $request->yPymtVchrTotal,
            'cPymtVchrRefNo' => $request->cPymtVchrRefNo,
            'iPymtVchrCompfk' => Auth::user()->role === 'system admin' ? $request->iPymtVchrCompfk : Auth::user()->company_id,
        ]);

        return redirect()->route('paymentVoucher.index')->with('success', 'Payment Voucher created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $paymentVoucher = PaymentVoucher::findOrFail($id);
        $user = Auth::user();

        if ($user->role === 'system admin') {
            $paymentMethods = PaymentMethod::all(['iPymtdPk', 'cPymtdDesc']);
            $companies = Company::all(['id', 'description']); // Fetch all companies
        } else {
            $paymentMethods = PaymentMethod::where('company_id', $user->company_id)->get(['iPymtdPk', 'cPymtdDesc']);
            $companies = [];
        }


        return view('paymentVoucher.edit', compact('paymentVoucher', 'paymentMethods', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $request->validate([
            'iPymtVchrPymtdfk' => 'required|exists:payments,iPymtdPk',
            'cPymtVchrDesc' => 'required|string|max:255',
            'dPymtVchrDate' => 'required|date',
            'cPymtVchrNoAcc' => 'required|string|max:50',
            'cPymtVchrMethod' => 'required|string|max:50',
            'cPymtVchrName' => 'required|string|max:100',
            'yPymtVchrTotal' => 'required|numeric',
            'cPymtVchrRefNo' => 'nullable|string|max:50',
            'iPymtVchrCompfk' => Rule::requiredIf($user->role === 'system admin'),
        ]);

        $paymentVoucher = PaymentVoucher::findOrFail($id);
        $paymentVoucher->update($request->all());

        return redirect()->route('paymentVoucher.index')->with('success', 'Payment Voucher updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($iPymtVchrPk)
    {
        $paymentVoucher = PaymentVoucher::findOrFail($iPymtVchrPk);
        $paymentVoucher->delete();
        return redirect()->route('paymentVoucher.index')->with('success', 'Payment Voucher updated successfully.');
    }


}
