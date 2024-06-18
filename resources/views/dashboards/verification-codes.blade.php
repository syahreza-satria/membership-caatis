@extends('dashboards.layouts.main')

@section('container')
    <div class="container mt-3">
        <h2>Kode Verifikasi</h2>
        <hr>
        <div class="container text-center mt-5">
            <h1 class="font-20 fw-bold">Kode Verifikasi</h1>
            <p>Ini adalah kode verifikasi yang dihasilkan untuk hari ini:</p>
            @if ($code)
                <h2 class="font-24 fw-bold">{{ $code->code }}</h2>
                <p>Code ini untuk: {{ $code->date }}</p>
            @else
                <p>Tidak ada kode verifikasi untuk hari ini.</p>
            @endif
        </div>
    </div>
@endsection
