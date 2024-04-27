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
                <label for="nama-lengkap" class="form-label font-14 fw-bold  mb-1">Nama Lengkap</label>
                <input type="text" class="form-control " id="nama-lengkap" placeholder="Masukkan nama lengkap" name="name" value="{{ old('name') }}">

                @error('name')
                    <p class="text-danger font-14 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-2">
                <label for="email" class="form-label font-14 fw-bold  mb-1">E-mail</label>
                <input type="email" class="form-control " id="email" placeholder="Masukkan email" name="email" value="{{ old('name') }}">
                
                @error('email')
                    <p class="text-danger font-14 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-2">
                <label for="nomor-handphone" class="form-label font-14 fw-bold  mb-1">Nomor Handphone</label>
                <input type="tel" class="form-control " id="nomor-handphone" placeholder="Masukkan nomor handphone" name="phone_number" value="{{ old('name') }}">

                @error('phone_number')
                    <p class="text-danger font-14 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password" class="form-label font-14 fw-bold  mb-1">Password</label>
                <input type="password" class="form-control " id="password" placeholder="Masukkan password" name="password">

                @error('password')
                    <p class="text-danger font-14 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password" class="form-label font-14 fw-bold  mb-1">Konfirmasi Password</label>
                <input type="password" class="form-control " id="password" placeholder="Masukkan Ulang password" name="password_confirmation">

                @error('password_confirmation')
                    <p class="text-danger font-14 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="btn fw-bold w-100 font-14 button-login">Daftar</button>
            <h3 class="font-14 fw-bold mb-3 mt-3  text-center ">Sudah Punya Akun?</h3>
            <a href="/login" class="btn fw-bold w-100 font-14 button-register">Masuk</a>
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