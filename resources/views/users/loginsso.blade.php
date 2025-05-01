@extends('layouts/layout-auth-sso')

@section('authentication')
    {{-- Headline Start --}}
    <section class=" mb-2 ">
        <h1 class=" mb-2 fw-bold font-20">Halo! Selamat Datang.</h1>
        <p class=" fw-normal font-14 lh-sm">Silahkan memasukkan Username dan Password yang telah berkaitan dengan
            akun SSO kampus!</p>
    </section>
    {{-- Headline End --}}

    {{-- Form Start --}}
    <section class="mb-5">
        <form action="/users/sso" method="POST">
            @csrf
            <div class="mb-2">
                <label for="username" class=" form-label font-14 fw-bold mb-1">Username SSO</label>
                <input type="text" name="username" id="username" class="form-control"
                    value="{{ old('username') }}">

                @error('username')
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
            <button type="submit" class="btn w-100 button-login-sso background-sso fw-semibold mt-2"><img
                    src="{{ asset('/img/ic_telkomUniversity.png') }}" alt="telkom university" width="20" class="me-1">
                Masuk Dengan SSO</button>
            <p class="fw-bold font-14 text-center my-2">ATAU</p>
            <a href="/login" class="w-100 btn button-register fw-semibold mb-2">Masuk dengan nomor</a>
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
