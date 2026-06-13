<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
 
    <title>ABX SITE</title>
    <link href="{{asset('Apx.jpeg')}}" rel="icon">
    <!-- Tell the browser to be responsive to screen width -->
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

body, .btn, h1,.h1,h2,.h2,h3,.h3,h4,.h4,h5,.h5,h6,.h6 {
  font-family: 'Cairo', sans-serif;
}

.main-sidebar, .sidebar {
  background: linear-gradient(180deg, var(--navy) 0%, var(--navy-light) 100%) !important;
}
.brand-link {
  background: var(--navy) !important;
  border-bottom: 1px solid rgba(255,255,255,0.08) !important;
}
.main-header.navbar {
  background: var(--navy) !important;
  border-bottom: 2px solid var(--primary) !important;
}
.main-header.navbar .nav-link { color: rgba(255,255,255,0.85) !important; }
.main-header.navbar .nav-link:hover { color: #fff !important; }

.btn-primary {
  background: linear-gradient(135deg, var(--primary) 0%, #0a3d99 100%) !important;
  border-color: var(--primary) !important;
}
.btn-primary:hover {
  background: linear-gradient(135deg, var(--primary-hover) 0%, #082e77 100%) !important;
}
.text-primary { color: var(--primary) !important; }
.bg-primary   { background-color: var(--primary) !important; }

.nav-pills .nav-link.active { background-color: var(--primary) !important; color: white !important; }
.page-item.active .page-link { background-color: var(--primary) !important; border-color: var(--primary) !important; }
.page-link { color: var(--primary) !important; }

.sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active {
  background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 100%) !important;
  color: white !important;
  border-radius: 6px !important;
}
.sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link:hover:not(.active) {
  background-color: rgba(26,107,255,0.18) !important;
  color: white !important;
}

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
</style>

  </head>