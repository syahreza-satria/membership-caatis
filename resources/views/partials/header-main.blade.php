{{-- Navbar Start --}}
<nav class="navbar background-secondary sticky-top" style="z-index: 9;">
    <div class="container">
        <a class="navbar-brand" href="{{ Auth::check() ? '/reward' : '/' }}">
            <img src="/img/lakesidefnb-white.png" alt="lakesidefnb" height="20">
        </a>
        <div class="btn-group">
            <button type="button" class="btn background-secondary" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-list" style="font-size: 30px; color: #fff "></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a href="/reward" class="dropdown-item text-decoration-none font-black">Beranda</a>
                </li>
                <li>
                    <a href="/branch" class="dropdown-item text-decoration-none font-black">Pembelian</a>
                </li>
                <li>
                    <a href="/history" class="dropdown-item text-decoration-none font-black">Riwayat</a>
                </li>
                {{-- <li>
                    <a href="/about" class="dropdown-item text-decoration-none font-black">Tentang Kami</a>
                </li> --}}
                <li>
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="dropdown-item">Keluar</button>
                    </form>
                </li>
            </ul>

        </div>
    </div>
</nav>
{{-- Navbar End --}}

@if(!Request::is('branch') && !Request::is('order/menu/*'))
    {{-- Hero Start --}}
    <section class="background-gradient justify-content-center text-center align-items-center hero-main">
        <h1 class="fw-bold text-white font-32 ">{{ $banner }}</h1>
    </section>
    {{-- Hero End --}}
@endif