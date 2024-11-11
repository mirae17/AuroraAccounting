@extends('layouts.template_dashboard')

@section('content')
<div class="container my-5">
    <!-- Main Title -->
    <h2 class="text-center mb-5" style="font-size: 2.2rem; font-weight: bold; color: #444;">Main Dashboard</h2>
    <!-- Year Selection Dropdown -->
    <form method="GET" action="{{ route('dashboard.index') }}" class="mb-4">
        <div class="form-group d-flex justify-content-center align-items-center">
            <label for="year" class="mr-3">Select Year:</label>
            <select name="year" id="year" class="form-control w-25">
                @foreach($years as $yearOption)
                    <option value="{{ $yearOption }}" {{ $year == $yearOption ? 'selected' : '' }}>{{ $yearOption }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary ml-3">Filter</button>
        </div>
    </form>
    
    <!-- Main Dashboard Metrics -->
    <div class="row">
        <!-- Sales Summary Column -->
        <div class="col-md-4">
            <div class="mb-4 p-4 text-center shadow-sm" style="background-color: #f9fafb; border-radius: 10px;">
                <h6 class="text-muted">Total Current Sale</h6>
                <h3 class="text-primary" style="font-weight: bold;">RM </h3>
            </div>
            <div class="mb-4 p-4 text-center shadow-sm" style="background-color: #f9fafb; border-radius: 10px;">
                <h6 class="text-muted">Total Yesterday Sale</h6>
                <h3 class="text-primary" style="font-weight: bold;">RM </h3>
            </div>
            <div class="mb-4 p-4 text-center shadow-sm" style="background-color: #f9fafb; border-radius: 10px;">
                <h6 class="text-muted">Total Today Sale</h6>
                <h3 class="text-primary" style="font-weight: bold;">RM </h3>
            </div>
        </div>

        <!-- Expenses and Debts Column -->
        <div class="col-md-4">
            <div class="mb-4 p-4 text-center shadow-sm" style="background-color: #fef6f6; border-radius: 10px;">
                <h6 class="text-muted">Total Curent Purchase + Expenses</h6>
                <h3 class="text-danger" style="font-weight: bold;">RM </h3>
            </div>
            <div class="mb-4 p-4 text-center shadow-sm" style="background-color: #fef6f6; border-radius: 10px;">
                <h6 class="text-muted">Total Debtor</h6>
                <h3 class="text-danger" style="font-weight: bold;">RM </h3>
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
                <h3 style="color: #333; font-weight: bold;">RM </h3>
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
                    <tbody>
                    <tr><td>January</td><td>RM </td></tr>
                    <tr><td>February</td><td>RM </td></tr>
                    <tr><td>March</td><td>RM </td></tr>
                    <tr><td>April</td><td>RM </td></tr>
                    <tr><td>May</td><td>RM </td></tr>
                    <tr><td>June</td><td>RM </td></tr>
                    <tr><td>July</td><td>RM </td></tr>
                    <tr><td>August</td><td>RM </td></tr>
                    <tr><td>September</td><td>RM </td></tr>
                    <tr><td>October</td><td>RM </td></tr>
                    <tr><td>November</td><td>RM </td></tr>
                    <tr><td>December</td><td>RM </td></tr>
                    </tbody>
                    </tbody>
                </table>
                <div class="text-center p-3 bg-light">
                    <strong>Total Annual Purchase: RM </strong>
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
                    <tr><td>January</td><td>RM </td></tr>
                    <tr><td>February</td><td>RM </td></tr>
                    <tr><td>March</td><td>RM </td></tr>
                    <tr><td>April</td><td>RM </td></tr>
                    <tr><td>May</td><td>RM </td></tr>
                    <tr><td>June</td><td>RM </td></tr>
                    <tr><td>July</td><td>RM </td></tr>
                    <tr><td>August</td><td>RM </td></tr>
                    <tr><td>September</td><td>RM </td></tr>
                    <tr><td>October</td><td>RM </td></tr>
                    <tr><td>November</td><td>RM </td></tr>
                    <tr><td>December</td><td>RM </td></tr>
                    </tbody>
                </table>
                <div class="text-center p-3 bg-light">
                    <strong>Total Annual Expenses: RM </strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Net Profit Table -->
    <div class="table-responsive mt-5 shadow-sm" style="border-radius: 10px; overflow: hidden;">
        <h5 class="bg-secondary text-white text-center p-3">Net Profit</h5>
        <table class="table table-bordered text-center mb-0">
            <thead class="bg-dark text-white">
                <tr>
                <tr><td>January</td>
                    <td>February</td>
                    <td>March</td>
                    <td>April</td>
                    <td>May</td>
                    <td>June</td>
                    <td>July</td>
                    <td>August</td>
                    <td>September</td>
                    <td>October</td>
                    <td>November</td>
                    <td>December</td>
                <th>Yearly Net</th>
                <th>Averange Month</th></tr>
            </thead>
            <tbody>
                <tr>
                <td>RM</td>
                    <td>RM</td>
                    <td>RM</td>
                    <td>RM</td>
                    <td>RM</td>
                    <td>RM</td>
                    <td>RM</td>
                    <td>RM</td>
                    <td>RM</td>
                    <td>RM</td>
                    <td>RM</td>
                    <td>RM</td>
                    <td>RM </td>
                    <td>RM </td>
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
