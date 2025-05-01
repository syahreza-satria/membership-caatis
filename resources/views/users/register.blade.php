@extends('layouts/layout-auth')

@section('authentication')
    {{-- Headline Start --}}
    <section class="mb-2">
        <h1 class="mb-2 fw-bold font-20">Registasi Akun</h1>
        <p class="fw-normal font-14 lh-sm ">Silahkan melengkapi data dibawah untuk membuat akun</p>
    </section>
    {{-- Headline End --}}

    {{-- Form Start --}}
    <section class=" mb-5 ">
        <form action="/users" method="POST">
            @csrf
            <div class="mb-2">
                <label for="fullname" class="form-label font-14 fw-bold  mb-1">Nama Lengkap</label>
                <input type="text" class="form-control " id="fullname" name="fullname" value="{{ old('fullname') }}">

                @error('fullname')
                    <p class="text-danger font-14 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-2">
                <label for="email" class="form-label font-14 fw-bold  mb-1">E-mail</label>
                <input type="email" class="form-control " id="email" name="email" value="{{ old('email') }}">

                @error('email')
                    <p class="text-danger font-14 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-2">
                <label for="phone" class="form-label font-14 fw-bold  mb-1">Nomor Handphone</label>
                <input type="tel" class="form-control " id="phone" name="phone" value="{{ old('phone') }}">

                @error('phone')
                    <p class="text-danger font-14 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-2">
                <label for="password" class="form-label font-14 fw-bold  mb-1">Password</label>
                <div class="input-group mb-2">
                    <input type="password" class="form-control " id="password" name="password">
                    <span class="input-group-text" onclick="togglePasswordVisibility('password')">
                        <i id="password-icon" class="fa-solid fa-eye"></i>
                    </span>
                </div>               
                
                @error('password')
                    <p class="text-danger font-14 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password" class="form-label font-14 fw-bold  mb-1">Konfirmasi Password</label>
                <div class="input-group mb-2">
                    <input type="password" class="form-control " id="password_confirm" name="password_confirmation">
                    <span class="input-group-text" onclick="togglePasswordVisibility('password_confirm')">
                        <i id="password_confirm-icon" class="fa-solid fa-eye"></i>
                    </span>
                </div>          
                
                @error('password_confirmation')
                    <p class="text-danger font-14 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="btn fw-bold w-100 font-14 button-login">Daftar Akun</button>
            <h3 class="font-14 fw-bold mb-3 mt-3  text-center ">SUDAH MEMILIKI AKUN?</h3>
            <a href="/login" class="btn fw-bold w-100 font-14 button-register mb-2">Masuk dengan nomor</a>
            <a href="/loginsso" class="w-100 btn button-sso fw-bold mb-2"><img src="/img/logoTelkom.png" alt="logo telkom"
                    height="16"> Masuk Dengan SSO</a>
        </form>
    </section>
    {{-- Headline End --}}

    {{-- Collaboration Start --}}
    <section class="pb-5">
        <div class="justify-content-center text-center ">
            <h1 class="font-20 fw-bold ">Collaboration Of:</h1>
            <img src="/img/Collaboration.png" alt="Collaboration" height="120">
        </div>
    </section>
    {{-- Collaboration End --}}
@endsection
