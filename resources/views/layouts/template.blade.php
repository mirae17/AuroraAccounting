<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="admin/dist/img/logo-aurora.png">
  <title>Aurora Ledger</title>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="{{ asset('admin/dist/css/adminlte.min.css') }}">
  <!-- Additional Styles -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  @vite(['resources/js/app.js', 'resources/sass/app.scss'])
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
      <img class="animation__shake" src="{{ asset('admin/dist/img/logo-aurora.png') }}" alt="AdminLTE Logo" height="60"
        width="60">
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
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
        <nav class="mt-3">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            <!-- Dashboard -->
            <li class="nav-item">
              <a href="{{ route('dashboard.index') }}"
                class="nav-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                <i class="nav-icon fas fa-home text-white"></i>
                <p>Dashboard</p>
              </a>
            </li>

            <!-- Database -->
            <li
              class="nav-item has-treeview {{ request()->routeIs('customerDetail.index') || request()->is('companyMaintenance*') ? 'menu-open' : '' }}">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-database text-success"></i>
                <p>Database<i class="right fas fa-angle-left"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('customerDetail.index') }}"
                    class="nav-link {{ request()->routeIs('customerDetail.index') ? 'active' : '' }}">
                    <i class="fas fa-address-book nav-icon"></i>
                    <p>Customer Database</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('companyMaintenance.index') }}"
                    class="nav-link {{ request()->routeIs('companyMaintenance.index') ? 'active' : '' }}">
                    <i class="fas fa-briefcase nav-icon"></i>
                    <p>Company Maintenance</p>
                  </a>
                </li>
              </ul>
            </li>

            <!-- Basic Code -->
            <li
              class="nav-item has-treeview {{ request()->is('payments*') || request()->is('expensesCode*') || request()->is('debtor*') || request()->is('suppliers*') || request()->is('inventory*') || request()->is('product*') ? 'menu-open' : '' }}">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-tools text-warning"></i>
                <p>Basic Code<i class="right fas fa-angle-left"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('payments.index') }}"
                    class="nav-link {{ request()->routeIs('payments.index') ? 'active' : '' }}">
                    <i class="fas fa-credit-card nav-icon"></i>
                    <p>Payment Method Code</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('expensesCode.index') }}"
                    class="nav-link {{ request()->routeIs('expensesCode.index') ? 'active' : '' }}">
                    <i class="fas fa-receipt nav-icon"></i>
                    <p>Expenses Code</p>
                  </a>
                </li>
                <!-- Suppliers -->
                <li class="nav-item">
                  <a href="{{ route('suppliers.index') }}"
                    class="nav-link {{ request()->routeIs('suppliers.index') ? 'active' : '' }}">
                    <i class="fas fa-people-carry nav-icon"></i>
                    <p> Suppliers Code</p>
                  </a>
                </li>

                <!-- Debtor Code -->
                <li class="nav-item">
                  <a href="{{ route('debtor.index') }}"
                    class="nav-link {{ request()->routeIs('debtor.index') ? 'active' : '' }}">
                    <i class="fas fa-users nav-icon"></i> <!-- Icon for Debtor Code -->
                    <p> Debtor Code</p>
                  </a>
                </li>

                <!-- Inventory Code -->
                <li class="nav-item">
                  <a href="{{ route('inventory.index') }}"
                    class="nav-link {{ request()->routeIs('inventory.index') ? 'active' : '' }}">
                    <i class="fas fa-boxes nav-icon"></i>
                    <p> Inventory Code</p>
                  </a>
                </li>

                <!-- Product Code -->
                <li class="nav-item">
                  <a href="{{ route('product.index') }}"
                    class="nav-link {{ request()->routeIs('product.index') ? 'active' : '' }}">
                    <i class="fas fa fa-cogs nav-icon"></i>
                    <p> Product/Service Code</p>
                  </a>
                </li>
              </ul>
            </li>

            <!-- Master Table -->
            <li
              class="nav-item has-treeview {{ request()->is('sales*') || request()->is('purchaseM*') || request()->is('expensesM*') || request()->is('inventoryM*') ? 'menu-open' : '' }}">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-table text-info"></i>
                <p>Master Table<i class="right fas fa-angle-left"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <!-- Sales -->
                <li class="nav-item">
                  <a href="{{ route('sales.index') }}"
                    class="nav-link {{ request()->routeIs('sales.index') ? 'active' : '' }}">
                    <i class="fas fa-chart-line nav-icon"></i>
                    <p> Sales</p>
                  </a>
                </li>

                <!-- Purchase -->
                <li class="nav-item">
                  <a href="{{ route('purchaseM.index') }}"
                    class="nav-link {{ request()->routeIs('purchaseM.index') ? 'active' : '' }}">
                    <i class="fas fa-shopping-bag nav-icon"></i>
                    <p> Purchase</p>
                  </a>
                </li>

                <!-- Expenses -->
                <li class="nav-item">
                  <a href="{{ route('expensesM.index') }}"
                    class="nav-link {{ request()->routeIs('expensesM.index') ? 'active' : '' }}">
                    <i class="fas fa-receipt nav-icon"></i>
                    <p> Expenses</p>
                  </a>
                </li>

              </ul>
            </li>

            <!-- Documents -->
            <li
              class="nav-item has-treeview {{ request()->is('quotations*') || request()->is('invoice*') || request()->is('receipt*') || request()->is('purchaseOrder*') || request()->is('paymentVoucher*') ? 'menu-open' : '' }}">
              <a href="#" class="nav-link ">
                <i class="nav-icon fas fa-file-alt text-info"></i>
                <p>Documents<i class="right fas fa-angle-left"></i></p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('quotations.index') }}"
                    class="nav-link {{ request()->routeIs('quotations.index') ? 'active' : '' }}">
                    <i class="fas fa-sticky-note nav-icon"></i>
                    <p>Quotation</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('invoice.index') }}"
                    class="nav-link {{ request()->routeIs('invoice.index') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice nav-icon"></i>
                    <p>Invoice</p>
                  </a>
                </li>
                <!-- Receipt -->
                <li class="nav-item">
                  <a href="{{ route('receipt.index') }}"
                    class="nav-link {{ request()->routeIs('receipt.index') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice-dollar nav-icon"></i>
                    <p> Receipt</p>
                  </a>
                </li>
                <!-- Purchase Order -->
                <li class="nav-item">
                  <a href="{{ route('purchaseOrder.index') }}"
                    class="nav-link {{ request()->routeIs('purchaseOrder.index') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-check nav-icon"></i>
                    <p> Purchase Order</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('paymentVoucher.index') }}"
                    class="nav-link {{ request()->routeIs('paymentVoucher.index') ? 'active' : '' }}">
                    <i class="fas fa-money-check-alt nav-icon"></i>
                    <p> Payment Voucher</p>
                  </a>
                </li>
              </ul>
            </li>

            <!-- Security -->
            <li
              class="nav-item has-treeview {{ request()->routeIs('users.index') || request()->is('employees*') ? 'menu-open' : '' }}">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-user-shield text-danger"></i>
                <p>Security<i class="right fas fa-angle-left"></i></p>
              </a>
              <ul class="nav nav-treeview">
                @if(Auth::user()->role === 'system admin')
          <li class="nav-item">
            <a href="{{ route('users.index') }}"
            class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
            <i class="fas fa-user-cog nav-icon"></i>
            <p>Manage Users</p>
            </a>
          </li>
        @endif
                <li class="nav-item">
                  <a href="{{ route('employees.index') }}"
                    class="nav-link {{ request()->routeIs('employees.index') ? 'active' : '' }}">
                    <i class="fas fa-users nav-icon"></i>
                    <p>Manage Employees</p>
                  </a>
                </li>
              </ul>
            </li>

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

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>


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