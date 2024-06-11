@extends('dashboards.layouts.main')

@section('container')
    <div class="container mt-3">
        <h2>Katalog Rewards</h2>
        <hr>
        <div class="table-responsive small">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Rewards Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Product Points</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rewards as $reward)
                        <tr>
                            <td>{{ $reward->id }}</td>
                            <td>{{ $reward->title }}</td>
                            <td>{{ $reward->description }}</td>
                            <td>{{ $reward->product_points }}</td>
                            <td>
                                <a href="#" class="badge bg-info"><i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="#" class="badge bg-warning"><i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="#" class="badge bg-danger"><i class="bi bi-x-circle-fill"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
