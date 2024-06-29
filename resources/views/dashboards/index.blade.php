@extends('dashboards.layouts.main')

@section('container')
    <div class="container mt-3">
        <h2>Dashboard</h2>
        <hr>
        <h3>Total Users: {{ $totalUsers }}</h3>
        <div class="mt-4">
            <a href="{{ route('dashboard.verification-codes') }}" class="mt-2 btn btn-primary">View Verification Codes</a>
            <a href="{{ route('dashboard.rewards') }}" class="mt-2 btn btn-primary">Kelola Rewards</a>
            <a href="{{ route('dashboard.users') }}" class="mt-2 btn btn-primary">View Users</a>
            <a href="{{ route('dashboard.orders') }}" class="mt-2 btn btn-primary">View Orders</a>
        </div>
    </div>
@endsection
