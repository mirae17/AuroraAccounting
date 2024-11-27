<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CompanyFilter
{
    public function handle(Request $request, Closure $next)
    {
        // Check if 'company_id' is in the request (user selected a company)
        if ($request->has('company_id')) {
            // Save the selected company in the session
            session(['selected_company_id' => $request->input('company_id')]);
        }

        // Retrieve the selected company from the session
        $companyId = session('selected_company_id', null);

        if (!$companyId) {
            // Handle cases where no company ID is selected
            return redirect()->back()->withErrors('No company selected.');
        }

        // Attach the selected company to the request
        $request->merge(['selected_company_id' => $companyId]);

        return $next($request);
    }
}
