@extends('layouts.layout-main')

@section('content')
    @php
        $imagePath = $reward->image_path ? asset('storage/' . $reward->image_path) : asset($reward->branch->logo);
    @endphp

    <section class="reward-detail">
        <img src="{{ $imagePath }}" alt="{{ $reward->title }}" width="350px" class="rounded-4 ">
        <h2 class="fw-bold font-20 pt-3 ">{{ $reward->title }}</h2>
        <h2 class="fw-semibold font-16 pb-2">Cabang: {{ $reward->branch->name }}</h2>
        <div class="poin-detail text-center rounded-5 mb-4">
            <p class="fw-semibold font-14 my-2 mx-auto">{{ $reward->product_points }} Poin</p>
        </div>
        <p class="font-14 w-100 text-secondary mt-0">Deskripsi:</p>
        <p class="font-14 w-100 ">{{ $reward->description }}</p>

        <form action="/rewards/redeem/{{ $reward->id }}" method="POST">
            @csrf
            <button id="tukar-point" type="submit"
                class="w-100 button-tukar mt-5 mb-3 border-0 text-light fw-bold ">Tukarkan Poin</button>
        </form>
    </section>

    @if (@session('error'))
        <script>
            Swal.fire({
                title: 'Error!',
                text: '{{ session('error') }}',
                icon: 'error',
                iconColor: '#FF444E',
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    @endif

    <script>
        document.getElementById('tukar-point').addEventListener('click', function(event) {
            event.preventDefault();
            let asu = {{ auth()->user()->user_points }} - {{ $reward->product_points }};
            Swal.fire({
                title: 'Konfirmasi Penukaran Poin',
                text: `Apakah Anda yakin ingin menukarkan poin sebesar {{ $reward->product_points }}, sehingga point Anda sisa ${asu} ?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#14B8A6',
                cancelButtonColor: '#FF444E',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.querySelector('form[action="/rewards/redeem/{{ $reward->id }}"]')
                        .submit(); // Submit form
                }
            });
        })
    </script>
@endsection
