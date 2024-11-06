@extends('layouts.template_dashboard')

@section('content')
<div class="container my-5">
    <!-- Main Title -->
    <h2 class="text-center mb-5" style="font-size: 2.2rem; font-weight: bold; color: #444;">Dashboard Utama</h2>
    
    <!-- Main Dashboard Metrics -->
    <div class="row">
        <!-- Sales Summary Column -->
        <div class="col-md-4">
            <div class="mb-4 p-4 text-center shadow-sm" style="background-color: #f9fafb; border-radius: 10px;">
                <h6 class="text-muted">Jumlah Jualan Semasa</h6>
                <h3 class="text-primary" style="font-weight: bold;">RM </h3>
            </div>
            <div class="mb-4 p-4 text-center shadow-sm" style="background-color: #f9fafb; border-radius: 10px;">
                <h6 class="text-muted">Jumlah Jualan Semalam</h6>
                <h3 class="text-primary" style="font-weight: bold;">RM </h3>
            </div>
            <div class="mb-4 p-4 text-center shadow-sm" style="background-color: #f9fafb; border-radius: 10px;">
                <h6 class="text-muted">Jumlah Jualan Hari Ini</h6>
                <h3 class="text-primary" style="font-weight: bold;">RM </h3>
            </div>
        </div>

        <!-- Expenses and Debts Column -->
        <div class="col-md-4">
            <div class="mb-4 p-4 text-center shadow-sm" style="background-color: #fef6f6; border-radius: 10px;">
                <h6 class="text-muted">Jumlah Belian + Perbelanjaan Semasa</h6>
                <h3 class="text-danger" style="font-weight: bold;">RM </h3>
            </div>
            <div class="mb-4 p-4 text-center shadow-sm" style="background-color: #fef6f6; border-radius: 10px;">
                <h6 class="text-muted">Jumlah Penghutang</h6>
                <h3 class="text-danger" style="font-weight: bold;">RM </h3>
            </div>
            <div class="mb-4 p-4 text-center shadow-sm" style="background-color: #fef6f6; border-radius: 10px;">
                <h6 class="text-muted">Jumlah Pemiutang</h6>
                <h3 class="text-danger" style="font-weight: bold;">RM </h3>
            </div>
        </div>

        <!-- Stock and Profit Column -->
        <div class="col-md-4">
            <div class="mb-4 p-4 text-center shadow-sm" style="background-color: #fff5e5; border-radius: 10px;">
                <h6 class="text-muted">Bilangan Stok</h6>
                <h3 style="color: #333; font-weight: bold;"></h3>
            </div>
            <div class="mb-4 p-4 text-center shadow-sm" style="background-color: #fff5e5; border-radius: 10px;">
                <h6 class="text-muted">Bilangan Stok BM</h6>
                <h3 style="color: #333; font-weight: bold;"></h3>
            </div>
            <div class="mb-4 p-4 text-center shadow-sm" style="background-color: #fff5e5; border-radius: 10px;">
                <h6 class="text-muted">Untung Bersih Semasa</h6>
                <h3 style="color: #333; font-weight: bold;">RM </h3>
            </div>
        </div>
    </div>

    <!-- Monthly Tables -->
    <div class="row mt-5">
        <!-- Sales Table -->
        <div class="col-md-4">
            <div class="table-responsive shadow-sm" style="border-radius: 10px; overflow: hidden;">
                <h5 class="bg-primary text-white text-center p-3">Jualan Bulanan</h5>
                <table class="table table-striped mb-0">
                    <thead>
                        <tr><th>Bulan</th><th>Jualan (RM)</th></tr>
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
                    <strong>Jumlah Jualan Tahunan: RM</strong>
                </div>
            </div>
        </div>

        <!-- Purchases Table -->
        <div class="col-md-4">
            <div class="table-responsive shadow-sm" style="border-radius: 10px; overflow: hidden;">
                <h5 class="bg-danger text-white text-center p-3">Belian Bulanan</h5>
                <table class="table table-striped mb-0">
                    <thead>
                        <tr><th>Bulan</th><th>Belian (RM)</th></tr>
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
                    <strong>Jumlah Belian Tahunan: RM </strong>
                </div>
            </div>
        </div>

        <!-- Expenditures Table -->
        <div class="col-md-4">
            <div class="table-responsive shadow-sm" style="border-radius: 10px; overflow: hidden;">
                <h5 class="bg-warning text-white text-center p-3">Perbelanjaan Bulanan</h5>
                <table class="table table-striped mb-0">
                    <thead>
                        <tr><th>Bulan</th><th>Perbelanjaan (RM)</th></tr>
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
                    <strong>Jumlah Perbelanjaan Tahunan: RM </strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Net Profit Table -->
    <div class="table-responsive mt-5 shadow-sm" style="border-radius: 10px; overflow: hidden;">
        <h5 class="bg-secondary text-white text-center p-3">Untung Bersih</h5>
        <table class="table table-bordered text-center mb-0">
            <thead class="bg-dark text-white">
                <tr>
                <th>Bulan</th>
                <th>Bersih Tahunan</th>
                <th>Purata Bulanan</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td>month</td>
                    <td>RM </td>
                    <td>RM </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Sales Analysis Chart -->
    <div class="bg-dark text-white mt-5 p-5 shadow-sm" style="border-radius: 10px;">
        <h4 class="text-center">Analisis Jualan</h4>
        <canvas id="salesChart" style="height: 300px;"></canvas>
    </div>
</div>

@endsection
