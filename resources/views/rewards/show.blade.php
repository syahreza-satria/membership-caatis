@extends('layouts.layout-main')

@section('content')
    <section class="reward-detail">
        <img src="/img/minuman.png" alt="" width="350px" class="rounded-4 ">
        <h2 class="fw-bold font-20 pb-2 pt-3 ">{{ $reward->title }}</h2>
        <div class="poin-detail text-center rounded-5 mb-4">
            <p class="fw-semibold font-14 my-2 mx-auto">{{ $reward->product_points }} Poin</p>
        </div>
        <p class="font-14 w-100 ">{{ $reward->description }}</p>

        <form action="/rewards/{reward}/redeem" method="POST">
            @csrf
            <button type="submit" class="w-100 button-tukar mt-5 mb-3 border-0 text-light fw-bold ">Tukarkan Poin</button>
        </form>
    </section>
@endsection