<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Adhirajasa Trans Sejahtera</title>

  <!-- Font Awesome Icons -->
  <link rel="icon" type="image/svg+xml" href="{{ asset('ats/ATSLogo.png') }}" />
  <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">

  <!-- Theme style -->
   <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">

   <script src="{{ asset('lte/plugins/jquery/jquery.min.js') }}"></script>

   <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

   <script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  @include('admin/header')
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
   @include('admin/sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="content-header">
          <div class="container-fluid pl-4">
              <div class="row mb-1">
                  <div class="col-sm-12" style="border: 1px solid; border-color: #D0D4DB; border-radius: 10px; background-color: white; padding: 10px;">
                      <h1 class="m-0" style="font-weight: 370; font-size: 16px; padding-left: 10px">Dashboard</h1>
                  </div><!-- /.col -->
              </div><!-- /.row -->
          </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->
      
      <!-- Main content -->
      <section class="content">
          <div class="container-fluid pl-4">
            <div class="row ml-0">
              @php
                  // Mengatur zona waktu ke WIB (UTC+7)
                  $currentHour = now('Asia/Jakarta')->format('H'); // Mengambil jam dalam format 24 jam di WIB
                  $currentHourInt = (int) $currentHour; // Mengonversi jam ke integer
          
                  if ($currentHourInt >= 3 && $currentHourInt < 10) {
                      $greeting = 'Selamat Pagi';
                  } elseif ($currentHourInt >= 11 && $currentHourInt < 18) {
                      $greeting = 'Selamat Siang';
                  } else {
                      $greeting = 'Selamat Malam';
                  }
              @endphp
              <div style="font-size:28px; font-weight: 450">
                  {{ $greeting }}, {{ Auth::user()->name ?? 'User' }}
              </div>
            </div>
            <div class="row mt-2" style="background: linear-gradient(to right, #3D6FFB, #9AB76F); height: 300px; border-radius: 10px; position: relative;">
              <div class="d-flex flex-column justify-content-end align-items-start h-100 p-5 text-white">
                  <div style="font-weight:900; font-size:24px;">Welcome to</div>
                  <div style="font-weight:900; font-size:40px; margin-top: 5px;">ATS Digital</div>
              </div>
              <div style="position: absolute; bottom: 20px; right: 20px; font-size: 40px; color: white;">
                  <i class="fas fa-home"></i>
              </div>
          </div>
          
      
              <div class="row mt-4" style="border:1px solid;border-color: #E2B84D;border-radius:10px; background-color: rgba(255, 236, 188, 0.2);">
                  <div class="row p-3">
                      <div class="pl-3 mr-4 justify-content-center d-flex align-items-center">
                          <i class="fas fa-info-circle" style="color: #E2B84D;"></i>
                      </div>   
                      <div class="col-md-11" style="font-size:12px; font-weight:400">
                          Data dan informasi dalam sistem ini bersifat <span style="color:red">RAHASIA</span> yang hanya digunakan untuk kepentingan lingkungan ATS Digital, sehingga diharapkan kepada seluruh pegawai untuk tetap menjaga kerahasiaan data.
                      </div>
                  </div>
              </div>
      
              <div class="mt-3" style="font-weight: 600; font-size: 20px;">Tentang Anda</div>
              <div class="row mt-1">
                  <div class="col-md-6">
                      <div class="card" style="border-radius: 10px; height:130px; background-color: white;">
                          <div class="card-body">
                              <div style="color: #A1AAB7; font-size:14px; text-align: right; margin-top:-5px">
                                  Username
                              </div>
                              <div class="row" style="margin-top:-10px">
                                  <div class="col-md-4 pl-4">
                                      <div class="d-flex justify-content-center align-items-center" style="border: 2px solid; border-radius:50%; width:80px;height:80px; border-color: #8FACFD; background-color:#E7EDFF">
                                          <i class="far fa-user" style="font-size: 50px; color: #8FACFD"></i>
                                      </div> 
                                  </div>    
                                  <div class="col-md-8">
                                      <div class="d-flex align-items-center text-center pl-5" style="height:100%">
                                          <div style="font-weight: 700; font-size: 14px; color: #152A4C;">
                                            {{ Auth::user()->name ?? 'User' }}
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
      
                  <div class="col-md-6">
                      <div class="card" style="border-radius: 10px; height:130px; background-color: white;">
                          <div class="card-body p-4">
                              <div class="row">
                                  <div class="col-md-4">
                                      <i class="fas fa-envelope" style="font-size: 14px; color: #0C4BFA"></i>
                                      <span class="p-2" style="font-size:14px; font-weight: 500"> Email</span>
                                  </div>    
                                  <div class="col-md-8">
                                      <span style="font-size:14px; font-weight: 400; pl-5">: {{ Auth::user()->email ?? 'User' }}</span>
                                  </div>
                              </div>
                              <div class="row mt-3">
                                  <div class="col-md-4">
                                      <i class="far fa-user" style="font-size: 14px; color: #0C4BFA"></i>
                                      <span class="p-2" style="font-size:14px; font-weight: 500"> Group Name</span>
                                  </div>
                                  <div class="col-md-8">
                                      <span style="font-size:14px; font-weight: 400;">: {{ Auth::user()->name ?? 'User' }} </span>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
      
              <div class="row mt-1">
                  <div class="col-md-12">
                      <div class="card" style="border-radius: 10px; height:130px; background-color: white;">
                          <div class="card-body pl-4 pt-3">
                              <div class="row pl-2" style="color: #A1AAB7; font-size:14px">
                                  Jam & Tanggal
                              </div>
                              <div class="row" style="margin-top:-20px">
                                <div class="col-md-4"></div>    
                                <div class="col-md-8 pl-5">
                                    <div id="time" style="font-size:42px; font-weight: 600">00:00
                                        <span class="p-1" style="font-size:14px;">WIB</span>
                                    </div>
                                    <span id="date" class="p-1" style="color: #8A94A5; font-size:auto">01 January 1970</span>
                                </div>
                            </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  @include('admin/footer')
</div>
<!-- ./wrapper -->
</body>
</html>

<script>
  function updateTime() {
      // Get the current time
      const now = new Date();

      // Convert time to WIB (UTC+7)
      const options = { timeZone: 'Asia/Jakarta', hour: '2-digit', minute: '2-digit', second: '2-digit', weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
      const timeString = now.toLocaleTimeString('id-ID', options);
      const dateString = now.toLocaleDateString('id-ID', options);

      // Set the time and date in the respective elements
      document.getElementById('time').textContent = timeString.split(' ')[0];
      document.getElementById('date').textContent = dateString;
  }

  // Update the time immediately
  updateTime();

  // Update the time every second
  setInterval(updateTime, 1000);
</script>