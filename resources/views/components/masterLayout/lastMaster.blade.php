<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
{{--    <meta name="csrf-token" content="{{ csrf_token() }}">--}}
    <title>@yield("title")</title>

    <!-- Custom fonts for this template-->
    <link href={{asset("vendor/fontawesome-free/css/all.css")}} rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href={{asset("css/sb-admin-2.css?var=".rand())}} rel="stylesheet">
    <!-- Custom styles for this page -->
    <link href={{asset("vendor/datatables/dataTables.bootstrap4.css")}} rel="stylesheet">

</head>

<body id="page-top">
<!-- Page Wrapper -->
<div id="wrapper">

@section("Sidebar")
        <h2 id="test_size_label"></h2>
        <!-- Sidebar -->
        <ul class=" navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" >
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3"> @yield("title") </div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

{{--            <!-- Nav Item - Dashboard -->--}}
{{--            <li class="nav-item active">--}}
{{--                <a class="nav-link" href="index.html">--}}
{{--                    <i class="fas fa-fw fa-tachometer-alt"></i>--}}
{{--                    <span>Dashboard</span></a>--}}
{{--            </li>--}}

            <!-- Divider -->
{{--            <hr class="sidebar-divider">--}}

            <!-- Heading -->
            <div class="sidebar-heading">
                Management section
            </div>

            @if(auth()->user()->hasRole("admin"))
                <!-- Nav Item - Admin management Collapse Menu -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                       aria-expanded="true" aria-controls="collapseTwo">
                        <i class="fas fa-fw fa-cog"></i>
                        <span>Admin management</span>
                    </a>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">admin management</h6>
                            <a class="collapse-item" href={{route("dashboard")}}>dashboard</a>
                            <a class="collapse-item" href={{route("user.viewUsers")}}>all users</a>
                            <a class="collapse-item" href={{route("account.viewAccounts")}}>view accounts</a>
                            <a class="collapse-item" href={{route("role.viewRoles")}}>roles</a>
                            <a class="collapse-item" href={{route("permission.viewPermissions")}}>permissions</a>
                            <a class="collapse-item" href={{route("pound.viewPounds")}}>pound</a>
                            <a class='collapse-item' href='{{backpack_url('backup') }}'><i class='nav-icon la la-hdd-o'></i> Backups</a>
{{--                            <a class='collapse-item' href='{{route('admin.backup') }}'><i class='nav-icon la la-hdd-o'></i> Backups</a>--}}
                        </div>
                    </div>
                </li>
            @endif

            <!-- Nav Item -Resources Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                   aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Resources</span>
                </a>

                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                     data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">resources:</h6>
                        <a class="collapse-item" href={{route("product.viewProducts")}}>view products</a>
                        <a class="collapse-item" href={{route("store.viewStores")}}>view stores</a>
                        <a class="collapse-item" href={{route("category.viewCategories")}}>view categories</a>

                    </div>
                </div>

            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Invoices
            </div>

            <!-- Nav Item - Add new invoice Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAddNewInvoice"
                   aria-expanded="true" aria-controls="collapseAddNewInvoice">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Add new Invoice</span>
                </a>
                <div id="collapseAddNewInvoice" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Add new Invoice</h6>
                        <a id="create_invoice" class="collapse-item" href="{{route("invoice.createInvoice","sale")}}">new sale invoice</a>
                        <a class="collapse-item" href="{{route("invoice.createInvoice","purchase")}}">new purchase invoice</a>
                        <a class="collapse-item" href="{{route("invoice.createInvoice","sale_return")}}">new sale return invoice</a>
                        <a class="collapse-item" href="{{route("invoice.createInvoice","purchase_return")}}">new purchase return invoice</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - view invoices Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseViewInvoices"
                   aria-expanded="true" aria-controls="collapseViewInvoices">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>View invoices</span>
                </a>

                <div id="collapseViewInvoices" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">View invoices</h6>
                        <a class="collapse-item" href="{{route("invoice.viewInvoices","sale")}}">view sale invoices</a>
                        <a class="collapse-item" href="{{route("invoice.viewInvoices","purchase")}}">view purchase invoices</a>
                        <a class="collapse-item" href="{{route("invoice.viewInvoices","sale_return")}}">view sale return invoices</a>
                        <a class="collapse-item" href="{{route("invoice.viewInvoices","purchase_return")}}">view purchase return invoices</a>

                    </div>
                </div>

            </li>


            <!-- Nav Item - search an invoice Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSearchInvoice"
                   aria-expanded="true" aria-controls="collapseSearchInvoice">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Search an invoice</span>
                </a>

                <div id="collapseSearchInvoice" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Collapse</h6>
                        <a class="collapse-item" href="{{route("invoice.showSearchInvoice","none")}}">search an invoice</a>
                    </div>
                </div>

            </li>

            <!-- Nav Item - edit an invoice Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseEditInvoice"
                   aria-expanded="true" aria-controls="collapseEditInvoice">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Edit an invoice</span>
                </a>
                <div id="collapseEditInvoice" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Edit an invoice</h6>
                        <a class="collapse-item" href="{{route("invoice.showSearchInvoice","none")}}">edit an invoice</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - delete an invoice Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDeleteInvoice"
                   aria-expanded="true" aria-controls="collapseDeleteInvoice">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Delete an invoice</span>
                </a>

                <div id="collapseDeleteInvoice" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Delete an invoice</h6>
                        <a class="collapse-item" href="{{route("invoice.showSearchInvoice","none")}}">delete an invoice</a>
                        <a class="collapse-item" href="{{route("invoice.viewInvoiceRecyclebin")}}">recycle bin</a>
                    </div>
                </div>

            </li>


            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Businesses
            </div>

            <!-- Nav Item - Payment invoices Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePayment"
                   aria-expanded="true" aria-controls="collapsePayment">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Cash invoices</span>
                </a>
                <div id="collapsePayment" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Cash invoices</h6>
                        <a class="collapse-item" href="{{route("invoice.createCashInvoice")}}">add new cash invoice</a>
                        <a class="collapse-item" href="{{route("invoice.viewCashInvoices")}}">view all cash invoices</a>
                        <a class="collapse-item" href="{{route("invoice.showSearchCashInvoice")}}">search a cash invoices</a>
                        <a class="collapse-item" href="{{route("invoice.showSearchCashInvoice")}}">update a cash invoices</a>
                        <a class="collapse-item" href="{{route("invoice.showSearchCashInvoice")}}">delete a cash invoices</a>
                        <a class="collapse-item" href="{{route("invoice.viewCashRecyclebin")}}">recycle bin</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - ProductMovement invoices Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProductMovement"
                   aria-expanded="true" aria-controls="collapseProductMovement">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Product Movement</span>
                </a>
                <div id="collapseProductMovement" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Cash invoices</h6>
                        <a class="collapse-item" href="{{route("invoice.createProductMovementInvoice")}}">add new Product Movement invoice</a>
                        <a class="collapse-item" href="{{route("invoice.viewProductMovementInvoices")}}">view all ProductMovement invoices</a>
                        <a class="collapse-item" href="{{route("invoice.showSearchProductMovementInvoice")}}">search a ProductMovement invoices</a>
                        <a class="collapse-item" href="{{route("invoice.showSearchProductMovementInvoice")}}">update a ProductMovement invoices</a>
                        <a class="collapse-item" href="{{route("invoice.showSearchProductMovementInvoice")}}">delete a ProductMovement invoices</a>
                        <a class="collapse-item" href="{{route("invoice.viewProductMovementRecyclebin")}}">recycle bin</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->
