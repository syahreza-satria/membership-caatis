@extends('layouts/layout-main')

@section('pemesanan')
    <main class="mx-auto justify-content-center main-content main">
        <h2 class="font-20 fw-bold mb-3 ">Silahkan Pilih cabang yang anda inginkan</h2>
        <a class="w-100 mb-2 shadow-sm d-flex rounded-3 text-decoration-none text-dark" href="/order/menu">
            <img src="/img/CabangLakeside.png" class="rounded-3" style="border: 1px solid #14B8A6" alt="Lakeside" width="70">
            <h3 class="font-16 fw-bold my-auto ms-3">Lakeside</h3>
        </a>
        <a class="w-100 mb-2 shadow-sm d-flex rounded-3 text-decoration-none text-dark" href="/order/menu">
            <img src="/img/CabangFIT.png" class="rounded-3" style="border: 1px solid #14B8A6" alt="Lakeside" width="70">
            <h3 class="font-16 fw-bold my-auto ms-3">Lakeside FIT+</h3>
        </a>
        <a class="w-100 mb-2 shadow-sm d-flex rounded-3 text-decoration-none text-dark" href="/order/menu">
            <img src="/img/CabangLiterasiCafe.png" class="rounded-3" style="border: 1px solid #14B8A6" alt="Lakeside" width="70">
            <h3 class="font-16 fw-bold my-auto ms-3">Literasi Cafe</h3>
        </a>
    </main>
@endsection