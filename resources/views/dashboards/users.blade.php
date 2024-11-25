@extends('dashboards.layouts.main')

@section('container')
    <div class="container mt-3">
        <h2 style="color: red;">Users</h2>

        <hr>
        <div class="container mt-4 shadow p-4 bg-white rounded">
            <h1 class="mb-4">Total Users: {{ $totalUsers }}</h1>
            @if ($users->isEmpty())
                <p>No users available.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Lengkap</th>
                                <th>E-mail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->fullname }}</td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
