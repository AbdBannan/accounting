<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" >
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield("title")</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta name="url" content="{{config('app.url')}}">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset("css/plugins/fontawesome-free/css/all.min.css")}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset("css/plugins/overlayScrollbars/css/OverlayScrollbars.min.css")}}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{asset("css/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css")}}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{asset("css/plugins/toastr/toastr.min.css")}}">
    <!-- custom date input -->
    <link rel="stylesheet" href="{{asset("css/plugins/customDateInput/dateInput.css")}}">

    @if(auth()->user()->getConfig("language") == "arabic")
        <!-- Theme style RTL -->
        <link rel="stylesheet" href="{{asset("css/dist/css/ar/adminlte.css")."?var=".rand()}}">

        <!-- Custom style for RTL -->
        <link rel="stylesheet" href="{{asset("css/dist/css/ar/custom.css")."?var=".rand()}}">
    @else
        <!-- Theme style LTR -->
        <link rel="stylesheet" href="{{asset("css/dist/css/en/adminlte.css")."?var=".rand()}}">
    @endif

    <!-- DataTables -->
    @if(auth()->user()->getConfig("language") == "arabic")
        <link rel="stylesheet" href="{{asset("css/plugins/datatables-bs4/css/ar/dataTables.bootstrap4.css")}}">
    @else
        <link rel="stylesheet" href="{{asset("css/plugins/datatables-bs4/css/en/dataTables.bootstrap4.css")}}">
    @endif
    <link rel="stylesheet" href="{{asset("css/plugins/datatables-responsive/css/responsive.bootstrap4.min.css")}}">
    <link rel="stylesheet" href="{{asset("css/plugins/datatables-buttons/css/buttons.bootstrap4.min.css")}}">

    @yield("style")
</head>

@php
    $lang = (auth()->user()->getConfig("language") == "english")? "en": "ar" ;
    session(["lang"=>$lang]);
