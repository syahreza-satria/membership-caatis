@extends('layouts.layout-auth')

@section('main')
    <div class="text-center justify-content-center align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor"
            class="bi bi-patch-check-fill mb-2 " style="color: #72BA25" viewBox="0 0 16 16">
            <path
                d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01zm.287 5.984-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7 8.793l2.646-2.647a.5.5 0 0 1 .708.708" />
        </svg>
        <h1 class="font-32 fw-bold mb-0">Pesanan berhasil</h1>
        <p class="font-10 text-secondary mb-3 ">{{ now()->format('d M Y - H:i:s T') }}</p>
        <div class="w-100 p-3 rounded-3 shadow mb-3">
            <div class="text-center mb-3 ">
                <p class="text-secondary mb-0 font-10">Atas Nama</p>
                <h2 class="fw-bold font-14">{{ auth()->user()->name }}</h2>
                <p class="text-secondary mb-0 font-10">Poin Anda yang Bertambah: {{ $pointsToAdd }}</p>
                <p class="text-secondary mb-0 font-10">Total Point Anda: {{ auth()->user()->user_points }}</p>
            </div>
            <div class="text-start ">
                <h3 class="font-16 fw-semibold">Total Pesanan</h3>
                <ul class="list-unstyled mb-2">
                    @foreach ($orders as $order)
                        <li class="font-14 mb-1">{{ $order['menu_name'] }} ({{ $order['quantity'] }})</li>
                    @endforeach
                </ul>
                <hr>
            </div>
            <div class="d-flex justify-content-between align align-items-center ">
                <h3 class="font-12 fw-semibold ">Total</h3>
                <h3 class="font-14 fw-bold ">Rp.{{ number_format($totalPrice, 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="w-100 py-2 rounded-3" style="background: linear-gradient(to bottom, #2DD4BF, #14B8A6)">
            <a href="{{ route('reward') }}" class="text-decoration-none text-white fw-semibold ">Kembali Ke Halaman
                Utama</a>
        </div>
    </div>
@endsection
