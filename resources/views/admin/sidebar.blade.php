<aside class="main-sidebar custom-sidebar elevation-4">
  <!-- Brand Logo -->
  <a href="{{ url('/dashboard') }}" class="brand-link mb-3">
    <img src="{{ asset('ats/ATSLogo.png') }}" alt="ATS Logo" class="brand-image">
    <span class="brand-text">ATS Digital</span>
  </a>


  <!-- Sidebar -->
  <div class="sidebar mt-1">
    <!-- SidebarSearch Form -->
    <div class="form-inline mt-2">
      <div class="input-group" data-widget="sidebar-search">
        <input id="sidebarSearch" class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
      <div id="noResults" class="no-results mt-2 d-none">No elements found</div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="nav-icon fa fa-home"></i>
            <p>Home</p>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->is('management-user/*') ? 'active' : '' }}">
            <i class="nav-icon fa fa-list-alt"></i>
            <p>Management User<i class="right fas fa-angle-left"></i></p>
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
            <p>Log<i class="right fas fa-angle-left"></i></p>
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
        <li class="nav-item">
          <a class="nav-link {{ request()->is('master-data/*') ? 'active' : '' }}">
            <i class="nav-icon fa fa-list-alt"></i>
            <p>Master Data<i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('master-data.customers.index') }}" class="nav-link {{ request()->routeIs('master-data.customers.index') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Data Customer</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('master-data.item-types.index') }}" class="nav-link {{ request()->routeIs('master-data.item-types.index') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Tipe Barang</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('master-data.bank-data.index') }}" class="nav-link {{ request()->routeIs('master-data.bank-data.index') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Data Bank</p>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->

    <div class="sidebar-footer">
      <form action="{{ route('logout') }}" method="POST" class="logout-form">
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
/* Sidebar styling */
.custom-sidebar {
  background-color: #f8f9fa; /* Light background color for the sidebar */
  color: #000; /* Black text color for contrast */
}

/* Brand Logo Styling */
.brand-link {
  display: flex;
  align-items: center;
  background-color: #ffffff; /* White background for better contrast */
  padding: 10px; /* Add padding around the link */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Soft shadow for depth */
  transition: background-color 0.3s, box-shadow 0.3s; /* Smooth transitions */
}

.brand-link:hover {
  background-color: #f1f1f1; /* Light gray background on hover */
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15); /* Enhanced shadow on hover */
}

.brand-image {
  width: 13px; /* Slightly larger image */
  height: 13px; /* Maintain aspect ratio */
}

.brand-text {
  font-size: 1.2rem; /* Increase font size for readability */
  font-weight: 600; /* Semi-bold text */
  color: #333; /* Dark gray color for text */
  text-transform: uppercase; /* Uppercase text for a bolder look */
  letter-spacing: 1px; /* Slightly spaced letters */
  transition: color 0.3s; /* Smooth color transition */
}

.brand-link:hover .brand-text {
  color: #007bff; /* Change text color on hover */
}

.form-control-sidebar {
  border-radius: 20px; /* Rounded corners for input */
  border: 1px solid #ced4da; /* Light border color */
  background-color: #ffffff; /* White background for the input */
}

.btn-sidebar {
  border-radius: 20px; /* Rounded corners to match input */
  background-color: #007bff; /* Bootstrap primary color */
  color: #ffffff; /* White text color */
  border: 1px solid #007bff; /* Matching border color */
}

.btn-sidebar:hover {
  background-color: #0056b3; /* Darker blue on hover */
  border-color: #0056b3; /* Matching border color */
}

.nav-sidebar .nav-link {
  color: #000; /* Black text color for nav links */
  border-radius: 8px; /* Rounded corners for nav items */
  margin: 5px 0; /* Space between nav items */
}

.nav-sidebar .nav-link.active {
  background-color: #e7edff; /* Light blue background for active items */
  color: #000; /* Black text color for active items */
}

.nav-sidebar .nav-treeview .nav-link {
  padding-left: 20px; /* Indentation for nested items */
}

.sidebar-footer {
  position: absolute;
  bottom: 0; /* Stick to the bottom of the sidebar */
  width: 100%;
  padding: 10px 15px; /* Add padding for better spacing */
  background-color: #ffffff; /* White background for footer */
  border-top: 1px solid #dee2e6; /* Subtle top border */
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
}

.logout-form .btn-danger {
  background-color: #dc3545; /* Bootstrap danger color */
  border-color: #dc3545; /* Matching border color */
  font-size: 14px; /* Font size for readability */
  padding: 10px 15px; /* Padding for balance */
  border-radius: 5px; /* Rounded corners */
}

.logout-form .btn-danger:hover {
  background-color: #c82333; /* Darker red on hover */
  border-color: #bd2130; /* Matching border color */
}

.logout-form .btn-danger i {
  margin-right: 8px; /* Space between icon and text */
}

/* Styling for the 'No elements found' message */
.no-results {
  color: #6c757d; /* Gray text color */
  text-align: center; /* Center the text */
  padding: 10px; /* Add padding for better spacing */
  font-size: 14px; /* Font size */
}

/* Ensure the search container has enough height */
.form-inline {
  margin-bottom: 1.5rem; /* Adjust margin if necessary */
}

</style>


<script>
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('sidebarSearch');
  const navLinks = document.querySelectorAll('.nav-sidebar .nav-link');
  const noResults = document.getElementById('noResults');

  searchInput.addEventListener('input', function() {
    const searchTerm = searchInput.value.toLowerCase();
    let hasVisibleItems = false;

    if (searchTerm === '') {
      navLinks.forEach(link => {
        link.style.display = ''; // Show all items
        link.classList.remove('highlight'); // Remove highlight
      });
      noResults.classList.add('d-none'); // Hide 'No elements found' message
    } else {
      navLinks.forEach(link => {
        const text = link.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
          link.style.display = ''; // Show matching items
          link.classList.add('highlight'); // Add highlight
          hasVisibleItems = true; // Indicate that there are visible items
        } else {
          link.style.display = 'none'; // Hide non-matching items
          link.classList.remove('highlight'); // Remove highlight
        }
      });
      noResults.classList.toggle('d-none', hasVisibleItems); // Show or hide 'No elements found' message
    }
  });
});

</script>


