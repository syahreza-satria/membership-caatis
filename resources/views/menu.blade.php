@extends('layouts.layout-main')

@section('pemesanan')
<main class="mx-auto justify-content-center main-content main h-100">
    {{-- Daftar Menu --}}
    @forelse($data as $categoryId => $menus)
    <h2 class="font-20 fw-bold mb-2 text-uppercase">{{ getCategoryName($categoryId) }}</h2>
    <div class="mb-3" style="border-top: 1px dashed #d5d5d5"></div>

    @foreach ($menus as $menu)
    <div class="w-100 mb-3">
        <div class="mb-1 d-flex justify-content-between">
            <div>
                <h3 class="font-14 mb-0 fw-semibold">{{ $menu['menu_name'] }}</h3>
                <p class="font-12 fw-semibold mt-3">Rp {{ number_format($menu['menu_price'], 0, ',', '.') }}</p>
            </div>
            <img src="/img/CabangLakeside.png" class="rounded-3" alt="Menu" width="60" height="60">
        </div>
        <div class="text-end">
            <div class="order-controls" data-name="{{ $menu['menu_name'] }}" data-price="{{ $menu['menu_price'] }}">
                <button class="font-10 color-primary fw-bold bg-white rounded-4 text-center tambah-menu" style="border: 1px solid #14b8a6; padding: 4px 12px 4px 11px">Tambah</button>
            </div>
        </div>
        <hr>
    </div>
    @endforeach
    <div class="bottom-0 pt-5"></div>
    @empty
    <div class="d-flex justify-content-center align-items-center w-100" style="height: 60vh;">
        <p class="font-20 fw-bold text-center">Tidak ada Menu tersedia saat ini <br> 🥲🥲🥲</p>
    </div>
    @endforelse

    {{-- Basket Button --}}
    <div class="container">
        <div class="fixed-bottom" style="margin-bottom: 24px; display: none;" id="basket-button">
            <button type="button" class="d-flex mx-auto background-primary rounded-3 justify-content-between text-white border-0" style="height: 33px; width: 350px" id="show-basket">
                <div class="d-flex my-auto">
                    <i class="bi bi-cart4 ms-3 my-auto" style="font-size: 1rem; color:white"></i>
                    <h5 class="font-14 fw-semibold ms-2 my-auto" id="basket-items-count">0 Barang</h5>
                </div>
                <h5 class="font-14 me-3 my-auto fw-semibold" id="basket-total-price">Rp 0</h5>
            </button>
        </div>
    </div>
</main>

{{-- Modal for Order Summary --}}
<div class="modal fade" id="basketModal" tabindex="-1" aria-labelledby="basketModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="basketModalLabel">Ringkasan Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul id="order-summary"></ul>
                <h5>Total: <span id="order-total">Rp 0</span></h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-success" style="background-color: #14b8a6" id="place-order">Pesan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@php
