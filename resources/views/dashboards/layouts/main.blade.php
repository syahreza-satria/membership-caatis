<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard Membership</title>

    {{-- SweetAlert --}}
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.20/dist/sweetalert2.min.css" rel="stylesheet">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom styles for this template -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.min.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="/css/dashboard.css" rel="stylesheet" />

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap"
        rel="stylesheet">

    @yield('styles')
</head>

<body style="background-color: #F5F5F5; font-family: 'Nunito', sans-serif">
    <header class="navbar sticky-top flex-md-nowrap p-0 shadow" style="background-color: #BB4B4B">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 text-white text-center fw-bold" href="/dashboard"
            style="background-color: #A52A2A">Caatis
            Membership</a>
    </header>

    <div class="container-fluid">
        <div class="row">
            <div class="sidebar border border-right col-md-3 col-lg-2 p-0">
                <div class="offcanvas-md offcanvas-end bg-body-tertiary" tabindex="-1" id="sidebarMenu"
                    aria-labelledby="sidebarMenuLabel">
                    <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3 overflow-y-auto">
                        <ul class="nav flex-column mx-3">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2 text-secondary"
                                    style="font-size: 16px" href="{{ route('dashboard') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                        width="24px" fill="#333333">
                                        <path d="M160-120v-480l320-240 320 240v480H560v-280H400v280H160Z" />
                                    </svg> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2 text-secondary"
                                    style="font-size: 16px" href="{{ route('dashboard.rewards') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                        width="24px" fill="#333333">
                                        <path
                                            d="M299-120q-91 0-155-64T80-339q0-86 57.5-148.5T281-557L120-880h280l80 160 80-160h280L680-559q85 8 142.5 70.5T880-340q0 92-64 156t-156 64q-9 0-18.5-.5T623-123q55-36 86-93.5T740-340q0-109-75.5-184.5T480-600q-109 0-184.5 75.5T220-340q0 68 28 128t88 89q-9 2-18.5 2.5t-18.5.5Zm181-40q-75 0-127.5-52.5T300-340q0-75 52.5-127.5T480-520q75 0 127.5 52.5T660-340q0 75-52.5 127.5T480-160Zm-74-70 74-56 74 56-28-91 74-53h-91l-29-96-29 96h-91l74 53-28 91Z" />
                                    </svg> Rewards
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2 text-secondary"
                                    style="font-size: 16px" href="{{ route('dashboard.branches') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                        width="24px" fill="#333333">
                                        <path
                                            d="M160-720v-80h640v80H160Zm0 560v-240h-40v-80l40-200h640l40 200v80h-40v240h-80v-240H560v240H160Zm80-80h240v-160H240v160Z" />
                                    </svg> Cabang
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2 text-secondary"
                                    style="font-size: 16px" href="{{ route('dashboard.orders') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                        width="24px" fill="#333333">
                                        <path
                                            d="M320-280q17 0 28.5-11.5T360-320q0-17-11.5-28.5T320-360q-17 0-28.5 11.5T280-320q0 17 11.5 28.5T320-280Zm0-160q17 0 28.5-11.5T360-480q0-17-11.5-28.5T320-520q-17 0-28.5 11.5T280-480q0 17 11.5 28.5T320-440Zm0-160q17 0 28.5-11.5T360-640q0-17-11.5-28.5T320-680q-17 0-28.5 11.5T280-640q0 17 11.5 28.5T320-600Zm120 320h240v-80H440v80Zm0-160h240v-80H440v80Zm0-160h240v-80H440v80ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Z" />
                                    </svg> Pesanan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2 text-secondary"
                                    style="font-size: 16px" href="{{ route('dashboard.users') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                        width="24px" fill="#333333">
                                        <path
                                            d="M40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Zm720 0v-120q0-44-24.5-84.5T666-434q51 6 96 20.5t84 35.5q36 20 55 44.5t19 53.5v120H760ZM360-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Zm400-160q0 66-47 113t-113 47q-11 0-28-2.5t-28-5.5q27-32 41.5-71t14.5-81q0-42-14.5-81T544-792q14-5 28-6.5t28-1.5q66 0 113 47t47 113Z" />
                                    </svg> Pengguna
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center gap-2 text-secondary"
                                    style="font-size: 16px" href="{{ route('dashboard.verification-codes') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                        width="24px" fill="#333333">
                                        <path
                                            d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                                    </svg> Kode Verifikasi
                                </a>
                            </li>
                        </ul>

                        <hr class="my-3 mx-4" style="color: #757575" />

                        <ul class="nav flex-column mb-auto mx-3">
                            <li class="nav-item">
                                <form method="POST" action="/logout">
                                    @csrf
                                    <button type="submit"
                                        class="nav-link d-flex align-items-center gap-2 text-secondary"
                                        style="font-size: 16px">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px"
                                            viewBox="0 -960 960 960" width="24px" fill="#333333">
                                            <path
                                                d="M120-120v-80h80v-640h400v40h160v600h80v80H680v-600h-80v600H120Zm320-320q17 0 28.5-11.5T480-480q0-17-11.5-28.5T440-520q-17 0-28.5 11.5T400-480q0 17 11.5 28.5T440-440Z" />
                                        </svg> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                @yield('container')
            </main>
        </div>
    </div>

    @yield('scripts')

    {{-- Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.20/dist/sweetalert2.all.min.js"></script>

    <script src="/js/dashboard.js"></script>
</body>

</html>
