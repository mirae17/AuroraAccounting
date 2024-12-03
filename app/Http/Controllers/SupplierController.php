<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Company;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'system admin') {
           
            $suppliers = Supplier::with('company')->get();
            $companies = Company::all(); // Get all companies
        } else {
           
            $suppliers = Supplier::with('company')->where('company_id', $user->company_id)->get();
            $companies = []; 
        }

       

        return view('suppliers.index', compact('suppliers','companies'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'iSuppCode' => 'required|string|max:10',
            'iSuppDesc' => 'required|string|max:50',
            'company_id' => $user->role === 'system admin' ? 'required|exists:companies,id' : 'nullable',
        ]);

        $suppliers= new Supplier();
        $suppliers->iSuppCode = $request->iSuppCode;
        $suppliers->iSuppDesc = $request->iSuppDesc;
        if ($user->role === 'system admin') {
            $suppliers->company_id = $request->company_id;
        } else {
            $suppliers->company_id = $user->company_id;
        }
        $suppliers->save();

        return redirect()->route('suppliers.index')
                         ->with('success', 'Supplier added successfully.');
    }
    

    public function update(Request $request, $id)
{
    $user = Auth::user();

    // Validate input
    $request->validate([
        'iSuppCode' => 'required|string|max:10',
        'iSuppDesc' => 'required|string|max:50',
        'company_id' => $user->role === 'system admin' ? 'required|exists:companies,id' : 'nullable',
    ]);

    // Fetch the existing supplier by ID
    $supplier = Supplier::findOrFail($id);

    // Update the supplier details
    $supplier->iSuppCode = $request->iSuppCode;
    $supplier->iSuppDesc = $request->iSuppDesc;
    if ($user->role === 'system admin') {
        $supplier->company_id = $request->company_id;
    }
    $supplier->save(); // Save the updated record

    // Redirect with success message
    return redirect()->route('suppliers.index')
                     ->with('success', 'Supplier updated successfully.');
}

    
  

    public function destroy($iSuppPk)
    {
        $suppliers= Supplier::findOrFail($iSuppPk);
        $suppliers->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