@show

<!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">
        @section("navbar")
            <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                   aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                 aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                               placeholder="Search for..." aria-label="Search"
                                               aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                 aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alerts Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 12, 2019</div>
                                        <span class="font-weight-bold">A new monthly report is ready to download!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-donate text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 7, 2019</div>
                                        $290.29 has been deposited into your account!
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 2, 2019</div>
                                        Spending Alert: We've noticed unusually high spending for your account.
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li>

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter">7</span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                 aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Message Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src={{asset("images/systemImages/undraw_profile_1.svg")}}
                                             alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div class="font-weight-bold">
                                        <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                            problem I've been having.
                                        </div>
                                        <div class="small text-gray-500">Emily Fowler · 58m</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src={{asset("images/systemImages/undraw_profile_2.svg")}}
                                             alt="...">
                                        <div class="status-indicator"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">I have the photos that you ordered last month, how
                                            would you like them sent to you?
                                        </div>
                                        <div class="small text-gray-500">Jae Chun · 1d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src={{asset("images/systemImages/undraw_profile_3.svg")}}
                                             alt="...">
                                        <div class="status-indicator bg-warning"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Last month's report looks great, I am very happy with
                                            the progress so far, keep up the good work!
                                        </div>
                                        <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{auth()->user()->first_name}}</span>
                                <img class="img-profile rounded-circle"
                                     src={{ asset( auth()->user()->profile_image)}}>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                 aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{route("user.showUser",auth()->user())}}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="{{route("config.viewUserConfig")}}">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                @if(auth()->user()->hasRole("admin"))
                                    <a class="dropdown-item" href="{{route("activityLog.viewUsersActivityLog")}}">
                                        <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                        {{__("Activity Log")}}
                                    </a>
                                @endif
                                @yield("recycle_bin")
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    {{__("Logout")}}
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->
        @show

        <!-- Begin Page Content -->
            <div class="container-fluid">
                @include("inc.messages")

                <!-- Back Button-->
                    <div >
{{--                        @if(session("last_method"))--}}
{{--                            <form action="{{\Illuminate\Support\Facades\URL::previous()}}" method="{{session("last_method")}}">--}}
{{--                                @foreach(session("last_params") as $name=>$value)--}}
{{--                                    <input type="hidden" name="{{$name}}" value="{{$value}}">--}}
{{--                                @endforeach--}}
{{--                                <i id="back_arrow" class="fas fa-arrow-left">--}}
{{--                                    <input id="back_submit" hidden type="submit" class="fas fa-arrow-left">--}}
{{--                                </i>--}}
{{--                            </form>--}}
{{--                        @endif--}}
                        <a id="back_arrow" href="#">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                    <!-- End Back Button-->

                @section("content")

                @show
            </div>
            <!-- /.container-fluid -->


        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Your Website 2021</span>
                </div>

            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>
<!-- End Scroll to Top Button-->

<x-modals.logout-modal></x-modals.logout-modal>
@section("modals")
@show

<!-- Bootstrap core JavaScript-->
<script src={{asset("vendor/jquery/jquery.min.js")}}></script>
<script src={{asset("vendor/bootstrap/js/bootstrap.bundle.min.js")}}></script>

<!-- Core plugin JavaScript-->
<script src={{asset("vendor/jquery-easing/jquery.easing.min.js")}}></script>

<!-- Custom scripts for all pages-->
<script src={{asset("js/sb-admin-2.js?var=".rand())}}></script>

<script src={{asset("vendor/select2/js/select2.js?var=".rand())}}></script>

@yield("script")
</body>

</html>

