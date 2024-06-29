@extends('layouts.layout-main')

@section('content')
    <section class="history">
        <p class="font-20 fw-semibold">Riwayat Penukaran Poin</p>

        <ul class="w-100 list-unstyled">
            @foreach ($history as $item)
                <li class="w-100 list-unstyled" style="height: 50px; margin-bottom: 35px">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" width="50"
                                height="50" style="border-radius: 10px">
                            <div class="ms-3 ">
                                <h3 class="font-14 fw-bold mb-0 ">{{ $item->title }}</h3>
                                <p class="font-10 fw-semibold text-secondary mb-0 ">
                                    {{ \Carbon\Carbon::parse($item->redeemed_at)->format('Y M d') }},
                                    {{ \Carbon\Carbon::parse($item->redeemed_at)->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <p class="font-10 fw-bold my-auto ">
                            @if ($item->annotation == 'Tambah')
                                +{{ $item->product_points }} Poin
                            @else
                                -{{ $item->product_points }} Poin
                            @endif
                        </p>
                    </div>
                    <hr>
                </li>
            @endforeach
        </ul>
    </section>
@endsection
