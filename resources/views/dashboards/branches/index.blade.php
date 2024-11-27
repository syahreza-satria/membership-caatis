@extends('dashboards.layouts.main')

@section('container')
    <div class="container mt-4">
        <h2 class="text-red font-32 fw-bold">Cabang</h2>

        <hr>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <a href="{{ route('dashboard.branches.create') }}"
            class="btn btn-danger background-red text-white fw-bold mb-3">Tambahkan
            Cabang</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="color: #a52a2a">Cabang</th>
                    <th style="color: #a52a2a">Alamat</th>
                    <th style="color: #a52a2a">Logo</th>
                    <th style="color: #a52a2a">API URL</th>
                    <th style="color: #a52a2a">API Token</th>
                    <th style="color: #a52a2a">Outlet ID</th>
                    <th style="color: #a52a2a">Actions</th>
                </tr>
            </thead>


            <tbody>
                @foreach ($branches as $branch)
                    <tr>
                        <td class="text-secondary">{{ $branch->name }}</td>
                        <td class="text-secondary">{{ $branch->address }}</td>
                        <td class="d-flex justify-content-center align-items-center">
                            @if ($branch->logo)
                                <img src="{{ Storage::url($branch->logo) }}" alt="{{ $branch->name }}" width="60"
                                    height="60" class="mx-auto my-auto">
                            @endif
                        </td>
                        <td class="text-secondary">{{ $branch->api_url }}</td>
                        <td class="text-secondary">{{ $branch->api_token }}</td>
                        <td class="text-secondary">{{ $branch->outletId }}</td>
                        <td>
                            <!-- Edit Button -->
                            <a href="{{ route('dashboard.branches.edit', $branch) }}" class="btn btn-warning"
                                title="Edit">
                                <i class="bi bi-pencil-fill text-white"></i>
                            </a>

                            <!-- Delete Button -->
                            <form action="{{ route('dashboard.branches.destroy', $branch) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" title="Delete">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
