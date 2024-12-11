<?php

namespace App\Http\Controllers;

use App\Models\CompanyMaintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;

class CompanyMaintenanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'system admin') {

            $companyMaintenance = CompanyMaintenance::with('company')->get();
            $companies = Company::all(); // Get all companies
        } else {
            $companyMaintenance = CompanyMaintenance::where('iCompMainName', $user->company_id)->get();
            $companies = [];


        }
        return view('companyMaintenance.index', compact('companyMaintenance', 'companies'));
    }

    // Show the form to create a new company
    public function create()
    {
        $user = Auth::user();



        if ($user->role === 'system admin') {

            $companies = Company::all(['id', 'description']); // Fetch all companies
        } else {

            $companies = []; // Fetch all companies
        }
        return view('companyMaintenance.create', compact('companies'));
    }

    // Store a new company
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'iCompMainName' => 'required|string|exists:companies,id',
            'iCompMainRegNo' => 'required|string|max:255|unique:company_maintenances',
            'iCompMainPhoneNo' => 'required|string|max:255|unique:company_maintenances',
            'iCompMainEmail' => 'required|email|max:255|unique:company_maintenances',
            'iCompMainLogo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'iCompMainAddress' => 'required|string',
        ]);

        // Handle file upload
        $logoPath = null;
        if ($request->hasFile('iCompMainLogo')) {
            // Store the file in the 'logos' directory within the 'public' disk
            $logoPath = $request->file('iCompMainLogo')->store('logos', 'public');
        }

        // Create a new company record
        CompanyMaintenance::create([
            'iCompMainName' => $request->iCompMainName,
            'iCompMainRegNo' => $request->iCompMainRegNo,
            'iCompMainPhoneNo' => $request->iCompMainPhoneNo,
            'iCompMainEmail' => $request->iCompMainEmail,
            'iCompMainLogo' => $logoPath, // Store the relative path in the database
            'iCompMainAddress' => $request->iCompMainAddress,
        ]);

        // Redirect with success message
        return redirect()->route('companyMaintenance.index')->with('success', 'Company created successfully.');
    }


    public function edit($companyMaintenance)
    {
        $user = Auth::user();
        $companyMaintenance = CompanyMaintenance::findOrFail($companyMaintenance);

        if ($user->role === 'system admin') {
            $companies = Company::all(['id', 'code', 'description']); // Fetch all companies
        } else {

            $companies = [];

        }

        return view('companyMaintenance.edit', compact('companyMaintenance', 'companies'));
    }

    // Update the specified resource in storage
    public function update(Request $request, $companyMaintenanceId)
    {
        $user = Auth::user();
        $companyMaintenance = CompanyMaintenance::findOrFail($companyMaintenanceId);

        // Validation
        $request->validate([
            'iCompMainName' => 'required|string|exists:companies,id',
            'iCompMainRegNo' => 'required|string|max:255',
            'iCompMainPhoneNo' => 'required|string|max:255',
            'iCompMainEmail' => 'required|email|max:255',
            'iCompMainLogo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'iCompMainAddress' => 'required|string',
        ]);



        if ($user->role === 'system admin') {
            $request->merge(['iCompMainName' => auth()->user()->company_id]);
        }

        // Handle file upload
        if ($request->hasFile('iCompMainLogo')) {
            // Delete the old logo if it exists
            if ($companyMaintenance->iCompMainLogo) {
                Storage::disk('public')->delete($companyMaintenance->iCompMainLogo);
            }
            // Store the new logo
            $logoPath = $request->file('iCompMainLogo')->store('logos', 'public');
            $companyMaintenance->iCompMainLogo = $logoPath;
        }

        // Update other fields
        $companyMaintenance->update([
            'iCompMainName' => $request->iCompMainName,
            'iCompMainRegNo' => $request->iCompMainRegNo,
            'iCompMainPhoneNo' => $request->iCompMainPhoneNo,
            'iCompMainEmail' => $request->iCompMainEmail,
            'iCompMainAddress' => $request->iCompMainAddress,
        ]);

        return redirect()->route('companyMaintenance.index')->with('success', 'Company updated successfully.');
    }




    // Delete a company
    public function destroy($id)
    {
        $company = CompanyMaintenance::findOrFail($id);
        if ($company->iCompMainLogo) {
            Storage::delete('public/' . $company->iCompMainLogo); // Delete the logo
        }
        $company->delete();

        return redirect()->route('companyMaintenance.index')->with('success', 'Company deleted successfully.');
    }
}
