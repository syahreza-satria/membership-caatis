@extends('layouts.layout-main')

@section('content')
    <section class="history">
        <p class="font-20 fw-semibold">Riwayat Pemesanan</p>

        @if ($orders->isEmpty())
            <p>Anda belum memiliki riwayat pesanan.</p>
        @else
            <ul class="w-100 list-unstyled">
                @foreach ($orders as $order)
                    <h3 class="fw-bold font-14">OrderID: {{ $order->id }}</h3>
                    @foreach ($order->orderDetails as $detail)
                        <li class="w-100 list-unstyled" style="margin-bottom: 35px">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    @php
                                        $branchLogo =
                                            $branches->firstWhere('id', $order->branch_id)->logo ??
                                            'img/default-logo.png';
                                    @endphp
                                    <img src="{{ Storage::url($branchLogo) }}" alt="Branch Logo" width="50" height="50"
                                        style="border-radius: 10px">
                                    <div class="ms-3">
                                        <h3 class="font-14 fw-bold mb-0">{{ $detail->menu_name }} x{{ $detail->quantity }}
                                        </h3>
                                        <p class="font-12 fw-semibold text-secondary mb-0">
                                            {{ $order->created_at->format('Y M d') }},
                                            {{ $order->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                <p class="font-12 fw-bold my-auto text-end">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    <br>
                                    @php
                                        $pointsAdded = intdiv($order->total_price, 10000);
                                    @endphp
                                    +{{ $pointsAdded }} Poin
                                </p>
                            </div>
                            @if ($detail->note)
                                <p class="font-12 mt-1"><strong>Catatan:</strong> {{ $detail->note }}</p>
                            @else
                                <p class="font-12 mt-1"><strong>Catatan:</strong> -</p>
                            @endif
                            <hr>
                        </li>
                    @endforeach
                @endforeach
            </ul>
        @endif
    </section>
@endsection
