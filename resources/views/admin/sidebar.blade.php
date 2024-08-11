<aside class="main-sidebar custom-sidebar elevation-4">
  <!-- Brand Logo -->
  <a href="{{ url('/dashboard') }}" class="brand-link mb-3">
    <img src="{{ asset('ats/ATSLogo.png') }}" alt="AdminLTE Logo" class="brand-image elevation-2" style="opacity: .8; width: 35px; height: 30px;">
    <span class="brand-text font-weight-light">ATS Digital</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar mb-3">
    <!-- SidebarSearch Form -->
    <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="nav-icon fa fa-home"></i>
            <p>
              Home
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('management-user/*') ? 'active' : '' }}">
            <i class="nav-icon fa fa-list-alt"></i>
            <p>
              Management User
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('management-user.users.index') }}" class="nav-link {{ request()->routeIs('management-user.users.index') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>User</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ request()->is('log/*') ? 'active' : '' }}">
            <i class="nav-icon fa fa-list-alt"></i>
            <p>
              Log
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Data Logs</p>
              </a>
            </li>
          </ul>
        </li>

      </ul>
    </nav>
    <!-- /.sidebar-menu -->

    <div class="sidebar-footer mb-6">
      <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-danger btn-block">
              <i class="fas fa-sign-out-alt"></i> Logout
          </button>
      </form>
  </div>
  
  </div>
  <!-- /.sidebar -->
</aside>

<style>
 .custom-sidebar {
  background-color: #ffffff; /* Set the background color to white */
  color: #000000; /* Set the text color to black for better contrast */
}

.custom-sidebar .nav-link {
  color: #000000; /* Set the text color of nav links */
}

.custom-sidebar .nav-link.active {
  background-color: #E7EDFF; /* Set background color for active links */
  color: #000000; /* Set text color of active links */
}

.custom-sidebar .brand-link {
  color: #000000; /* Set the color for the brand text */
}

.custom-sidebar .form-control-sidebar {
  background-color: #e9ecef; /* Optional: background color for search input */
}

.nav-sidebar .nav-link {
  color: #000000; /* Set text color to black */
}

/* Set text color for active nav link and background color */
.nav-sidebar .nav-link.active {
  color: #000000; /* Set text color to black */
  background-color: #E7EDFF; /* Set background color to blue */
}

/* Set text color for active nav link in dropdown */
.nav-sidebar .nav-treeview .nav-link.active {
  color: #000000; /* Set text color to black */
  background-color: #E7EDFF; /* Set background color to blue */
}

/* Optional: Change color on hover */
.nav-sidebar .nav-link:hover {
  color: #000000; /* Set text color to black on hover */
}

/* Style for the sidebar footer and logout button */
.sidebar-footer {
  position: absolute;
  bottom: 50px; /* Adjust based on the height of the new footer */
  width: 100%;
  padding: 10px;
  background-color: #f8f9fa; /* Background color for the footer */
}

.sidebar-footer .btn {
  border-radius: 0; /* Remove border radius for a more rectangular look */
  margin: 0; /* Remove margin */
}

.sidebar-footer .btn-danger {
  background-color: #dc3545; /* Bootstrap danger color for logout button */
  border-color: #dc3545; /* Border color for logout button */
}

.sidebar-footer .btn-danger:hover {
  background-color: #c82333; /* Darker color on hover */
  border-color: #bd2130; /* Border color on hover */
}
</style>
