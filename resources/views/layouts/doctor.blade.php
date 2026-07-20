<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Doctor Panel | Clinic PWA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Hind:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary-color: #0d6efd;
            --primary-gradient: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%);
            --success-color: #198754;
            --success-gradient: linear-gradient(135deg, #198754 0%, #146c43 100%);
            --warning-color: #f59e0b;
            --sidebar-bg: #ffffff;
            --bg-light: #f8fafc;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --border-radius: 16px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --glass-bg: rgba(255, 255, 255, 0.8);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Outfit', 'Hind', sans-serif;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }

        .scale-in {
            animation: scaleIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        /* ================= TOP BAR (MOBILE) ================= */
        .topbar {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 8px 16px;
            color: var(--text-dark);
            font-weight: 700;
            font-size: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-sm);
            border-bottom: 1px solid var(--glass-border);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .menu-btn {
            background: #f1f5f9;
            border: none;
            color: var(--primary-color);
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            transition: var(--transition);
        }

        .menu-btn:active {
            transform: scale(0.9);
        }

        /* ================= SIDEBAR ================= */
        .sidebar {
            background: var(--sidebar-bg);
            padding: 24px 16px;
            height: 100vh;
            border-right: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            overflow-y: auto;
            transition: var(--transition);
        }

        .sidebar h5 {
            color: var(--primary-color);
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 24px;
            padding-left: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            font-weight: 500;
            color: var(--text-muted);
            border-radius: var(--border-radius);
            text-decoration: none;
            margin-bottom: 8px;
            transition: var(--transition);
        }

        .sidebar a i {
            font-size: 1.2rem;
        }

        .sidebar a:hover {
            background: #f8fafc;
            color: var(--primary-color);
            transform: translateX(4px);
        }

        .sidebar a.active {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25);
        }

        /* ================= SUBMENU ================= */
        .dropdown-toggle::after {
            display: none;
        }

        .dropdown-toggle .chevron {
            margin-left: auto;
            transition: transform 0.3s ease;
        }

        .dropdown-toggle.open .chevron {
            transform: rotate(90deg);
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            padding-left: 20px;
        }

        .submenu.show {
            max-height: 500px;
        }

        .submenu a {
            font-size: 0.95rem;
            padding: 10px 16px;
        }

        /* ================= OVERLAY ================= */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
            z-index: 1040;
        }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -280px;
                width: 280px !important;
                min-width: 280px !important;
                max-width: 280px !important;
                z-index: 1050;
                height: 100vh;
                overflow-y: auto;
                margin: 0 !important; /* Reset any margins */
            }

            .sidebar.show {
                left: 0 !important; /* Explicitly touch the left edge */
                box-shadow: 20px 0 50px rgba(0, 0, 0, 0.1);
            }

            .sidebar-overlay.show {
                display: block;
            }
        }

        /* Card Customization */
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            background: #ffffff;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .btn {
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
            color: white;
        }

        .btn-premium {
            background: var(--primary-gradient);
            color: white;
            border: none;
            box-shadow: 0 10px 20px -5px rgba(13, 110, 253, 0.4);
        }

        .btn-premium:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 15px 25px -5px rgba(13, 110, 253, 0.5);
            color: white;
        }

        /* Breadcrumb */
        .breadcrumb {
            padding: 0 !important;
            margin-bottom: 0.25rem !important;
            font-size: 0.85rem;
        }

        /* Mobile-First Optimizations */
        @media (max-width: 768px) {
            .btn {
                min-height: 40px;
                padding-left: 10px;
                padding-right: 10px;
                max-width: 100%;
                width: auto;
                box-sizing: border-box;
                font-size: 0.85rem;
            }

            .btn.w-100,
            .btn.btn-lg {
                width: calc(100% - 0px) !important;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 6px 8px !important;
                word-break: break-word;
            }

            .form-control,
            .form-select {
                min-height: 44px;
                font-size: 16px;
                border-radius: 12px;
                width: 100%;
                box-sizing: border-box;
                padding: 8px 12px;
            }

            .card-body {
                padding: 1.5rem;
            }

            .container-fluid {
                padding-left: 12px;
                padding-right: 12px;
                margin-top: 0;
            }

            main {
                padding-left: 0 !important;
                padding-right: 0 !important;
                padding-top: 0 !important;
                margin-top: 2px !important;
            }

            .row {
                margin-left: 0 !important;
                margin-right: 0 !important;
                row-gap: 0.5rem !important;
                padding-top: 0.25rem;
            }

            .col-12 {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            .card {
                margin-bottom: 0.25rem !important;
            }

            .card-body {
                padding: 0.75rem !important;
            }

            .mb-4 {
                margin-bottom: 0.5rem !important;
            }

            .mb-3 {
                margin-bottom: 0.5rem !important;
            }

            .gap-2 {
                gap: 0.5rem !important;
            }

            .gap-3 {
                gap: 0.75rem !important;
            }

            .gap-4 {
                gap: 1rem !important;
            }

            .container-fluid {
                padding-left: 0;
                padding-right: 0;
                margin-top: 10px;
                padding-top: 0 !important;
            }

            .dropdown-menu {
                min-width: 160px;
                max-width: calc(100vw - 20px);
            }
        }
    </style>
</head>

<body>

    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" onclick="toggleMenu()"></div>

    <!-- ================= MOBILE TOP BAR ================= -->
    <div class="topbar d-md-none">
        <div class="d-flex align-items-center gap-3">
            <button class="menu-btn" onclick="toggleMenu()">
                <i class="bi bi-list"></i>
            </button>
            <span>Doctor Panel</span>
        </div>
        <div class="dropdown">
            <button class="menu-btn" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="border-radius: 12px;">
                <li>
                    <form method="POST" action="{{ route('doctor.logout') }}">
                        @csrf
                        <button class="dropdown-item text-danger fw-600">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">

            <!-- ================= SIDEBAR ================= -->
            @php
            $mastersActive = request()->routeIs(
            'medicine.categories.*',
            'dose.masters.*',
            'start.times.*',
            'medicine.masters.*'
            );
            @endphp

            <div id="sidebar" class="col-md-3 col-lg-2 sidebar">
                <h5><i class="bi bi-heart-pulse-fill"></i> Clinic PWA</h5>

                @if(auth()->user()->role === 'doctor')
                <div class="mb-4 d-flex flex-column gap-2 p-2 bg-light rounded-3">
                    <div class="small fw-bold text-muted text-uppercase px-2 mb-1" style="font-size: 0.7rem;">Switch Panel</div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-dark d-flex align-items-center gap-2 border-0 text-start py-2">
                        <i class="bi bi-gear-fill"></i> Admin
                    </a>
                    <a href="{{ route('doctor.dashboard') }}" class="btn btn-sm btn-primary d-flex align-items-center gap-2 border-0 text-start py-2 active">
                        <i class="bi bi-stethoscope"></i> Doctor
                    </a>
                    <a href="{{ route('reception.dashboard') }}" class="btn btn-sm btn-outline-success d-flex align-items-center gap-2 border-0 text-start py-2">
                        <i class="bi bi-person-badge"></i> Reception
                    </a>
                </div>
                @endif

                <a href="{{ route('doctor.dashboard') }}"
                    class="{{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>

                <a href="{{ route('doctor.pending') }}"
                    class="{{ request()->routeIs('doctor.pending') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i> Pending Patients
                </a>

                <a href="{{ route('doctor.arrived') }}"
                    class="{{ request()->routeIs('doctor.arrived') ? 'active' : '' }}">
                    <i class="bi bi-person-check-fill"></i> Arrived Patients
                </a>

                <a href="{{ route('doctor.seen') }}"
                    class="{{ request()->routeIs('doctor.seen') ? 'active' : '' }}">
                    <i class="bi bi-check-all"></i> Seen Patients
                </a>

                <!-- ===== MASTERS DROPDOWN ===== -->
                <a href="#"
                    class="dropdown-toggle {{ $mastersActive ? 'active open' : '' }}"
                    onclick="toggleMasters(this); return false;">
                    <i class="bi bi-database-fill-gear"></i> Masters
                    <i class="bi bi-chevron-right chevron"></i>
                </a>

                <div id="mastersSubmenu" class="submenu {{ $mastersActive ? 'show' : '' }}">
                    <a href="{{ route('medicine.categories.index') }}"
                        class="{{ request()->routeIs('medicine.categories.*') ? 'active' : '' }}">
                        <i class="bi bi-capsule"></i> Categories
                    </a>

                    <a href="{{ route('dose.masters.index') }}"
                        class="{{ request()->routeIs('dose.masters.*') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i> Dose Master
                    </a>

                    <a href="{{ route('start.times.index') }}"
                        class="{{ request()->routeIs('start.times.*') ? 'active' : '' }}">
                        <i class="bi bi-alarm"></i> Start Times
                    </a>

                    <a href="{{ route('medicine.masters.index') }}"
                        class="{{ request()->routeIs('medicine.masters.*') ? 'active' : '' }}">
                        <i class="bi bi-prescription2"></i> Medicines
                    </a>
                </div>

                <div class="mt-auto pt-4">
                    <hr class="text-muted opacity-25">
                    <a href="#" class="text-danger"
                        onclick="event.preventDefault(); document.getElementById('doctorLogoutForm').submit();">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>

                <form id="doctorLogoutForm" method="POST" action="{{ route('doctor.logout') }}">
                    @csrf
                </form>
            </div>

            <!-- ================= MAIN CONTENT ================= -->
            <main class="col-md-9 col-lg-10 p-4">
                <div class="fade-in-up">
                    @yield('content')
                </div>
            </main>

        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        /* MOBILE SIDEBAR TOGGLE */
        function toggleMenu() {
            document.getElementById("sidebar").classList.toggle("show");
            document.querySelector(".sidebar-overlay").classList.toggle("show");
        }

        /* MASTERS DROPDOWN */
        function toggleMasters(el) {
            document.getElementById("mastersSubmenu").classList.toggle("show");
            el.classList.toggle("open");
        }

        /* AUTO CLOSE SIDEBAR ON MOBILE LINK CLICK */
        document.querySelectorAll('.sidebar a:not(.dropdown-toggle)').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 768) {
                    document.getElementById("sidebar").classList.remove("show");
                    document.querySelector(".sidebar-overlay").classList.remove("show");
                }
            });
        });
    </script>

    @stack('modals')
    @stack('scripts')
</body>

</html>