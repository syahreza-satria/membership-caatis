@extends('dashboards.layouts.main')

@section('container')
    <div class="container mt-3">
        <h2 class="mb-4 text-danger">Edit Cabang</h2>
        <hr>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <form action="{{ route('dashboard.branches.update', $branch) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nama Cabang</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $branch->name }}" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Alamat</label>
                <input type="text" class="form-control" id="address" name="address" value="{{ $branch->address }}">
            </div>
            <div class="mb-3">
                <label for="logo" class="form-label">Logo</label>
                <input type="file" class="form-control" id="logo" name="logo">
                @if ($branch->logo)
                    <div class="mt-2">
                        <img src="{{ Storage::url($branch->logo) }}" alt="{{ $branch->name }}" width="100">
                    </div>
                @endif
            </div>
            <div class="mb-3">
                <label for="api_url" class="form-label">API URL</label>
                <input type="text" class="form-control" id="api_url" name="api_url" value="{{ $branch->api_url }}">
            </div>
            <div class="mb-3">
                <label for="api_token" class="form-label">API Token</label>
                <input type="text" class="form-control" id="api_token" name="api_token" value="{{ $branch->api_token }}">
            </div>
            <div class="mb-3">
                <label for="outletId" class="form-label">Outlet ID</label>
                <input type="text" class="form-control" id="outletId" name="outletId" value="{{ $branch->outletId }}">
            </div>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('dashboard.branches') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-danger">Perbarui Cabang</button>
            </div>

        </form>
    </div>
@endsection
