
<!doctype html>
<html lang="bn">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Roman Electronic & Furnitures @yield('title')</title>

    <!-- Preconnect -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Bangla Font -->
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">

    <!-- jQuery -->
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <style>

        /* =========================================
           GLOBAL
        ========================================= */

        :root {
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --secondary: #7c3aed;
            --dark: #111827;
            --text: #374151;
            --muted: #6b7280;
            --light-bg: #f5f7fb;
            --border: #e5e7eb;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            padding: 0;
            background: var(--light-bg);
            color: var(--text);
            font-family: "SolaimanLipi", "Noto Sans Bengali", Arial, sans-serif;
            min-height: 100vh;
        }

        /* =========================================
           NAVBAR
        ========================================= */

        .main-navbar {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(229, 231, 235, 0.8);
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.06);
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .navbar-container {
            max-width: 1600px;
            margin: auto;
            padding: 0 20px;
        }

        /* Logo */

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 21px;
            font-weight: 800;
            color: var(--dark) !important;
            letter-spacing: -0.3px;
            padding: 15px 0;
            transition: 0.3s ease;
        }

        .navbar-brand:hover {
            color: var(--primary) !important;
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            color: white;
            background: linear-gradient(
                135deg,
                var(--primary),
                var(--secondary)
            );
            box-shadow: 0 6px 15px rgba(79, 70, 229, 0.25);
        }

        .brand-text {
            line-height: 1.1;
        }

        .brand-subtitle {
            display: block;
            font-family: Arial, sans-serif;
            font-size: 10px;
            font-weight: 500;
            color: var(--muted);
            margin-top: 3px;
            letter-spacing: 0.5px;
        }

        /* Toggle */

        .navbar-toggler {
            border: 0;
            padding: 8px 10px;
            border-radius: 10px;
            background: #f3f4f6;
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }

        /* Navigation */

        .navbar-nav {
            gap: 3px;
        }

        .nav-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: 6px;
            color: #4b5563 !important;
            font-size: 14px;
            font-weight: 600;
            padding: 10px 12px !important;
            border-radius: 9px;
            transition: all 0.25s ease;
        }

        .nav-link:hover {
            color: var(--primary) !important;
            background: #f3f4ff;
        }

        .nav-link.active {
            color: var(--primary) !important;
            background: #eef2ff;
        }

        .nav-link.active::before {
            content: "";
            position: absolute;
            left: 8px;
            right: 8px;
            bottom: 3px;
            height: 2px;
            border-radius: 10px;
            background: linear-gradient(
                90deg,
                var(--primary),
                var(--secondary)
            );
        }

        /* =========================================
           LANGUAGE SELECT
        ========================================= */

        .language-wrapper {
            margin-right: 5px;
        }

        .language-select {
            min-width: 105px;
            border: 1px solid var(--border);
            border-radius: 9px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            background-color: white;
            padding: 7px 30px 7px 10px;
            cursor: pointer;
            transition: 0.25s ease;
        }

        .language-select:hover,
        .language-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.10);
        }

        /* =========================================
           USER DROPDOWN
        ========================================= */

        .user-nav {
            margin-left: 7px;
        }

        .user-button {
            background: #f8fafc;
            border: 1px solid var(--border);
            padding: 8px 12px !important;
            border-radius: 10px;
        }

        .user-button:hover {
            background: #eef2ff;
            border-color: #c7d2fe;
        }

        .user-icon {
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: white;
            background: linear-gradient(
                135deg,
                var(--primary),
                var(--secondary)
            );
        }

        .dropdown-menu {
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 8px;
            min-width: 210px;
            box-shadow: 0 15px 40px rgba(15, 23, 42, 0.12);
        }

        .dropdown-item {
            border-radius: 9px;
            padding: 9px 12px;
            font-size: 14px;
            font-weight: 500;
            transition: 0.2s ease;
        }

        .dropdown-item:hover {
            background: #f3f4ff;
            color: var(--primary);
        }

        .dropdown-divider {
            border-color: var(--border);
        }

        /* =========================================
           MAIN CONTENT
        ========================================= */

        .main-content {
            width: 100%;
            max-width: 1600px;
            margin: auto;
            padding: 28px 20px 10px;
            min-height: calc(100vh - 150px);
        }

        /* =========================================
           FOOTER
        ========================================= */

        .main-footer {
            margin-top: 40px;
            padding: 22px 15px;
            background: white;
            border-top: 1px solid var(--border);
            color: var(--muted);
        }

        .footer-content {
            max-width: 1600px;
            margin: auto;
            text-align: center;
            font-size: 13px;
        }

        .footer-brand {
            color: var(--primary);
            font-weight: 700;
        }

        /* =========================================
           SCROLLBAR
        ========================================= */

        ::-webkit-scrollbar {
            width: 7px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 20px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* =========================================
           RESPONSIVE
        ========================================= */

        @media (max-width: 1199px) {

            .nav-link {
                font-size: 13px;
                padding: 9px 10px !important;
            }

            .navbar-nav {
                gap: 2px;
            }

        }

        @media (max-width: 991px) {

            .navbar-container {
                padding: 0 15px;
            }

            .navbar-collapse {
                margin-top: 10px;
                padding: 10px 0 15px;
                border-top: 1px solid var(--border);
            }

            .navbar-nav {
                gap: 4px;
            }

            .nav-link {
                padding: 11px 13px !important;
            }

            .nav-link.active::before {
                display: none;
            }

            .language-wrapper {
                margin: 8px 0;
            }

            .language-select {
                width: 100%;
            }

            .user-nav {
                margin: 8px 0 0;
            }

            .user-button {
                width: 100%;
                justify-content: flex-start;
            }

            .dropdown-menu {
                width: 100%;
                box-shadow: none;
            }

            .main-content {
                padding: 20px 15px;
            }

        }

        @media (max-width: 576px) {

            .navbar-brand {
                font-size: 17px;
            }

            .brand-icon {
                width: 38px;
                height: 38px;
            }

            .brand-subtitle {
                font-size: 8px;
            }

            .main-content {
                padding: 15px 10px;
            }

            .main-footer {
                margin-top: 25px;
            }

        }

    </style>

    @stack('styles')

</head>

<body>

    <!-- =========================================
         NAVBAR
    ========================================== -->

    <nav class="navbar navbar-expand-lg main-navbar">

        <div class="container-fluid navbar-container">

            <!-- Logo -->

            <a class="navbar-brand" href="{{ route('dashboard') }}">

                <span class="brand-icon">
                    <i class="bi bi-shop"></i>
                </span>

                <span class="brand-text">
                    Roman EMI
                    <small class="brand-subtitle">
                        ELECTRONICS & FURNITURES
                    </small>
                </span>

            </a>


            <!-- Mobile Toggle -->

            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainNavbar"
                aria-controls="mainNavbar"
                aria-expanded="false"
                aria-label="Toggle navigation">

                <i class="bi bi-list fs-4"></i>

            </button>


            <!-- Navigation -->

            <div class="collapse navbar-collapse" id="mainNavbar">

                <ul class="navbar-nav ms-auto align-items-lg-center">


                    <!-- Language -->

                    <li class="nav-item language-wrapper">

                        <form method="POST" action="{{ route('locale.switch') }}">

                            @csrf

                            <select
                                name="locale"
                                class="form-select language-select"
                                onchange="this.form.submit()"
                                aria-label="{{ __('ui.language') }}">

                                <option value="en"
                                    @selected(app()->getLocale() === 'en')>
                                    🇬🇧 {{ __('ui.english') }}
                                </option>

                                <option value="bn"
                                    @selected(app()->getLocale() === 'bn')>
                                    🇧🇩 {{ __('ui.bangla') }}
                                </option>

                            </select>

                        </form>

                    </li>


                    <!-- Dashboard -->

                    <li class="nav-item">

                        <a
                            href="{{ route('dashboard') }}"
                            class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-semibold' : '' }}">

                            <i class="bi bi-speedometer2"></i>
                            Dashboard

                        </a>

                    </li>


                    <!-- Customers -->

                    @can('customer-list')

                    <li class="nav-item">

                        <a
                            href="{{ route('customers.index') }}"
                            class="nav-link {{ request()->routeIs('customers.*') ? 'active fw-semibold' : '' }}">

                            <i class="bi bi-people"></i>
                            Customers

                        </a>

                    </li>

                    @endcan


                    <!-- Locations -->

                    @can('location-list')

                    <li class="nav-item">

                        <a
                            href="{{ route('locations.index') }}"
                            class="nav-link {{ request()->routeIs('locations.*') ? 'active fw-semibold' : '' }}">

                            <i class="bi bi-geo-alt"></i>
                            Locations

                        </a>

                    </li>

                    @endcan


                    <!-- Products -->

                    @can('product-list')

                    <li class="nav-item">

                        <a
                            href="{{ route('products.index') }}"
                            class="nav-link {{ request()->routeIs('products.*') ? 'active fw-semibold' : '' }}">

                            <i class="bi bi-box-seam"></i>
                            Products

                        </a>

                    </li>

                    @endcan


                    <!-- Product Models -->

                    @can('product-model-list')

                    <li class="nav-item">

                        <a
                            href="{{ route('products.model') }}"
                            class="nav-link {{ request()->routeIs('products.model') ? 'active fw-semibold' : '' }}">

                            <i class="bi bi-grid-3x3-gap"></i>
                            Product Models

                        </a>

                    </li>

                    @endcan


                    <!-- Users -->

                    @can('user-list')

                    <li class="nav-item">

                        <a
                            href="{{ route('users.index') }}"
                            class="nav-link {{ request()->routeIs('users.*') ? 'active fw-semibold' : '' }}">

                            <i class="bi bi-person-gear"></i>
                            Users

                        </a>

                    </li>

                    @endcan


                    <!-- Roles -->

                    @can('role-list')

                    <li class="nav-item">

                        <a
                            href="{{ route('roles.index') }}"
                            class="nav-link {{ request()->routeIs('roles.*') ? 'active fw-semibold' : '' }}">

                            <i class="bi bi-shield-lock"></i>
                            User Roles

                        </a>

                    </li>

                    @endcan


                    <!-- User -->

                    <li class="nav-item dropdown user-nav">

                        <a
                            class="nav-link dropdown-toggle user-button"
                            href="#"
                            id="userDropdown"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">

                            <span class="user-icon">
                                <i class="bi bi-person"></i>
                            </span>

                            <span>
                                {{ Auth::check() ? Auth::user()->name : 'Login' }}
                            </span>

                        </a>


                        <ul
                            class="dropdown-menu dropdown-menu-end"
                            aria-labelledby="userDropdown">

                            <li>

                                <a
                                    class="dropdown-item"
                                    href="{{ route('dashboard') }}">

                                    <i class="bi bi-speedometer2 me-2"></i>
                                    Dashboard

                                </a>

                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>

                                <form
                                    method="POST"
                                    action="{{ route('logout') }}"
                                    onsubmit="return confirm('Are you sure you want to logout?')">

                                    @csrf

                                    <button
                                        type="submit"
                                        class="dropdown-item text-danger">

                                        <i class="bi bi-box-arrow-right me-2"></i>
                                        Logout

                                    </button>

                                </form>

                            </li>

                        </ul>

                    </li>

                </ul>

            </div>

        </div>

    </nav>


    <!-- =========================================
         MAIN CONTENT
    ========================================== -->

    <main class="main-content">

        @yield('content')

    </main>


    <!-- =========================================
         FOOTER
    ========================================== -->

    <footer class="main-footer">

        <div class="footer-content">

            <div>
                <span class="footer-brand">
                    Roman Electronic & Furnitures
                </span>
            </div>

            <div class="mt-1">
                <small>
                    © {{ date('Y') }}
                    রোমান ইলেকট্রনিক্স ও ফার্নিচার
                    — All Rights Reserved.
                </small>
            </div>

        </div>

    </footer>


    <!-- =========================================
         SCRIPTS
    ========================================== -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @livewireScripts

    @stack('scripts')

    @yield('scripts')

</body>

</html>