@extends('layouts.layout-main')

@section('content')
    <section class="history my-3">
        @if ($orders->isEmpty())
            <div class="alert alert-info text-center">
                <p class="font-16">Anda belum memiliki riwayat pesanan.</p>
            </div>
        @else
            <div class="container">
                <div class="row">
                    @foreach ($orders as $order)
                        <div class="col-md-6 mb-4">
                            <div class="card shadow">
                                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                                    <h5 class="fw-bold font-14">OrderID: {{ $order->id }}</h5>
                                    <span class="badge bg-primary">{{ $order->created_at->format('Y M d') }}</span>
                                </div>
                                <div class="card-body">
                                    @foreach ($order->orderDetails as $detail)
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="d-flex align-items-center">
                                                @php
                                                    $branch = $branches->firstWhere('outletId', $order->branch_id);
                                                    $branchLogo = $branch ? $branch->logo : 'img/default-logo.png';
                                                @endphp
                                                <img src="{{ isset($item['image']) && !empty($item['image']) ? 'https://pos.lakesidefnb.group/storage/' . $item['image'] : asset('img/CabangLakeside.png') }}"
                                                    alt="{{ $detail->menu_name }}" width="60" height="60"
                                                    class="rounded-circle shadow-sm">
                                                <div class="ms-3">
                                                    <h6 class="font-14 fw-bold mb-1">{{ $detail->menu_name }}
                                                        x{{ $detail->quantity }}</h6>
                                                    <p class="font-12 text-muted mb-0">Rp
                                                        {{ number_format($detail->menu_price, 0, ',', '.') }} / pcs
                                                    </p> <!-- Harga per menu -->
                                                    <p class="font-12 text-muted mb-0">
                                                        {{ $order->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($detail->note)
                                            <p class="font-12 text-secondary"><strong>Catatan:</strong> {{ $detail->note }}
                                            </p>
                                        @else
                                            <p class="font-12 text-secondary"><strong>Catatan:</strong> -</p>
                                        @endif
                                        <hr>
                                    @endforeach
                                </div>
                                <div class="card-footer bg-white border-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="font-14 fw-bold text-dark mb-0">Total Harga:</p>
                                        <p class="font-14 fw-bold text-dark mb-0">Rp
                                            {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                    </div>
                                    <p class="font-12 text-secondary text-end mt-1">
                                        <span class="badge bg-success">+{{ intdiv($order->total_price, 10000) }}
                                            Poin</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
@endsection
