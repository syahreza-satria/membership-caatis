@extends('layouts.layout-auth')

@section('main')
    <main class="mx-auto main-content h-100" style="max-width: 800px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="font-20 fw-bold text-uppercase">Resi Pembelian</h2>
            <a href="/" class="btn btn-outline-secondary btn-sm">Kembali ke home</a>
        </div>

        <!-- Garis Pembatas -->
        <div class="mb-4" style="border-top: 1px dashed #d5d5d5;"></div>

        <!-- Informasi Utama Pesanan -->
        <div class="card p-3 mb-4">
            <h3 class="font-16 fw-bold mb-3">Informasi Pesanan</h3>
            <p class="mb-1"><strong>Order ID:</strong> {{ $order->id }}</p>
            <p class="mb-1"><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
            <p class="mb-1"><strong>Total Harga:</strong> <span class="text-success fw-semibold">Rp
                    {{ number_format($order->total_price, 0, ',', '.') }}</span></p>
            <p class="mb-1"><strong>Poin Diperoleh:</strong> <span class="badge bg-success">+{{ $pointsEarned }}
                    poin</span>
            </p>
        </div>

        <!-- Detail Pesanan -->
        <div class="card p-3 mb-4">
            <h3 class="font-16 fw-bold mb-3">Detail Pesanan</h3>
            @if (count($orderDetails) > 0)
                @foreach ($orderDetails as $detail)
                    @if ($detail)
                        <div class="mb-3">
                            <p class="mb-0"><strong>Menu:</strong> {{ $detail['menu_name'] ?? 'N/A' }}</p>
                            <p class="mb-0"><strong>Jumlah:</strong> {{ $detail['quantity'] ?? 'N/A' }}</p>
                            <p class="mb-0"><strong>Harga:</strong> Rp
                                {{ number_format($detail['menu_price'], 0, ',', '.') }}</p>
                            <p class="mb-0 text-muted"><strong>Catatan:</strong> {{ $detail['note'] ?? '-' }}</p>
                        </div>
                        @if (!$loop->last)
                            <div class="mb-2" style="border-bottom: 1px dashed #d5d5d5;"></div>
                        @endif
                    @else
                        <p class="text-danger">Data pesanan tidak valid.</p>
                    @endif
                @endforeach
            @else
                <p class="text-muted">Tidak ada detail pesanan tersedia.</p>
            @endif
        </div>

        <!-- Tombol Kembali -->
        <div class="text-end mt-4">
            <a href="/" class="btn btn-secondary">Kembali ke home</a>
        </div>
    </main>
@endsection
