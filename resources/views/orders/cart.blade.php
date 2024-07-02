@extends('layouts.layout-auth')

@section('main')
    <main class="mx-auto justify-content-center main-content main h-100">
        <div class="d-flex justify-content-start align-items-center mb-3 gap-2" style="margin-left: 0 !important">
            <button type="button" id="backButton" class="btn">
                <i class="bi bi-arrow-left-circle-fill"></i>
            </button>
            <h2 class="font-20 fw-bold text-uppercase">Keranjang Belanja</h2>
        </div>

        <div class="mb-3" style="border-top: 1px dashed #d5d5d5"></div>

        <form id="checkout-form" method="POST" action="{{ route('checkout') }}">
            @csrf
            <input type="hidden" name="branch_id" value="{{ $branch_id }}">
            @forelse($orderDetails as $index => $item)
                <div class="w-100 mb-3" data-index="{{ $index }}">
                    <div class="mb-1 d-flex justify-content-between">
                        <div>
                            <h3 class="font-14 mb-0 fw-semibold">{{ $item['menu_name'] }}</h3>
                            <p class="font-12 fw-semibold mt-3">Rp {{ number_format($item['menu_price'], 0, ',', '.') }}</p>
                            <p class="font-12 mt-1">{{ $item['category_name'] }}</p>
                        </div>
                        <img src="{{ $item['branch_logo'] }}" class="rounded-3" alt="Menu" width="60"
                            height="60">
                    </div>
                    <div class="mb-3">
                        <label for="note-{{ $index }}" class="form-label">Catatan</label>
                        <textarea class="form-control" id="note-{{ $index }}" name="orderDetails[{{ $index }}][note]"
                            rows="2">{{ $item['note'] ?? '' }}</textarea>
                    </div>
                    <div class="d-flex">
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
                            <button type="button" class="btn btn-danger btn-sm remove-item"
                                data-index="{{ $index }}">Hapus</button>
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
                    <hr>
                </div>
            @empty
                <div class="d-flex justify-content-center align-items-center w-100" style="height: 60vh;">
                    <p class="font-20 fw-bold text-center">Keranjang Anda kosong</p>
                </div>
            @endforelse
            @if (count($orderDetails) > 0)
                <div class="d-flex justify-content-end my-3">
                    <button type="button" class="btn btn-success" id="checkoutButton">Checkout</button>
                </div>
            @endif
        </form>
    </main>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const branchId = '{{ $branch_id }}';

            document.querySelectorAll('.decrease-qty').forEach(button => {
                button.addEventListener('click', function() {
                    const index = this.getAttribute('data-index');
                    const quantityInput = document.querySelector(
                        `input[name='orderDetails[${index}][quantity]']`);
                    let quantity = parseInt(quantityInput.value);
                    if (quantity > 1) {
                        quantity--;
                        quantityInput.value = quantity;
                        updateCart();
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
                    updateCart();
                });
            });

            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', function() {
                    const index = this.getAttribute('data-index');
                    const item = {
                        menu_name: document.querySelector(
                            `input[name='orderDetails[${index}][menu_name]']`).value,
                        menu_price: document.querySelector(
                            `input[name='orderDetails[${index}][menu_price]']`).value,
                        menu_id: document.querySelector(
                            `input[name='orderDetails[${index}][menu_id]']`).value,
                        category_id: document.querySelector(
                            `input[name='orderDetails[${index}][category_id]']`).value,
                        category_name: document.querySelector(
                            `input[name='orderDetails[${index}][category_name]']`).value,
                        note: document.querySelector(`#note-${index}`).value,
                        quantity: document.querySelector(
                            `input[name='orderDetails[${index}][quantity]']`).value
                    };

                    fetch('{{ route('removeItem') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                index
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.querySelector(`div[data-index='${index}']`).remove();
                                if (document.querySelectorAll('.w-100.mb-3').length === 0) {
                                    window.location =
                                        `{{ route('order.menu', ['branch_id' => $branch_id]) }}`;
                                }
                            }
                        });

                    fetch('{{ route('logRemoveItem') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                index,
                                item
                            })
                        }).then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Remove item log successfully saved');
                            } else {
                                console.error('Failed to log remove item action');
                            }
                        }).catch(error => {
                            console.error('Error logging remove item action:', error);
                        });
                });
            });

            document.getElementById('backButton').addEventListener('click', function() {
                saveOrderDetails().then(() => {
                    window.location = `{{ route('order.menu', ['branch_id' => $branch_id]) }}`;
                }).catch(error => {
                    console.error('Error saving order details:', error);
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
                }).then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                }).then(data => {
                    if (data.success) {
                        console.log(data);
                        console.log('Order details saved successfully');
                    } else {
                        throw new Error('Failed to save order details');
                    }
                }).catch(error => {
                    console.error('Error saving order details:', error);
                    throw error;
                });
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
                    }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Cart updated successfully');
                        } else {
                            console.error('Failed to update cart');
                        }
                    }).catch(error => {
                        console.error('Error updating cart:', error);
                    });
            }
        });
    </script>
@endsection
