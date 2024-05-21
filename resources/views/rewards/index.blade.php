@extends('layouts/layout-main')

@section('content')
    {{-- Member Card Start --}}
    @include('partials.member-card')
    {{-- Member Card End --}}

    {{-- Cards Start --}}
    <section>
        <div class="d-flex flex-wrap justify-content-between">
          @unless (count($rewards) == 0)          
            @foreach ($rewards as $reward)
              @php
                  $redeemed = auth()->user()->rewards->contains($reward->id);
              @endphp
              <div class="card-items {{ $redeemed ? 'disabled' : '' }}">
                @if (!$redeemed)
                <a href="/rewards/{{ $reward->id }}" class="text-decoration-none text-dark">
                @endif
                  <img class="w-100 h-auto {{ $redeemed ? 'grayscale' : '' }}" src="/img/Minuman.png" alt="minuman" />
                  <div class="card-container">
                    {{-- <span class="color-primary font-10 fw-bold ">{{ $reward->tags }}</span> --}}
                    <h4 class="mt-2 font-14 fw-normal"><b>{{ $reward->title }}</b></h4>
                    <h3 class="color-primary mt-1 fw-bold font-10">{{ $reward->product_points }} Poin</h3>
                  </div>
                @if (!$redeemed)
                </a>
                @else
                <div class="font-14 text-center text-muted fw-bold mt-auto mb-1">Telah Ditukar</div>    
                @endif
              </div>
            @endforeach
          @else
            <h1 class=" text-center ">
              Promo Kamu Telah habis 😔
              <br>
              Silahkan tunggu promo kami selanjutnya
            </h1>
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

{{-- Footer Start --}}
@section('footer')
    @include('partials.footer')
@endsection
{{-- Footer End --}}