@endphp
<body class="hold-transition
    @if(auth()->user()->getConfig("dark_mode") !== null) dark-mode @endif
    @if(auth()->user()->getConfig("collapsed") !== null) sidebar-collapse @endif
    @if(auth()->user()->getConfig("fixed_sidebar") !== null) layout-fixed @endif
    @if(auth()->user()->getConfig("sidebar_mini") !== null) sidebar-mini @endif
    @if(auth()->user()->getConfig("sidebar_mini_md") !== null) sidebar-mini-md @endif
    @if(auth()->user()->getConfig("sidebar_mini_xs") !== null) sidebar-mini-xs @endif

    @if(auth()->user()->getConfig("fixed_header") !== null) layout-navbar-fixed @endif

    @if(auth()->user()->getConfig("fixed_footer") !== null) layout-footer-fixed @endif

    @if(auth()->user()->getConfig("body_small_text_options") !== null) text-sm @endif

    @if(auth()->user()->getConfig("accent_color_variants") !== null and auth()->user()->getConfig("accent_color_variants") != "None Selected") accent-{{strtolower(auth()->user()->getConfig("accent_color_variants"))}} @endif


    ">

    <div class="wrapper">
        <h5 style="display: inline-block;position: absolute" id="test_size_label"></h5>
        <!-- Preloader -->
        @if(!session("is_first_load"))
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{asset("images/systemImages/AdminLTELogo.png")}}" alt="AdminLTELogo" height="60" width="60">
        </div>
        @endif

        @php
        session(["is_first_load"=>true])
        @endphp
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand
        @if(auth()->user()->getConfig("drop_down_legacy_offset") !== null) dropdown-legacy @endif
        @if(auth()->user()->getConfig("no_border") !== null) border-bottom-0 @endif
        @if(auth()->user()->getConfig("navbar_small_text_options") !== null) text-sm @endif
        @if(in_array(strtolower(auth()->user()->getConfig("navbar_variants")),["light","white"]))
            navbar-light
       @else
            navbar-dark
       @endif
        navbar-{{strtolower(auth()->user()->getConfig("navbar_variants"))}}">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a id="btn_welcome" href="{{route("welcomePage")}}" class="nav-link">{{__("global.home")}}</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{route("contact")}}" class="nav-link">{{__("global.contact")}}</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul @if(auth()->user()->getConfig("language") == "english") class="navbar-nav ml-auto" @else class="navbar-nav mr-auto-navbav" @endif >
                <!-- Navbar Search -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                        <i class="fas fa-search"></i>
                    </a>
                    <div class="navbar-search-block">
                        <form class="form-inline">
                            <div class="input-group input-group-sm">
                                <input id="search" class="form-control form-control-navbar" type="search" placeholder="{{__("global.Search")}}" aria-label="Search">
                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>

                <!-- Messages Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-comments"></i>
                        <span class="badge badge-danger navbar-badge">3</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="{{asset("images/systemImages/user1-128x128.jpg")}}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Brad Diesel
                                        <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">Call me whenever you can...</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="{{asset("images/systemImages/user8-128x128.jpg")}}" alt="User Avatar" class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        John Pierce
                                        <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">I got your message bro</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="{{asset("images/systemImages/user3-128x128.jpg")}}" alt="User Avatar" class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Nora Silvester
                                        <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">The subject goes here</p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                    </div>
                </li>
                <!-- Notifications Dropdown Menu -->
                @if(!str_contains(strtolower(\Illuminate\Support\Facades\URL::current()),"notification"))
                    <li class="nav-item dropdown">
                    <a id="btn_notifications" class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        @php
                            $notification_count = auth()->user()->notifications()->where("has_seen",0)->count();
                        @endphp
                        @if($notification_count > 0)
                            <span class="badge badge-warning navbar-badge">{{$notification_count}}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">{{$notification_count . " " . __("global.new_notifications")}}</span>
                        <div class="dropdown-divider"></div>
                        <div id="notification_container" style="max-height: 100px;overflow-y: scroll">
                            @foreach(auth()->user()->notifications()->where("has_seen",0)->get() as $notification)
                                <a href="{{route("notifications.viewNotifications")."#".$notification->name}}" class="dropdown-item" style="white-space: unset;">
                                    <div>
                                        @if ($notification->type == "product_quantity_is_not_enough")
                                            <i class="fas fa-bell  mr-2"></i>
                                        @elseif ($notification->type == "new_messages")
                                            <i class="fas fa-envelope mr-2"></i>
                                        @elseif ($notification->type == "friend_requests")
                                            <i class="fas fa-users mr-2"></i>
                                        @elseif ($notification->type == "new_reports")
                                            <i class="fas fa-file mr-2"></i>
                                        @endif
                                        {{__("global.product_quantity_running_out",["attribute"=>$notification->name])}}</div>
                                    <div class="d-flex justify-content-end justify-content-start ">
                                        <span class="text-blue text-muted text-sm">{{$notification->created_at->diffForHumans()}}</span>
                                    </div>
                                </a>
                                <div class="dropdown-divider"></div>
                            @endforeach
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="{{route("notifications.viewNotifications")}}" class="dropdown-item dropdown-footer">{{__("global.see_all_notifications")}}</a>
                    </div>
                </li>
                @endif
                <li class="nav-item">
                    <a id="btn_fullscreen" class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>

                <div class="topbar-divider d-none d-sm-block"></div>
                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{auth()->user()->first_name}}</span>
                    </a>

                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                         aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="{{route("user.showUser",auth()->user())}}">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                            {{__("global.profile")}}
                        </a>
                        <a class="dropdown-item" href="{{route("config.viewUserConfig")}}">
                            <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                            {{__("global.settings")}}
                        </a>
                        <a class="dropdown-item" href="{{route("help.viewHelp")}}">
                            <i class="fas fa-question fa-sm fa-fw mr-2 text-gray-400"></i>
                            {{__("global.help")}}
                        </a>
                        @if(auth()->user()->isAdmin())
                            <a class="dropdown-item" href="{{route("activityLog.viewUsersActivityLog")}}">
                                <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                {{__("global.activity_log")}}
                            </a>
                        @endif
                        @yield("recycle_bin")
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            {{__("global.logout")}}
                        </a>
                    </div>
                </li>

                <img class="img-profile rounded-circle" src="{{asset( auth()->user()->profile_image)}}">

            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar
            @if(auth()->user()->getConfig("disable_hover_or_focus_auto_expand") !== null and auth()->user()->getConfig("disable_hover_or_focus_auto_expand") != "None Selected") sidebar-no-expand @endif
            @if(auth()->user()->getConfig("light_sidebar_variants") !== null and auth()->user()->getConfig("light_sidebar_variants") != "None Selected") sidebar-light-{{strtolower(auth()->user()->getConfig("light_sidebar_variants"))}} @endif
            @if(auth()->user()->getConfig("dark_sidebar_variants") !== null and auth()->user()->getConfig("dark_sidebar_variants") != "None Selected") sidebar-dark-{{strtolower(auth()->user()->getConfig("dark_sidebar_variants"))}} @endif
            elevation-4">
            <!-- Brand Logo -->

            <a class="brand-link @if(auth()->user()->getConfig("brand_small_text_options") !== null) text-sm @endif navbar-{{strtolower(auth()->user()->getConfig("brand_logo_variants"))}}">
                <img src="{{asset("images/systemImages/AdminLTELogo.png")}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">@yield("title","accounting")</span>
            </a>


            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{asset(auth()->user()->profile_image)}}" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">{{auth()->user()->first_name}}</a>
                    </div>
                </div>

                <!-- SidebarSearch Form -->
                <div class="form-inline">
                    <div class="input-group" data-widget="sidebar-search">
                        <input id="search" class="form-control form-control-sidebar" type="search" placeholder="{{__("global.Search")}}" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar">
                                <i class="fas fa-search fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar
                        @if(auth()->user()->getConfig("nav_legacy_style") !== null) nav-legacy @endif
                        @if(auth()->user()->getConfig("nav_compact") !== null) nav-compact @endif
                        @if(auth()->user()->getConfig("nav_child_indent") !== null) nav-child-indent @endif
                        @if(auth()->user()->getConfig("nav_child_hide_on_collapse") !== null) nav-collapse-hide-child @endif
                        @if(auth()->user()->getConfig("side_bar_nav_hover_or_focus_auto_expand") !== null) text-sm @endif

                        flex-column" data-widget="treeview" role="menu" data-accordion="true">
                        <!-- Add icons to the links using the .nav-icon class
                             with font-awesome or any other icon font library -->
                        @if(auth()->user()->isAdmin())
                        {{--admin controle--}}
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    {{__("global.management_section")}}
                                    <i class="@if(app()->getLocale() == "ar") left @else right @endif fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route("dashboard")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.dashboard")}}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("user.viewUsers")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.users")}}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("account.viewAccounts")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.accounts")}}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("role.viewRoles")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.roles")}}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("permission.viewPermissions")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.permissions")}}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("pound.viewPounds")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.pounds")}}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a id="btn_show_discover_dashboard" href="{{route("discover.showDiscoverDashboard")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.discovers")}}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("backup.view")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.backups")}}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("archive.viewArchiveBalances")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.role_balances")}}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        {{--resources--}}
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-file-invoice"></i>
                                <p>
                                    {{__("global.resources")}}
                                    <i class="fas fa-angle-left @if(app()->getLocale() == "ar") left @else right @endif"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route("product.viewProducts")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.products")}}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("store.viewStores")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.stores")}}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-header">{{__("global.invoices")}}</li>

                        {{--new invoices--}}
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-file-invoice"></i></i>
                                <p>
                                    {{__("global.add_new_invoice")}}
                                    <i class="fas fa-angle-left @if(app()->getLocale() == "ar") left @else right @endif"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a id="create_invoice" href="{{route("invoice.createInvoice","sale")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.sale")}}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("invoice.createInvoice","purchase")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.purchase")}}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("invoice.createInvoice","sale_return")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.sale_return")}}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("invoice.createInvoice","purchase_return")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.purchase_return")}}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        {{--view invoices--}}
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-file-invoice"></i></i>
                                <p>
                                    {{__("global.view_invoices")}}
                                    <i class="fas fa-angle-left @if(app()->getLocale() == "ar") left @else right @endif"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{route("invoice.viewInvoices","sale")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.sale")}}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("invoice.viewInvoices","purchase")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.purchase")}}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("invoice.viewInvoices","sale_return")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.sale_return")}}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route("invoice.viewInvoices","purchase_return")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.purchase_return")}}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        {{--search edit delete an invoices--}}
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-file-invoice"></i>
                                <p>
                                    {{__("global.search_edit_delete")}}
                                    <i class="fas fa-angle-left @if(app()->getLocale() == "ar") left @else right @endif"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a id="btn_search_edit_delete_invoice" href="{{route("invoice.showSearchInvoice","none")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.search_edit_delete")}}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a id="btn_invoices_recycle_bin" href="{{route("invoice.viewInvoiceRecyclebin","none")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.recyclebin")}}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-header">{{__("global.cash_invoices")}}</li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-cash-register"></i>
                                <p>
                                    {{__("global.cash_invoices")}}
                                    <i class="fas fa-angle-left @if(app()->getLocale() == "ar") left @else right @endif"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                {{--new cash invoice--}}
                                <li class="nav-item">
                                    <a href="{{route("invoice.createCashInvoice")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.add_new_cash_invoice")}}</p>
                                    </a>
                                </li>
                                {{--view cash invoices--}}
                                <li class="nav-item">
                                    <a href="{{route("invoice.viewCashInvoices")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.view_cash_invoices")}}</p>
                                    </a>
                                </li>
                                {{--search edit delete cash invoice--}}
                                <li class="nav-item">
                                    <a id="btn_search_edit_delete_cash" href="{{route("invoice.showSearchCashInvoice")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.search_edit_delete_cash_invoice")}}</p>
                                    </a>
                                </li>
                                {{--recyclebin cash invoices--}}
                                <li class="nav-item">
                                    <a id="btn_cashes_recycle_bin" href="{{route("invoice.viewCashRecyclebin")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.recyclebin")}}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-header">{{__("global.product_movement_invoices")}}</li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-book"></i>
                                <p>
                                    {{__("global.product_movement_invoices")}}
                                    <i class="fas fa-angle-left @if(app()->getLocale() == "ar") left @else right @endif"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                {{--new product movement invoice--}}
                                <li class="nav-item">
                                    <a href="{{route("invoice.createProductMovementInvoice")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.add_new_product_movement_invoice")}}</p>
                                    </a>
                                </li>
                                {{--view product movement invoices--}}
                                <li class="nav-item">
                                    <a href="{{route("invoice.viewProductMovementInvoices")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.view_product_movement_invoices")}}</p>
                                    </a>
                                </li>
                                {{--search edit delete product movement invoice--}}
                                <li class="nav-item">
                                    <a id="btn_search_edit_delete_product_movement" href="{{route("invoice.showSearchProductMovementInvoice")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.search_edit_delete_product_movement_invoice")}}</p>
                                    </a>
                                </li>
                                {{--recyclebin product movement invoices--}}
                                <li class="nav-item">
                                    <a id="btn_product_movement_recycle_bin" href="{{route("invoice.viewProductMovementRecyclebin")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.recyclebin")}}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>


                        <li class="nav-header">{{__("global.manufacturing")}}</li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-book"></i>
                                <p>
                                    {{__("global.manufacturing")}}
                                    <i class="fas fa-angle-left @if(app()->getLocale() == "ar") left @else right @endif"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                {{--new manufacturing action--}}
                                <li class="nav-item">
                                    <a href="{{route("invoice.createManufacturingInvoice")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.add_new_manufacturing_action")}}</p>
                                    </a>
                                </li>
                                {{--view manufacturing actions--}}
                                <li class="nav-item">
                                    <a href="{{route("invoice.viewManufacturingInvoices")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.view_all_manufacturing_actions")}}</p>
                                    </a>
                                </li>
                                {{--search edit delete manufacturing actions --}}
                                <li class="nav-item">
                                    <a id="btn_search_edit_delete_manufacturing_action" href="{{route("invoice.showSearchManufacturingInvoice")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.search_edit_delete_manufacturing_actions")}}</p>
                                    </a>
                                </li>
                                {{--recyclebin manufacturing actions --}}
                                <li class="nav-item">
                                    <a id="btn_product_movement_recycle_bin" href="{{route("invoice.viewManufacturingRecyclebin")}}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{__("global.recyclebin")}}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div style="padding-top:2%" class="container-fluid">
                <!-- Back Button-->
                <div style="height: 46px">
                    <a id="back_arrow" style="margin: 80px" href="{{route("back")}}">
                        @if(auth()->user()->getConfig("language") == "arabic")
                            <i class="fas fa-arrow-right"></i>
                        @else
                            <i class="fas fa-arrow-left"></i>
                        @endif
                    </a>
                </div>
                <!-- End Back Button-->

                @section("content")

                @show

            </div>
        </div>
        <!-- /.content-wrapper -->
        <footer style="text-align: center" class="main-footer @if(auth()->user()->getConfig("footer_small_text_options") !== null) text-sm @endif">
            {{__("global.all_rights_reserved")}}
            <div class="float-right d-none d-sm-inline-block">
                <b>{{__("global.version")}} 1.0.0</b>
            </div>
            <strong>Copyright &copy; 2022-2023 <a href="{{route("contact")}}">Abdulmoty Bannan</a>.</strong>
        </footer>

    </div>
