@extends('dashboards.layouts.main')
<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>


@section('container')
    <div class="container mt-3">
        <h2 style="color: red;">Cabang</h2>

        <hr>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <a href="{{ route('dashboard.branches.create') }}" class="btn btn-danger mb-3">Tambahkan Cabang</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="color: red;">Cabang</th>
                    <th style="color: red;">Alamat</th>
                    <th style="color: red;">Logo</th>
                    <th style="color: red;">API URL</th>
                    <th style="color: red;">API Token</th>
                    <th style="color: red;">Outlet ID</th>
                    <th style="color: red;">Actions</th>
                </tr>
            </thead>


            <tbody>
                @foreach ($branches as $branch)
                    <tr>
                        <td>{{ $branch->name }}</td>
                        <td>{{ $branch->address }}</td>
                        <td class="d-flex justify-content-center align-items-center">
                            @if ($branch->logo)
                                <img src="{{ Storage::url($branch->logo) }}" alt="{{ $branch->name }}" width="60" height="60" class="mx-auto my-auto">
                            @endif
                        </td>
                        <td>{{ $branch->api_url }}</td>
                        <td>{{ $branch->api_token }}</td>
                        <td>{{ $branch->outletId }}</td>
                        <td>
                            <!-- Edit Button -->
                            <a href="{{ route('dashboard.branches.edit', $branch) }}" class="btn btn-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- Delete Button -->
                            <form action="{{ route('dashboard.branches.destroy', $branch) }}" method="POST" class="d-inline">
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
@endsection
