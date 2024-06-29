@extends('layouts/layout-main')

@section('content')
    {{-- Member Card Start --}}
    <section class="w-100 mb-3 rounded-4 shadow-lg ">
        <div class="p-3">
            <div class="card-nama">
                <span class="font-10">Nama Member</span>
                <h2 class="fw-bold font-14">{{ auth()->user()->fullname }}</h2>
            </div>
            <div class="d-flex justify-content-between align-items-center ">
                <div>
                    <span class="fw-semibold font-10">Poin</span>
                    <h2 class="font-10">{{ auth()->user()->user_points }}</h2>
                </div>
                <img src="/img/Collaboration 2.png" alt="F&B Caatis" height="24">
            </div>
        </div>
    </section>
    {{-- Member Card End --}}

    {{-- Cards Start --}}
    <section>
        <div class="d-flex flex-wrap justify-content-between">
            @unless (count($rewards) == 0)
                @foreach ($rewards as $reward)
                    @if ($reward->is_active)
                        @php
                            $redeemed = auth()
                                ->user()
                                ->rewards->contains($reward->id);
                            $imagePath = $reward->image_path
                                ? asset('storage/' . $reward->image_path)
                                : asset($reward->branch->logo);
                        @endphp
                        <div class="card-items {{ $redeemed ? 'disabled' : '' }}">
                            @if (!$redeemed)
                                <a href="/rewards/{{ $reward->id }}" class="text-decoration-none text-dark">
                            @endif
                            <img class="w-100 h-auto {{ $redeemed ? 'grayscale' : '' }}" src="{{ $imagePath }}"
                                alt="{{ $reward->title }}" />
                            <div class="card-container">
                                <h4 class="mt-2 font-14 fw-normal mb-1"><b>{{ $reward->title }}</b></h4>
                                <p class="font-12 fw-semibold">{{ $reward->branch->name }}</p> {{-- Menampilkan nama cabang --}}
                                <h3 class="color-primary mt-1 fw-bold font-10">{{ $reward->product_points }} Poin</h3>
                            </div>
                            @if (!$redeemed)
                                </a>
                            @else
                                <div class="font-14 text-center text-muted fw-bold mt-auto mb-1">Telah Ditukar</div>
                            @endif
                        </div>
                    @endif
                @endforeach
            @else
                <div class="text-center mt-5">
                    <h1 class="font-20">Promo kosong</h1>
                    <p>Mohon tunggu untuk promo promo kami yang akan datang</p>
                </div>
            @endunless
        </div>
    </section>
    {{-- Cards End --}}

    @if (@session('success'))
        <script>
            Swal.fire({
                title: 'Sukses!',
                text: '{{ session('success') }}',
                icon: 'success',
                iconColor: '#73AA14',
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif

@endsection
