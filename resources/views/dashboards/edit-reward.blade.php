@extends('dashboards.layouts.main')

@section('container')
    <div class="container mt-3">
        <h2 class="text-red fw-bold">Rewards</h2>
        <hr>
        <div class="container mt-3 shadow p-4 bg-white rounded">
            <h3 class="text-red fw-bold mb-2 font-24">Edit Rewards</h3>
            <form action="{{ route('dashboard.rewards.update', $reward->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group mb-2">
                    <label for="title" class=" mb-1">Judul</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ $reward->title }}"
                        required>
                </div>
                <div class="form-group mb-2">
                    <label for="product_points" class=" mb-1">Poin</label>
                    <input type="number" class="form-control" id="product_points" name="product_points"
                        value="{{ $reward->product_points }}" required>
                </div>
                <div class="form-group mb-2">
                    <label for="description" class=" mb-1">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" required>{{ $reward->description }}</textarea>
                </div>
                <div class="form-group mb-2">
                    <label for="image" class="mb-1">Gambar</label>
                    <input type="file" class="form-control mb-3" id="image" name="image" accept="image/*">
                    <div class="d-flex">
                        @if ($reward->image_path)
                            <img src="{{ asset('storage/' . $reward->image_path) }}" alt="{{ $reward->title }}"
                                width="100" class="mt-2 rounded" id="currentImage">
                        @endif
                        <img id="previewImage" class="mt-2 rounded ms-3"
                            style="display: none; max-width: 100px; max-height: 100px;">

                        <!-- Tombol Hapus Preview -->
                        <button type="button" id="clearPreviewBtn"
                            class="btn btn-danger background-red text-white font-bold ms-2 my-5"
                            style="display: none;">Hapus
                            gambar</button>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-danger background-red text-white fw-bold mt-3">Update
                        Reward</button>
                </div>
            </form>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('image');
        const previewImage = document.getElementById('previewImage');
        const clearPreviewBtn = document.getElementById('clearPreviewBtn');

        // Fungsi untuk menampilkan preview gambar dan tombol hapus
        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block'; // Menampilkan gambar preview
                    clearPreviewBtn.style.display = 'inline-block'; // Menampilkan tombol hapus
                };
                reader.readAsDataURL(file);
            } else {
                previewImage.style.display =
                    'none'; // Menyembunyikan gambar preview jika tidak ada file
                clearPreviewBtn.style.display =
                    'none'; // Menyembunyikan tombol hapus jika tidak ada file
            }
        });

        // Fungsi untuk menghapus gambar dan menyembunyikan tombol hapus
        clearPreviewBtn.addEventListener('click', function() {
            // Mengosongkan input file
            imageInput.value = '';
            previewImage.style.display = 'none'; // Menyembunyikan gambar preview
            clearPreviewBtn.style.display = 'none'; // Menyembunyikan tombol hapus
        });
    });
</script>
