@extends('dashboards.layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Kode Verifikasi -->
        <div class="col-xl-3 col-md-6 mb-4 text-decoration-none">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Kode Verifikasi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $code->code }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-code fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rewards Active -->
        <a href="{{ route('dashboard.rewards') }}" class="col-xl-3 col-md-6 mb-4 text-decoration-none">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Rewards (active)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $rewards->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-gift fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        <!-- Cabang -->
        <a href="{{ route('dashboard.branches') }}" class="col-xl-3 col-md-6 mb-4 text-decoration-none">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Cabang
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $branches->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-code-branch fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        <!-- Pesanan -->
        <a href="{{ route('dashboard.orders') }}" class="col-xl-3 col-md-6 mb-4 text-decoration-none">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Pesanan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $orders->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>

        <!-- User -->
        <a href="{{ route('dashboard.users') }}" class="col-xl-3 col-md-6 mb-4 text-decoration-none">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                User
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $users->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
@endsection
