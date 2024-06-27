@extends('layouts.layout-auth')

@section('main')
    <main class="mx-auto justify-content-center main-content main h-100">
        <h2 class="font-20 fw-bold mb-2 text-uppercase">Verifikasi Kode</h2>
        <div class="mb-3" style="border-top: 1px dashed #d5d5d5"></div>

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('confirmOrder') }}">
            @csrf
            <div class="mb-3">
                <label for="verification_code" class="form-label">Masukkan Kode Verifikasi</label>
                <input type="text" name="verification_code" id="verification_code" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Konfirmasi Pesanan</button>
        </form>
    </main>
@endsection
