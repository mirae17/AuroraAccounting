<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
           
            $suppliers= Supplier::with('company')->get();;
        } else {
           
            $suppliers= Supplier::with('company')->where('company_id', $user->company_id)->get();
        }

       

        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'iSuppCode' => 'required|string|max:10',
            'iSuppDesc' => 'required|string|max:100',
        ]);

        $suppliers= new Supplier();
        $suppliers->iSuppCode = $request->iSuppCode;
        $suppliers->iSuppDesc = $request->iSuppDesc;
        $suppliers->company_id = Auth::user()->company_id; // Set the user_id based on the authenticated user
        $suppliers->save();

        return redirect()->route('suppliers.index')
                         ->with('success', 'Supplier added successfully.');
    }

    public function edit(Supplier $suppliers)
    {
        return view('suppliers.edit', compact('suppliers'));
    }

    public function update(Request $request, $iSuppPk)
    {
        $request->validate([
            'iSuppCode' => 'required|string|max:10',
            'iSuppDesc' => 'required|string|max:100',
        ]);

        
        $suppliers= new Supplier();
        $suppliers->iSuppCode = $request->iSuppCode;
        $suppliers->iSuppDesc = $request->iSuppDesc;
        $suppliers->company_id = Auth::user()->company_id; // Set the user_id based on the authenticated user
        $suppliers->save();

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
