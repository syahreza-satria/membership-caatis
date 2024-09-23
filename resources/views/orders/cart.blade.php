@extends('layouts.layout-auth')

@section('main')
    <main class="mx-auto justify-content-center main-content main h-100">
        <div class="d-flex justify-content-start align-items-center mb-3 gap-2" style="height: 30px;">
            <button type="button" id="backButton" class="btn" style="line-height: 0;">
                <i class="bi bi-arrow-left-circle-fill" style="font-size: 1.5rem; color: #14B8A6;"></i>
            </button>
            <h2 class="font-20 fw-bold text-uppercase" style="color: #14B8A6; margin-bottom: 0;">Keranjang Belanja</h2>
        </div>


        <div class="mb-3" style="border-top: 1px dashed #d5d5d5;"></div>

        <form id="checkout-form" method="POST" action="{{ route('checkout') }}">
            @csrf
            {{-- Check if branch_id is set before displaying --}}
            @if (isset($outletId))
                <input type="hidden" name="outletId" value="{{ $outletId }}">
            @endif
            <input type="hidden" name="outletId" value="{{ $outletId }}">

            @forelse($orderDetails as $index => $item)
                <div class="w-100 mb-3 p-3 rounded shadow-sm bg-white" style="transition: transform 0.3s, box-shadow 0.3s;"
                    data-index="{{ $index }}">
                    <div class="mb-1 d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="font-14 mb-0 fw-semibold">{{ $item['menu_name'] }}</h3>
                            <p class="font-12 fw-semibold mt-3">Rp {{ number_format($item['menu_price'], 0, ',', '.') }}</p>
                            <p class="font-12 mt-1 text-muted">{{ $item['category_name'] }}</p>
                        </div>
                        <img src="{{ isset($item['image']) ? 'https://pos.lakesidefnb.group/storage/' . $item['image'] : asset('img/default-image.png') }}"
                            class="rounded-3" alt="Menu" width="60" height="60"
                            style="border: 2px solid #14B8A6;">
                    </div>
                    <div class="mb-3">
                        <label for="note-{{ $index }}" class="form-label">Catatan</label>
                        <textarea class="form-control" id="note-{{ $index }}" name="orderDetails[{{ $index }}][note]"
                            rows="2">{{ $item['note'] ?? '' }}</textarea>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="d-flex align-items-center mb-3">
                            <button type="button" class="btn btn-outline-secondary btn-sm me-2 decrease-qty"
                                data-index="{{ $index }}">-</button>
                            <input type="number" name="orderDetails[{{ $index }}][quantity]"
                                class="form-control form-control-sm text-center quantity-input"
                                value="{{ $item['quantity'] }}" style="width: 50px;">
                            <button type="button" class="btn btn-outline-secondary btn-sm ms-2 increase-qty"
                                data-index="{{ $index }}">+</button>
                        </div>
                        <div class="ms-auto">
                            <button type="button" class="btn btn-danger btn-sm remove-item d-flex align-items-center"
                                data-index="{{ $index }}">
                                <i class="bi bi-trash-fill me-1"></i> Hapus
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="orderDetails[{{ $index }}][menu_name]"
                        value="{{ $item['menu_name'] }}">
                    <input type="hidden" name="orderDetails[{{ $index }}][menu_price]"
                        value="{{ $item['menu_price'] }}">
                    <input type="hidden" name="orderDetails[{{ $index }}][category_id]"
                        value="{{ $item['category_id'] }}">
                    <input type="hidden" name="orderDetails[{{ $index }}][category_name]"
                        value="{{ $item['category_name'] }}">
                    <input type="hidden" name="orderDetails[{{ $index }}][menu_id]"
                        value="{{ $item['menu_id'] }}">
                </div>
            @empty
                <div class="d-flex justify-content-center align-items-center w-100" style="height: 60vh;">
                    <p class="font-20 fw-bold text-center">Keranjang Anda kosong</p>
                </div>
            @endforelse
            @if (count($orderDetails) > 0)
                <div class="d-flex justify-content-between align-items-center my-3 p-3"
                    style="background-color: #f8f9fa; border-radius: 8px;">
                    <div>
                        <h4 class="fw-semibold mb-0" style="font-size: 1rem">Total:</h4>
                        <span id="cart-total" style="font-weight: bold; color: #14B8A6; font-size: 1.2rem">Rp 0</span>
                    </div>
                    <div>
                        <button type="button" class="btn btn-success" id="checkoutButton"
                            style="padding: 10px 20px; background-color: #14B8A6; border: none; border-radius: 5px; font-size: 1rem">Checkout</button>
                    </div>
                </div>
            @endif
        </form>
    </main>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const outletId = '{{ $outletId }}';

            function updateCartTotal() {
                let total = 0;
                document.querySelectorAll('.quantity-input').forEach((input) => {
                    const price = parseFloat(input.closest('.w-100').querySelector(
                        'input[name$="[menu_price]"]').value);
                    const quantity = parseInt(input.value);
                    total += price * quantity;
                });
                document.getElementById('cart-total').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(
                    total);
            }

            updateCartTotal(); // Hitung total saat pertama kali halaman dimuat

            // Fungsi untuk memperbarui indeks array setelah penghapusan item
            function updateIndices() {
                document.querySelectorAll('.w-100.mb-3').forEach((itemDiv, newIndex) => {
                    // Update semua atribut terkait indeks dengan newIndex yang benar
                    itemDiv.setAttribute('data-index', newIndex);
                    itemDiv.querySelectorAll('input, textarea, button').forEach(input => {
                        const oldName = input.getAttribute('name');
                        if (oldName) {
                            // Replace old index in the name attribute
                            const newName = oldName.replace(/\[\d+\]/, `[${newIndex}]`);
                            input.setAttribute('name', newName);
                        }
                        if (input.classList.contains('decrease-qty') || input.classList.contains(
                                'increase-qty') || input.classList.contains('remove-item')) {
                            input.setAttribute('data-index', newIndex);
                        }
                    });
                });
            }

            document.querySelectorAll('.decrease-qty').forEach(button => {
                button.addEventListener('click', function() {
                    const index = this.getAttribute('data-index');
                    const quantityInput = document.querySelector(
                        `input[name='orderDetails[${index}][quantity]']`);
                    let quantity = parseInt(quantityInput.value);
                    if (quantity > 1) {
                        quantity--;
                        quantityInput.value = quantity;
                        updateCart(); // Panggil update cart setelah perubahan
                    }
                });
            });

            document.querySelectorAll('.increase-qty').forEach(button => {
                button.addEventListener('click', function() {
                    const index = this.getAttribute('data-index');
                    const quantityInput = document.querySelector(
                        `input[name='orderDetails[${index}][quantity]']`);
                    let quantity = parseInt(quantityInput.value);
                    quantity++;
                    quantityInput.value = quantity;
                    updateCart(); // Panggil update cart setelah perubahan
                });
            });

            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', function() {
                    const index = this.getAttribute('data-index');
                    Swal.fire({
                        title: 'Hapus Item?',
                        text: 'Apakah Anda yakin ingin menghapus item ini dari keranjang?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch('{{ route('removeItem') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        index
                                    })
                                }).then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Hapus elemen item dari DOM
                                        const itemDiv = document.querySelector(
                                            `div[data-index='${index}']`);
                                        if (itemDiv) {
                                            itemDiv.remove();
                                        }
                                        updateCartTotal
                                            (); // Update total ketika item dihapus

                                        // Perbarui indeks setelah penghapusan
                                        updateIndices();

                                        // Redirect ke halaman menu jika tidak ada item tersisa
                                        if (document.querySelectorAll('.w-100.mb-3')
                                            .length === 0) {
                                            window.location =
                                                `{{ route('order.menu', ['outletId' => $outletId]) }}`;
                                        }
                                    }
                                }).catch(error => console.error('Terjadi kesalahan:',
                                    error));
                        }
                    });
                });
            });

            document.getElementById('backButton').addEventListener('click', function() {
                saveOrderDetails().then(() => {
                    window.location = `{{ route('order.menu', ['outletId' => $outletId]) }}`;
                });
            });

            document.getElementById('checkoutButton').addEventListener('click', function() {
                Swal.fire({
                    title: 'Konfirmasi Checkout',
                    text: 'Anda yakin ingin melanjutkan ke proses checkout?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Checkout',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('checkout-form').submit();
                    }
                });
            });

            function saveOrderDetails() {
                const form = document.getElementById('checkout-form');
                const formData = new FormData(form);

                return fetch('{{ route('updateCart') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        console.log('Order details saved successfully');
                    }
                }).catch(error => console.error('Error saving order details:', error));
            }

            function updateCart() {
                const form = document.getElementById('checkout-form');
                const formData = new FormData(form);

                fetch('{{ route('updateCart') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        console.log('Cart updated successfully');
                        updateCartTotal(); // Update total setiap ada perubahan kuantitas
                    }
                }).catch(error => console.error('Error updating cart:', error));
            }
        });
    </script>
@endsection
