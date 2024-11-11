<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers= Supplier::all();
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

        Supplier::create([
            'iSuppCode' => $request->iSuppCode,
            'iSuppDesc' => $request->iSuppDesc,
        ]);

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

        $suppliers = Supplier::findOrFail($iSuppPk);
        $suppliers->update([
            'iSuppCode' => $request->iSuppCode,
            'iSuppDesc' => $request->iSuppDesc,
        ]);

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
