{{-- Navbar Start --}}
<nav class="navbar background-secondary">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="/img/logoCaatis.png" alt="Logo Caatis" height="32">
        </a>
        <div class="btn-group">
            <button type="button" class="btn background-secondary" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-list" style="font-size: 30px; color: #fff "></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <button class="dropdown-item" type="button">
                        <a href="/" class="text-decoration-none font-black">Beranda</a>
                    </button>
                </li>
                <li>
                    <button class="dropdown-item" type="button">
                        <a href="/order" class="text-decoration-none font-black">Pembelian</a>
                    </button>
                </li>
                <li>
                    <button class="dropdown-item" type="button">
                        <a href="/history" class="text-decoration-none font-black">Riwayat</a>
                    </button>
                </li>
                <li>
                    <button class="dropdown-item" type="button">
                        <a href="/about" class="text-decoration-none font-black">Tentang Kami</a>
                    </button>
                </li>
                <li>
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="dropdown-item" >
                            Keluar
                        </button>
                    </form>
                </li>
                </ul>
          </div>
    </div>
</nav>
{{-- Navbar End --}}

{{-- Hero Start --}}
<section class="background-gradient justify-content-center text-center align-items-center hero-main">
    <h1 class="fw-bold text-white font-32 ">{{ $banner }}</h1>
</section>
{{-- Hero End --}}