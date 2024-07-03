@extends('dashboards.layouts.main')

@section('container')
    <div class="container mt-3">
        <h2>Manage Branches</h2>
        <hr>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <a href="{{ route('dashboard.branches.create') }}" class="btn btn-primary mb-3">Add New Branch</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Logo</th>
                    <th>API URL</th>
                    <th>API Token</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($branches as $branch)
                    <tr>
                        <td>{{ $branch->name }}</td>
                        <td>{{ $branch->address }}</td>
                        <td class="d-flex justify-content-center align-items-center">
                            @if ($branch->logo)
                                <img src="{{ Storage::url($branch->logo) }}" alt="{{ $branch->name }}" width="60"
                                    height="60" class="mx-auto my-auto">
                            @endif
                        </td>
                        <td>{{ $branch->api_url }}</td>
                        <td>{{ $branch->api_token }}</td>
                        <td>
                            <a href="{{ route('dashboard.branches.edit', $branch) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('dashboard.branches.destroy', $branch) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
