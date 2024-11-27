@extends('layouts.layout-main')

@section('pemesanan')
    <div id="backdrop" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 10;"></div>

    <section class="px-2 py-4 mx-auto" style="max-width: 420px">
        <div class="d-flex text-dark text-start ms-3">
            <img src="{{ Storage::url($branch->logo) }}" style="border: 1px solid #14B8A6" class="rounded-3" alt="{{ $branch->name }}" height="70" width="70">
            <div class="ms-2">
                <h3 class="font-16 fw-bold mb-2">{{ $branch->name }}</h3>
                <p class="m-0" style="font-size:13px;"><i class="bi bi-geo-alt-fill me-1" style="color: #ffc800"></i>{{ $branch->address }}</p>
                <span class="badge rounded-pill bg-primary" style="background: linear-gradient(90deg, rgb(33, 107, 196) 0%, rgb(0, 110, 242) 100%);"><i class="bi bi-check-lg"></i> Verified Outlet</span>
            </div>
        </div>
    </section>
    <div class="mx-auto" style="max-width: 420px; border-top: 1px solid rgb(232, 232, 232)"></div>

    <main class="mx-auto justify-content-center main-content main h-100">
        {{-- Daftar Menu --}}
        @forelse($data as $categoryId => $menus)
            <h2 class="font-20 fw-bold mb-2 text-uppercase" style="color: #14B8A6;">{{ $menus[0]['category_name'] }}</h2>
            <div class="mb-3" style="border-top: 1px dashed #d5d5d5;"></div>
            <div class="px-2">
                @foreach ($menus as $menu)
                    <div class="w-100 mb-4 p-3 rounded shadow-sm bg-white" style="transition: transform 0.3s, box-shadow 0.3s;">
                        <div class="mb-1 d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="font-14 mb-1 fw-semibold">{{ $menu['name'] }}</h3>
                                @if ($menu['is_active'] == 0)
                                    <span class="font-12 text-muted">(Stok Habis)</span>
                                @endif
                                <p class="font-12 text-muted mt-2">{{ $menu['description'] }}</p>
                            </div>
                            <img src="{{ isset($menu['image']) && !empty($menu['image']) ? 'https://pos.lakesidefnb.group/storage/' . $menu['image'] : asset('img/default-image.png') }}"
                                class="menu-img rounded" alt="{{ $menu['name'] }}"
                                style="width: 60px; height: 60px; object-fit: cover; border: 2px solid #14B8A6; border-radius: 0.5rem;">
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="font-12 fw-semibold mb-0" style="color: {{ $menu['is_active'] == 0 ? '#d3d3d3' : '#14b8a6' }};">Rp
                                {{ number_format($menu['variants'][0]['price'], 0, ',', '.') }}</p>
                            <div class="order-controls" data-name="{{ $menu['name'] }}"
                                data-price="{{ $menu['variants'][0]['price'] }}" data-category-id="{{ $menu['category_id'] }}"
                                data-category-name="{{ $menu['category_name'] }}" data-image="https://pos.lakesidefnb.group/storage/{{ $menu['image'] }}">
                                <button class="font-10 fw-bold text-center tambah-menu"
                                    style="padding: 4px 12px; background-color: {{ $menu['is_active'] == 0 ? '#d3d3d3' : '#14b8a6' }}; color: white; border: none; border-radius: 20px; transition: background-color 0.3s;" {{ $menu['is_active'] == 0 ? 'disabled' : '' }}>
                                    Tambah
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="bottom-0 pt-5"></div>
        @empty
            <div class="d-flex justify-content-center align-items-center w-100" style="height: 60vh;">
                <p class="font-20 fw-bold text-center">Tidak ada Menu tersedia saat ini <br> 🥲🥲🥲</p>
            </div>
        @endforelse

        {{-- Modals --}}
        <div class="bottom-drawer d-flex flex-column" id="orderDrawer"
            style="border-top-left-radius: 20px; border-top-right-radius: 20px;">
            <div id="drawerHandle" class="d-flex justify-content-center">
                <div style="width: 32px; height: 2px; background: rgb(196, 196, 196); border-radius: 100px; margin: auto auto 16px; margin-top: 5px"></div>
            </div>
            <div class="drawer-body flex-grow-1">
                <img src="" class="img-fluid rounded" id="drawer-menu-image" alt="Menu Image">
                <h5 id="drawer-menu-name" class="fw-bolder mt-2"></h5>
                <p id="drawer-menu-price" class="fw-bolder"></p>
                <div class="mb-3">
                    <label for="orderNote" class="form-label">Notes</label>
                    <div class="textarea-wrapper">
                        <i class="bi bi-journal-plus"></i>
                        <textarea class="form-control notes" id="orderNote" rows="1" placeholder="Tambah catatan..."></textarea>
                    </div>
                </div>
            </div>
            <hr class="divider"/>
            <div class="drawer-footer" style="padding-top: 10px;">
                <div class="d-flex justify-content-between mb-3">
                    <h6>Jumlah</h6>
                    <div class="qty-controls d-flex align-items-center mb-2">
                        <button class="btn btn-outline-secondary btn-sm me-2 rounded-pill" id="decreaseQty">-</button>
                        <input type="number" class="form-control form-control-sm text-center" id="qtyInput" value="1"
                            style="width: 50px;" readonly>
                        <button class="btn btn-outline-secondary btn-sm rounded-pill ms-2" id="increaseQty">+</button>
                    </div>
                </div>
                
                <input type="hidden" id="branchId" value="{{ $branch_id }}">
                <button type="button" class="btn btn-primary rounded-pill w-100" style="font-size: 0.9rem"id="addToCartButton">
                    <div class="d-flex justify-content-between">
                        <span>+ Keranjang</span>
                        <span id="drawer-total-price">Rp 0</span>
                    </div>
                </button>
            </div>
        </div>

        <!-- Basket Button -->
        <div class="container">
            <div class="fixed-bottom" style="margin-bottom: 24px; display: none;" id="basket-button">
                <button type="button" class="d-flex mx-auto rounded-3 justify-content-between text-white border-0"
                    style="background-color: #14b8a6; padding: 8px; width: 350px; height: 45px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);"
                    id="show-basket">
                    <div class="d-flex my-auto">
                        <i class="bi bi-cart4 ms-3 my-auto" style="font-size: 1rem; color: white;"></i>
                        <h5 class="font-14 fw-semibold ms-2 my-auto" id="basket-items-count">0 Barang</h5>
                    </div>
                    <h5 class="font-14 me-3 my-auto fw-semibold" id="basket-total-price">Rp 0</h5>
                </button>
            </div>
        </div>
    </main>
