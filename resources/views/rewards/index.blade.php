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
              <div class="card-items">
                <a href="/rewards/{{ $reward->id }}" class="text-decoration-none text-dark">
                  <img class="w-100 h-auto" src="/img/Minuman.png" alt="minuman" />
                  <div class="card-container">
                    <span class="color-primary font-10 fw-bold ">{{ $reward->tags }}</span>
                    <h3 class="fw-bold font-10">{{ $reward->product_points }} Poin</h3>
                    <h4 class="font-14 fw-normal"><b>{{ $reward->title }}</b></h4>
                  </div>
                </a>
              </div>
            @endforeach
              
          @else
            <h1 class=" text-center ">No Listing Found</h1>
          @endunless
        </div>
      </section>
    {{-- Cards End --}}

@endsection

{{-- Footer Start --}}
@section('footer')
    @include('partials.footer')
@endsection
{{-- Footer End --}}