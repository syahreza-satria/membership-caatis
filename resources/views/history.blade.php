@extends('layouts.layout-main')

@section('content')
    <section class="history">
        <h2 class="font-20 mb-3">Silahkan memilih riwayat apa yang ingin anda lihat</h2>
        <a class="text-decoration-none fw-semibold text-dark py-2 px-3 d-block mb-2 rounded-3 shadow-sm"
            style="border: 1px solid #000" href="/history/orders">
            <p class="m-0">Riwayat Pemesanan</p>
        </a>
        <a class="text-decoration-none fw-semibold text-dark py-2 px-3 d-block mb-2 rounded-3 shadow-sm"
            style="border: 1px solid #000" href="/history/rewards">
            <p class="m-0">Riwayat Penukaran Poin</p>
        </a>
    </section>
@endsection
