@extends('dashboards.layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">User</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Content -->
        <div class="col-xl-12 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <h2 class="text-lg font-weight-bold text-info text-uppercase mb-1">
                                Daftar User
                            </h2>
                            <hr />
                            <div class="table-responsive">
                                <table class="table table-striped bg-white">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="align-middle">No</th>
                                            <th scope="col" class="align-middle">Nama</th>
                                            <th scope="col" class="align-middle">Email</th>
                                            <th scope="col" class="align-middle">Role</th>
                                            <th scope="col" class="align-middle text-center">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td scope="row" class="align-middle" style="font-size: small">
                                                    1
                                                </td>
                                                <td class="align-middle" style="font-size: small">
                                                    {{ $user->fullname }}
                                                </td>
                                                <td class="align-middle" style="font-size: small">
                                                    {{ $user->email }}
                                                </td>
                                                <td class="align-middle" style="font-size: small">
                                                    @if (!$user->is_admin)
                                                        User
                                                    @else
                                                        Admin
                                                    @endif
                                                </td>
                                                <td class="align-middle text-center" style="font-size: small">
                                                    <button class="btn btn-warning">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-danger">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
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
