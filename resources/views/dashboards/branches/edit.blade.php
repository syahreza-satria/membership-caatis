@extends('dashboards.layouts.main')

@section('container')
    <div class="container mt-4">
        <h2 class="mb-3 text-red fw-bold">Cabang</h2>
        <hr>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="container mt-3 shadow p-4 bg-white rounded">
            <h3 class="text-red fw-bold font-24 mb-2">Edit Cabang</h3>
            <form action="{{ route('dashboard.branches.update', $branch) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-2">
                    <label for="name" class="form-label mb-1">Nama Cabang</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $branch->name }}"
                        required>
                </div>
                <div class="mb-2">
                    <label for="address" class="form-label mb-1">Alamat</label>
                    <input type="text" class="form-control" id="address" name="address" value="{{ $branch->address }}">
                </div>
                <div class="mb-2">
                    <label for="logo" class="form-label mb-1">Logo</label>
                    <input type="file" class="form-control" id="logo" name="logo">
                    @if ($branch->logo)
                        <div class="mt-2">
                            <img src="{{ Storage::url($branch->logo) }}" alt="{{ $branch->name }}" width="100">
                        </div>
                    @endif
                </div>
                <div class="mb-2">
                    <label for="api_url" class="form-label mb-1">API URL</label>
                    <input type="text" class="form-control" id="api_url" name="api_url" value="{{ $branch->api_url }}">
                </div>
                <div class="mb-2">
                    <label for="api_token" class="form-label mb-1">API Token</label>
                    <input type="text" class="form-control" id="api_token" name="api_token"
                        value="{{ $branch->api_token }}">
                </div>
                <div class="mb-2">
                    <label for="outletId" class="form-label mb-1">Outlet ID</label>
                    <input type="text" class="form-control" id="outletId" name="outletId"
                        value="{{ $branch->outletId }}">
                </div>
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="{{ route('dashboard.branches') }}" class="btn btn-secondary fw-bold">Kembali</a>
                    <button type="submit" class="btn btn-danger background-red text-white fw-bold">Perbarui Cabang</button>
                </div>
            </form>
        </div>
    </div>
@endsection
