@extends('dashboards.layouts.main')

@section('styles')
    <style>
        .grayscale {
            filter: grayscale(100%);
        }
    </style>
@endsection

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Rewards</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Add Rewards -->
        <div class="col-xl-12 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-lg font-weight-bold text-success text-uppercase mb-1">
                                Tambah Rewards
                            </div>
                            <hr />
                            <form action="{{ route('dashboard.rewards.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <!-- Judul -->
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="title">Nama Rewards</label>
                                            <input type="text" class="form-control" id="title" required
                                                name="title" />
                                        </div>
                                    </div>
                                    <!-- Poin -->
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="product_points">Poin</label>
                                            <input type="number" class="form-control" id="product_points"
                                                name="product_points" min="0" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <!-- Cabang -->
                                        <div class="form-group">
                                            <label for="branch_id">Cabang</label>
                                            <select class="form-control" id="branch_id" name="branch_id" required>
                                                <option>Pilih Cabang</option>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <!-- Logo -->
                                        <div class="form-group">
                                            <label for="image">Gambar</label>
                                            <!-- Preview Image -->
                                            <div class="d-flex align-items-center">
                                                <div class="pr-1">
                                                    <img id="imagePreview" src="#" alt="Preview Gambar"
                                                        class="img-thumbnail d-none" width="150">
                                                </div>
                                                <div class="custom-file mb-3">
                                                    <input type="file" class="custom-file-input" id="validatedCustomFile"
                                                        name="image" accept="image/*" onchange="previewImage(event)" />
                                                    <label class="custom-file-label" for="validatedCustomFile">Pilih
                                                        Gambar</label>
                                                    <div class="invalid-feedback">
                                                        Example invalid custom file feedback
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Deskripsi -->
                                <div class="form-group">
                                    <label for="description">Deskripsi</label>
                                    <textarea class="form-control" id="description" required rows="3" name="description"></textarea>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        Tambahkan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Existing Reward -->
        <div class="col-xl-12 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-lg font-weight-bold text-success text-uppercase mb-1">
                                Daftar Rewards
                            </div>
                            <hr />
                            @if ($rewards->isNotEmpty())
                                @foreach ($rewards as $reward)
                                    <div class="card mb-2">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                    <h5 class="card-title font-weight-bold" style="color: #000000">
                                                        {{ $reward->title }}
                                                    </h5>
                                                    <h6 class="card-subtitle mb-2 text-muted">
                                                        Cabang:
                                                        <span
                                                            class="text-dark font-weight-bolder text-primary">{{ $reward->branch->name }}</span>
                                                    </h6>
                                                    <p class="card-text font-weight-light">
                                                        {{ $reward->description }}
                                                    </p>
                                                    <p class="">{{ $reward->product_points }} Points</p>
                                                </div>
                                                <div class="col d-flex justify-content-end">
                                                    <div class="text-center">
                                                        <img src="{{ $reward->image_path ? Storage::url($reward->image_path) : asset('img/default-image.png') }}"
                                                            alt="{{ $reward->title }}" width="100" height="100"
                                                            class="rounded {{ $reward->is_active ? '' : 'grayscale' }} shadow"
                                                            style="object-fit: cover" />

                                                        <div class="mt-1 d-flex justify-content-center align-items-center">
                                                            <button type="button"
                                                                data-url="{{ route('dashboard.rewards.toggle', $reward->id) }}"
                                                                class="card-link btn {{ $reward->is_active ? 'text-success' : 'text-secondary' }} toggle-status-btn"
                                                                title="Show" data-bs-toggle="tooltip"
                                                                data-bs-placement="top">{!! $reward->is_active ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>' !!}

                                                            </button>
                                                            <a href="{{ route('dashboard.rewards.edit', $reward->id) }}"
                                                                class="card-link text-warning" title="Edit"
                                                                data-bs-toggle="tooltip" data-bs-placement="top">
                                                                <i class="fas fa-pencil-ruler"></i>

                                                            </a>
                                                            <button type="button"
                                                                data-url="{{ route('dashboard.rewards.destroy', $reward->id) }}"
                                                                class="card-link text-danger btn delete-btn"
                                                                title="Delete" data-bs-toggle="tooltip"
                                                                data-bs-placement="top">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <h5 class="text-center my-5">Tidak ada rewards yang terdaftar</h5>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="toggle-status-form" method="POST" style="display: none;">
        @csrf
    </form>

    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Preview Gambar --}}
    <script>
        function previewImage(event) {
            const fileInput = event.target;
            const file = fileInput.files[0]; // Ambil file yang dipilih
            const fileName = file ? file.name : "Pilih Gambar"; // Ambil nama file
            const reader = new FileReader();

            if (file) {
                reader.onload = function(e) {
                    // Set preview gambar
                    const imgPreview = document.getElementById('imagePreview');
                    imgPreview.src = e.target.result;
                    imgPreview.classList.remove('d-none');
                };

                reader.readAsDataURL(file);
            }

            // Ganti teks label dengan nama file
            fileInput.nextElementSibling.innerText = fileName;
        }
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.toggle-status-btn').forEach(button => {
                button.addEventListener('click', function() {
                    var url = this.dataset.url;
                    var isActive = this.classList.contains('text-success');

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
