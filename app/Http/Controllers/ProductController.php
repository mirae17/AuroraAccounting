<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;


class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'system admin') {
           
            $product = Product::with('company')->get();
            $companies = Company::all(); // Get all companies
        } else {
           
            $product = Product::with('company')->where('company_id', $user->company_id)->get();
            $companies = []; 
        }

       

        return view('product.index', compact('product','companies'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'cProCode' => 'required|string|max:10',
            'cProName' => 'required|string|max:50',
            'cProType' => 'required|string|max:50',
            'iProUom' => 'required|string|max:50',
            'yProPrice' => 'required|numeric',
            'iProComfk' => $user->role === 'system admin' ? 'required|exists:companies,id' : 'nullable',
        ]);

        $product= new Product();
        $product->cProCode = $request->cProCode;
        $product->cProName = $request->cProName;
        $product->cProType = $request->cProType;
        $product->iProUom = $request->iProUom;
        $product->yProPrice=$request-> yProPrice;
        if ($user->role === 'system admin') {
            $product->iProComfk = $request->iProComfk;
        } else {
            $product->iProComfk = $user->iProComfk;
        }
        $product->save();

        return redirect()->route('product.index')
                         ->with('success', 'Product added successfully.');
    }
    

    public function update(Request $request, $id)
    { 
        $user = Auth::user();
        // Validate incoming data
        $request->validate([
            'cProCode' => 'required|string|max:10',
            'cProName' => 'required|string|max:50',
            'cProType' => 'required|string|max:50',
            'iProUom' => 'required|string|max:50',
            'yProPrice' => 'required|numeric',
            'iProComfk' => $user->role === 'system admin' ? 'required|exists:companies,id' : 'nullable',
        ]);

        // Find the productss record
        $product = Product::findOrFail($id);

        // Update the record
        $product->update([
            'cProCode' => $request->cProCode,
            'cProName' => $request->cProName,
            'cProType' => $request->cProType,
            'iProUom' => $request->iProUom,
            'yProPrice' => $request->yProPrice,
            'iProComfk' => $request->iProComfk,
            
        ]);

        // Redirect back with success message
        return redirect()->route('product.index')->with('success', 'Product updated successfully.');
    }


    public function destroy($iProPk)
    {
        $product = Product::findOrFail($iProPk);
        $product->delete();
        return redirect()->route('product.index')->with('success', 'Product deleted successfully.');
    }
}
