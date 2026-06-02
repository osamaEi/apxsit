<!DOCTYPE html>


@include('admin.body.header')

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  @include('admin.body.top_nav')

  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('admin.body.side_nav')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"></h1>
          </div><!-- /.col -->

        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
          <!-- Small boxes (Stat box) -->
          <div class="">


            @yield('content')

          </div>
        </div>
    </section>
    <!-- /.content -->
  </div>
  @include('admin.body.footer')

</body>
</html>
