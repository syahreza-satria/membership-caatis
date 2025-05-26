@extends('dashboards.layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kode Verifikasi</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Content -->
        <div class="col-xl-12 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <h2 class="text-lg font-weight-bold text-primary text-uppercase mb-1 text-center">
                                Kode Verifikasi
                            </h2>
                            <hr />
                            @if ($code)
                                <h3 class="text-center h3 font-weight-bold text-primary">{{ $code->code }}</h3>
                                <p class="text-center text-dark">Kode verifikasi ini hanya berlaku pada tanggal :
                                    <span class="text-primary">{{ $code->date }}</span>
                                    <br>
                                    <span class="text-danger"><small>Kode verifikasi akan berubah tiap hari</small></span>
                                </p>
                            @else
                                <p>Tidak ada kode verifikasi terbuat untuk hari ini.</p>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
