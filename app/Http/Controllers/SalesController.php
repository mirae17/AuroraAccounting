<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesMaster;
use App\Models\PaymentMethod;
use App\Models\Supplier;
use App\Models\User;

class SalesController extends Controller
{
    // Display sales records
    public function index()
    {
        $currentYear = date('Y');
        $totalSales = SalesMaster::whereYear('dsmasdate', $currentYear)->sum('ysmasdeposit');
      
    
        // Join with PaymentMethod to get the payment method description
        $sales = SalesMaster::with(['paymentMethod' => function($query) {
            $query->select('iPymtdPk', 'cPymtdDesc'); // Payment method description
        }, 'supplier' => function($query) {
            $query->select('iSuppPk', 'iSuppCode','iSuppDesc'); // Supplier description
        }])->get();
    

        return view('sales.index', compact('sales', 'totalSales'));
    }

    // Show form to add a new sale
    public function create()
    {
        $paymentMethods = PaymentMethod::all(['iPymtdPk', 'cPymtdDesc']); // Fetch primary key and description only
        $suppliers = Supplier::all(['iSuppPk', 'iSuppDesc']); // Assuming 'supplier_name' is the field for supplier names
        return view('sales.create', compact('paymentMethods', 'suppliers'));
    }

  // Store new sale
    public function store(Request $request)
    {
        $request->validate([
            'dsmasdate' => 'required|date',
            'csmasdesc' => 'required|string|max:150',
            'ysmasdeposit' => 'required|numeric',
            'ismasPymtdfk' => 'required|exists:payment_methods,iPymtdPk',
            'ismasSuppfk' => 'required|exists:suppliers,iSuppPk',
            'ismasinvoiceref' => 'required|string|max:150',
            'ysmaspayment' => 'required|numeric',
            'ismausersfk' => 'required|string|max:150',
        ]);

        // Debugging to verify incoming data
        // dd($request->all()); // This will stop execution and display form data

        SalesMaster::create([
            'dsmasdate' => $request->dsmasdate,
            'csmasdesc' => $request->csmasdesc,
            'ysmasdeposit' => $request->ysmasdeposit,
            'ismasPymtdfk' => $request->ismasPymtdfk,
            'ismasSuppfk' => $request->ismasSuppfk,
            'ismasinvoiceref' => $request->ismasinvoiceref,
            'cara_jualan' => $request->ysmasdeposit == $request->ysmaspayment ? 'Cash' : 'Credit',
            'ysmaspayment' => $request->ysmaspayment,
            'ismausersfk' => $request->ismausersfk,
        ]);

        return redirect()->route('sales.index')->with('success', 'Sale record added successfully.');
    }


    // Show individual sale record
    public function show($id)
    {
        $sale = SalesMaster::findOrFail($id);
        return view('sales.show', compact('sale'));
    }

    // Show form to edit a sale
    public function edit($id)
    {
        $sale = SalesMaster::findOrFail($id);
        $paymentMethods = PaymentMethod::all(['iPymtdPk', 'cPymtdDesc']);
        $suppliers = Supplier::all(['iSuppPk', 'supplier_name']);
        return view('sales.edit', compact('sale', 'paymentMethods', 'suppliers'));
    }

    // Update existing sale
    public function update(Request $request, $id)
    {
        $request->validate([
            'dsmasdate' => 'required|date',
            'csmasdesc' => 'required|string|max:150',
            'ysmasdeposit' => 'required|numeric',
            'ysmaspayment' => 'required|numeric',
            'ismasPymtdfk' => 'required|exists:payment_methods,iPymtdPk',
            'ismasSuppfk' => 'required|exists:suppliers,iSuppPk',
        ]);

        $sale = SalesMaster::findOrFail($id);
        $sale->update($request->all());

        return redirect()->route('sales.index')->with('success', 'Sale record updated successfully.');
    }

    // Delete sale record
    public function destroy($id)
    {
        $sale = SalesMaster::findOrFail($id);
        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Sale record deleted successfully.');
    }
}
