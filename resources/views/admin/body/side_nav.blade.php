<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
        <img src="{{ asset('Apx.jpeg')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">
            @if(auth()->user()->role === 'Admin')

            Admin

            @elseif(auth()->user()->role === 'Register')
        
            Register

            @elseif(auth()->user()->role === 'Sales')

            Sales


            @else 

            User

            @endif

        </span>
    </a>
  
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Dashboard -->

                @if(auth()->user()->role === 'Admin')

                <li class="nav-item {{ request()->is('admin/dashboard*') ? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}">
                      <i class="nav-icon fas fa-tachometer-alt"></i>
                      <p>
                          Dashboard
                          <i class="right fas fa-angle-left"></i>
                      </p>
                  </a>
                  <ul class="nav nav-treeview">
                      <li class="nav-item">
                          <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Overview</p>
                          </a>
                      </li>
                     
                  </ul>
              </li>
                <li class="nav-item {{ request()->is('admin/world*') ? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ request()->is('admin/world*') ? 'active' : '' }}">
                      <i class="nav-icon fas fa-globe"></i>
                      <p>
                          World Data
                          <i class="right fas fa-angle-left"></i>
                      </p>
                  </a>
                  <ul class="nav nav-treeview">
                      <li class="nav-item">
                          <a href="{{ route('admin.world.dashboard') }}" class="nav-link {{ request()->routeIs('admin.world.dashboard') ? 'active' : '' }}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Overview</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{ route('admin.countries.index') }}" class="nav-link {{ request()->routeIs('admin.countries.index') ? 'active' : '' }}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Countries</p>
                          </a>
                      </li>
                  </ul>
              </li>
  @elseif(auth()->user()->role === 'Employee')
  <li class="nav-item {{ request()->is('admin/dashboard*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>
            Dashboard
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('employee.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Overview</p>
            </a>
        </li>
       
    </ul>
</li>
 

  @elseif(auth()->user()->role === 'Sales')
  <li class="nav-item {{ request()->is('admin/dashboard*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>
            Dashboard
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('sales.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Overview</p>
            </a>
        </li>
       
    </ul>
</li>


@elseif(auth()->user()->role === 'Register')
<li class="nav-item {{ request()->is('Register/dashboard*') ? 'menu-open' : '' }}">
  <a href="" class="nav-link {{ request()->is('Register/dashboard*') ? 'active' : '' }}">
      <i class="nav-icon fas fa-tachometer-alt"></i>
      <p>
          Dashboard
          <i class="right fas fa-angle-left"></i>
      </p>
  </a>
  <ul class="nav nav-treeview">
      <li class="nav-item">
          <a href="{{ route('register.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
              <i class="far fa-circle nav-icon"></i>
              <p>Overview</p>
          </a>
      </li>
     
  </ul>
</li>

@endif

              <li class="nav-item {{ request()->is('admin/universities*') ? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ request()->is('admin/universities*') ? 'active' : '' }}">
                      <i class="nav-icon fas fa-university"></i>
                      <p>
                          Universities
                          <i class="right fas fa-angle-left"></i>
                      </p>
                  </a>
                  <ul class="nav nav-treeview">
                      <li class="nav-item">
                          <a href="{{ route('admin.universities.index') }}" class="nav-link {{ request()->routeIs('admin.universities.index') ? 'active' : '' }}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>All Universities</p>
                          </a>
                      </li>
                      @if(auth()->user()->role === 'Admin' ||  auth()->user()->role === 'Register')

                      <li class="nav-item">
                          <a href="{{ route('admin.universities.create') }}" class="nav-link {{ request()->routeIs('admin.universities.create') ? 'active' : '' }}">
                              <i class="fas fa-plus nav-icon"></i>
                              <p>Add University</p>
                          </a>
                      </li>

                      @endif
                  </ul>
              </li>
              <li class="nav-item {{ request()->is('admin/announcements*') ? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ request()->is('admin/announcements*') ? 'active' : '' }}">
                      <i class="nav-icon fas fa-bullhorn"></i>
                      <p>
                          Announcements
                          <i class="right fas fa-angle-left"></i>
                      </p>
                  </a>
                  <ul class="nav nav-treeview">
                      <li class="nav-item">
                          <a href="{{ route('admin.announcements.index') }}" class="nav-link {{ request()->routeIs('admin.announcements.index') ? 'active' : '' }}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>All Announcements</p>
                          </a>
                      </li>

                      @if(auth()->user()->role === 'Admin' ||  auth()->user()->role === 'Register')

                      <li class="nav-item">
                          <a href="{{ route('admin.announcements.create') }}" class="nav-link {{ request()->routeIs('admin.announcements.create') ? 'active' : '' }}">
                              <i class="fas fa-plus nav-icon"></i>
                              <p>Add Announcement</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{ route('admin.announcements.index', ['status' => 'published']) }}" class="nav-link {{ request()->is('admin/announcements*') && request()->query('status') == 'published' ? 'active' : '' }}">
                              <i class="fas fa-check-circle nav-icon"></i>
                              <p>Published</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{ route('admin.announcements.index', ['status' => 'draft']) }}" class="nav-link {{ request()->is('admin/announcements*') && request()->query('status') == 'draft' ? 'active' : '' }}">
                              <i class="fas fa-file nav-icon"></i>
                              <p>Drafts</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{ route('admin.announcements.index', ['status' => 'scheduled']) }}" class="nav-link {{ request()->is('admin/announcements*') && request()->query('status') == 'scheduled' ? 'active' : '' }}">
                              <i class="fas fa-clock nav-icon"></i>
                              <p>Scheduled</p>
                          </a>
                      </li>

                      @endif
                  </ul>
              </li>

              @if(auth()->user()->role === 'Admin' ||  auth()->user()->role === 'Register')

              <li class="nav-item {{ request()->is('admin/departments*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->is('admin/departments*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-bullhorn"></i>
                    <p>
                        Departments
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.departments.index') }}" class="nav-link {{ request()->routeIs('admin.departments.index') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Departments</p>
                        </a>
                    </li>


                   
                    

                </ul>
            </li>

            <li class="nav-item {{ request()->is('admin/degrees*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->is('admin/degrees*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-bullhorn"></i>
                    <p>
                        Degrees
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('admin.degrees.index') }}" class="nav-link {{ request()->routeIs('admin.degrees.index') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Degrees</p>
                        </a>
                    </li>


                   
                    

                </ul>
            </li>

            @endif


              <li class="nav-item {{ request()->is('admin/programs*') ? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ request()->is('admin/programs*') ? 'active' : '' }}">
                      <i class="nav-icon fas fa-graduation-cap"></i>
                      <p>
                          Programs
                          <i class="right fas fa-angle-left"></i>
                      </p>
                  </a>
                  <ul class="nav nav-treeview">
                      <li class="nav-item">
                          <a href="{{ route('admin.programs.index') }}" class="nav-link {{ request()->routeIs('admin.programs.index') ? 'active' : '' }}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>All Programs</p>
                          </a>
                      </li>
                      @if(auth()->user()->role === 'Admin' ||  auth()->user()->role === 'Register')

                      <li class="nav-item">
                          <a href="{{ route('admin.programs.create') }}" class="nav-link {{ request()->routeIs('admin.programs.create') ? 'active' : '' }}">
                              <i class="fas fa-plus nav-icon"></i>
                              <p>Add Program</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{ route('admin.programs.index', ['status' => 'Active']) }}" class="nav-link {{ request()->is('admin/programs*') && request()->query('status') == 'Active' ? 'active' : '' }}">
                              <i class="fas fa-check-circle nav-icon"></i>
                              <p>Active Programs</p>
                          </a>
                      </li>
                      @endif
                  </ul>
              </li>
              <li class="nav-item {{ request()->is('admin/students*') ? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ request()->is('admin/students*') ? 'active' : '' }}">
                      <i class="nav-icon fas fa-user-graduate"></i>
                      <p>
                          Students
                          <i class="right fas fa-angle-left"></i>
                      </p>
                  </a>
                  <ul class="nav nav-treeview">
                      <li class="nav-item">
                          <a href="{{ route('admin.students.index') }}" class="nav-link {{ request()->routeIs('admin.students.index') ? 'active' : '' }}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>All Student</p>
                          </a>
                      </li>
                      
                  </ul>
              </li>
              <li class="nav-item {{ request()->is('admin/applications*') ? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ request()->is('admin/applications*') ? 'active' : '' }}">
                      <i class="nav-icon fas fa-file-alt"></i>
                      <p>
                          Applications
                          <i class="right fas fa-angle-left"></i>
                      </p>
                  </a>
                  <ul class="nav nav-treeview">
                      <li class="nav-item">
                          <a href="{{ route('admin.applications.index') }}" class="nav-link {{ request()->routeIs('admin.applications.index') ? 'active' : '' }}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>All Applications</p>
                          </a>
                      </li>
                      @if(auth()->user()->role === 'Admin' ||  auth()->user()->role === 'Register')

                      <li class="nav-item">
                          <a href="{{ route('admin.applications.create') }}" class="nav-link {{ request()->routeIs('admin.applications.create') ? 'active' : '' }}">
                              <i class="fas fa-plus nav-icon"></i>
                              <p>Add Application</p>
                          </a>
                      </li>

                      @endif
                  </ul>
              </li>

              @if(auth()->user()->role === 'Admin')

              <li class="nav-item {{ request()->is('users*') ? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                      <i class="nav-icon fas fa-users"></i>
                      <p>
                          Employees
                          <i class="right fas fa-angle-left"></i>
                      </p>
                  </a>
                  <ul class="nav nav-treeview">
                      <li class="nav-item">
                          <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('admin.applications.index') ? 'active' : '' }}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>All Employees</p>
                          </a>
                      </li>
                      
                  </ul>
              </li>
              @endif

<!-- 
              <li class="nav-item {{ request()->is('chat*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->is('chat*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        Messages
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('chat') }}" class="nav-link {{ request()->routeIs('chat') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>All Messages</p>
                        </a>
                    </li>
                    
                </ul>
            </li> -->
            @if(auth()->user()->role === 'Admin' ||auth()->user()->role === 'Register' || auth()->user()->role === 'Employee')

            <li class="nav-item {{ request()->is('subagents*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ request()->is('subagents*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        Sub agent
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('subagents.index') }}" class="nav-link {{ request()->routeIs('subagents.index') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Sub agents</p>
                        </a>
                    </li>
                    
                </ul>
            </li>

            @endif

            @if(auth()->user()->role === 'Admin')
            <li class="nav-item">
                <a href="{{ route('admin.notification-settings.index') }}"
                   class="nav-link {{ request()->routeIs('admin.notification-settings.*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-bell"></i>
                    <p>Notification Settings</p>
                </a>
            </li>
            @endif

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>