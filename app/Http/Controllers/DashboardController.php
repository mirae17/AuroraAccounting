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

        // Calculate annual total sales
        $annualTotalSales = $monthlySales->sum();

        // Prepare data for monthly display (to avoid missing months)
        $monthlySalesData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlySalesData[$i] = $monthlySales[$i] ?? 0;
        }

        // Calculate totals for Current Month, Yesterday, and Today with the selected year filter
        $totalCurrentSale = \DB::table('sales')
            ->whereYear('dsmasdate', $year)
            ->whereMonth('dsmasdate', date('m'))
            ->sum('ysmaspayment');

        $totalYesterdaySale = \DB::table('sales')
            ->whereYear('dsmasdate', $year)
            ->whereDate('dsmasdate', now()->subDay()->toDateString())
            ->sum('ysmaspayment');

        $totalTodaySale = \DB::table('sales')
            ->whereYear('dsmasdate', $year)
            ->whereDate('dsmasdate', now()->toDateString())
            ->sum('ysmaspayment');

        return view('dashboard.index', compact(
            'monthlySalesData', 'annualTotalSales', 'year', 'years',
            'totalCurrentSale', 'totalYesterdaySale', 'totalTodaySale'
        ));
    }



}
