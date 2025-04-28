@extends('layouts.layout-main')

@section('content')
    <section class="history">
        <p class="font-20 fw-semibold mb-4">Riwayat Penukaran Poin</p>

        @if ($history->isEmpty())
            <p class="text-center text-muted">Tidak ada riwayat penukaran poin.</p>
        @else
            <ul class="list-unstyled">
                @foreach ($history as $item)
                    @php
                        $branch = $branches->firstWhere('id', $item->branch_id);
                        $branchLogo = $branch->logo ?? 'img/default-logo.png';
                        $itemImage = $item->image_path ? asset('storage/' . $item->image_path) : asset($branchLogo);
                    @endphp
                    <li class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <img src="{{ $itemImage }}" alt="{{ $item->title }}" class="rounded-circle" width="40"
                                height="40">
                            <div class="ms-3">
                                <h5 class="font-14 fw-bold mb-0">{{ $item->title }}</h5>
                                <p class="font-10 text-muted mb-0">
                                    {{ \Carbon\Carbon::parse($item->redeemed_at)->format('Y M d') }},
                                    {{ \Carbon\Carbon::parse($item->redeemed_at)->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <p class="font-10 fw-bold my-0">
                            @if ($item->annotation == 'Tambah')
                                +{{ $item->product_points }} Poin
                            @else
                                -{{ $item->product_points }} Poin
                            @endif
                        </p>
                    </li>
                    <hr class="my-2">
                @endforeach
            </ul>
        @endif
    </section>
@endsection
