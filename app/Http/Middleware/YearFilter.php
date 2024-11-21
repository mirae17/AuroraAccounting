<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class YearFilter
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the 'selected_year' is provided in the request (user manually selected a year)
        if ($request->has('selected_year')) {
            // Save the selected year in the session
            session(['selected_year' => $request->input('selected_year')]);
        }

        // Retrieve the selected year from the session or use the current year if none is set
        $year = session('selected_year', date('Y'));

        // Attach the selected year to the request
        $request->merge(['selected_year' => $year]);

        return $next($request);
    }
}
