@extends('dashboards.layouts.main')

@section('container')
    <div class="container mt-3">
        <h2 class="fs-1 text-red fw-bold">Kode Verifikasi</h2>
        <hr>
        <div class="container text-center mt-5 bg-white p-4 mt-4 rounded shadow">
            <h1 class="fs-1 text-red" style="font-weight: 800">Kode Verifikasi</h1>
            <p class="fs-6 text-secondary">Ini adalah kode verifikasi yang dihasilkan untuk hari ini:</p>
            @if ($code)
                <h2 class="fs-1" style="font-weight: 800">{{ $code->code }}</h2>
                <p class="fs-6 text-secondary mb-0">Kode verifikasi ini hanya berlaku pada tanggal: {{ $code->date }}
                </p>
                <p class="fs-6 text-secondary">kode verifikasi akan berubah tiap hari
                </p>
            @else
                <p class="text-secondary">Tidak ada kode verifikasi untuk hari ini.</p>
            @endif
        </div>
    </div>
@endsection
