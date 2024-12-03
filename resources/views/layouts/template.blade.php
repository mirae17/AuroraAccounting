<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Aurora Ledger</title>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('admin/dist/css/adminlte.min.css') }}">
    <!-- Additional Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <style>
    div.dataTables_wrapper div.dataTables_filter input {
        width: 400px !important;
        height: 35px !important;
    }
</style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{ asset('admin/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
  </nav>

  <!-- Sidebar -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('dashboard.index') }}" class="brand-link">
      <img src="{{ asset('admin/dist/img/logo-aurora.png') }}" alt="AdminLTE Logo" 
        class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Aurora Ledger</span>
    </a>

    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
          <!-- Dashboard -->
          <li class="nav-item">
            <a href="{{ route('dashboard.index') }}" 
              class="nav-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
              <i class="fas fa-home"></i>
              <p> Dashboard</p>
            </a>

          </li>

          <li class="nav-header text-uppercase font-weight-bold" 
              style="color: #333333; font-size: 1rem; padding-top: 10px; border-top: 1px solid #dddddd; margin-top: 10px;">
           
          </li>
          
          <!-- Customer Database-->
          <li class="nav-item">
            <a href="#" 
              class="nav-link">
              <i class="fas fa-address-book"></i>
              <p> Customer Database</p>
            </a>
          </li>
           <!-- Manage Users (System Admin Only) -->
           @if(Auth::user()->role !== 'system admin')
           <li class="nav-header text-uppercase font-weight-bold" 
              style="color: #333333; font-size: 1rem; padding-top: 10px; border-top: 1px solid #dddddd; margin-top: 10px;">
           
          </li>
            <li class="nav-item">
              <a href="" 
                class="nav-link ">
                <i class="fas fa-briefcase"></i>
                <p> Company Maintenance</p>
              </a>
            </li>
           
          
          @endif

          <li class="nav-header text-uppercase font-weight-bold" 
              style="color: #333333; font-size: 1rem; padding-top: 10px; border-top: 1px solid #dddddd; margin-top: 10px;">
           
          </li>

          <!-- Payment Method -->
          <li class="nav-item">
            <a href="{{ route('payments.index') }}" 
              class="nav-link {{ request()->routeIs('payments.index') ? 'active' : '' }}">
              <i class="fas fa-credit-card"></i>
              <p> Payment Method Code</p>
            </a>
          </li>

           <!-- Code Expenses -->
           <li class="nav-item">
            <a href="{{ route('expenses.index') }}" 
              class="nav-link {{ request()->routeIs('expenses.index') ? 'active' : '' }}">
              <i class="fas fa-receipt"></i>
              <p> Expenses Code</p>
            </a>
          </li>

          <!-- Suppliers -->
          <li class="nav-item">
            <a href="{{ route('suppliers.index') }}" 
              class="nav-link {{ request()->routeIs('suppliers.index') ? 'active' : '' }}">
              <i class="fas fa-people-carry"></i>
              <p> Suppliers Code</p>
            </a>
          </li>

          <!-- Debtor Code -->
        <li class="nav-item">
          <a href="{{ route('debtor.index') }}" 
            class="nav-link {{ request()->routeIs('debtor.index') ? 'active' : '' }}">
            <i class="fas fa-users"></i> <!-- Icon for Debtor Code -->
            <p> Debtor Code</p>
          </a>
        </li>

        <!-- Inventory Code -->
        <li class="nav-item">
          <a href="{{ route('inventory.index') }}" 
            class="nav-link {{ request()->routeIs('inventory.index') ? 'active' : '' }}">
            <i class="fas fa-boxes"></i>
            <p> Inventory Code</p>
          </a>
        </li>

        <!-- Product Code -->
        <li class="nav-item">
          <a href="{{ route('product.index') }}" 
            class="nav-link {{ request()->routeIs('product.index') ? 'active' : '' }}">
            <i class="fas fa fa-cogs"></i>
            <p> Product/Service Code</p>
          </a>
        </li>


        <li class="nav-header text-uppercase font-weight-bold"  style="color: #333333; font-size: 1rem; padding-top: 10px; border-top: 1px solid #dddddd; margin-top: 10px;">
           
          </li>
          <!-- Sales -->
          <li class="nav-item">
            <a href="{{ route('sales.index') }}" 
              class="nav-link {{ request()->routeIs('sales.index') ? 'active' : '' }}">
              <i class="fas fa-chart-line"></i>
              <p> Sales</p>
            </a>
          </li>

          <!-- Purchase -->
          <li class="nav-item">
            <a href="{{ route('purchaseM.index') }}" 
              class="nav-link {{ request()->routeIs('purchaseM.index') ? 'active' : '' }}">
              <i class="fas fa-shopping-bag "></i>
              <p> Purchase</p>
            </a>
          </li>

                    <!-- Expenses -->
          <li class="nav-item">
            <a href="{{ route('expensesM.index') }}" 
              class="nav-link {{ request()->routeIs('expensesM.index') ? 'active' : '' }}">
              <i class="fas fa-receipt"></i>
              <p> Expenses</p>
            </a>
          </li>

         

        <li class="nav-header text-uppercase font-weight-bold" 
              style="color: #333333; font-size: 1rem; padding-top: 10px; border-top: 1px solid #dddddd; margin-top: 10px;">
           
          </li>

          <!-- Manage Users (System Admin Only) -->
          @if(Auth::user()->role === 'system admin')
            <li class="nav-item">
              <a href="{{ route('users.index') }}" 
                class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                <i class="fas fa-user-shield"></i>
                <p> Manage Users</p>
              </a>
            </li>
           
          
          @endif
          <li class="nav-item">
              <a href="{{ route('employees.index') }}" 
                class="nav-link {{ request()->routeIs('employees.index') ? 'active' : '' }} ">
                <i class="fas fa-user-cog"></i>
                <p> Manage Employee</p>
              </a>
            </li>
            @if(Auth::user()->role === 'system admin')
          <li class="nav-item">
              <a href="#" 
                class="nav-link ">
                <i class="fas fa-user-lock"></i>
                <p> Manage Admin</p>
              </a>
            </li>
            @endif
          <!-- Logout -->
          <li class="nav-item">
            <a href="{{ route('logout') }}" class="nav-link" 
              onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="fas fa-power-off"></i>
              <p> Logout</p>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
            </form>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <section class="content">
      <div class="container-fluid">
        @yield('content')
      </div>
    </section>
  </div>

  <!-- Footer -->
  <footer class="main-footer">
    <strong>&copy; 2024 Aurora Cloud Works Sdn. Bhd</a>.</strong> All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 0.0.1
    </div>
  </footer>
</div>
<!-- Scripts -->
<script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin/dist/js/adminlte.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<!-- jQuery and Bootstrap JavaScript for modal functionality -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
  AOS.init();
</script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>



<!-- DataTables  & Plugins -->
<script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('admin/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('admin/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('admin/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
   
<!-- Page specific script -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('.dataTables_filter input').css('width', '400px');  // Set visual width
    $('.dataTables_filter input').attr('maxlength', 50); 
  });
</script>
</body>
</html>
