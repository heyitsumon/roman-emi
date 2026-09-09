<!doctype html>
<html lang="bn">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Roman Electronic & Furnitures @yield('title')</title>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</head>

<body>

@include('components.app-loader')

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid">

       
        <!-- Logo -->
        <a class="navbar-brand fw-bold text-primary" href="{{ route('dashboard') }}">
            Roman Emi
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNavbar" aria-controls="mainNavbar"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                <li class="nav-item d-flex align-items-center me-2">
                    <form method="POST" action="{{ route('locale.switch') }}">
                        @csrf
                        <select name="locale" class="form-select form-select-sm" onchange="this.form.submit()" aria-label="{{ __('ui.language') }}">
                            <option value="en" @selected(app()->getLocale() === 'en')>{{ __('ui.english') }}</option>
                            <option value="bn" @selected(app()->getLocale() === 'bn')>{{ __('ui.bangla') }}</option>
                        </select>
                    </form>
                </li>

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-semibold text-primary' : '' }}">
                        Dashboard
                    </a>
                </li>

                @can('customer-list')
                <li class="nav-item">
                    <a href="{{ route('customers.index') }}"
                       class="nav-link {{ request()->routeIs('customers.*') ? 'active fw-semibold text-primary' : '' }}">
                        Customers
                    </a>
                </li>
                @endcan

                @can('location-list')
                <li class="nav-item">
                    <a href="{{ route('locations.index') }}"
                       class="nav-link {{ request()->routeIs('locations.*') ? 'active fw-semibold text-primary' : '' }}">
                        Locations
                    </a>
                </li>
                @endcan

                @can('product-list')
                <li class="nav-item">
                    <a href="{{ route('products.index') }}"
                       class="nav-link {{ request()->routeIs('products.*') ? 'active fw-semibold text-primary' : '' }}">
                        Products
                    </a>
                </li>
                @endcan

                @can('product-model-list')
                <li class="nav-item">
                    <a href="{{ route('products.model') }}"
                       class="nav-link {{ request()->routeIs('products.model') ? 'active fw-semibold text-primary' : '' }}">
                        Product Models
                    </a>
                </li>
                @endcan

                @can('user-list')
                <li class="nav-item">
                    <a href="{{ route('users.index') }}"
                       class="nav-link {{ request()->routeIs('users.*') ? 'active fw-semibold text-primary' : '' }}">
                        Users
                    </a>
                </li>
                @endcan

                @can('role-list')
                <li class="nav-item">
                    <a href="{{ route('roles.index') }}"
                       class="nav-link {{ request()->routeIs('roles.*') ? 'active fw-semibold text-primary' : '' }}">
                        User Roles
                    </a>
                </li>
                @endcan

                <li class="nav-item dropdown ms-lg-3">
            </ul>
        </div>
    </div>
</nav>



    <!-- Scrolling Notices -->
    <div class="scrolling-notices">
        <div class="container">
            @foreach ($notices as $notice)

                <h4>{{ $notice->name }}</h4>
            @endforeach
        </div>
    </div>

    <!-- Main Content -->
    <main class="container mt-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="text-center py-3 mt-5">
        <small>© {{ date('Y') }} রোমান ইলেকট্রনিক্স ও ফার্নিচার</small>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
    @stack('scripts')
    @yield('scripts')

</body>
</html>
