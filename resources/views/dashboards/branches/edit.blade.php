@extends('dashboards.layouts.main')

@section('container')
    <div class="container mt-3">
        <h2>Edit Branch</h2>
        <hr>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <form action="{{ route('dashboard.branches.update', $branch) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Branch Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $branch->name }}" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Branch Address</label>
                <input type="text" class="form-control" id="address" name="address" value="{{ $branch->address }}">
            </div>
            <div class="mb-3">
                <label for="logo" class="form-label">Branch Logo</label>
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
            <button type="submit" class="btn btn-primary">Update Branch</button>
            <a href="{{ route('dashboard.branches') }}" class="btn btn-secondary">Back to Manage Branches</a>
        </form>
    </div>
@endsection
