@extends('layouts.app')

@section('title', 'Keranjang Belanja')
@section('page_title', 'Cart')
@section('breadcrumb', 'Home » Cart')
@section('content')

<section class="py-16 bg-white min-h-screen">
    <div class="max-w-6xl mx-auto px-6 lg:px-10">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6 text-center shadow">
                {{ session('success') }}
            </div>
        @endif

        @if (count($cart) > 0)

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <div class="lg:col-span-2 space-y-6">

                    @foreach ($cart as $id => $item)
                        @php
                            $subtotal = $item['selling_price'] * $item['quantity'];
                        @endphp

                        <div class="bg-white rounded-2xl shadow-md p-6 hover:shadow-xl border border-gray-100 transition"
                            data-id="{{ $id }}"
                            data-price="{{ $item['selling_price'] }}"
                            data-quantity="{{ $item['quantity'] }}">

                            <div class="flex items-center gap-5 w-full">
                                <input type="checkbox" class="cart-checkbox w-5 h-5 text-blue-600 border-gray-300 rounded">

                                <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : 'https://via.placeholder.com/150' }}"
                                    alt="{{ $item['name'] }}" class="w-24 h-24 object-cover rounded-xl shadow-sm">

                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 leading-tight">
                                        {{ $item['name'] }}
                                    </h3>

                                    <p class="text-gray-500 font-bold mt-1">
                                        Rp {{ number_format($item['selling_price'], 0, ',', '.') }}
                                    </p>

                                    <div class="flex items-center gap-3 mt-4">
                                        <div class="flex items-center gap-2">
                                            <button type="button" id="minus-btn-{{ $id }}"
                                                onclick="updateQuantity({{ $id }}, parseInt(document.getElementById('quantity-{{ $id }}').value) - 1)"
                                                class="w-7 h-7 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 transition text-sm"
                                                {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                                -
                                            </button>

                                            <input type="number" readonly
                                                id="quantity-{{ $id }}" value="{{ $item['quantity'] }}"
                                                min="1" class="w-12 text-center rounded text-sm">

                                            <button type="button" id="plus-btn-{{ $id }}"
                                                onclick="updateQuantity({{ $id }}, parseInt(document.getElementById('quantity-{{ $id }}').value) + 1)"
                                                class="w-7 h-7 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 transition text-sm">
                                                +
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right space-y-3">

                                    <div>
                                        <p class="text-sm text-gray-500">Subtotal</p>
                                        <p class="text-lg font-bold text-gray-900 subtotal" id="subtotal-{{ $id }}">
                                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    <form action="{{ route('removeCart', $id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 rounded-full hover:bg-blue-100 text-red-600 hover:text-red-700 transition">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 
                                                    2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 
                                                    1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>

                                </div>

                            </div>
                        </div>
                    @endforeach

                </div>

  
                <div class="bg-white rounded-2xl shadow-xl p-7 h-fit sticky top-24 border border-gray-100">

                    <h2 class="text-xl font-bold text-gray-900 mb-5">Ringkasan Belanja</h2>

                    <div class="flex justify-between text-gray-700 mb-2">
                        <span>Total Produk</span>
                        <span id="total-products">0</span>
                    </div>

                    <div class="flex justify-between text-gray-700 mb-5">
                        <span>Total Pembayaran</span>
                        <span class="text-xl font-bold text-gray-500" id="total-payment">Rp 0</span>
                    </div>

                    <form id="checkout-form" action="{{ route('checkout.page') }}" method="GET">
                        <input type="hidden" name="items" id="selected-items">

                        <button type="submit" id="checkout-btn"
                            class="block w-full bg-gray-400 text-white py-3 rounded-xl text-center font-medium transition shadow-lg cursor-not-allowed"
                            disabled>
                            Checkout Sekarang
                        </button>
                    </form>

                    <a href="{{ route('home') }}"
                        class="block w-full mt-3 border border-gray-400 text-gray-700 py-3 rounded-xl text-center font-medium hover:bg-gray-100 transition">
                        Lanjut Belanja
                    </a>
                </div>

            </div>

        @else
            <div class="bg-white rounded-2xl shadow-lg p-10 text-center border border-gray-100">
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Keranjang Kosong</h3>
                <p class="text-gray-600 mb-6">Belum ada produk di keranjang belanja Anda.</p>
                <a href="{{ route('home') }}"
                    class="inline-flex items-center bg-blue-600 text-white py-3 px-8 rounded-xl hover:bg-blue-700 transition font-medium shadow-lg">
                    Mulai Belanja
                </a>
            </div>
        @endif
    </div>
</section>

<script>
    function updateQuantity(productId, newQuantity) {
        if (newQuantity < 1) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/cart/${productId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                quantity: newQuantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('quantity-' + productId).value = newQuantity;
                const itemDiv = document.querySelector(`[data-id="${productId}"]`);
                itemDiv.setAttribute('data-quantity', newQuantity);
                const price = parseInt(itemDiv.getAttribute('data-price'));
                const subtotal = price * newQuantity;
                document.getElementById('subtotal-' + productId).textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
                const minusBtn = document.getElementById('minus-btn-' + productId);
                const plusBtn = document.getElementById('plus-btn-' + productId);

                if (newQuantity <= 1) {
                    minusBtn.disabled = true;
                } else {
                    minusBtn.disabled = false;
                }
                const checkbox = itemDiv.querySelector('.cart-checkbox');
                if (checkbox.checked) {
                    updateSummary();
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function updateSummary() {
        const checkboxes = document.querySelectorAll('.cart-checkbox');
        let count = 0;
        let total = 0;
        let selected = [];

        checkboxes.forEach(ch => {
            if (ch.checked) {
                const item = ch.closest('[data-id]');
                const id = item.getAttribute('data-id');
                const price = parseInt(item.getAttribute('data-price'));
                const qty = parseInt(item.getAttribute('data-quantity'));
                count += qty;
                total += price * qty;
                selected.push(id);
            }
        });

        document.getElementById('total-products').textContent = count;
        document.getElementById('total-payment').textContent = 'Rp ' + total.toLocaleString('id-ID');

        let btn = document.getElementById('checkout-btn');
        let itemsField = document.getElementById('selected-items');
        itemsField.value = selected.join(',');

        if (count > 0) {
            btn.disabled = false;
            btn.classList.remove('cursor-not-allowed', 'bg-gray-400');
            btn.classList.add('bg-gray-800', 'hover:bg-gray-900');
        } else {
            btn.disabled = true;
            btn.classList.add('cursor-not-allowed', 'bg-gray-400');
            btn.classList.remove('bg-gray-800', 'hover:bg-gray-900');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.cart-checkbox').forEach(cb => {
            cb.addEventListener('change', updateSummary);
        });
        updateSummary();
    });
</script>

@endsection
