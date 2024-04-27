@extends('layouts/layout-auth')

@section('authentication')
    {{-- Headline Start --}}
    <section class=" mb-2 ">
        <h1 class=" mb-2 fw-bold font-20">Halo! Selamat Datang.</h1>
        <p class=" fw-normal font-14 lh-sm">Silahkan memasukkan Nomor Handphone dan Password kamu untuk masuk klaim voucher kamu</p>
    </section>
    {{-- Headline End --}}

    {{-- Form Start --}}
    <section class=" mb-5 ">
        <form action="/users/authenticate" method="POST">
            @csrf
            <div class="mb-2">
                <label for="nomor-handphone" class=" form-label font-14 fw-bold mb-1">No Handphone</label>
                <input type="tel" name="phone_number" id="phone_number" placeholder="Masukkan No Handphone" class="form-control" name="phone_number" value="{{ old('phone_number') }}">

                @error('phone_number')
                    <p class="text-danger font-14 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-2">
                <label for="password" class=" form-label font-14 fw-bold mb-1">Password</label>
                <input type="password" name="password" id="password" placeholder="Masukkan Password" class="form-control"  name="password" value="{{ old('password') }}">

                @error('password')
                    <p class="text-danger font-14 mt-1">{{ $message }}</p>
                @enderror

                <div class="text-end mt-2 ">
                    <a href="#" class="link-primary font-14 text-decoration-none ">Lupa Password</a>
                </div>
            </div>
            <button type="submit" class="btn w-100 button-login fw-bold mb-2">Masuk</button>
            <a href="/register" class="w-100 btn button-register fw-bold mb-2">Daftar Akun</a>
        </form>
    </section>
    {{-- Form End --}}

    {{-- Collaboration Start --}}
    <section>
        <div class="justify-content-center text-center">
            <h1 class="font-20 fw-bold ">Collaboration Of:</h1>
            <img src="/img/Collaboration.png" alt="Collaboration" height="120">
        </div>
    </section>
    {{-- Collaboration End --}}
@endsection