<!-- ./wrapper -->

    <x-modals.logout-modal></x-modals.logout-modal>

    @section("modals")
    @show


    <!-- jQuery -->
    <script src="{{asset("js/plugins/jquery/jquery.min.js")}}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{asset("js/plugins/jquery-ui/jquery-ui.min.js")}}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>

    <!-- Bootstrap 4 -->
    <script src="{{asset("js/plugins/bootstrap/js/bootstrap.bundle.min.js")}}"></script>
    <!-- overlayScrollbars -->
    <script src="{{asset("js/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js")}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset("js/dist/js/adminlte.js")}}"></script>
    <!-- SweetAlert2 -->
    <script src="{{asset("js/plugins/sweetalert2/sweetalert2.min.js")}}"></script>
    <!-- Toastr -->
    <script src="{{asset("js/plugins/toastr/toastr.min.js")}}"></script>
    <!-- Savy -->
    <script type="text/javascript" src="{{asset("js/plugins/savy.js")}}"></script>
    <!-- custom date input -->
    <script type="text/javascript" src="{{asset("js/plugins/customDateInput/dateInput.js")}}"></script><!-- custom date input -->
    <!-- custom number round -->
    <script type="text/javascript" src="{{asset("js/plugins/customNumberRound/numberRound.js")}}"></script>
    <!-- nice scroll -->
    <script src="{{asset("js/plugins/nice-scroll/jquery.nicescroll.min.js")}}"></script>
    <!-- Page level plugins -->
    <script src="{{asset("js/plugins/datatables/jquery.dataTables.js")}}"></script>
    <script src="{{asset("js/plugins/datatables/dataTables.bootstrap4.js")}}"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{asset("js/js_common.js")}}"></script>
    <script>
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": ($("html").attr("lang") == "ar")? "toast-bottom-left" : "toast-bottom-right",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }

        $(function () {
            $("#date_container").buildDateInput({classes: "form-control",id:"closing_date",tabindex:11,focusElemAfterFinish:"btn_save_invoice",form:"form",initialDate:"{{\Carbon\Carbon::now()->format("Y-m-d")}}"});
            // Call the dataTables jQuery plugin
            let info = "{{__("global.Showing")}} _START_ {{__("global.to")}} _END_ {{__("global.of")}} _TOTAL_ {{__("global.entries")}}";
            let emptyTable = "{{__("global.no_data_available_in_table")}}";
            let infoEmpty = "{{__("global.Showing")}} 0 {{__("global.to")}} 0 {{__("global.of")}} 0 {{__("global.entries")}}";
            let lengthMenu = "{{__("global.Show")}} _MENU_ {{__("global.entries")}}";
            let loadingRecords = "{{__("global.please_wait_loading")}}";
            let search = "{{__("global.Search")}}:";
            let next = "{{__("global.Next")}}";
            let previous = "{{__("global.Previous")}}";
            let infoFiltered = " - {{__("global.filtered_from")}} _MAX_ {{__("global.entries")}}";
            let pageLength = {{auth()->user()->getConfig("row_count_in_table")}};
            $('#dataTable').DataTable(
                {
                    "ordering":true,
                    'columnDefs': [ {
                        'targets': [0], // column index (start from 0)
                        'orderable': false, // set orderable false for selected columns
                    }],
                    "autoWidth": false,
                    "language": {
                        // "info": "Showing page _PAGE_ of _PAGES_",
                        "info": info,
                        "infoEmpty": infoEmpty,
                        "emptyTable": emptyTable,
                        "lengthMenu": lengthMenu,
                        "loadingRecords": loadingRecords,
                        "search": search,
                        "paginate": {
                            "next": next,
                            "previous": previous,
                        },
                        "infoFiltered": infoFiltered,
                    },
                    "processing": true,
                    "stateSave": true,
                    "createdRow": function( row, data, dataIndex ) {
                    },
                    "scrollCollapse": true,
                    "lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
                    "pageLength": pageLength,
                    drawCallback: function(){
                        $('.paginate_button', this.api().table().container())
                            .on('click', function(){
                                $("td input[type='checkbox']").get(0).click();
                                $("td input[type='checkbox']").get(0).click();
                            });
                    }
                });
        });
    </script>
    @include("inc.messages")
    @yield("script")
</body>
</html>
