@extends('layouts.layout-auth')

@section('main')
    <main class="mx-auto justify-content-center main-content main h-100 d-flex flex-column align-items-center"
        style="max-width: 400px; padding: 30px; background-color: #f9fafb; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
        <h2 class="font-20 fw-bold mb-4 text-uppercase text-center" style="color: #14B8A6;">Verifikasi Kode</h2>
        <div class="mb-4" style="border-top: 1px dashed #d5d5d5; width: 100%;"></div>

        @if (session('error'))
            <div class="alert alert-danger text-center" style="font-size: 1rem;">
                {{ session('error') }}
            </div>
        @endif

        {{-- Catatan untuk meminta kode verifikasi kepada barista --}}
        <div class="alert alert-info text-center mb-4"
            style="font-size: 1rem; color: #0d6efd; background-color: #eaf4ff; border-color: #bee5eb;">
            <i class="bi bi-info-circle-fill"></i> Silakan meminta kode verifikasi dari barista untuk melanjutkan proses
            pemesanan.
        </div>

        <form method="POST" action="{{ route('confirmOrder') }}" style="width: 100%;">
            @csrf
            <div class="mb-4">
                <label for="verification_code" class="form-label" style="font-size: 1rem; color: #6b7280;">Masukkan Kode
                    Verifikasi</label>
                <input type="text" name="verification_code" id="verification_code" class="form-control text-center"
                    placeholder="Contoh: 123456" required
                    style="font-size: 1.3rem; padding: 12px; border-radius: 8px; border: 1px solid #e5e7eb; box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1); transition: all 0.3s;">
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success w-100 fs-bold"
                    style="padding: 12px 0; font-size: 1rem; border-radius: 8px; background-color: #14B8A6; border: none; transition: background-color 0.3s;">
                    Konfirmasi Pesanan
                </button>
            </div>
        </form>
    </main>
@endsection
