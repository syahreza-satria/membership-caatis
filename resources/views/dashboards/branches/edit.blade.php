@extends('dashboards.layouts.main')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Cabang</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Edit Branch -->
        <div class="col-xl-12 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-lg font-weight-bold text-success text-uppercase mb-1">
                                Edit Cabang
                            </div>
                            <hr />
                            <form action="{{ route('dashboard.branches.update', $branch->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <!-- Nama Cabang -->
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="name">Nama Cabang</label>
                                            <input type="text" class="form-control" id="name" required
                                                name="name" value="{{ $branch->name }}" />
                                        </div>
                                    </div>
                                    <!-- Alamat -->
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="address">Alamat</label>
                                            <input type="text" class="form-control" id="address" name="address"
                                                min="0" required value="{{ $branch->address }}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- API URL -->
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="api_url">API URL</label>
                                            <input type="text" class="form-control" id="api_url" required
                                                name="api_url" value="{{ $branch->api_url }}" />
                                        </div>
                                    </div>
                                    <!-- API Token -->
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="api_token">API Token</label>
                                            <input type="text" class="form-control" id="api_token" name="api_token"
                                                min="0" required value="{{ $branch->api_token }}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- Outlet -->
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="outletId">Outlet Id</label>
                                            <input type="text" class="form-control" id="outletId" name="outletId"
                                                min="0" required value="{{ $branch->outletId }}" />
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="order_type">Tipe Orderan</label>
                                            <select class="form-control" id="order_type" name="order_type">
                                                <option value="dinein"
                                                    {{ old('order_type', $branch->order_type ?? '') == 'dinein' ? 'selected' : '' }}>
                                                    Dine-in</option>
                                                <option value="takeaway",
                                                    {{ old('order_type', $branch->order_type ?? '') == 'takeaway' ? 'selected' : '' }}>
                                                    Takeaway</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Logo -->
                                <div class="d-flex align-items-center">
                                    @if ($branch->logo)
                                        <img id="previewImage" src="{{ asset('storage/' . $branch->logo) }}"
                                            alt="{{ $branch->name }}" width="100" height="100" class="rounded"
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
                                                name="logo">
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
                                    <a href="{{ route('dashboard.branches') }}" class="btn btn-secondary mr-2">Batalkan</a>
                                    <button type="submit" class="btn btn-primary">
                                        Update Cabang
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

@section('scripts')
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
@endsection
