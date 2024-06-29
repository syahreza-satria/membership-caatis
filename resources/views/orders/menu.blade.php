@extends('layouts.layout-main')

@section('pemesanan')
    <main class="mx-auto justify-content-center main-content main h-100">
        {{-- Daftar Menu --}}
        @forelse($data as $categoryId => $menus)
            <h2 class="font-20 fw-bold mb-2 text-uppercase">{{ $menus[0]['category_name'] }}</h2>
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
                        <div class="order-controls" data-name="{{ $menu['menu_name'] }}"
                            data-price="{{ $menu['menu_price'] }}" data-category-id="{{ $menu['category_id'] }}"
                            data-category-name="{{ $menu['category_name'] }}">
                            <button class="font-10 color-primary fw-bold bg-white rounded-4 text-center tambah-menu"
                                style="border: 1px solid #14b8a6; padding: 4px 12px 4px 11px">Tambah</button>
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

        {{-- Modals --}}
        <div class="bottom-drawer d-flex flex-column" id="orderDrawer">
            <div class="drawer-header d-flex justify-content-between">
                <h5 class="modal-title">Tambahkan ke Keranjang</h5>
                <button type="button" class="btn-close" id="closeDrawer">&times;</button>
            </div>
            <div class="drawer-body flex-grow-1">
                <h5 id="drawer-menu-name"></h5>
                <p id="drawer-menu-price"></p>
                <div class="mb-3">
                    <label for="orderNote" class="form-label">Catatan</label>
                    <textarea class="form-control" id="orderNote" rows="3"></textarea>
                </div>
            </div>
            <div class="drawer-footer d-flex justify-content-between align-items-center">
                <div class="d-flex flex-column">
                    <div class="qty-controls d-flex align-items-center mb-2">
                        <button class="btn btn-outline-secondary btn-sm me-2" id="decreaseQty">-</button>
                        <input type="number" class="form-control form-control-sm text-center" id="qtyInput" value="1"
                            style="width: 50px;" readonly>
                        <button class="btn btn-outline-secondary btn-sm ms-2" id="increaseQty">+</button>
                    </div>
                    <div class="d-flex justify-content-between">
                        <h5 class="fw-semibold">Total:</h5>
                        <h5 class="fw-semibold" id="drawer-total-price">Rp 0</h5>
                    </div>
                </div>
                <input type="hidden" id="branchId" value="{{ $branch_id }}">
                <button type="button" class="btn btn-primary" id="addToCartButton">Tambahkan ke Keranjang</button>
            </div>
        </div>

        <!-- Basket Button -->
        <div class="container">
            <div class="fixed-bottom" style="margin-bottom: 24px; display: none;" id="basket-button">
                <button type="button"
                    class="d-flex mx-auto background-primary rounded-3 justify-content-between text-white border-0"
                    style="height: 33px; width: 350px" id="show-basket">
                    <div class="d-flex my-auto">
                        <i class="bi bi-cart4 ms-3 my-auto" style="font-size: 1rem; color:white"></i>
                        <h5 class="font-14 fw-semibold ms-2 my-auto" id="basket-items-count">0 Barang</h5>
                    </div>
                    <h5 class="font-14 me-3 my-auto fw-semibold" id="basket-total-price">Rp 0</h5>
                </button>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let basket = @json(Session::get('basket', []));
            let currentItem = null;
            let qty = 1;

            document.querySelectorAll('.tambah-menu').forEach(button => {
                button.addEventListener('click', function() {
                    const menuName = this.parentElement.getAttribute('data-name');
                    const menuPrice = parseInt(this.parentElement.getAttribute('data-price'));
                    const categoryId = this.parentElement.getAttribute('data-category-id');
                    const categoryName = this.parentElement.getAttribute('data-category-name');

                    // Set current item
                    currentItem = {
                        menu_name: menuName,
                        menu_price: menuPrice,
                        category_id: categoryId,
                        category_name: categoryName,
                        quantity: 1
                    };

                    // Set modal content
                    document.getElementById('drawer-menu-name').textContent = menuName;
                    document.getElementById('drawer-menu-price').textContent =
                        `Rp ${menuPrice.toLocaleString()}`;
                    document.getElementById('drawer-total-price').textContent =
                        `Rp ${menuPrice.toLocaleString()}`;
                    document.getElementById('orderNote').value = '';
                    document.getElementById('qtyInput').value = 1;

                    // Show drawer
                    document.getElementById('orderDrawer').style.bottom = '0';
                });
            });

            document.getElementById('decreaseQty').addEventListener('click', function() {
                if (qty > 1) {
                    qty--;
                    currentItem.quantity = qty;
                    document.getElementById('qtyInput').value = qty;
                    updateTotalPrice();
                }
            });

            document.getElementById('increaseQty').addEventListener('click', function() {
                qty++;
                currentItem.quantity = qty;
                document.getElementById('qtyInput').value = qty;
                updateTotalPrice();
            });

            document.getElementById('addToCartButton').addEventListener('click', function() {
                qty = 1;
                currentItem.note = document.getElementById('orderNote').value;

                // Dapatkan branch_id dari elemen hidden input atau elemen lain
                const branchIdElement = document.getElementById('branchId');

                if (branchIdElement) {
                    let branch_id = branchIdElement.value;

                    // Tambahkan branch_id ke sessionStorage
                    sessionStorage.setItem('branch_id', branch_id);

                    // Tambahkan branch_id ke currentItem jika diperlukan
                    currentItem.branch_id = branch_id;

                    // Log branch_id untuk memastikan sudah ada
                    console.log('Branch ID:', branch_id);
                    console.log('Current Item:', currentItem);

                    basket.push(currentItem);

                    // Update basket button
                    updateBasketButton();

                    // Hide drawer
                    document.getElementById('orderDrawer').style.bottom = '-100%';

                    // Save basket to session
                    saveBasketToSession(basket);
                } else {
                    console.error('Branch ID element not found');
                }
            });

            function updateTotalPrice() {
                const totalPrice = currentItem.menu_price * currentItem.quantity;
                document.getElementById('drawer-total-price').textContent = `Rp ${totalPrice.toLocaleString()}`;
            }

            function updateBasketButton() {
                const totalItems = basket.reduce((sum, item) => {
                    const quantity = parseInt(item.quantity);
                    return sum + quantity;
                }, 0);

                const totalPrice = basket.reduce((sum, item) => {
                    const price = parseInt(item.menu_price);
                    const quantity = parseInt(item.quantity);
                    return sum + (price * quantity);
                }, 0);

                document.getElementById('basket-items-count').textContent = `${totalItems} Barang`;
                document.getElementById('basket-total-price').textContent = `Rp ${totalPrice.toLocaleString()}`;

                if (totalItems > 0) {
                    document.getElementById('basket-button').style.display = 'block';
                } else {
                    document.getElementById('basket-button').style.display = 'none';
                }
            }

            function saveBasketToSession(basket) {
                // Dapatkan branch_id dari sessionStorage
                let branch_id = sessionStorage.getItem('branch_id');

                fetch('/order/save-basket', {
                        method: 'POST',
                        body: JSON.stringify({
                            basket: basket,
                            branch_id: branch_id
                        }),
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    }).then(response => response.json())
                    .then(data => {
                        console.log('Save basket response:', data);
                    })
                    .catch(error => console.error('Error:', error));
            }

            document.getElementById('show-basket').addEventListener('click', function() {
                const orderDetails = JSON.stringify(basket);
                const formData = new FormData();
                formData.append('orderDetails', orderDetails);

                fetch('/order/add-to-cart', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = data.redirect;
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });

            // Event untuk menutup drawer
            document.getElementById('closeDrawer').addEventListener('click', function() {
                document.getElementById('orderDrawer').style.bottom = '-100%';
            });

            // Initialize basket button on page load
            updateBasketButton();
        });
    </script>
@endsection