function getCategoryName($categoryId) {
    $categories = [
        1 => 'Kopi',
        2 => 'Susu',
        3 => 'Coklat',
        4 => 'Jus',
        5 => 'Teh',
        6 => 'Air Mineral',
        9 => 'Roti',
        13 => 'Lainnya'
    ];

    return $categories[$categoryId] ?? 'Kategori Tidak Dikenal';
}
@endphp

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const basketButton = document.getElementById('basket-button');
    const basketItemsCount = document.getElementById('basket-items-count');
    const basketTotalPrice = document.getElementById('basket-total-price');
    const orderSummary = document.getElementById('order-summary');
    const orderTotal = document.getElementById('order-total');
    const showBasketButton = document.getElementById('show-basket');

    let itemCount = 0;
    let totalPrice = 0;
    let orderItems = {};

    document.querySelectorAll('.tambah-menu').forEach(button => {
        button.addEventListener('click', function() {
            const parent = this.parentElement;
            const menu_name = parent.getAttribute('data-name');
            const price = parseInt(parent.getAttribute('data-price'));

            if (orderItems[menu_name]) {
                orderItems[menu_name].quantity++;
            } else {
                orderItems[menu_name] = { menu_name, price, quantity: 1 };
            }

            itemCount++;
            totalPrice += price;

            updateOrder();

            parent.innerHTML = `
                <button class="decrease-quantity font-10 color-primary fw-bold bg-white rounded-4 text-center" style="border: 1px solid #14b8a6; padding: 4px 12px 4px 11px">-</button>
                <span class="quantity-display font-14 fw-semibold mx-2">${orderItems[menu_name].quantity}</span>
                <button class="increase-quantity font-10 color-primary fw-bold bg-white rounded-4 text-center" style="border: 1px solid #14b8a6; padding: 4px 12px 4px 11px">+</button>
            `;

            attachQuantityControlListeners(parent, menu_name, price);
        });
    });

    function attachQuantityControlListeners(parent, menu_name, price) {
        parent.querySelector('.increase-quantity').addEventListener('click', function() {
            orderItems[menu_name].quantity++;
            itemCount++;
            totalPrice += price;
            updateOrder();
            parent.querySelector('.quantity-display').textContent = orderItems[menu_name].quantity;
        });

        parent.querySelector('.decrease-quantity').addEventListener('click', function() {
            if (orderItems[menu_name].quantity > 1) {
                orderItems[menu_name].quantity--;
                itemCount--;
                totalPrice -= price;
                parent.querySelector('.quantity-display').textContent = orderItems[menu_name].quantity;
            } else {
                delete orderItems[menu_name];
                itemCount--;
                totalPrice -= price;
                parent.innerHTML = `
                    <button class="font-10 color-primary fw-bold bg-white rounded-4 text-center tambah-menu" style="border: 1px solid #14b8a6; padding: 4px 12px 4px 11px">Tambah</button>
                `;
                parent.querySelector('.tambah-menu').addEventListener('click', function() {
                    const parent = this.parentElement;
                    const menu_name = parent.getAttribute('data-name');
                    const price = parseInt(parent.getAttribute('data-price'));

                    if (orderItems[menu_name]) {
                        orderItems[menu_name].quantity++;
                    } else {
                        orderItems[menu_name] = { menu_name, price, quantity: 1 };
                    }

                    itemCount++;
                    totalPrice += price;

                    updateOrder();

                    parent.innerHTML = `
                        <button class="decrease-quantity font-10 color-primary fw-bold bg-white rounded-4 text-center" style="border: 1px solid #14b8a6; padding: 4px 12px 4px 11px">-</button>
                        <span class="quantity-display font-14 fw-semibold mx-2">${orderItems[menu_name].quantity}</span>
                        <button class="increase-quantity font-10 color-primary fw-bold bg-white rounded-4 text-center" style="border: 1px solid #14b8a6; padding: 4px 12px 4px 11px">+</button>
                    `;

                    attachQuantityControlListeners(parent, menu_name, price);
                });
            }
            updateOrder();
        });
    }

    showBasketButton.addEventListener('click', function() {
        orderSummary.innerHTML = '';
        Object.values(orderItems).forEach(item => {
            orderSummary.innerHTML += `<li>${item.menu_name} x${item.quantity} - Rp ${new Intl.NumberFormat('id-ID').format(item.price * item.quantity)}</li>`;
        });
        orderTotal.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(totalPrice)}`;
        new bootstrap.Modal(document.getElementById('basketModal')).show();
    });

    function updateOrder() {
        basketItemsCount.textContent = `${itemCount} Barang`;
        basketTotalPrice.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(totalPrice)}`;
        basketButton.style.display = itemCount > 0 ? 'block' : 'none';
    }

    document.getElementById('place-order').addEventListener('click', function() {
        console.log(orderItems);
        fetch('{{ route('order.create') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ order: orderItems })
        }).then(response => {
            if (!response.ok) {
                return response.text().then(text => { throw new Error(text) });
            }
            return response.json();
        }).then(data => {
            if (data.success) {
                window.location.href = '{{ route('order.success') }}';
            }
        }).catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses pesanan Anda.');
        });
    });
});
</script>
@endsection
