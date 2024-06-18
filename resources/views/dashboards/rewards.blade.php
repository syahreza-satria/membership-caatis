@extends('dashboards.layouts.main')

@section('container')
    <div class="container mt-3">
        <h2>Kelola Rewards</h2>
        <hr>
        <div class="container mt-4 shadow p-4 bg-white rounded">
            <h1 class="mb-4">Tambahkan Rewards</h1>
            <form action="{{ route('dashboard.rewards.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="title">Reward Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="product_points">Reward Points</label>
                    <input type="number" class="form-control" id="product_points" name="product_points" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" class="form-control" id="image" name="image">
                </div>
                <button type="submit" class="btn btn-primary mt-3">Add Reward</button>
            </form>
            @if ($rewards->isNotEmpty())
                <h2 class="mt-4">Existing Rewards</h2>
                <ul class="list-group">
                    @foreach ($rewards as $reward)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h5>{{ $reward->title }}</h5>
                                <p>{{ $reward->description }}</p>
                                <p class="fw-semibold">{{ $reward->product_points }} points</p>
                                @if ($reward->image_path)
                                    <img src="{{ asset('storage/' . $reward->image_path) }}" alt="{{ $reward->title }}"
                                        width="100">
                                @endif
                            </div>
                            <div class="d-flex">
                                <a href="{{ route('dashboard.rewards.edit', $reward->id) }}"
                                    class="btn btn-warning me-2">Edit</a>
                                <form action="{{ route('dashboard.rewards.destroy', $reward->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this reward?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

@endsection
