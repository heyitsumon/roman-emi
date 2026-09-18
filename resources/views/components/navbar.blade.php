<nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm sticky-top">
    <div class="container-xl">

        {{-- Brand --}}
        <a class="navbar-brand fw-bold text-primary"
           href="{{ route('dashboard') }}">
            Emi-System
        </a>

        {{-- Mobile Menu Button --}}
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
            aria-controls="mainNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>

        </button>

        {{-- Navbar Links --}}
        <div class="collapse navbar-collapse" id="mainNavbar">

            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">

                {{-- Dashboard --}}
                <li class="nav-item">
                    <a wire:navigate
                       href="{{ route('dashboard') }}"
                       class="nav-link px-3">
                        Dashboard
                    </a>
                </li>

                {{-- Customers --}}
                <li class="nav-item">
                    <a wire:navigate
                       href="{{ route('customers.index') }}"
                       class="nav-link px-3">
                        Customers
                    </a>
                </li>

                {{-- Locations --}}
                <li class="nav-item">
                    <a wire:navigate
                       href="{{ route('locations.index') }}"
                       class="nav-link px-3">
                        Locations
                    </a>
                </li>

                {{-- Purchases --}}
                <li class="nav-item">
                    <a href="{{ route('purchases.index') }}"
                       class="nav-link px-3">
                        Purchases
                    </a>
                </li>

                {{-- Products --}}
                <li class="nav-item">
                    <a wire:navigate
                       href="{{ route('products.index') }}"
                       class="nav-link px-3">
                        Products
                    </a>
                </li>

                {{-- Products Model --}}
                <li class="nav-item">
                    <a wire:navigate
                       href="{{ route('products.model') }}"
                       class="nav-link px-3">
                        Products Model
                    </a>
                </li>

                {{-- Users --}}
                <li class="nav-item">
                    <a wire:navigate
                       href="{{ route('users.index') }}"
                       class="nav-link px-3">
                        Users
                    </a>
                </li>

                {{-- Roles --}}
                <li class="nav-item">
                    <a wire:navigate
                       href="{{ route('roles.index') }}"
                       class="nav-link px-3">
                        Roles
                    </a>
                </li>

            </ul>

        </div>
    </div>
</nav>