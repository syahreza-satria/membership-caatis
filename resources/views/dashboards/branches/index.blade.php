@extends('dashboards.layouts.main')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Cabang</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Content Row -->
    <div class="row">
        <div class="col-xl-12 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="d-flex justify-content-between mb-1">
                                <h2 class="text-lg font-weight-bold text-warning my-auto text-uppercase">
                                    Daftar cabang
                                </h2>
                                <a href="{{ route('dashboard.branches.create') }}" class="btn btn-primary"
                                    style="font-size: small">
                                    Tambah +
                                </a>
                            </div>

                            <hr />
                            <div class="table-responsive">
                                <table class="table table-striped bg-white">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="align-middle">No</th>
                                            <th scope="col" class="align-middle">Cabang</th>
                                            <th scope="col" class="align-middle">Alamat</th>
                                            <th scope="col" class="align-middle">Logo</th>
                                            <th scope="col" class="align-middle">
                                                Api URL
                                            </th>
                                            <th scope="col" class="align-middle">
                                                Api Token
                                            </th>
                                            <th scope="col" class="align-middle">
                                                Outlet ID
                                            </th>
                                            <th scope="col" class="align-middle">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($branches as $branch)
                                            <tr>
                                                <th scope="row" class="align-middle" style="font-size: small">
                                                    1
                                                </th>
                                                <td class="align-middle" style="font-size: small">
                                                    {{ $branch->name }}
                                                </td>
                                                <td class="align-middle" style="font-size: small">
                                                    {{ $branch->address }}
                                                </td>
                                                <td class="align-middle">
                                                    @if ($branch->logo)
                                                        <img src="{{ Storage::url($branch->logo) }}"
                                                            alt="{{ $branch->name }}" class="rounded" width="50"
                                                            height="50" style="object-fit: cover" />
                                                    @else
                                                        <img src="http://placehold.co/100x100" alt="{{ $branch->name }}"
                                                            class="rounded" width="50" height="50" />
                                                    @endif
                                                </td>
                                                <td class="text-truncate align-middle" style="font-size: small">
                                                    {{ $branch->api_url }}
                                                </td>
                                                <td class="text-truncate align-middle" style="font-size: small">
                                                    {{ $branch->api_token }}
                                                </td>
                                                <td class="text-truncate align-middle" style="font-size: small">
                                                    {{ $branch->outletId }}
                                                </td>
                                                <td class="text-truncate align-middle">
                                                    <a href="{{ route('dashboard.branches.edit', $branch) }}"
                                                        class="btn btn-warning">
                                                        <i class="fas fa-pencil-ruler"></i>
                                                    </a>
                                                    <form action="{{ route('dashboard.branches.destroy', $branch) }}"
                                                        method="post" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
