@extends('layouts/layout-auth')

@section('authentication')
    {{-- Headline Start --}}
    <section class=" mb-2 ">
        <h1 class=" mb-2 fw-bold font-20">Halo! Selamat Datang.</h1>
        <p class=" fw-normal font-14 lh-sm">Silahkan memasukkan Nomor Handphone dan Password kamu untuk masuk klaim voucher
            kamu</p>
    </section>
    {{-- Headline End --}}

    {{-- Form Start --}}
    <section class=" mb-5 ">
        <form action="/users/authenticate" method="POST">
            @csrf
            <div class="mb-2">
                <label for="phone" class=" form-label font-14 fw-bold mb-1">No Handphone</label>
                <input type="tel" name="phone" id="phone" class="form-control"
                    value="{{ old('phone') }}">

                @error('phone')
                    <p class="text-danger font-14 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-2">
                <label for="password" class=" form-label font-14 fw-bold mb-1">Password</label>
                <div class="input-group mb-2">
                    <input type="password" name="password" id="password" class="form-control"
                        name="password" value="{{ old('password') }}">
                        <span class="input-group-text" onclick="togglePasswordVisibility('password')">
                            <i id="password-icon" class="fa-solid fa-eye"></i>
                        </span>
                </div>
                
                @error('password')
                    <p class="text-danger font-14 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="btn w-100 button-login fw-bold my-2">Masuk</button>
            <a href="/register" class="w-100 btn button-register fw-bold">Registrasi</a>
            <p class="fw-bold font-14 text-center my-2">Atau</p>
            <a href="/loginsso" class="w-100 btn button-sso fw-bold mb-2"><img
                    src="{{ asset('/img/ic_telkomUniversity.png') }}" alt="logo telkom" height="16"> Masuk Dengan SSO</a>
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
