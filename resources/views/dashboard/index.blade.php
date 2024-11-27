@extends('layouts.template')

@section('content')

<div class="container my-5">
   <!-- Main Title and Year & Company Filters -->
   <div class="d-flex justify-content-between align-items-center mb-5">
        <h2 class="text-center" style="font-size: 2.2rem; font-weight: bold; color: #444;">
            Dashboard 
        </h2>

        <form method="GET" action="{{ route('dashboard.index') }}" class="d-flex align-items-center">
           
               
        <!-- Year Selection -->
        <div class="p-3 shadow-sm" style="background-color: #f1f1f1; border-radius: 15px;">
                <label for="year" style="font-weight: bold; font-size: 1.1rem; color: #444;">Year:</label>
                <select name="year" id="year" class="form-control" style="width: 120px; border-radius: 8px;">
                    @foreach($years as $yearOption)
                        <option value="{{ $yearOption }}" {{ $year == $yearOption ? 'selected' : '' }}>
                            {{ $yearOption }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            @if (Auth::user()->role === 'system admin')
           <!-- Company Selection -->
        <div class="p-3 shadow-sm ml-3" style="background-color: #f1f1f1; border-radius: 15px;">
            <label for="company_id" style="font-weight: bold; font-size: 1.1rem; color: #444;">Company:</label>
            <select name="company_id" id="company_id" class="form-control" style="width: 150px; border-radius: 8px;">
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ $companyId == $company->id ? 'selected' : '' }}>
                        {{ $company->description }}
                    </option>
                @endforeach
            </select>
        </div>
            @endif
             <!-- Submit Button -->
            <button type="submit" class="btn btn-primary ml-3" style="border-radius: 8px; font-weight: bold;">
                <i class="fas fa-filter"></i>
            </button>
        </form>
    </div>

    <!-- Main Dashboard Metrics -->
    <div class="row">
        <!-- Sales Summary Column -->
        <div class="col-md-4">
            <div class="mb-4 p-4 text-center shadow-sm" style="background-color: #f9fafb; border-radius: 10px;">
                <h6 class="text-muted">Total Current Sale</h6>
                <h3 class="text-primary" style="font-weight: bold;">RM {{ number_format($totalCurrentSale, 2) }}</h3>
            </div>
            <div class="mb-4 p-4 text-center shadow-sm" style="background-color: #f9fafb; border-radius: 10px;">
                <h6 class="text-muted">Total Yesterday Sale</h6>
                <h3 class="text-primary" style="font-weight: bold;">RM {{ number_format($totalYesterdaySale, 2) }}</h3>
            </div>
            <div class="mb-4 p-4 text-center shadow-sm" style="background-color: #f9fafb; border-radius: 10px;">
                <h6 class="text-muted">Total Today Sale</h6>
                <h3 class="text-primary" style="font-weight: bold;">RM {{ number_format($totalTodaySale, 2) }}</h3>
            </div>
        </div>

        <!-- Expenses and Debts Column -->
        <div class="col-md-4">
            <!-- Total Current Purchase + Expenses -->
            <div class="mb-4 p-4 text-center shadow-sm" style="background-color: #fef6f6; border-radius: 10px;">
                <h6 class="text-muted">Total Current Purchase + Expenses</h6>
                <h3 class="text-danger" style="font-weight: bold;">
                    RM {{ number_format($totalAnnualPurchase + $totalAnnualExpenses, 2) }}
                </h3>
            </div>
            <div class="mb-4 p-4 text-center shadow-sm" style="background-color: #fef6f6; border-radius: 10px;">
                <h6 class="text-muted">Total Debtor</h6>
                <h3 class="text-danger" style="font-weight: bold;">RM {{ number_format($totalDebtor, 2) }} </h3>
            </div>
            <div class="mb-4 p-4 text-center shadow-sm" style="background-color: #fef6f6; border-radius: 10px;">
                <h6 class="text-muted">Total Creditor</h6>
                <h3 class="text-danger" style="font-weight: bold;">RM </h3>
            </div>
        </div>

        <!-- Stock and Profit Column -->
        <div class="col-md-4">
            <div class="mb-4 p-4 text-center shadow-sm" style="background-color: #fff5e5; border-radius: 10px;">
                <h6 class="text-muted">Total Stock</h6>
                <h3 style="color: #333; font-weight: bold;"></h3>
            </div>
            <!-- <div class="mb-4 p-4 text-center shadow-sm" style="background-color: #fff5e5; border-radius: 10px;">
                <h6 class="text-muted">Bilangan Stok BM</h6>
                <h3 style="color: #333; font-weight: bold;"></h3>
            </div> -->
            <div class="mb-4 p-4 text-center shadow-sm" style="background-color: #fff5e5; border-radius: 10px;">
                <h6 class="text-muted">Current Net Profit</h6>
                <h3 style="color: #333; font-weight: bold;">RM {{ number_format($currentNetProfit, 2) }} </h3>
            </div>
        </div>
    </div>

    <!-- Monthly Tables -->
    <div class="row mt-5">
       <!-- Monthly Sales Table -->
            <div class="col-md-4">
                <div class="table-responsive shadow-sm" style="border-radius: 10px; overflow: hidden;">
                    <h5 class="bg-primary text-white text-center p-3">Monthly Sale</h5>
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr><th>Month</th><th>Sale (RM)</th></tr>
                        </thead>
                        <tbody>
                        @foreach($monthlySalesData as $month => $total)
                            <tr>
                                <td>{{ DateTime::createFromFormat('!m', $month)->format('F') }}</td>
                                <td>RM {{ number_format($total, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="text-center p-3 bg-light">
                        <strong>Total Annual Sale: RM {{ number_format($annualTotalSales, 2) }}</strong>
                    </div>
                </div>
            </div>


        <!-- Purchases Table -->
        <div class="col-md-4">
            <div class="table-responsive shadow-sm" style="border-radius: 10px; overflow: hidden;">
                <h5 class="bg-danger text-white text-center p-3">Monthly Purchase</h5>
                <table class="table table-striped mb-0">
                    <thead>
                        <tr><th>Month</th><th>Purchase (RM)</th></tr>
                    </thead>
                    <tbody>
                    @foreach($monthlyPurchasesData as $month => $total)
                        <tr>
                            <td>{{ DateTime::createFromFormat('!m', $month)->format('F') }}</td>
                            <td>RM {{ number_format($total, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    </table>
                    <div class="text-center p-3 bg-light">
                        <strong>Total Annual Purchase: RM {{ number_format($totalAnnualPurchase, 2) }}</strong>
                 </div>
            </div>
        </div>

        <!-- Expenditures Table -->
        <div class="col-md-4">
            <div class="table-responsive shadow-sm" style="border-radius: 10px; overflow: hidden;">
                <h5 class="bg-warning text-white text-center p-3">Monthly Expenses</h5>
                <table class="table table-striped mb-0">
                    <thead>
                        <tr><th>Month</th><th>Expenses (RM)</th></tr>
                    </thead>
                    <tbody>
                    @foreach($monthlyExpensesData as $month => $total)
                    <tr>
                        <td>{{ DateTime::createFromFormat('!m', $month)->format('F') }}</td>
                        <td>RM {{ number_format($total, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
                </table>
                <div class="text-center p-3 bg-light">
                    <strong>Total Annual Expenses: RM {{ number_format($totalAnnualExpenses, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>
<!-- Net Profit Table -->
<div class="table-responsive mt-5 shadow-sm" style="border-radius: 10px; overflow-x: auto;">
    <h5 class="bg-secondary text-white text-center p-3">Net Profit</h5>
    <table class="table table-bordered text-center mb-0">
        <thead class="bg-dark text-white">
            <tr>
                @foreach ($monthlyNetProfitData as $month => $total)
                    <td>{{DateTime::createFromFormat('!m', $month)->format('F') }}</td>
                @endforeach
                <td>Average Net Profit</td>
                <td>Annual Net Profit</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach ($monthlyNetProfitData as $total)
                    <td>RM {{ number_format($total, 2) }}</td>
                @endforeach
                <td>RM {{ number_format($averageMonthlyNetProfit, 2) }}</td>
                <td>RM {{ number_format($annualNetProfit, 2) }}</td>
            </tr>
        </tbody>
    </table>
</div>




</div>
<div class="bg-dark text-white mt-5 p-5 shadow-sm" style="border-radius: 10px;">
    <h4 class="text-center">Analysis</h4>
    <canvas id="salesChart" style="height: 300px;"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        
        const salesData = @json($monthlySalesData); // Dynamic data for sales
        const expensesData = @json($monthlyExpensesData); // Dynamic data for expenses
        const purchasesData = @json($monthlyPurchasesData); // Dynamic data for purchases

        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
              
                datasets: [
                    {
                        label: 'Sales',
                        data: salesData,
                        borderColor: 'blue',
                        backgroundColor: 'rgba(0, 0, 255, 0.1)',
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Expenses',
                        data: expensesData,
                        borderColor: 'yellow',
                        backgroundColor: 'rgba(255, 255, 0, 0.1)',
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Purchases',
                        data: purchasesData,
                        borderColor: 'red',
                        backgroundColor: 'rgba(255, 0, 0, 0.1)',
                        fill: true,
                        tension: 0.3
                    }
                    
                 
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: 'white'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: 'white' },
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { color: 'white' },
                        grid: { color: 'rgba(255, 255, 255, 0.1)' }
                    }
                }
            }
        });
    });
</script>

@endsection