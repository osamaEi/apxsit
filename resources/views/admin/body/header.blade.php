<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
 
    <title>ABX SITE</title>
    <link href="{{asset('Apx.jpeg')}}" rel="icon">
    <!-- Tell the browser to be responsive to screen width -->

    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.css')}}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/alt/adminlte.components.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/alt/adminlte.core.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/alt/adminlte.extra-components.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/alt/adminlte.plugins.min.css">

    @yield('additional_css')

    
    <!-- Custom style for RTL -->
  
  
 <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css')}}">





<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap">



</head>
<style>
:root {
  --primary:            #1a6bff;
  --primary-hover:      #1558d6;
  --primary-active:     #1046b0;
  --primary-light:      #4d8eff;
  --primary-very-light: #ccdeff;
  --navy:               #0a1628;
  --navy-light:         #0f2040;
}

/* ── Sidebar background ── */
.main-sidebar, .sidebar {
  background: linear-gradient(180deg, var(--navy) 0%, var(--navy-light) 100%) !important;
}
.brand-link {
  background: var(--navy) !important;
  border-bottom: 1px solid rgba(255,255,255,0.08) !important;
}

/* ── Top navbar ── */
.main-header.navbar {
  background: var(--navy) !important;
  border-bottom: 2px solid var(--primary) !important;
}
.main-header.navbar .nav-link,
.main-header.navbar .navbar-nav > .nav-item > .nav-link {
  color: rgba(255,255,255,0.85) !important;
}
.main-header.navbar .nav-link:hover {
  color: #fff !important;
}

