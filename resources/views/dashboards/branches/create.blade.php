@extends('dashboards.layouts.main')

@section('container')
    <div class="container mt-3">
        <h2 class="mb-4 text-danger">Tambah Cabang</h2>
        <hr>
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
            <button type="submit" class="btn btn-danger" style="float: right;">Tambah Cabang</button>
                <a href="{{ route('dashboard.branches') }}" style="float:right;" class="btn btn-secondary">Kembali</a>

        </form>
    </div>
@endsection
