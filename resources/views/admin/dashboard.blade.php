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
        <!-- Main Sidebar Container -->
        @include('admin/sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <!-- Content Header -->
                    <div class="content-header">
                        <div class="container-fluid pl-4">
                            <div class="row mb-1">
                                <div class="col-sm-12" style="border: 1px solid #D0D4DB; border-radius: 10px; background-color: white; padding: 10px;">
                                    <h1 class="m-0" style="font-size: 16px; font-weight: bold; padding-left: 10px;">
                                        Dashboard
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main content -->
                    <section class="content">
                        <div class="container-fluid pl-4">
                            <!-- Greeting Section -->
                            <div class="row ml-0">
                                @php
                                // Mengatur zona waktu ke WIB (UTC+7)
                                $currentHour = now('Asia/Jakarta')->format('H');
                                $currentHourInt = (int) $currentHour;
                                if ($currentHourInt >= 3 && $currentHourInt < 10) {
                                    $greeting = 'Selamat Pagi';
                                } elseif ($currentHourInt >= 11 && $currentHourInt < 18) {
                                    $greeting = 'Selamat Siang';
                                } else {
                                    $greeting = 'Selamat Malam';
                                }
                                @endphp
                                <div style="font-size:28px; font-weight: 450;">
                                    {{ $greeting }}, {{ Auth::user()->name ?? 'User' }}
                                </div>
                            </div>

                            <!-- Welcome Section -->
                            <div class="row mt-2" style="background: linear-gradient(to right, #3D6FFB, #9AB76F); height: 300px; border-radius: 10px; position: relative;">
                                <div class="d-flex flex-column justify-content-end align-items-start h-100 p-5 text-white">
                                    <div style="font-weight: 900; font-size: 24px;">Welcome to</div>
                                    <div style="font-weight: 900; font-size: 40px; margin-top: 5px;">ATS Digital</div>
                                </div>
                                <div style="position: absolute; bottom: 20px; right: 20px; font-size: 40px; color: white;">
                                    <i class="fas fa-home"></i>
                                </div>
                            </div>

                            <!-- Info Section -->
                            <div class="row mt-4" style="border:1px solid #E2B84D; border-radius:10px; background-color: rgba(255, 236, 188, 0.2);">
                                <div class="row p-3">
                                    <div class="pl-3 mr-4 justify-content-center d-flex align-items-center">
                                        <i class="fas fa-info-circle" style="color: #E2B84D;"></i>
                                    </div>
                                    <div class="col-md-11" style="font-size:12px; font-weight:400;">
                                        Data dan informasi dalam sistem ini bersifat <span style="color:red">RAHASIA</span> yang hanya digunakan untuk kepentingan lingkungan ATS Digital, sehingga diharapkan kepada seluruh pegawai untuk tetap menjaga kerahasiaan data.
                                    </div>
                                </div>
                            </div>

                            <!-- About You Section -->
                            <div class="mt-3" style="font-weight: 600; font-size: 20px;">Tentang Anda</div>
                            <div class="row mt-1">
                                <!-- Card 1: Username -->
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100" style="border-radius: 10px;">
                                        <div class="card-body d-flex flex-column justify-content-between">
                                            <div class="text-right text-muted" style="font-size: 14px;">Username</div>
                                            <div class="d-flex">
                                                <div class="d-flex justify-content-center align-items-center" style="border: 2px solid #8FACFD; border-radius: 50%; width: 80px; height: 80px; background-color: #E7EDFF;">
                                                    <i class="far fa-user" style="font-size: 50px; color: #8FACFD;"></i>
                                                </div>
                                                <div class="d-flex justify-content-center align-items-center flex-grow-1">
                                                    <span class="font-weight-bold text-primary text-center" style="font-size: 16px;">
                                                        {{ Auth::user()->name ?? 'User' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card 2: Email and Group -->
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100" style="border-radius: 10px;">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-4 d-flex align-items-center">
                                                    <i class="fas fa-envelope text-primary" style="font-size: 14px;"></i>
                                                    <span class="ml-2 font-weight-medium" style="font-size: 14px;">Email</span>
                                                </div>
                                                <div class="col-8">
                                                    <span style="font-size: 14px;">: {{ Auth::user()->email ?? 'User' }}</span>
                                                </div>
                                            </div>
                                            <div class="row align-items-center mt-3">
                                                <div class="col-4 d-flex align-items-center">
                                                    <i class="far fa-user text-primary" style="font-size: 14px;"></i>
                                                    <span class="ml-2 font-weight-medium" style="font-size: 14px;">Group Name</span>
                                                </div>
                                                <div class="col-8">
                                                    <span style="font-size: 14px;">: {{ Auth::user()->group_name ?? 'Group' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Date and Time Section -->
                            <div class="row mt-1">
                                <div class="col-md-12">
                                    <div class="card" style="border-radius: 10px; height: 130px; background-color: white;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 text-muted" style="font-size: 14px;">
                                                    Jam & Tanggal
                                                </div>
                                            </div>
                                            <div class="row d-flex align-items-center" style="overflow: hidden;">
                                                <div class="col-md-4 d-none d-md-block"></div>
                                                <div class="col-md-8 text-center text-md-left">
                                                    <div id="time" style="font-size: 42px; font-weight: Bold; white-space: nowrap;">
                                                        00:00 <span style="font-size: 14px;">WIB</span>
                                                    </div>
                                                    <div id="date" class="text-muted" style="font-size: 16px; white-space: nowrap;">
                                                        01 January 1970
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <div class="p-3">
                <h5>Title</h5>
                <p>Sidebar content</p>
            </div>
        </aside>

        <!-- Main Footer -->
        @include('admin/footer')
    </div>
</body>

</html>


<script>
    function updateTime() {
        // Get the current time
        const now = new Date();

        // Convert time to WIB (UTC+7)
        const options = {
            timeZone: 'Asia/Jakarta',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        };
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