/* ── Primary buttons ── */
.btn-primary {
  background: linear-gradient(135deg, var(--primary) 0%, #0a3d99 100%) !important;
  border-color: var(--primary) !important;
}
.btn-primary:hover {
  background: linear-gradient(135deg, var(--primary-hover) 0%, #082e77 100%) !important;
  border-color: var(--primary-hover) !important;
}
.btn-primary:active, .btn-primary:focus {
  background-color: var(--primary-active) !important;
  border-color: var(--primary-active) !important;
  box-shadow: 0 0 0 0.2rem rgba(26,107,255,0.4) !important;
  color: white !important;
}

/* ── Text ── */
.text-primary { color: var(--primary) !important; }
a.text-primary:hover { color: var(--primary-hover) !important; }

/* ── Backgrounds ── */
.bg-primary { background-color: var(--primary) !important; }
.bg-primary-light { background-color: var(--primary-light) !important; }

/* ── Nav pills / tabs ── */
.nav-pills .nav-link.active, .nav-pills .show > .nav-link {
  background-color: var(--primary) !important;
  color: white !important;
}
.nav-pills .nav-link:hover:not(.active) {
  background-color: var(--primary-very-light) !important;
  color: var(--navy) !important;
}

/* ── Pagination ── */
.page-item.active .page-link {
  background-color: var(--primary) !important;
  border-color: var(--primary) !important;
  color: white !important;
}
.page-link { color: var(--primary) !important; }
.page-link:hover {
  color: var(--primary-hover) !important;
  background-color: var(--primary-very-light) !important;
}

/* ── Sidebar nav links ── */
.sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active,
.sidebar-light-primary .nav-sidebar > .nav-item > .nav-link.active {
  background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 100%) !important;
  color: white !important;
  border-radius: 6px !important;
}
.sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link:hover:not(.active),
.sidebar-light-primary .nav-sidebar > .nav-item > .nav-link:hover:not(.active) {
  background-color: rgba(26,107,255,0.18) !important;
  color: white !important;
}

/* ── Dropdown ── */
.dropdown-item.active, .dropdown-item:active {
  background-color: var(--primary) !important;
  color: white !important;
}
.dropdown-item:hover:not(.active) {
  background-color: var(--primary-very-light) !important;
  color: var(--navy) !important;
}

/* ── List groups ── */
.list-group-item.active {
  background-color: var(--primary) !important;
  border-color: var(--primary) !important;
  color: white !important;
}
.list-group-item-action:hover:not(.active) {
  background-color: var(--primary-very-light) !important;
}

/* ── Outline primary ── */
.btn-outline-primary {
  color: var(--primary) !important;
  border-color: var(--primary) !important;
  background-color: transparent !important;
}
.btn-outline-primary:hover {
  color: white !important;
  background-color: var(--primary) !important;
  border-color: var(--primary) !important;
}
.btn-outline-primary:active, .btn-outline-primary:focus {
  color: white !important;
  background-color: var(--primary-active) !important;
  border-color: var(--primary-active) !important;
  box-shadow: 0 0 0 0.2rem rgba(26,107,255,0.4) !important;
}

/* ── Card outlines ── */
.card.card-primary.card-outline { border-top: 3px solid var(--primary) !important; }

/* ── Small boxes (dashboard stats) ── */
.small-box.bg-info    { background: linear-gradient(135deg,#1a6bff,#0a3d99) !important; }
.small-box.bg-primary { background: linear-gradient(135deg,var(--navy),#1a3a6b) !important; }

/* ── Typography ── */
body, .btn, h1,.h1,h2,.h2,h3,.h3,h4,.h4,h5,.h5,h6,.h6 {
  font-family: 'Cairo', sans-serif;
}

/* ── Legacy pink button kept as blue ── */
.btn-pink {
  background: linear-gradient(135deg, var(--primary), var(--navy)) !important;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
}
.btn-pink:hover {
  background: linear-gradient(135deg, var(--primary-hover), var(--navy-light)) !important;
}

/* ═══════════════════════════════════════════════
   DARK MODE — content area matches navy sidebar
   ═══════════════════════════════════════════════ */
body.dark-mode {
  background-color: #0a1628 !important;
  color: #d0d8e8 !important;
}

/* Main content wrapper */
body.dark-mode .content-wrapper,
body.dark-mode .content-header {
  background-color: #0d1e38 !important;
  color: #d0d8e8 !important;
}

/* Cards */
body.dark-mode .card {
  background-color: #0f2040 !important;
  border-color: rgba(255,255,255,0.07) !important;
  color: #d0d8e8 !important;
}
body.dark-mode .card-header {
  background-color: #0d1e38 !important;
  border-bottom-color: rgba(255,255,255,0.08) !important;
  color: #e2e8f4 !important;
}
body.dark-mode .card-footer {
  background-color: #0d1e38 !important;
  border-top-color: rgba(255,255,255,0.08) !important;
}
body.dark-mode .card-title { color: #e2e8f4 !important; }
body.dark-mode .card-body  { color: #c8d2e6 !important; }

/* Card outlines */
body.dark-mode .card.card-primary.card-outline {
  border-top-color: var(--primary) !important;
}
body.dark-mode .card.card-secondary.card-outline {
  border-color: rgba(255,255,255,0.1) !important;
}

/* Tables */
body.dark-mode .table {
  color: #c8d2e6 !important;
}
body.dark-mode .table thead th {
  background-color: #0a1628 !important;
  color: #90a4c8 !important;
  border-bottom-color: rgba(255,255,255,0.1) !important;
}
body.dark-mode .table td,
body.dark-mode .table th {
  border-top-color: rgba(255,255,255,0.07) !important;
}
body.dark-mode .table-bordered,
body.dark-mode .table-bordered td,
body.dark-mode .table-bordered th {
  border-color: rgba(255,255,255,0.08) !important;
}
body.dark-mode .table-striped tbody tr:nth-of-type(odd) {
  background-color: rgba(255,255,255,0.03) !important;
}
body.dark-mode .table-hover tbody tr:hover {
  background-color: rgba(26,107,255,0.1) !important;
  color: #e2e8f4 !important;
}

/* Forms */
body.dark-mode .form-control,
body.dark-mode .custom-select,
body.dark-mode select.form-control,
body.dark-mode select {
  background-color: #0a1628 !important;
  border-color: rgba(255,255,255,0.15) !important;
  color: #d0d8e8 !important;
  color-scheme: dark;
}
body.dark-mode .form-control:focus,
body.dark-mode .custom-select:focus,
body.dark-mode select:focus {
  border-color: var(--primary) !important;
  box-shadow: 0 0 0 0.2rem rgba(26,107,255,0.25) !important;
  background-color: #0d1e38 !important;
  color: #e2e8f4 !important;
}
/* Disabled selects — keep readable, just dimmed */
body.dark-mode .form-control:disabled,
body.dark-mode select:disabled {
  background-color: #060e1c !important;
  color: #4a6080 !important;
  opacity: 1 !important;
  -webkit-text-fill-color: #4a6080 !important;
}
/* Option elements */
body.dark-mode option {
  background-color: #0f2040 !important;
  color: #d0d8e8 !important;
}
body.dark-mode option:disabled {
  color: #4a6080 !important;
}
body.dark-mode .form-control::placeholder { color: #4a6080 !important; }
body.dark-mode .input-group-text {
  background-color: #0d1e38 !important;
  border-color: rgba(255,255,255,0.15) !important;
  color: #90a4c8 !important;
}
body.dark-mode label { color: #a8b8d0 !important; }
body.dark-mode .form-text.text-muted { color: #4a6080 !important; }

/* Select2 */
body.dark-mode .select2-container--bootstrap4 .select2-selection {
  background-color: #0a1628 !important;
  border-color: rgba(255,255,255,0.15) !important;
  color: #d0d8e8 !important;
}
body.dark-mode .select2-container--bootstrap4 .select2-selection__rendered {
  color: #d0d8e8 !important;
}
body.dark-mode .select2-dropdown {
  background-color: #0f2040 !important;
  border-color: rgba(255,255,255,0.12) !important;
  color: #d0d8e8 !important;
}
body.dark-mode .select2-results__option {
  color: #c8d2e6 !important;
}
body.dark-mode .select2-results__option--highlighted {
  background-color: var(--primary) !important;
  color: #fff !important;
}
body.dark-mode .select2-search--dropdown .select2-search__field {
  background-color: #0a1628 !important;
  border-color: rgba(255,255,255,0.15) !important;
  color: #d0d8e8 !important;
}

/* Badges, alerts */
body.dark-mode .badge-light { background-color: #1a3050 !important; color: #d0d8e8 !important; }
body.dark-mode .alert-info  { background-color: #0d2a4a !important; border-color: #1a4a7a !important; color: #90c8f0 !important; }

/* Modals */
body.dark-mode .modal-content {
  background-color: #0f2040 !important;
  border-color: rgba(255,255,255,0.1) !important;
  color: #d0d8e8 !important;
}
body.dark-mode .modal-header {
  background-color: #0d1e38 !important;
  border-bottom-color: rgba(255,255,255,0.08) !important;
}
body.dark-mode .modal-footer {
  background-color: #0d1e38 !important;
  border-top-color: rgba(255,255,255,0.08) !important;
}

/* Dropdowns */
body.dark-mode .dropdown-menu {
  background-color: #0f2040 !important;
  border-color: rgba(255,255,255,0.1) !important;
}
body.dark-mode .dropdown-item {
  color: #c8d2e6 !important;
}
body.dark-mode .dropdown-item:hover {
  background-color: rgba(26,107,255,0.18) !important;
  color: #fff !important;
}
body.dark-mode .dropdown-divider {
  border-top-color: rgba(255,255,255,0.08) !important;
}
body.dark-mode .dropdown-header {
  color: #6a86aa !important;
}

/* Pagination */
body.dark-mode .page-link {
  background-color: #0f2040 !important;
  border-color: rgba(255,255,255,0.1) !important;
  color: var(--primary) !important;
}
body.dark-mode .page-item.disabled .page-link {
  background-color: #0a1628 !important;
  color: #4a6080 !important;
}

/* Breadcrumb & content-header */
body.dark-mode .content-header h1 { color: #e2e8f4 !important; }
body.dark-mode .breadcrumb {
  background-color: transparent !important;
}
body.dark-mode .breadcrumb-item a   { color: var(--primary-light) !important; }
body.dark-mode .breadcrumb-item.active { color: #90a4c8 !important; }

/* Text helpers */
body.dark-mode .text-muted  { color: #4a6080 !important; }
body.dark-mode .text-dark   { color: #d0d8e8 !important; }
body.dark-mode .text-black  { color: #d0d8e8 !important; }
body.dark-mode h1, body.dark-mode h2, body.dark-mode h3,
body.dark-mode h4, body.dark-mode h5, body.dark-mode h6 { color: #e2e8f4 !important; }

/* Small boxes (dashboard stat cards) */
body.dark-mode .small-box {
  background-color: #0f2040 !important;
}
body.dark-mode .small-box .inner h3,
body.dark-mode .small-box .inner p { color: #fff !important; }
body.dark-mode .small-box-footer {
  background-color: rgba(0,0,0,0.2) !important;
  color: rgba(255,255,255,0.7) !important;
}

/* Scrollbar (webkit) */
body.dark-mode ::-webkit-scrollbar { width: 6px; height: 6px; }
body.dark-mode ::-webkit-scrollbar-track { background: #0a1628; }
body.dark-mode ::-webkit-scrollbar-thumb { background: #1a3050; border-radius: 3px; }
body.dark-mode ::-webkit-scrollbar-thumb:hover { background: var(--primary); }
</style>

  </head>