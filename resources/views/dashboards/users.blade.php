@extends('dashboards.layouts.main')

@section('container')
    <div class="container mt-4">
        <h2 class="text-red fw-bold">Pengguna</h2>
        <hr>
        <div class="container mt-3 shadow p-4 bg-white rounded">
            <h3 class="fw-bold font-24">Total Users: {{ $totalUsers }}</h3>
            @if ($users->isEmpty())
                <p>No users available.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Lengkap</th>
                                <th>E-mail</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->fullname }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
