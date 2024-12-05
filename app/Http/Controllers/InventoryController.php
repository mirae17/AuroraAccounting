<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Company;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'system admin') {

            $inventory = Inventory::with('company')->get();
            $companies = Company::all(); // Get all companies
        } else {

            $inventory = Inventory::with('company')->where('company_id', $user->company_id)->get();
            $companies = [];
        }



        return view('inventory.index', compact('inventory', 'companies'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'cInvCode' => 'required|string|max:10',
            'cInvName' => 'required|string|max:50',
            'cInvType' => 'required|string|max:50',
            'iInvUom' => 'required|string|max:50',
            'yInvPrice' => 'required|numeric',
            'iInvComfk' => $user->role === 'system admin' ? 'required|exists:companies,id' : 'nullable',
        ]);

        $inventory = new Inventory();
        $inventory->cInvCode = $request->cInvCode;
        $inventory->cInvName = $request->cInvName;
        $inventory->cInvType = $request->cInvType;
        $inventory->iInvUom = $request->iInvUom;
        $inventory->yInvPrice = $request->yInvPrice;
        if ($user->role === 'system admin') {
            $inventory->iInvComfk = $request->iInvComfk;
        } else {
            $inventory->iInvComfk = $user->iInvComfk;
        }
        $inventory->save();

        return redirect()->route('inventory.index')
            ->with('success', 'Inventory added successfully.');
    }


    public function update(Request $request, $id)
    {
        $user = Auth::user();
        // Validate incoming data
        $request->validate([
            'cInvCode' => 'required|string|max:6',
            'cInvName' => 'required|string|max:100',
            'cInvType' => 'required|string|max:50',
            'iInvUom' => 'required|string|max:10',
            'yInvPrice' => 'required|numeric',
            'iInvComfk' => $user->role === 'system admin' ? 'required|exists:companies,id' : 'nullable',
        ]);

        // Find the inventory record
        $inventory = Inventory::findOrFail($id);

        // Update the record
        $inventory->update([
            'cInvCode' => $request->cInvCode,
            'cInvName' => $request->cInvName,
            'cInvType' => $request->cInvType,
            'iInvUom' => $request->iInvUom,
            'yInvPrice' => $request->yInvPrice,
            'iInvComfk' => $request->iInvComfk,
        ]);

        // Redirect back with success message
        return redirect()->route('inventory.index')->with('success', 'Inventory updated successfully.');
    }


    public function destroy($iInvPK)
    {
        $inventory = Inventory::findOrFail($iInvPK);
        $inventory->delete();
        return redirect()->route('inventory.index')->with('success', 'Inventory deleted successfully.');
    }


}
