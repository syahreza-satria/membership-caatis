@extends('dashboards.layouts.main')

@section('styles')
    <style>
        .popup {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%) scale(0.5);
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
            opacity: 0;
        }

        .popup.show {
            display: block;
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
        }

        .popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .popup-body {
            margin-top: 10px;
        }

        .popup-body ul {
            list-style-type: none;
            padding-left: 0;
        }

        .popup-body ul li {
            padding: 5px 0;
            border-bottom: 1px solid #ddd;
        }

        .popup-body ul li:last-child {
            border-bottom: none;
        }

        .close-btn {
            cursor: pointer;
            font-size: 20px;
        }
    </style>
@endsection

@section('container')
    <div class="container mt-3">
        <h2>Orders</h2>
        <hr>

        <!-- Form Pencarian -->
        <div class="mb-4">
            <div class="input-group">
                <input type="text" id="search" class="form-control" placeholder="Cari menggunakan nama lengkap"
                    value="{{ old('search') }}">
            </div>
        </div>

        <!-- Filter by Branch -->
        <div class="mb-4">
            <div class="input-group">
                <select id="branchFilter" class="form-control">
                    <option value="">Semua Cabang</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if ($orders->isEmpty())
            <p>No orders found.</p>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Lengkap</th>
                        <th>Cabang</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Pesanan Dibuat</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user->fullname }}</td>
                            <td>{{ $order->branch->name }}</td>
                            <td>{{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td>
                                @if ($order->status == 'success')
                                    <span class="fw-bold text-success">{{ $order->status }}</span>
                                @elseif ($order->status == 'pending')
                                    <span class="fw-bold text-warning">{{ $order->status }}</span>
                                @else
                                    <span class="fw-bold text-danger">{{ $order->status }}</span>
                                @endif
                            </td>
                            <td>{{ $order->created_at }}</td>
                            <td>
                                <button class="btn btn-info order-details-button"
                                    data-order="{{ json_encode($order->orderDetails) }}"
                                    data-total-price="{{ $order->total_price }}">View</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Popup untuk detail pesanan -->
    <div id="orderDetailsPopup" class="popup">
        <div class="popup-header">
            <h5 class="popup-title">Order Details</h5>
            <span id="closePopup" class="close-btn">&times;</span>
        </div>
        <div class="popup-body">
            <ul id="order-details-list">
                <!-- Order details will be loaded here -->
            </ul>
            <p id="total-points" class="mt-3"><strong>Total Points Earned:</strong> <span></span></p>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function formatCurrency(value) {
                return 'Rp' + new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 0
                }).format(value);
            }

            function formatDateTime(dateTime) {
                const options = {
                    year: 'numeric',
                    month: 'short',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false,
                };
                return new Date(dateTime).toLocaleDateString('id-ID', options);
            }

            function calculatePoints(totalPrice) {
                return Math.floor(totalPrice / 10000);
            }

            function showOrderDetails(orderDetails, totalPrice) {
                var detailsList = document.getElementById('order-details-list');

                // Clear previous details
                detailsList.innerHTML = '';

                // Populate popup with order details
                orderDetails.forEach(function(detail) {
                    var listItem = document.createElement('li');
                    listItem.textContent = detail.menu_name + ' (' + 'x' + detail.quantity + ')' + ' - ' +
                        formatCurrency(detail.menu_price);
                    detailsList.appendChild(listItem);
                });

                // Calculate and display total points earned
                var totalPoints = calculatePoints(totalPrice);
                document.getElementById('total-points').querySelector('span').textContent = totalPoints;

                // Show the popup with animation
                document.getElementById('orderDetailsPopup').classList.add('show');
            }

            document.querySelectorAll('.order-details-button').forEach(button => {
                button.addEventListener('click', function() {
                    var orderDetails = JSON.parse(this.getAttribute('data-order'));
                    var totalPrice = this.getAttribute('data-total-price');
                    showOrderDetails(orderDetails, totalPrice);
                });
            });

            document.getElementById('closePopup').addEventListener('click', function() {
                document.getElementById('orderDetailsPopup').classList.remove('show');
            });

            document.getElementById('search').addEventListener('input', function() {
                var query = this.value;
                var branch_id = document.getElementById('branchFilter').value;

                fetch('{{ route('dashboard.orders.search') }}?query=' + query + '&branch_id=' + branch_id)
                    .then(response => response.json())
                    .then(orders => {
                        var tbody = document.querySelector('table tbody');
                        tbody.innerHTML = '';

                        orders.forEach(order => {
                            var row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${order.id}</td>
                                <td>${order.user.fullname}</td>
                                <td>${order.branch.name}</td>
                                <td>${formatCurrency(order.total_price)}</td>
                                <td>${order.status}</td>
                                <td>${formatDateTime(order.created_at)}</td>
                                <td>
                                    <button class="btn btn-info order-details-button" data-order='${JSON.stringify(order.order_details)}' data-total-price="${order.total_price}">View</button>
                                </td>
                            `;
                            tbody.appendChild(row);

                            row.querySelector('.order-details-button').addEventListener('click',
                                function() {
                                    var orderDetails = JSON.parse(this.getAttribute(
                                        'data-order'));
                                    var totalPrice = this.getAttribute('data-total-price');
                                    showOrderDetails(orderDetails, totalPrice);
                                });
                        });
                    });
            });

            document.getElementById('branchFilter').addEventListener('change', function() {
                var branch_id = this.value;
                var query = document.getElementById('search').value;

                fetch('{{ route('dashboard.orders.search') }}?query=' + query + '&branch_id=' + branch_id)
                    .then(response => response.json())
                    .then(orders => {
                        var tbody = document.querySelector('table tbody');
                        tbody.innerHTML = '';

                        orders.forEach(order => {
                            var row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${order.id}</td>
                                <td>${order.user.fullname}</td>
                                <td>${order.branch.name}</td>
                                <td>${formatCurrency(order.total_price)}</td>
                                <td>${order.status}</td>
                                <td>${formatDateTime(order.created_at)}</td>
                                <td>
                                    <button class="btn btn-info order-details-button" data-order='${JSON.stringify(order.order_details)}' data-total-price="${order.total_price}">View</button>
                                </td>
                            `;
                            tbody.appendChild(row);

                            row.querySelector('.order-details-button').addEventListener('click',
                                function() {
                                    var orderDetails = JSON.parse(this.getAttribute(
                                        'data-order'));
                                    var totalPrice = this.getAttribute('data-total-price');
                                    showOrderDetails(orderDetails, totalPrice);
                                });
                        });
                    });
            });
        });
    </script>
@endsection
