@extends('layouts.layout-auth')

@section('main')
    <main class="mx-auto main-content h-100" style="max-width: 800px;">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-1">
            <h2 class="font-16 fw-bold text-uppercase font-primary">Resi Pembelian</h2>
        </div>

        <!-- Garis Pembatas -->
        <div class="mb-3" style="border-top: 2px dashed #ddd;"></div>

        <!-- Informasi Utama Pesanan -->
        <div class="card shadow p-3 mb-4">
            <h3 class="font-14 fw-bold mb-2 text-secondary">Informasi Pesanan</h3>
            <p class="mb-1 font-14"><strong>Order ID:</strong> {{ $order->id }}</p>
            <p class="mb-1 font-14"><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
            <p class="mb-1 font-14"><strong>Total Harga:</strong> <span class="text-success fw-bold">Rp
                    {{ number_format($order->total_price, 0, ',', '.') }}</span></p>
            <p class="mb-1 font-14"><strong>Poin Diperoleh:</strong> <span
                    class="badge bg-success px-2 py-1">+{{ $pointsEarned }}
                    poin</span></p>
        </div>

        <!-- Detail Pesanan -->
        <div class="card shadow p-3 mb-4">
            <h3 class="font-14 fw-bold mb-2 text-secondary">Detail Pesanan</h3>
            @if (count($orderDetails) > 0)
                @foreach ($orderDetails as $detail)
                    <div class="mb-2">
                        <p class="mb-1 font-14"><strong>Menu:</strong> {{ $detail['menu_name'] ?? 'N/A' }}</p>
                        <p class="mb-1 font-14"><strong>Jumlah:</strong> {{ $detail['quantity'] ?? 'N/A' }}</p>
                        <p class="mb-1 font-14"><strong>Harga:</strong> Rp
                            {{ number_format($detail['menu_price'], 0, ',', '.') }}
                        </p>
                        <p class="mb-1 text-muted font-14"><strong>Catatan:</strong> {{ $detail['note'] ?? '-' }}</p>
                    </div>
                    @if (!$loop->last)
                        <div class="mb-2" style="border-bottom: 1px dashed #e0e0e0;"></div>
                    @endif
                @endforeach
            @else
                <p class="text-muted">Tidak ada detail pesanan tersedia.</p>
            @endif
        </div>

        <!-- Tombol Kembali -->
        <div class="text-end mt-4">
            <a href="/" class="btn btn-outline-success px-4 py-2 rounded-pill shadow-sm mb-3">Kembali ke Home</a>
        </div>
    </main>
@endsection
