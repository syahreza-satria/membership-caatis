@extends('dashboards.layouts.main')

@section('container')
    <div class="container mt-3">
        <h2>Kelola Rewards</h2>
        <hr>
        <div class="container mt-4 shadow p-4 bg-white rounded">
            <h1 class="mb-4">Tambahkan Rewards</h1>
            <form action="{{ route('dashboard.rewards.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="title">Reward Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="product_points">Reward Points</label>
                    <input type="number" class="form-control" id="product_points" name="product_points" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" class="form-control" id="image" name="image">
                </div>
                <div class="form-group">
                    <label for="branch_id">Branch</label>
                    <select class="form-control" id="branch_id" name="branch_id" required>
                        <option value="">Pilih Cabang</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Add Reward</button>
            </form>
            @if ($rewards->isNotEmpty())
                <h2 class="mt-4">Existing Rewards</h2>
                <ul class="list-group">
                    @foreach ($rewards as $reward)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h5>{{ $reward->title }}</h5>
                                <p class="fw-semibold">Cabang: {{ $reward->branch->name }}</p>
                                <p>{{ $reward->description }}</p>
                                <p class="fw-semibold">{{ $reward->product_points }} points</p>
                                @if ($reward->image_path)
                                    <img src="{{ asset('storage/' . $reward->image_path) }}" alt="{{ $reward->title }}"
                                        width="100" class="{{ $reward->is_active ? '' : 'grayscale' }} rounded-4">
                                @else
                                    <p class="fw-semibold mt-3">Tidak Memiliki Foto</p>
                                @endif
                            </div>
                            <div class="d-flex">
                                <button type="button"
                                    class="btn me-2 toggle-status-btn {{ $reward->is_active ? 'btn-success' : 'btn-secondary' }}"
                                    data-url="{{ route('dashboard.rewards.toggle', $reward->id) }}">
                                    {!! $reward->is_active ? '<i class="bi bi-eye-fill"></i>' : '<i class="bi bi-eye-slash-fill"></i>' !!}
                                </button>

                                <a href="{{ route('dashboard.rewards.edit', $reward->id) }}" class="btn btn-info me-2"><i
                                        class="bi bi-pencil-fill text-white"></i>
                                </a>
                                <button type="button" class="btn btn-danger delete-btn"
                                    data-url="{{ route('dashboard.rewards.destroy', $reward->id) }}">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
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
