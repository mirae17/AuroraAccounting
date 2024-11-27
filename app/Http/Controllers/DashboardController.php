<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; 
use App\Models\Sales;
use App\Models\PaymentMethod;
use App\Models\Expense;
use App\Models\User;
use App\Models\Company;
use App\Models\PurchaseM;
use App\Models\ExpensesM;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user(); // Get the logged-in user

        // Fetch and persist selected year
        $year = $request->input('year', session('selected_year', date('Y')));
        session(['selected_year' => $year]);

        // Fetch and persist selected company
        $companyId = $request->input('company_id', session('selected_company', $user->company_id));
        session(['selected_company' => $companyId]);
   
       // Fetch companies based on user's role
       $companies = $user->role === 'system admin'
           ? Company::all()
           : Company::where('id', $user->company_id)->get();
   
       // Retrieve years for selection
       $yearsQuery = DB::table('sales_master')
           ->selectRaw('YEAR(dsmasdate) as year')
           ->distinct()
           ->where('company_id', $companyId); // Filter by company_id
   
       $years = $yearsQuery->pluck('year');
   
       // Retrieve monthly sales data
       $monthlySalesQuery = DB::table('sales_master')
           ->selectRaw('MONTH(dsmasdate) as month, SUM(ysmaspayment) as total')
           ->whereYear('dsmasdate', $year)
           ->where('company_id', $companyId) // Filter by company_id
           ->groupByRaw('MONTH(dsmasdate)');
   
       $monthlySales = $monthlySalesQuery->pluck('total', 'month');
       $annualTotalSales = $monthlySales->sum();
   
       $monthlySalesData = [];
       for ($i = 1; $i <= 12; $i++) {
           $monthlySalesData[$i] = $monthlySales[$i] ?? 0;
       }
   
       // Current Sale
       $totalCurrentSale = DB::table('sales_master')
           ->whereYear('dsmasdate', $year)
           ->whereMonth('dsmasdate', now()->month)
           ->where('company_id', $companyId) // Filter by company_id
           ->sum('ysmaspayment');
   
       // Yesterday's Sale
       $totalYesterdaySale = DB::table('sales_master')
           ->whereYear('dsmasdate', $year)
           ->whereDate('dsmasdate', now()->subDay()->toDateString())
           ->where('company_id', $companyId) // Filter by company_id
           ->sum('ysmaspayment');
   
       // Today's Sale
       $totalTodaySale = DB::table('sales_master')
           ->whereYear('dsmasdate', $year)
           ->whereDate('dsmasdate', now()->toDateString())
           ->where('company_id', $companyId) // Filter by company_id
           ->sum('ysmaspayment');
   
       // Purchases
       $monthlyPurchases = DB::table('purchase_master')
           ->selectRaw('MONTH(dpmasdate) as month, SUM(ypmaspayment) as total')
           ->whereYear('dpmasdate', $year)
           ->where('company_id', $companyId) // Filter by company_id
           ->groupByRaw('MONTH(dpmasdate)')
           ->pluck('total', 'month');
   
       // Purchases (All months)
       $monthlyPurchasesData = [];
       for ($i = 1; $i <= 12; $i++) {
           $monthlyPurchasesData[$i] = $monthlyPurchases[$i] ?? 0;
       }
       $totalAnnualPurchase = $monthlyPurchases->sum();
   
       // Expenses
       $monthlyExpenses = DB::table('expenses_master')
           ->selectRaw('MONTH(dexmasdate) as month, SUM(yexmaspayment) as total')
           ->whereYear('dexmasdate', $year)
           ->where('company_id', $companyId) // Filter by company_id
           ->groupByRaw('MONTH(dexmasdate)')
           ->pluck('total', 'month');
   
       // Expenses (All months)
       $monthlyExpensesData = [];
       for ($i = 1; $i <= 12; $i++) {
           $monthlyExpensesData[$i] = $monthlyExpenses[$i] ?? 0;
       }
       $totalAnnualExpenses = $monthlyExpenses->sum();
   
       // Total Debtor Calculation (Hutang)
       $totalDebtor = DB::table('sales_master')
           ->selectRaw('SUM(ysmaspayment - ysmasdeposit) as total')
           ->whereYear('dsmasdate', $year)
           ->where('cara_jualan', 'Credit')
           ->where('company_id', $companyId) // Filter by company_id
           ->value('total') ?? 0;
   
       // Current Net Profit
       $currentNetProfit = $totalCurrentSale - ($totalAnnualPurchase + $totalAnnualExpenses);
   
       // Net Profit Calculation
       $monthlyNetProfitData = [];
       for ($i = 1; $i <= 12; $i++) {
           $monthlyNetProfitData[$i] = ($monthlySales[$i] ?? 0) - (($monthlyPurchases[$i] ?? 0) + ($monthlyExpenses[$i] ?? 0));
       }
       $annualNetProfit = array_sum($monthlyNetProfitData);
       $averageMonthlyNetProfit = $annualNetProfit / 12;
   
       return view('dashboard.index', compact(
           'year',
           'years',
           'companyId',
           'companies',
           'monthlySalesData',
           'monthlyPurchasesData',
           'monthlyExpensesData',
           'annualTotalSales',
           'totalCurrentSale',
           'totalYesterdaySale',
           'totalTodaySale',
           'totalAnnualPurchase',
           'totalAnnualExpenses',
           'totalDebtor',
           'currentNetProfit',
           'monthlyNetProfitData',
           'annualNetProfit',
           'averageMonthlyNetProfit'
       ));
   }

}
