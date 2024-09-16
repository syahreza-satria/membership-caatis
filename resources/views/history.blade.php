@extends('layouts.layout-main')

@section('content')
    <section class="history my-5">
        <h2 class="font-20 mb-4 text-center fw-semibold">Silahkan Memilih Riwayat</h2>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <a class="text-decoration-none fw-semibold text-dark py-3 px-4 d-block mb-3 rounded-3 shadow-sm hover-shadow"
                        style="border: 1px solid #007bff; transition: all 0.3s ease; background-color: #f8f9fa;"
                        href="/history/orders">
                        <p class="m-0 text-center">Riwayat Pemesanan</p>
                    </a>
                    <a class="text-decoration-none fw-semibold text-dark py-3 px-4 d-block mb-3 rounded-3 shadow-sm hover-shadow"
                        style="border: 1px solid #28a745; transition: all 0.3s ease; background-color: #f8f9fa;"
                        href="/history/rewards">
                        <p class="m-0 text-center">Riwayat Penukaran Poin</p>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
