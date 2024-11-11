<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\PaymentMethod;
use App\Models\Expense;
use App\Models\User;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        // Retrieve selected year from request or default to current year
        $year = $request->input('year', date('Y'));
    
        // Retrieve distinct years available in sales data for the year selection dropdown
        $years = \DB::table('sales')->selectRaw('YEAR(dsmasdate) as year')->distinct()->pluck('year');
    
        // Query sales data for each month in the selected year
        $monthlySales = \DB::table('sales')
            ->selectRaw('MONTH(dsmasdate) as month, SUM(ysmaspayment) as total')
            ->whereYear('dsmasdate', $year)
            ->groupByRaw('MONTH(dsmasdate)')
            ->pluck('total', 'month');
    
      
        // Calculate annual total sales, expenses, and purchases
        $annualTotalSales = $monthlySales->sum();
       
    
        // Prepare data for monthly display (e.g., to avoid missing months)
        $monthlySalesData = [];
       
    
        for ($i = 1; $i <= 12; $i++) {
            $monthlySalesData[$i] = $monthlySales[$i] ?? 0;
           
        }
    
        return view('dashboard.index', compact('monthlySalesData', 'annualTotalSales', 'year', 'years'));
    }
    

}
