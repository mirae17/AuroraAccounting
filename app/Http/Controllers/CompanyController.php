<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:10|unique:companies',
            'description' => 'required|string|max:255',
        ]);
    
        $company = Company::create($validatedData);
    
        return response()->json(['company' => $company], 201); // Return the created company as JSON
    }
    
}
