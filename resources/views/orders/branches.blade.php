@extends('layouts.layout-main')

@section('content')
    <main class="mx-auto justify-content-center main-content main">
        <h2 class="font-20 fw-bold mb-3">Silahkan Pilih Cabang</h2>
        @foreach ($branches as $branch)
            <a class="w-100 mb-2 shadow-sm d-flex rounded-3 text-decoration-none text-dark"
                href="{{ route('order.menu', $branch->id) }}">
                <img src="{{ asset($branch->logo) }}" class="rounded-3" style="border: 1px solid #14B8A6"
                    alt="{{ $branch->name }}" width="70">
                <h3 class="font-16 fw-bold my-auto ms-3">{{ $branch->name }}</h3>
            </a>
        @endforeach
    </main>
@endsection
