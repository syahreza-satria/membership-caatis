@extends('layouts.layout-main')

@section('content')
    <section class="history my-4">
        @if ($orders->isEmpty())
            <div class="alert alert-info text-center">
                <p class="mb-0">Anda belum memiliki riwayat pesanan.</p>
            </div>
        @else
            <div class="container">
                <div class="row row-cols-1 g-3">
                    @foreach ($orders as $order)
                        <div class="col">
                            <div class="card border-0 shadow">
                                <div class="card-header bg-white d-flex align-items-center text-start p-3">
                                    <span class="text-muted small ms-auto">{{ $order->created_at->format('d M Y') }}</span>
                                </div>
                                <div class="card-body p-3">
                                    @foreach ($order->orderDetails as $detail)
                                        <div class="d-flex mb-3">
                                            <img src="{{ isset($item['image']) && !empty($item['image']) ? 'https://pos.lakesidefnb.group/storage/' . $item['image'] : asset('img/CabangLakeside.png') }}"
                                                alt="{{ $detail->menu_name }}" width="48" height="48"
                                                class="rounded-circle me-3">

                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between">
                                                    <h6 class="fw-semibold mb-1">{{ $detail->menu_name }} ×
                                                        {{ $detail->quantity }}</h6>
                                                    <p class="text-muted mb-1">Rp
                                                        {{ number_format($detail->menu_price, 0, ',', '.') }}</p>
                                                </div>
                                                <p class="small text-muted mb-1">
                                                    <strong>Catatan:</strong> {{ $detail->note ?: '-' }}
                                                </p>
                                            </div>
                                        </div>
                                        @if (!$loop->last)
                                            <hr class="my-2">
                                        @endif
                                    @endforeach
                                </div>

                                <div class="card-footer bg-white p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold">Total</span>
                                        <span class="fw-semibold">Rp
                                            {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="text-end mt-1">
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success">+{{ intdiv($order->total_price, 10000) }}
                                            Poin</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
@endsection
