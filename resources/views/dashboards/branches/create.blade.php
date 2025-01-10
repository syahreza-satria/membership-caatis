@extends('dashboards.layouts.main')

@section('container')
    <div class="container mt-4">
        <h2 class="mb-3 text-red fw-bold">Cabang</h2>
        <hr>
        <div class="container mt-3 shadow p-4 bg-white rounded">
            <h3 class="text-red fw-bold font-24 mb-2">Tambah Cabang</h3>
            <form action="{{ route('dashboard.branches.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Cabang</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Alamat</label>
                    <input type="text" class="form-control" id="address" name="address">
                </div>
                <div class="mb-3">
                    <label for="logo" class="form-label">Logo</label>
                    <input type="file" class="form-control" id="logo" name="logo">
                </div>
                <div class="mb-3">
                    <label for="api_url" class="form-label">API URL</label>
                    <input type="text" class="form-control" id="api_url" name="api_url">
                </div>
                <div class="mb-3">
                    <label for="api_token" class="form-label">API Token</label>
                    <input type="text" class="form-control" id="api_token" name="api_token">
                </div>
                <div class="mb-3">
                    <label for="outletId" class="form-label">Outlet ID</label>
                    <input type="text" class="form-control" id="outletId" name="outletId">
                </div>
                <div class="mb-3">
                    <label for="order_type" class="form-label">Tipe Orderan</label>
                    <select class="form-control" id="order_type" name="order_type">
                        <option value="dinein">Dine-in</option>
                        <option value="takeaway">Takeaway</option>
                    </select>
                </div>
                <div class="text-end">
                    <a href="{{ route('dashboard.branches') }}" class="btn btn-secondary fw-bold">Kembali</a>
                    <button type="submit" class="btn btn-danger background-red fw-bold">Tambah Cabang</button>
                </div>
            </form>
        </div>
    </div>
@endsection
