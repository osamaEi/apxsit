<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
 
    <title>Deva School</title>
    <link href="{{asset('logo.jpg')}}" rel="icon">
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
  --primary: #7a0066cb;
  --primary-hover: #630053cb;  /* Darker for hover */
  --primary-active: #4e0041cb; /* Even darker for active states */
  --primary-light: #9c1a87cb;  /* Lighter for backgrounds or focus rings */
  --primary-very-light: #ba4ba6cb; /* Very light for subtle backgrounds */
}

/* Primary buttons */
.btn-primary {
  background-color: var(--primary) !important;
  border-color: var(--primary) !important;
}
.btn-primary:hover {
  background-color: var(--primary-hover) !important;
  border-color: var(--primary-hover) !important;
}
.btn-primary:active, .btn-primary:focus {
  background-color: var(--primary-active) !important;
  border-color: var(--primary-active) !important;
  box-shadow: 0 0 0 0.2rem rgba(122, 0, 102, 0.5) !important;
  color: white !important;
}

/* Primary text color */
.text-primary {
  color: var(--primary) !important;
}
a.text-primary:hover {
  color: var(--primary-hover) !important;
}
a.text-primary:active {
  color: white !important;
}

/* Primary background */
.bg-primary {
  background-color: var(--primary) !important;
}
.bg-primary-light {
  background-color: var(--primary-light) !important;
}

/* Navs, tabs */
.nav-pills .nav-link.active, .nav-pills .show > .nav-link {
  background-color: var(--primary) !important;
  color: white !important;
}
.nav-pills .nav-link:hover:not(.active) {
  background-color: var(--primary-very-light) !important;
  color: white !important;
}

/* Pagination */
.page-item.active .page-link {
  background-color: var(--primary) !important;
  border-color: var(--primary) !important;
  color: white !important;
}
.page-link {
  color: var(--primary) !important;
}
.page-link:hover {
  color: var(--primary-hover) !important;
  background-color: var(--primary-very-light) !important;
}
.page-link:active {
  color: white !important;
  background-color: var(--primary-active) !important;
}

/* Links in general when active */
a:active {
  color: white !important;
}

/* Sidebar links */
.sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active, 
.sidebar-light-primary .nav-sidebar > .nav-item > .nav-link.active {
  background-color: var(--primary) !important;
  color: white !important;
}
.sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link:hover:not(.active), 
.sidebar-light-primary .nav-sidebar > .nav-item > .nav-link:hover:not(.active) {
  background-color: var(--primary-hover) !important;
  color: white !important;
}
.sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link:active, 
.sidebar-light-primary .nav-sidebar > .nav-item > .nav-link:active {
  background-color: var(--primary-active) !important;
  color: white !important;
}

/* Dropdown menu */
.dropdown-item.active, .dropdown-item:active {
  background-color: var(--primary) !important;
  color: white !important;
}
.dropdown-item:hover:not(.active) {
  background-color: var(--primary-very-light) !important;
  color: white !important;
}

/* List groups */
.list-group-item.active {
  background-color: var(--primary) !important;
  border-color: var(--primary) !important;
  color: white !important;
}
.list-group-item-action:hover:not(.active) {
  background-color: var(--primary-very-light) !important;
}
.list-group-item-action:active {
  background-color: var(--primary-active) !important;
  color: white !important;
}
/* Primary Outline Button */
.btn-outline-primary {
  color: var(--primary) !important;
  border-color: var(--primary) !important;
  background-color: transparent !important;
}

.btn-outline-primary:hover {
  color: white !important;
  background-color: var(--primary-hover) !important;
  border-color: var(--primary-hover) !important;
}

.btn-outline-primary:active, 
.btn-outline-primary:focus {
  color: white !important;
  background-color: var(--primary-active) !important;
  border-color: var(--primary-active) !important;
  box-shadow: 0 0 0 0.2rem rgba(122, 0, 102, 0.5) !important;
}

.btn-outline-primary.disabled, 
.btn-outline-primary:disabled {
  color: var(--primary) !important;
  background-color: transparent !important;
  border-color: var(--primary) !important;
  opacity: 0.5;
}
    body {
    font-family: 'Cairo', sans-serif;
}
.btn {
    font-family: 'Cairo', sans-serif;

}
h1, .h1, h2, .h2, h3, .h3, h4, .h4, h5, .h5, h6, .h6 {
    font-family: 'Cairo', sans-serif;

}
  .btn-pink {
      background-color: #f0657c; /* Pink color code */
      color: white; /* Text color */
      border: none; /* Remove border */
      padding: 8px 16px; /* Padding */
      border-radius: 4px; /* Rounded corners */
      cursor: pointer; /* Cursor style */
  }

  /* Hover effect */
  .btn-pink:hover {
      background-color: #f0657c; /* Darker pink on hover */
  }
</style>

  </head>