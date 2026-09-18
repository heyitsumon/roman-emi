
<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Roman Electronic & Furnitures @yield('title')</title>

    {{-- Bootstrap 5 --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- Bootstrap Icons --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    {{-- Font Awesome --}}
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        rel="stylesheet"
    >

    {{-- Bangla Font --}}
    <link
        href="https://fonts.maateen.me/solaiman-lipi/font.css"
        rel="stylesheet"
    >

    {{-- Select2 --}}
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
        rel="stylesheet"
    >

    {{-- jQuery --}}
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js">
    </script>

    {{-- Select2 JS --}}
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js">
    </script>

    <style>
        body {
            font-family: 'SolaimanLipi', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            min-height: 68px;
        }

        .navbar-brand {
            font-size: 1.35rem;
            letter-spacing: .2px;
        }

        .navbar-nav .nav-link {
            padding: .65rem .75rem;
            font-weight: 500;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: var(--bs-primary) !important;
        }

        .language-select {
            min-width: 105px;
        }

        .notice-bar {
            background: var(--bs-primary);
            color: #fff;
        }

        .notice-wrapper {
            overflow: hidden;
            white-space: nowrap;
        }

        .notice-content {
            display: inline-flex;
            gap: 3rem;
            animation: noticeScroll 25s linear infinite;
        }

        .notice-content:hover {
            animation-play-state: paused;
        }

        @keyframes noticeScroll {
            from {
                transform: translateX(100%);
            }

            to {
                transform: translateX(-100%);
            }
        }

        main {
            min-height: calc(100vh - 180px);
        }

        footer {
            background: #fff;
            border-top: 1px solid #dee2e6;
        }

        .dropdown-menu {
            border: 0;
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .12);
        }

        .dropdown-item {
            padding: .6rem 1rem;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: var(--bs-primary);
        }

        @media (max-width: 991.98px) {

            .navbar-nav {
                padding-top: .75rem;
            }

            .navbar-nav .nav-link {
                padding: .75rem .5rem;
                border-bottom: 1px solid #f1f1f1;
            }

            .language-wrapper {
                padding: .75rem 0;
            }

            .language-select {
                width: 100%;
            }

            .navbar-collapse {
                padding-bottom: .75rem;
            }

            .notice-content {
                animation-duration: 18s;
            }
        }
    </style>
</head>


<body>

{{-- =========================================================
     NAVBAR
========================================================= --}}

<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">

    <div class="container-xl">

        {{-- Logo --}}
        <a
            class="navbar-brand fw-bold text-primary"
            href="{{ route('dashboard') }}"
        >
            Roman Emi
        </a>


        {{-- Mobile Button --}}
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
            aria-controls="mainNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>


        {{-- Navbar Menu --}}
        <div
            class="collapse navbar-collapse"
            id="mainNavbar"
        >

            <ul class="navbar-nav ms-auto align-items-lg-center">


                {{-- Language --}}
                <li class="nav-item language-wrapper me-lg-2">

                    <form
                        method="POST"
                        action="{{ route('locale.switch') }}"
                    >
                        @csrf

                        <select
                            name="locale"
                            class="form-select form-select-sm language-select"
                            onchange="this.form.submit()"
                            aria-label="{{ __('ui.language') }}"
                        >

                            <option
                                value="en"
                                @selected(app()->getLocale() === 'en')
                            >
                                {{ __('ui.english') }}
                            </option>

                            <option
                                value="bn"
                                @selected(app()->getLocale() === 'bn')
                            >
                                {{ __('ui.bangla') }}
                            </option>

                        </select>
                    </form>

                </li>


                {{-- Dashboard --}}
                <li class="nav-item">

                    <a
                        href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    >
                        Dashboard
                    </a>

                </li>


                {{-- Customers --}}
                @can('customer-list')

                    <li class="nav-item">

                        <a
                            href="{{ route('customers.index') }}"
                            class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}"
                        >
                            Customers
                        </a>

                    </li>

                @endcan


                {{-- Locations --}}
                @can('location-list')

                    <li class="nav-item">

                        <a
                            href="{{ route('locations.index') }}"
                            class="nav-link {{ request()->routeIs('locations.*') ? 'active' : '' }}"
                        >
                            Locations
                        </a>

                    </li>

                @endcan


                {{-- Products --}}
                @can('product-list')

                    <li class="nav-item">

                        <a
                            href="{{ route('products.index') }}"
                            class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                        >
                            Products
                        </a>

                    </li>

                @endcan


                {{-- Product Models --}}
                @can('product-model-list')

                    <li class="nav-item">

                        <a
                            href="{{ route('products.model') }}"
                            class="nav-link {{ request()->routeIs('products.model') ? 'active' : '' }}"
                        >
                            Product Models
                        </a>

                    </li>

                @endcan


                {{-- Users --}}
                @can('user-list')

                    <li class="nav-item">

                        <a
                            href="{{ route('users.index') }}"
                            class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                        >
                            Users
                        </a>

                    </li>

                @endcan


                {{-- Roles --}}
                @can('role-list')

                    <li class="nav-item">

                        <a
                            href="{{ route('roles.index') }}"
                            class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}"
                        >
                            User Roles
                        </a>

                    </li>

                @endcan

            </ul>

        </div>

    </div>

</nav>



{{-- =========================================================
     NOTICE BAR
========================================================= --}}

<div class="notice-bar py-2">

    <div class="container-fluid">

        <div class="notice-wrapper">

            <div class="notice-content">

                @foreach ($notices as $notice)

                    <span class="fw-semibold">
                        <i class="bi bi-megaphone-fill me-2"></i>
                        {{ $notice->name }}
                    </span>

                @endforeach

            </div>

        </div>

    </div>

</div>



{{-- =========================================================
     MAIN CONTENT
========================================================= --}}

<main class="container-xl py-4">

    {{-- Flash Message --}}
    @if(session('success'))

        <div
            class="alert alert-success alert-dismissible fade show shadow-sm"
            role="alert"
        >
            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>

        </div>

    @endif


    {{-- Error Message --}}
    @if(session('error'))

        <div
            class="alert alert-danger alert-dismissible fade show shadow-sm"
            role="alert"
        >
            <i class="bi bi-exclamation-triangle me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>

        </div>

    @endif


    @yield('content')

</main>



{{-- =========================================================
     FOOTER
========================================================= --}}

<footer class="py-4 mt-4">

    <div class="container-xl">

        <div class="row align-items-center gy-2">

            <div class="col-md-6 text-center text-md-start">

                <span class="text-muted small">

                    <i class="bi bi-c-circle me-1"></i>

                    {{ date('Y') }}
                    রোমান ইলেকট্রনিক্স ও ফার্নিচার

                </span>

            </div>


            <div class="col-md-6 text-center text-md-end">

                <span class="text-muted small">

                    Powered by
                    <strong class="text-primary">
                        Roman Emi
                    </strong>

                </span>

            </div>

        </div>

    </div>

</footer>



{{-- =========================================================
     JAVASCRIPT
========================================================= --}}

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

<script
    src="https://cdn.jsdelivr.net/npm/sweetalert2@11">
</script>


@stack('scripts')

@yield('scripts')

</body>
</html>

