@extends('dashboards.layouts.main')

@section('container')
    <div class="container mt-4">
        <h2 class="text-red font-32 fw-bold">Rewards</h2>

        <hr>
        <div class="container mt-4 shadow p-4 bg-white rounded">
            <h1 class="mb-3 text-red font-24 fw-bold">Tambahkan Rewards</h1>
            <form action="{{ route('dashboard.rewards.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-2">
                    <label for="title" class="mb-1">Judul</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="form-group mb-2">
                    <label for="product_points" class="mb-1">Poin</label>
                    <input type="number" class="form-control" id="product_points" name="product_points" required>
                </div>
                <div class="form-group mb-2">
                    <label for="description" class="mb-1">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" required></textarea>
                </div>
                <div class="form-group mb-2">
                    <label for="image" class="mb-1">Image</label>
                    <input type="file" class="form-control" id="image" name="image">
                </div>
                <div class="form-group mb-2">
                    <label for="branch_id" class="mb-1">Cabang</label>
                    <select class="form-control" id="branch_id" name="branch_id" required>
                        <option value="">Pilih Cabang</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-danger background-red text-white fw-bold mt-3">Tambah
                        Reward</button>
                </div>

            </form>

            @if ($rewards->isNotEmpty())
                <h2 class="mt-4 text-red font-24 fw-bold">Rewards yang telah ada</h2>
                <ul class="list-group mt-3">
                    @foreach ($rewards as $reward)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="fw-bold font-20">{{ $reward->title }}</h5>
                                <p class="fw-semibold font-16 text-secondary">Cabang: {{ $reward->branch->name }}</p>
                                <p class="text-secondary font-16">{{ $reward->description }}</p>
                                <p class="fw-bold text-red">{{ $reward->product_points }} points</p>
                            </div>
                            <div class="d-flex flex-column align-items-end">
                                @if ($reward->image_path)
                                    <img src="{{ asset('storage/' . $reward->image_path) }}" alt="{{ $reward->title }}"
                                        width="100" class="{{ $reward->is_active ? '' : 'grayscale' }} rounded-4 mb-2">
                                @else
                                    <p class="fw-semibold mb-2">Tidak Memiliki Foto</p>
                                @endif
                                <div class="d-flex">
                                    <button type="button"
                                        class="btn me-2 toggle-status-btn {{ $reward->is_active ? 'btn-success' : 'btn-secondary' }}"
                                        data-url="{{ route('dashboard.rewards.toggle', $reward->id) }}">
                                        {!! $reward->is_active ? '<i class="bi bi-eye-fill"></i>' : '<i class="bi bi-eye-slash-fill"></i>' !!}
                                    </button>

                                    <a href="{{ route('dashboard.rewards.edit', $reward->id) }}"
                                        class="btn btn-warning me-2"><i class="bi bi-pencil-fill text-white"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger delete-btn"
                                        data-url="{{ route('dashboard.rewards.destroy', $reward->id) }}">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="mt-4">Belum ada reward yang ditambahkan.</p>
            @endif


        </div>
    </div>

    <!-- Hidden form for toggling status -->
    <form id="toggle-status-form" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Hidden form for deleting reward -->
    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@section('styles')
    <style>
        .grayscale {
            filter: grayscale(100%);
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.toggle-status-btn').forEach(button => {
                button.addEventListener('click', function() {
                    var url = this.dataset.url;
                    var isActive = this.classList.contains('btn-success');

                    var title = isActive ? 'Kamu yakin menyembunyikan katalog ini?' :
                        'Kamu yakin menampilkan katalog ini?';
                    var text = isActive ?
                        'Pelanggan tidak akan bisa melihat reward ini pada webnya sampai kamu mengaktifkannya lagi' :
                        'Pelanggan akan bisa melihat reward ini pada webnya';
                    var confirmButtonText = isActive ? 'Iya, Sembunyikan!' : 'Iya, Tampilkan!';

                    Swal.fire({
                        title: title,
                        text: text,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: confirmButtonText
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var form = document.getElementById('toggle-status-form');
                            form.action = url;
                            form.submit();
                        }
                    });
                });
            });

            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    var url = this.dataset.url;
                    Swal.fire({
                        title: 'Kamu yakin menghapus katalog ini?',
                        text: "Kamu akan menghapus reward ini dari database yang bisa mengakibatkan hilangnya history dari orang-orang yang telah menukarkan rewardnya",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Iya, Hapus saja!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var form = document.getElementById('delete-form');
                            form.action = url;
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
