@extends('dashboards.layouts.main')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Cabang</h1>
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
                                Tambah Cabang
                            </div>
                            <hr />
                            <form action="{{ route('dashboard.branches.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <!-- Nama Cabang -->
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="name">Nama Cabang</label>
                                            <input type="text" class="form-control" id="name" required
                                                name="name" />
                                        </div>
                                    </div>
                                    <!-- Alamat -->
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="address">Alamat</label>
                                            <input type="text" class="form-control" id="address" name="address"
                                                required />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- API URL -->
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="api_url">API URL</label>
                                            <input type="text" class="form-control" id="api_url" required
                                                name="api_url" />
                                        </div>
                                    </div>
                                    <!-- API Token -->
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="api_token">API Token</label>
                                            <input type="text" class="form-control" id="api_token" name="api_token"
                                                required />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- Outlet -->
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="outletId">Outlet Id</label>
                                            <input type="text" class="form-control" id="outletId" name="outletId"
                                                required />
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="order_type">Tipe Orderan</label>
                                            <select class="form-control" id="order_type" name="order_type">
                                                <option value="">
                                                    Dine-in / Takeaway</option>
                                                <option value="dinein"
                                                    {{ 'order_type', $branch ?? (('')->order_type ?? '' == 'dinein') ? 'selected' : '' }}>
                                                    Dine-in</option>
                                                <option value="takeaway",
                                                    {{ old('order_type', $branch ?? (('')->order_type ?? '')) == 'takeaway' ? 'selected' : '' }}>
                                                    Takeaway</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- Logo -->
                                <div class="d-flex">
                                    <div class="form-group my-auto w-100">
                                        <label for="logo">Gambar</label>
                                        <div class="custom-file mb-3">
                                            <input type="file" class="custom-file-input" id="logo" name="logo" />
                                            <label class="custom-file-label" for="logo">Pilih
                                                Gambar</label>
                                            <div class="invalid-feedback">
                                                Example invalid custom file feedback
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('dashboard.branches') }}" class="btn btn-secondary mr-2">Batalkan</a>
                                    <button type="submit" class="btn btn-primary">
                                        Tambah Cabang
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
