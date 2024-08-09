<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
      <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button">
              <i class="fas fa-bars"></i>
          </a>
      </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
      <!-- Dropdown for User Profile (Optional) -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button">
            <i class="fas fa-user"></i> {{ Auth::user()->name ?? 'User' }}
        </a>
          <div class="dropdown-menu dropdown-menu-right">
            <form action="{{ route('logout') }}" method="POST" class="dropdown-item">
                @csrf
                <button type="submit" class="btn btn-link">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </button>
            </form>
        </div>        
      </li>
  </ul>
</nav>
