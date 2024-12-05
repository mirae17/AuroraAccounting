<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Models\CustomerDetail;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerDetailController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'system admin') {

            $customerDetail = CustomerDetail::with('company')->get();
            $companies = Company::all(); // Get all companies
        } else {

            $customerDetail = CustomerDetail::with('company')->where('company_id', $user->company_id)->get();
            $companies = [];
        }


        return view('customerDetail.index', compact('customerDetail', 'companies'));
    }

    // Show form to add a new 
    public function create()
    {
        $user = Auth::user();

        if ($user->role === 'system admin') {
            $customerDetail = CustomerDetail::with('company')->get();
            $companies = Company::get(['id', 'code', 'description']);
        } else {
            $customerDetail = CustomerDetail::with('company')->where('iCustDCompfk', $user->iCustDCompfk)->get();
            $companies = [];
        }
        return view('customerDetail.create', compact('companies', 'customerDetail'));
    }

    // Store 
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'cCustDName' => 'required|string',
            'cCustDPhoneNo' => 'required|string',
            'cCustDAddress' => 'required|string',
            'cCustDCity' => 'required|string',
            'cCustDPostcode' => 'required|string',
            'cCustDState' => 'required|string',
            'iCustDCompfk' => Rule::requiredIf($user->role === 'system admin'),
            'cCustDCompNo' => 'required|string',
            'cCustDCompOfficeNo' => 'required|string',
            'cCustDCompEmail' => 'required|string|email|max:255|unique:customer_details',
            'cCustDCompWebsite' => 'required|url',
        ]);

        $customerDetail = new CustomerDetail();
        $customerDetail->cCustDName = $request->cCustDName;
        $customerDetail->cCustDPhoneNo = $request->cCustDPhoneNo;
        $customerDetail->cCustDAddress = $request->cCustDAddress;
        $customerDetail->cCustDCity = $request->cCustDCity;
        $customerDetail->cCustDPostcode = $request->cCustDPostcode;
        $customerDetail->cCustDState = $request->cCustDState;
        $customerDetail->cCustDCompNo = $request->cCustDCompNo;
        $customerDetail->cCustDCompOfficeNo = $request->cCustDCompOfficeNo;
        $customerDetail->cCustDCompEmail = $request->cCustDCompEmail;
        $customerDetail->cCustDCompWebsite = $request->cCustDCompWebsite;
        if ($user->role === 'system admin') {
            $customerDetail->iCustDCompfk = $request->iCustDCompfk;
        } else {
            $customerDetail->iCustDCompfk = $user->iCustDCompfk;
        }
        $customerDetail->save();

        return redirect()->route('customerDetail.index')->with('success', 'Customer created successfully.');
    }



    // Show form to edit a sale
    public function edit($customerDetail)
    {

        $customerDetail = CustomerDetail::findOrFail($customerDetail);

        $user = Auth::user();

        if ($user->role === 'system admin') {

            $companies = Company::get(['id', 'code', 'description']);
        } else {

            $companies = [];
        }
        return view('customerDetail.edit', compact('companies', 'customerDetail'));

    }

    public function update(Request $request, $customerDetail)
    {
        $user = Auth::user();

        $request->validate([
            'cCustDName' => 'required|string',
            'cCustDPhoneNo' => 'required|string',
            'cCustDAddress' => 'required|string',
            'cCustDCity' => 'required|string',
            'cCustDPostcode' => 'required|string',
            'cCustDState' => 'required|string',
            'iCustDCompfk' => Rule::requiredIf($user->role === 'system admin'),
            'cCustDCompNo' => 'required|string',
            'cCustDCompOfficeNo' => 'required|string',
            'cCustDCompEmail' => 'required|email',
            'cCustDCompWebsite' => 'required|url',
        ]);

        $customerDetail = CustomerDetail::findOrFail($customerDetail);




        $customerDetail->update([
            'cCustDName' => $request->cCustDName,
            'cCustDPhoneNo' => $request->cCustDPhoneNo,
            'cCustDAddress' => $request->cCustDAddress,
            'cCustDCity' => $request->cCustDCity,
            'cCustDState' => $request->cCustDState,
            'cCustDCompNo' => $request->cCustDCompNo,
            'cCustDCompOfficeNo' => $request->cCustDCompOfficeNo,
            'cCustDCompEmail' => $request->cCustDCompEmail,
            'cCustDCompWebsite' => $request->cCustDCompWebsite,
            'iCustDCompfk' => Auth::user()->role === 'system admin' ? $request->iCustDCompfk : Auth::user()->iCustDCompfk,
        ]);

        return redirect()->route('customerDetail.index')->with('success', 'Inventory record updated successfully.');
    }


    // Delete sale record
    public function destroy($iCustDPk)
    {
        $customerDetail = CustomerDetail::findOrFail($iCustDPk);
        $customerDetail->delete();

        return redirect()->route('customerDetail.index')->with('success', 'Customer record deleted successfully.');
    }

    public function exportPDF()
    {
        $user = Auth::user(); // Get the authenticated user

        // Build the query
        $query = CustomerDetail::query(); // Use query builder

        if ($user->role !== 'system admin') {
            $query->where('company_id', $user->company_id);
        }

        $customerDetail = $query->get(); // Execute the query

        // Generate the PDF
        $pdf = PDF::loadView('customerDetail.pdf', compact('customerDetail'))
            ->setPaper('a4', 'landscape')
            ->setOption('margin-top', 10)
            ->setOption('margin-bottom', 10)
            ->setOption('margin-left', 10)
            ->setOption('margin-right', 10);

        return $pdf->download('customerDetail.pdf');
    }

}
