@extends('dashboards.layouts.main')

@section('container')
    <div class="container mt-3">
        <h2 class="text-danger">Rewards</h2>
        <hr>
        <div class="container mt-3">
            <hr>
            <div class="container mt-4 shadow p-4 bg-white rounded">
                <form action="{{ route('dashboard.rewards.update', $reward->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="title">Judul</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ $reward->title }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="product_points">Poin</label>
                        <input type="number" class="form-control" id="product_points" name="product_points"
                            value="{{ $reward->product_points }}" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" required>{{ $reward->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" class="form-control" id="image" name="image">
                        @if ($reward->image_path)
                            <img src="{{ asset('storage/' . $reward->image_path) }}" alt="{{ $reward->title }}"
                                width="100" class="mt-2">
                        @endif
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-danger mt-3">Update Reward</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
