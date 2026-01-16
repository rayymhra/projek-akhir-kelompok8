<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik Prima Medika - @yield('title')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #2c3e50;
        }
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 10px 15px;
        }
        .sidebar .nav-link:hover {
            background-color: #34495e;
            color: #ffffff;
        }
        .sidebar .nav-link.active {
            background-color: #3498db;
            color: #ffffff;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stat-card {
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse" style="background-color: #2c3e50;">
                <div class="position-sticky pt-3">
                    <h4 class="text-white px-3 mb-3">Prima Medika</h4>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}" href="{{ route('patients.index') }}">
                                <i class="fas fa-users me-2"></i> Data Pasien
                            </a>
                        </li>
                        @if(auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('payment-methods.*') ? 'active' : '' }}" href="{{ route('payment-methods.index') }}">
                                <i class="fas fa-credit-card me-2"></i> Metode Pembayaran
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}" href="{{ route('transactions.index') }}">
                                <i class="fas fa-exchange-alt me-2"></i> Transaksi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                                <i class="fas fa-chart-bar me-2"></i> Laporan
                            </a>
                        </li>
                    </ul>
                    
                    <hr class="text-white">
                    
                    <div class="px-3">
                        <div class="text-white small">Logged in as:</div>
                        <div class="text-white fw-bold">{{ auth()->user()->name }}</div>
                        <div class="text-white small">{{ auth()->user()->role }}</div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-light mt-3 w-100">
                                <i class="fas fa-sign-out-alt me-1"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js for Reports -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @stack('scripts')
</body>
</html>