@endsection



@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const backdrop = document.getElementById('backdrop');
            const orderDrawer = document.getElementById('orderDrawer');
            const drawerHandle = document.getElementById('drawerHandle'); // The top area to detect touch
            let basket = @json(Session::get('basket', []));
            let currentItem = null;
            let qty = 1;

            let startY, currentY, drawerOpen = false;

            document.getElementById('orderNote').addEventListener('input', function() {
                this.style.height = 'auto';  
                this.style.height = (this.scrollHeight) + 'px';  
            });

            document.querySelectorAll('.tambah-menu').forEach(button => {
                button.addEventListener('click', function() {
                    const menuName = this.parentElement.getAttribute('data-name');
                    const menuPrice = parseInt(this.parentElement.getAttribute('data-price'));
                    const categoryId = this.parentElement.getAttribute('data-category-id');
                    const categoryName = this.parentElement.getAttribute('data-category-name');
                    const menuImage = this.parentElement.getAttribute('data-image');

                    currentItem = {
                        menu_name: menuName,
                        menu_price: menuPrice,
                        category_id: categoryId,
                        category_name: categoryName,
                        quantity: 1
                    };

                    document.getElementById('drawer-menu-image').src = menuImage;
                    document.getElementById('drawer-menu-name').textContent = menuName;
                    document.getElementById('drawer-menu-price').textContent = `Rp ${menuPrice.toLocaleString()}`;
                    document.getElementById('drawer-total-price').textContent = `Rp ${menuPrice.toLocaleString()}`;
                    document.getElementById('orderNote').value = '';
                    document.getElementById('qtyInput').value = 1;

                    showDrawer(); // Show drawer when menu is clicked
                });
            });

            function showDrawer() {
                orderDrawer.style.transition = 'bottom 0.3s ease';
                orderDrawer.style.bottom = '0';
                backdrop.style.display = 'block';
                document.body.style.overflow = 'hidden'; // Disable scroll
                drawerOpen = true;
            }

            function hideDrawer() {
                orderDrawer.style.transition = 'bottom 0.3s ease';
                orderDrawer.style.bottom = '-100%';
                backdrop.style.display = 'none';
                document.body.style.overflow = ''; // Enable scroll
                drawerOpen = false;
            }

            // Touch events for swipe-to-close functionality (on drawerHandle only)
            drawerHandle.addEventListener('touchstart', function(event) {
                startY = event.touches[0].clientY;
                orderDrawer.style.transition = 'none'; // Disable smooth animation during dragging
            });

            drawerHandle.addEventListener('touchmove', function(event) {
                currentY = event.touches[0].clientY;
                const deltaY = currentY - startY;

                if (deltaY > 0) { // Move only if dragging downwards
                    orderDrawer.style.bottom = `-${deltaY}px`;
                }
            });

            drawerHandle.addEventListener('touchend', function() {
                const deltaY = currentY - startY;

                if (deltaY > 300) { // Threshold for closing
                    hideDrawer();
                } else {
                    showDrawer(); // Reset the drawer to fully open
                }
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

                const branchIdElement = document.getElementById('branchId');

                if (branchIdElement) {
                    let branch_id = branchIdElement.value;
                    sessionStorage.setItem('branch_id', branch_id);
                    currentItem.branch_id = branch_id;
                    basket.push(currentItem);

                    updateBasketButton();

                    hideDrawer(); // Hide drawer after adding item
                    saveBasketToSession(basket);
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

            const isAuthenticated = @json(Auth::check());
            if (!isAuthenticated) {
                document.getElementById('basket-button').addEventListener('click', function() {
                    Swal.fire({
                        title: "Ups, kamu belum login",
                        text: "kamu harus login untuk melanjutkan proses checkout",
                        icon: "info"
                    }).then(() => {
                        window.location.href = '/login';
                    });
                });
            }
            
            function saveBasketToSession(basket) {
                let branch_id = sessionStorage.getItem('branch_id');

                fetch('/order/save-basket', {
                    method: 'POST',
                    body: JSON.stringify({
                        basket: basket,
                        branch_id: branch_id
                    }),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        console.error('Error in saving basket:', response);
                    }
                }).then(data => {
                    console.log('Save basket response:', data);
                }).catch(error => console.error('Error:', error));
            }

            document.getElementById('show-basket').addEventListener('click', function() {
                const orderDetails = JSON.stringify(basket);
                const formData = new FormData();
                formData.append('orderDetails', orderDetails);

                fetch('/order/add-to-cart', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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

            updateBasketButton();

            backdrop.addEventListener('click', function() {
                hideDrawer(); // Hide drawer if backdrop is clicked
            });
        });


    </script>
@endsection
