@extends('layouts.layout-auth')

@section('main')
    <main class="mx-auto justify-content-center main-content main h-100">
        <h2 class="font-20 fw-bold mb-2 text-uppercase">Resi Pembelian</h2>
        <div class="mb-3" style="border-top: 1px dashed #d5d5d5"></div>

        <div class="w-100 mb-3">
            <p>Order ID: {{ $order->id }}</p>
            <p>Tanggal: {{ $order->created_at }}</p>
            <p>Total Harga: Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
            <p>Poin Diperoleh: {{ $pointsEarned }}</p>
            <hr>
            <h3 class="font-16 mb-2">Detail Pesanan:</h3>
            @foreach ($orderDetails as $detail)
                <div class="mb-2">
                    <p class="mb-0"><strong>Menu:</strong> {{ $detail->menu_name }}</p>
                    <p class="mb-0"><strong>Jumlah:</strong> {{ $detail->quantity }}</p>
                    <p class="mb-0"><strong>Harga:</strong> Rp {{ number_format($detail->menu_price, 0, ',', '.') }}</p>
                    <p class="mb-0"><strong>Catatan:</strong> {{ $detail->note }}</p> <!-- Tampilkan catatan -->
                </div>
                <hr>
            @endforeach
        </div>
        <div class="text-end mt-3">
            <a href="/" type="button" class="btn btn-outline-secondary">Kembali ke home</a>
        </div>
    </main>
@endsection
