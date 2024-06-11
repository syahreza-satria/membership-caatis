@extends('layouts/layout-main')

@section('content')
    <section class="history">
        <p class="font-14">Poin secara otomatis akan ditambahkan setelah melakukan pembelian.</p>

        <ul class="w-100 list-unstyled">
            @foreach ($history as $item)
                <li class="w-100 list-unstyled" style="height: 50px; margin-bottom: 35px">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="/img/CabangLakeside.png" alt="image" width="50" height="50"
                                style="border-radius: 10px">
                            <div class="ms-3 ">
                                <h3 class="font-14 fw-bold mb-0 ">{{ $item->title }}</h3>
                                <p class="font-10 fw-semibold text-secondary mb-0 ">
                                    {{ \Carbon\Carbon::parse($item->redeemed_at)->format('Y M d') }},
                                    {{ \Carbon\Carbon::parse($item->redeemed_at)->diffForHumans() }}</p>
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

        {{-- <ul>
            <li>
                <h3 class="font-14 fw-semibold ">Rabu, 16 Desember 2023</h3>
                <div class="w-100 font-14 fw-normal shadow-sm rounded-4 mb-3 data-history">
                    <div class="p-3">
                        <p class="mb-1">Creampie <span>(1)</span></p>
                        <p class="mb-1">Blue Moon <span>(1)</span></p>
                    </div>
                </div>
            </li>
            <li>
                <h3 class="font-14 fw-semibold ">Senin, Desember 2023</h3>
                <div class="w-100 font-14 fw-normal shadow-sm rounded-4 mb-3 border-1 data-history">
                    <div class="p-3">
                        <p class="mb-1">Creampie <span>(1)</span></p>
                        <p class="mb-1">Blue Moon <span>(1)</span></p>
                    </div>
                </div>
            </li>
            <li>
                <h3 class="font-14 fw-semibold ">Kamis, 2 Desember 2023</h3>
                <div class="w-100 font-14 fw-normal shadow-sm rounded-4 mb-3 border-1 data-history">
                    <div class="p-3">
                        <p class="mb-1">Creampie <span>(1)</span></p>
                        <p class="mb-1">Blue Moon <span>(1)</span></p>
                    </div>
                </div>
            </li>
        </ul> --}}
    </section>
@endsection
