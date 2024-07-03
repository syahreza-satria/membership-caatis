@extends('dashboards.layouts.main')

@section('container')
    <div class="container mt-3">
        <h2>Dashboard</h2>
        <hr>
        <div class="row mt-4">
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-key fa-2x mb-3"></i>
                        <h5 class="card-title">Kode Verifikasi</h5>
                        <p class="card-text">View Verification Codes</p>
                        <a href="{{ route('dashboard.verification-codes') }}" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-gift fa-2x mb-3"></i>
                        <h5 class="card-title">Kelola Rewards</h5>
                        <p class="card-text">Manage Rewards</p>
                        <a href="{{ route('dashboard.rewards') }}" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-store fa-2x mb-3"></i>
                        <h5 class="card-title">Kelola Cabang</h5>
                        <p class="card-text">Add, Edit, and Delete Cabang</p>
                        <a href="{{ route('dashboard.branches') }}" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-users fa-2x mb-3"></i>
                        <h5 class="card-title">Users</h5>
                        <p class="card-text">View Users</p>
                        <a href="{{ route('dashboard.users') }}" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="fas fa-receipt fa-2x mb-3"></i>
                        <h5 class="card-title">Pesanan</h5>
                        <p class="card-text">View Orders</p>
                        <a href="{{ route('dashboard.orders') }}" class="btn btn-primary">Go</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
