@extends('layouts.layout-auth')

@section('main')
    <div>
        <h1 class="font-20 fw-bold  text-center mb-3">Mohon Tunjukkan Halaman Ini Kepada Kasir/Barista</h1>
        <form action="{{ route('order.verifyCode') }}" method="POST">
            @csrf
            @error('code')
                  <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <input type="text" name="code" placeholder="Masukkan Kode" class="w-100 mb-3 text-center py-4 rounded-3 font-20 color-primary" style="border: 2px solid #14B8A6">
            <button type="submit" class="w-100 py-2 rounded-3" style="background: linear-gradient(to bottom, #2DD4BF, #14B8A6)">
                <span class="text-decoration-none text-white fw-semibold ">Lanjutkan</span>
            </button>
        </form>
    </div>
@endsection