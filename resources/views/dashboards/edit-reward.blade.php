@extends('dashboards.layouts.main')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Rewards</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Edit Rewards -->
        <div class="col-xl-12 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-lg font-weight-bold text-success text-uppercase mb-1">
                                Tambah Rewards
                            </div>
                            <hr />
                            <form action="{{ route('dashboard.rewards.update', $reward->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <!-- Judul -->
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="title">Nama Rewards</label>
                                            <input type="text" class="form-control" id="title" required
                                                name="title" value="{{ $reward->title }}" />
                                        </div>
                                    </div>
                                    <!-- Poin -->
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="product_points">Poin</label>
                                            <input type="number" class="form-control" id="product_points"
                                                name="product_points" min="0" required
                                                value="{{ $reward->product_points }}" />
                                        </div>
                                    </div>
                                </div>
                                <!-- Deskripsi -->
                                <div class="form-group">
                                    <label for="description">Deskripsi</label>
                                    <textarea class="form-control" id="description" required rows="3" name="description">{{ $reward->description }}</textarea>
                                </div>

                                <!-- Logo -->
                                <div class="d-flex align-items-center">
                                    @if ($reward->image_path)
                                        <img id="previewImage" src="{{ asset('storage/' . $reward->image_path) }}"
                                            alt="{{ $reward->title }}" width="100" height="100" class="rounded"
                                            style="object-fit: cover; margin-top: 8px; margin-right: 16px">
                                    @else
                                        <img id="previewImage" src="#" alt="Preview Gambar" width="100"
                                            height="100" class="rounded d-none"
                                            style="object-fit: cover; margin-top: 8px; margin-right: 16px">
                                    @endif

                                    <div class="form-group my-auto w-100">
                                        <label for="image">Gambar</label>
                                        <div class="custom-file mb-3">
                                            <input type="file" class="custom-file-input" id="validatedCustomFile"
                                                name="image">
                                            <label class="custom-file-label" for="validatedCustomFile">Pilih Gambar</label>
                                            <div class="invalid-feedback">Example invalid custom file feedback</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol Hapus Preview -->
                                <button type="button" id="clearPreviewBtn" class="btn btn-danger btn-sm mt-2 d-none">
                                    Hapus Gambar
                                </button>
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('dashboard.rewards') }}" class="btn btn-secondary mr-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">
                                        Update Reward
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('validatedCustomFile'); // Perbaikan ID
        const previewImage = document.getElementById('previewImage');
        const clearPreviewBtn = document.getElementById('clearPreviewBtn');

        // Fungsi untuk menampilkan preview gambar
        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.classList.remove('d-none'); // Menampilkan gambar preview
                    clearPreviewBtn.classList.remove('d-none'); // Menampilkan tombol hapus
                };
                reader.readAsDataURL(file);
            }
        });

        // Fungsi untuk menghapus preview gambar
        clearPreviewBtn.addEventListener('click', function() {
            imageInput.value = ''; // Reset input file
            previewImage.src = '#'; // Menghapus preview
            previewImage.classList.add('d-none'); // Sembunyikan gambar preview
            clearPreviewBtn.classList.add('d-none'); // Sembunyikan tombol hapus
        });
    });
</script>
