@extends('layouts.layout-main')

@section('content')
    <div class="container text-center mt-5">
        <h1 class="font-20 fw-bold">Kode Verifikasi</h1>
        <p>Ini adalah kode verifikasi yang dihasilkan untuk hari ini:</p>
        <h2 class="font-24 fw-bold">{{ $code }}</h2>
    </div>
@endsection