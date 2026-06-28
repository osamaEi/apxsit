<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="" class="nav-link"></a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto"> 
    <!-- Messages Dropdown Menu -->
    {{-- <li class="nav-item dropdown"> 
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-comments"></i>
        <span class="badge badge-danger navbar-badge">3</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <a href="#" class="dropdown-item">
          <!-- Message Start -->
          <div class="media">
            <img src="{{ asset('dist/img/user1-128x128.jpg') }}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
            <div class="media-body">
              <h3 class="dropdown-item-title">
                John Doe
                <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
              </h3>
              <p class="text-sm">Application approved for Oxford...</p>
              <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
            </div>
          </div>
          <!-- Message End -->
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <!-- Message Start -->
          <div class="media">
            <img src="{{ asset('dist/img/user8-128x128.jpg') }}" alt="User Avatar" class="img-size-50 img-circle mr-3">
            <div class="media-body">
              <h3 class="dropdown-item-title">
                Sarah Johnson
                <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
              </h3>
              <p class="text-sm">New student registration complete</p>
              <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 2 hours ago</p>
            </div>
          </div>
          <!-- Message End -->
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <!-- Message Start -->
          <div class="media">
            <img src="{{ asset('dist/img/user3-128x128.jpg') }}" alt="User Avatar" class="img-size-50 img-circle mr-3">
            <div class="media-body">
              <h3 class="dropdown-item-title">
                Mike Peterson
                <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
              </h3>
              <p class="text-sm">University added new program</p>
              <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 5 hours ago</p>
            </div>
          </div>
          <!-- Message End -->
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
      </div>
    </li> --}}

    <!-- Notifications Dropdown Menu -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          @if(auth()->user()->unreadNotifications->count() > 0)
              <span class="badge badge-warning navbar-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
          @endif
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">{{ auth()->user()->unreadNotifications->count() }} Notifications</span>
          <div class="dropdown-divider"></div>
          
          @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
              @php
                  $data = $notification->data;
                  $message = $data['message'] ?? 'New notification';
                  // Truncate message if it's too long (40 chars)
                  $truncatedMessage = strlen($message) > 40 ? substr($message, 0, 37) . '...' : $message;
              @endphp
              <a href="{{ isset($data['application_id']) ? route('admin.applications.show', $data['application_id']) : (isset($data['student_id']) ? route('admin.students.show', $data['student_id']) : '#') }}"
                 class="dropdown-item"
                 title="{{ $message }}">
                  <i class="fas fa-file-alt mr-2"></i> 
                  <div class="d-flex justify-content-between w-100">
                      <span class="text-truncate" style="max-width: 180px;">{{ $truncatedMessage }}</span>
                      <span class="text-muted text-sm ml-2">{{ $data['time'] ?? 'Just now' }}</span>
                  </div>
              </a>
              <div class="dropdown-divider"></div>
          @empty
              <a href="#" class="dropdown-item">
                  <i class="fas fa-info-circle mr-2"></i> No new notifications
              </a>
              <div class="dropdown-divider"></div>
          @endforelse
          
          <a href="{{ route('notifications.index') }}" class="dropdown-item dropdown-footer">See All Notifications</a>
      </div>
  </li>
    
    <!-- Dark Mode Toggle -->
    <li class="nav-item">
      <button id="theme-toggle" class="btn btn-link nav-link">
        <i class="fas fa-moon"></i> Dark Mode
      </button>
    </li>
 
    <!-- User Menu -->
    <div class="btn-group">
      <button type="button" class="btn btn-sm btn-primary">
        <i class="fas fa-user"></i> {{ Auth::user()->name }}
      </button>
      <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
        <span class="sr-only">Toggle Dropdown</span>
      </button>
      <div class="dropdown-menu" role="menu">
        <a class="dropdown-item" href="{{route('admin.profile.settings')}}">
          <i class="fas fa-user"></i> Profile
        </a>
     
        <div class="dropdown-divider"></div>
        @auth
        <a class="dropdown-item" href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i>Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        @endauth
      </div>
    </div>
  </ul>
</nav>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
<script>
  $(document).ready(function() {
    // Check for saved theme preference
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
      $('body').addClass('dark-mode');
      $('#theme-toggle').html('<i class="fas fa-sun"></i> Light Mode');
    }
    // Toggle theme button
    $('#theme-toggle').click(function() {
      if ($('body').hasClass('dark-mode')) {
        // Switch to light mode
        $('body').removeClass('dark-mode');
        $(this).html('<i class="fas fa-moon"></i> Dark Mode');
        localStorage.setItem('theme', 'light');
      } else {
        // Switch to dark mode
        $('body').addClass('dark-mode');
        $(this).html('<i class="fas fa-sun"></i> Light Mode');
        localStorage.setItem('theme', 'dark');
      }
    });
  });
</script>