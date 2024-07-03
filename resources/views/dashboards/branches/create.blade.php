@extends('dashboards.layouts.main')

@section('container')
    <div class="container mt-3">
        <h2>Add New Branch</h2>
        <hr>
        <form action="{{ route('dashboard.branches.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Branch Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Branch Address</label>
                <input type="text" class="form-control" id="address" name="address">
            </div>
            <div class="mb-3">
                <label for="logo" class="form-label">Branch Logo</label>
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
            <button type="submit" class="btn btn-primary">Add Branch</button>
            <a href="{{ route('dashboard.branches') }}" class="btn btn-secondary">Back to Manage Branches</a>
        </form>
    </div>
@endsection
