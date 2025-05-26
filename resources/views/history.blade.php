@extends('layouts.layout-main')

@section('content')
    <section class="history my-4">
        <div class="container">
            <h2 class="h5 mb-4 fw-semibold">Silakan Memilih Riwayat</h2>

            <div class="d-grid gap-3">
                <a class="btn px-4 rounded-4 py-3 text-start fw-semibold" href="/history/orders"
                    style="border: 1px solid #2dd4bf;">
                    Riwayat Pemesanan
                </a>
                <a class="btn px-4 rounded-4 py-3 text-start fw-semibold" href="/history/rewards"
                    style="border: 1px solid #2dd4bf;">
                    Riwayat Penukaran Poin
                </a>
            </div>
        </div>
    </section>
@endsection
