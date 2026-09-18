<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>{{ $title ?? 'Page Title' }}</title>


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


    {{-- Select2 --}}
    <link
        href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
        rel="stylesheet"
    >

    <script
        src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js">
    </script>

    <script
        src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js">
    </script>


    {{-- Bangla Font --}}
    <link
        href="https://fonts.maateen.me/solaiman-lipi/font.css"
        rel="stylesheet"
    >


    <style>

        body {
            font-family: 'SolaimanLipi', sans-serif;
            background-color: #f8f9fa;
            color: #212529;
        }

        main {
            min-height: 100vh;
        }

        .select2-container {
            width: 100% !important;
        }

    </style>


    @fluxAppearance

</head>


<body>


    <main>

        {{-- Navbar --}}
        @include('components.navbar')


        {{-- Page Content --}}
        <div class="container-fluid">

            {{ $slot }}

        </div>

    </main>


    @fluxScripts


    {{-- Bootstrap JS --}}
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>


    {{-- Select2 Initialization --}}
    <script>

        document.addEventListener('DOMContentLoaded', function () {

            if (typeof $ !== 'undefined' && $.fn.select2) {

                $('.select2').select2({
                    width: '100%'
                });

            }

        });

    </script>


</body>

</html>
