@extends('dashboards.layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pesanan</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-xl-12 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col">
                            <div class="mb-1 d-flex flex-wrap justify-content-between">
                                <h2 class="text-lg font-weight-bold text-danger text-uppercase">
                                    Daftar Pesanan
                                </h2>
                            </div>
                            <hr />

                            <!-- Filter -->
                            <div class="row mb-4">
                                <!-- Search Input -->
                                <form action="{{ route('dashboard.orders.search') }}" method="GET" class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" name="search" id="search" class="form-control"
                                            placeholder="Cari berdasarkan nama, cabang, pesanan"
                                            value="{{ old('search', $search) }}">
                                    </div>
                                </form>

                                <!-- Branch Filter Buttons -->
                                <div class="col-md-8">
                                    <div class="d-flex flex-wrap justify-content-end">
                                        <a href="{{ request()->url() }}"
                                            class="btn btn-outline-primary m-1 {{ request('branch_id') ? '' : 'active' }}">
                                            Semua Cabang
                                        </a>
                                        @foreach ($branches as $branch)
                                            <a href="{{ request()->fullUrlWithQuery(['branch_id' => $branch->outletId]) }}"
                                                class="btn btn-outline-primary m-1 {{ request('branch_id') == $branch->outletId ? 'active' : '' }}">
                                                {{ $branch->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            @if ($orders->isEmpty())
                                <h5 class="text-center my-5">Tidak ditemukan adanya pesanan</h5>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped bg-white">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="align-middle">No</th>
                                                <th scope="col" class="align-middle">Nama</th>
                                                <th scope="col" class="align-middle">Cabang</th>
                                                <th scope="col" class="align-middle">Total Harga</th>
                                                <th scope="col" class="align-middle">Status</th>
                                                <th scope="col" class="align-middle">Waktu</th>
                                                <th scope="col" class="align-middle">Detail</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $index => $order)
                                                <tr>
                                                    <th scope="row" class="align-middle" style="font-size: small">
                                                        {{ $index + 1 }}
                                                    </th>
                                                    <td class="align-middle" style="font-size: small">
                                                        {{ $order->user->fullname }}
                                                    </td>
                                                    <td class="align-middle" style="font-size: small">
                                                        {{ $order->branch->name }}
                                                    </td>
                                                    <td class="align-middle" style="font-size: small">
                                                        {{ number_format($order->total_price, 0, ',', '.') }}
                                                    </td>
                                                    <td class="align-middle" style="font-size: small">
                                                        @if ($order->status == 'success')
                                                            <span class="fw-bold text-success">{{ $order->status }}</span>
                                                        @elseif ($order->status == 'pending')
                                                            <span class="fw-bold text-warning">{{ $order->status }}</span>
                                                        @else
                                                            <span class="fw-bold text-danger">{{ $order->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle" style="font-size: small">
                                                        <span
                                                            class="text-primary">{{ \Carbon\Carbon::parse($order->created_at)->format('H:i') }}</span>
                                                        <br>
                                                        {{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y') }}
                                                    </td>
                                                    <td class="align-middle" style="font-size: small">
                                                        <button class="btn btn-info order-details-button font-12 text-white"
                                                            data-order="{{ json_encode($order->orderDetails) }}"
                                                            data-total-price="{{ $order->total_price }}" id="myBtn">
                                                            <i class="fas fa-info-circle"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Pesanan -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Detail Pesanan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Order Details List -->
                    <ul id="order-details-list" class="list-group">
                        <!-- Data akan dimasukkan lewat JavaScript -->
                    </ul>
                    <p id="total-price" class="mt-3"><strong>Total Harga:</strong> <span>Rp 0</span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Event delegation untuk tombol order details
            document.addEventListener("click", function(event) {
                if (event.target.closest(".order-details-button")) {
                    const button = event.target.closest(".order-details-button");
                    const orderDetails = JSON.parse(button.getAttribute("data-order"));
                    const totalPrice = button.getAttribute("data-total-price");
                    const orderDetailsList = document.getElementById("order-details-list");
                    const totalPriceElement = document.getElementById("total-price").querySelector("span");

                    // Kosongkan list sebelum mengisi ulang
                    orderDetailsList.innerHTML = "";

                    if (orderDetails.length === 0) {
                        orderDetailsList.innerHTML =
                            `<li class="list-group-item text-center">Tidak ada data pesanan</li>`;
                    } else {
                        orderDetails.forEach((item, index) => {
                            orderDetailsList.innerHTML += `
                        <li class="list-group-item">
                            <strong>${index + 1}. ${item.menu_name}</strong> <br>
                            Jumlah: ${item.quantity} <br>
                            Harga: Rp ${parseInt(item.menu_price).toLocaleString('id-ID')} <br>
                            Catatan: ${item.note ? item.note : '-'}
                        </li>
                    `;
                        });
                    }

                    // Tampilkan total harga
                    totalPriceElement.innerText = `Rp ${parseInt(totalPrice).toLocaleString('id-ID')}`;

                    // Tampilkan modal
                    $("#myModal").modal("show");
                }
            });

            document.getElementById('search').addEventListener('keyup', function() {
                let query = this.value;
                let branchOutletId = new URLSearchParams(window.location.search).get('branch_id') || '';

                fetch(
                        `{{ route('dashboard.orders.search') }}?query=${encodeURIComponent(query)}&branch_id=${encodeURIComponent(branchOutletId)}`
                    )
                    .then(response => response.json())
                    .then(data => {
                        let tableBody = document.querySelector('table tbody');
                        tableBody.innerHTML = '';

                        if (data.length === 0) {
                            tableBody.innerHTML =
                                '<tr><td colspan="7" class="text-center">Tidak ditemukan adanya pesanan</td></tr>';
                        } else {
                            data.forEach((order, index) => {
                                let statusClass = order.status === 'success' ? 'text-success' :
                                    order.status === 'pending' ? 'text-warning' : 'text-danger';

                                let formattedPrice = new Intl.NumberFormat('id-ID', {
                                    style: 'decimal',
                                    maximumFractionDigits: 0
                                }).format(order.total_price);

                                let formattedDate = new Date(order.created_at).toLocaleString(
                                    'id-ID', {
                                        day: '2-digit',
                                        month: 'short',
                                        year: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    });

                                tableBody.innerHTML += `
                        <tr>
                            <th scope="row" class="align-middle" style="font-size: small">${index + 1}</th>
                            <td class="align-middle" style="font-size: small">${order.user ? order.user.fullname : '-'}</td>
                            <td class="align-middle" style="font-size: small">${order.branch ? order.branch.name : '-'}</td>
                            <td class="align-middle" style="font-size: small">${formattedPrice}</td>
                            <td class="align-middle" style="font-size: small"><span class="fw-bold ${statusClass}">${order.status}</span></td>
                            <td class="align-middle" style="font-size: small">${formattedDate}</td>
                            <td class="align-middle" style="font-size: small">
                                <button class="btn btn-info font-12 text-white order-details-button"
                                    data-order='${JSON.stringify(order.orderDetails)}'
                                    data-total-price='${order.total_price}'>
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                            });
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    </script>
@endsection
