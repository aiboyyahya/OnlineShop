@extends('layouts.app')

@section('title', $product->product_name)
@section('page_title', $product->product_name)
@section('breadcrumb', 'Home » Products » ' . $product->product_name)
@section('content')


    <section class="py-10 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 p-6">

                <div class="space-y-6">
                    <div class="border border-gray-200 shadow-md rounded-3xl p-4 bg-white">
                        <img id="main-product-image"
                            src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/400x400' }}"
                            alt="{{ $product->product_name }}" class="object-contain w-full h-[320px] rounded-2xl">
                    </div>

                    @if ($product->images && $product->images->count())
                        <div class="flex gap-3 overflow-x-auto justify-center">
                            @foreach ($product->images as $img)
                                <button type="button"
                                    onclick="document.getElementById('main-product-image').src='{{ asset('storage/' . $img->image_file) }}'"
                                    class="w-16 h-16 rounded-lg overflow-hidden border border-gray-200 hover:border-green-500 transition-all">
                                    <img src="{{ asset('storage/' . $img->image_file) }}"
                                        class="object-cover w-full h-full">
                                </button>
                            @endforeach
                        </div>
                    @endif

                    <div class="bg-white border border-gray-200 rounded-3xl p-6 shadow-sm">
                        <div class="flex gap-3 mb-6">
                            <button class="tab-btn active px-6 py-2 rounded-full border text-sm font-semibold"
                                data-tab="desc">Description</button>

                            <button class="tab-btn px-6 py-2 rounded-full border text-sm font-semibold"
                                data-tab="vendor">Vendor</button>

                            <button class="tab-btn px-6 py-2 rounded-full border text-sm font-semibold"
                                data-tab="reviews">Reviews ({{ $ratingCount ?? 0 }})</button>
                        </div>

                        <div class="tab-content" id="desc">
                            <h3 class="text-xl font-bold mb-3">Product Description</h3>
                            <p class="text-gray-700 leading-relaxed">
                                {!! nl2br(e($product->description)) !!}
                            </p>
                        </div>

                        <div class="tab-content hidden" id="vendor">
                            <h3 class="text-xl font-bold mb-3">Vendor</h3>
                            <p class="text-gray-700 leading-relaxed">
                                {{ $store->store_name ?? 'Vendor Store' }}
                            </p>
                        </div>

                        <div class="tab-content hidden" id="reviews">
                            <h3 class="text-xl font-bold mb-3">Customer Reviews</h3>

                            @if (isset($ratings) && $ratings->count())
                                @foreach ($ratings as $rating)
                                    <div class="border rounded-2xl p-4 mb-4 bg-white">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $rating->customer && $rating->customer->profile_photo
                                                ? asset('storage/' . $rating->customer->profile_photo)
                                                : 'https://ui-avatars.com/api/?name=' . urlencode($rating->customer->name ?? 'User') }}"
                                                class="w-10 h-10 rounded-full object-cover border">
                                            <div>
                                                <p class="font-semibold">{{ $rating->customer->name }}</p>
                                                <div class="flex text-yellow-400">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <svg class="w-4 h-4 {{ $i <= $rating->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                            fill="currentColor" viewBox="0 0 20 20">
                                                            <path
                                                                d="M9.049 2.927c-.3-.921 1.603-.921 1.902 0l1.286 3.974 4.181.032c.969.007 1.371 1.24.588 1.81l-3.378 2.455 1.266 3.998c.29.916-.755 1.688-1.54 1.118l-3.422-2.475-3.422 2.475c-.785.57-1.83-.202-1.54-1.118l1.266-3.998-3.378-2.455c-.783-.57-.381-1.803.588-1.81l4.181-.032 1.286-3.974z" />
                                                        </svg>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>

                                        <p class="text-gray-700 mt-3">{{ $rating->comment }}</p>

                                        @if ($rating->image)
                                            <img src="{{ asset('storage/' . $rating->image) }}"
                                                class="w-20 h-20 mt-3 rounded-lg border object-cover">
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <p class="text-gray-500">Belum ada ulasan.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="border border-gray-200 rounded-3xl p-10 shadow-md bg-white sticky top-10 space-y-6">

                    <div>
                        <span
                            class="inline-block bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-bold mb-2">Sale
                            Off</span>

                        <h2 class="text-3xl font-bold text-gray-900 leading-snug">
                            {{ $product->product_name }}
                        </h2>

                        <p class="mt-3 text-gray-600">
                            {{ $product->short_description ?? $product->description }}
                        </p>
                    </div>

                    <div class="flex items-center space-x-2">
                        <div class="flex text-yellow-400">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $avgRating ? 'text-yellow-400' : 'text-gray-300' }}"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c-.3-.921 1.603-.921 1.902 0l1.286 3.974 4.181.032c.969.007 1.371 1.24.588 1.81l-3.378 2.455 1.266 3.998c.29.916-.755 1.688-1.54 1.118l-3.422-2.475-3.422 2.475c-.785.57-1.83-.202-1.54-1.118l1.266-3.998-3.378-2.455c-.783-.57-.381-1.803.588-1.81l4.181-.032 1.286-3.974z" />
                                </svg>
                            @endfor
                        </div>
                        <span class="text-sm text-gray-500">({{ $ratingCount }} ulasan)</span>
                    </div>

                    <div class="flex items-end gap-3">
                        <p class="text-4xl font-bold text-gray-900">
                            Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                        </p>
                        @if ($product->price_before)
                            <span class="line-through text-gray-400 text-lg">
                                Rp {{ number_format($product->price_before, 0, ',', '.') }}
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center gap-3">
                        <button id="decrement-btn"
                            class="w-10 h-10 bg-gray-200 rounded-full font-bold hover:bg-gray-300">-</button>
                        <input type="number" id="product-quantity" min="1" max="{{ $product->stock }}"
                            value="1" readonly class="w-16 text-center border rounded-lg py-2 font-semibold">
                        <button id="increment-btn"
                            class="w-10 h-10 bg-gray-200 rounded-full font-bold hover:bg-gray-300">+</button>
                    </div>

                    <p class="text-sm text-gray-500">{{ $product->stock }} items left</p>

                    <div class="flex gap-4">
                        <form method="GET" action="{{ route('checkout.page') }}" class="flex-1">
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" id="checkout-qty" value="1">
                            <input type="hidden" name="direct" value="1">
                            <button type="submit"
                                class="w-full py-4 bg-black text-white font-semibold rounded-xl hover:bg-gray-700 transition">
                                Checkout
                            </button>
                        </form>

                        <form method="POST" action="{{ route('addToCart') }}" class="flex-1">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" id="cart-qty" value="1">
                            <button type="submit"
                                class="w-full py-4 border border-black text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition">
                                Add to Cart
                            </button>
                        </form>
                    </div>

                    <div class="border-t pt-4">
                        <h4 class="font-bold text-gray-900">Returns</h4>
                        <p class="text-sm text-gray-600">Free returns within 6 days.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>




    <script>
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabs = document.querySelectorAll('.tab-content');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {

                tabBtns.forEach(b => b.classList.remove('active', 'text-indigo-600', 'border-indigo-600'));
                btn.classList.add('active', 'text-indigo-600', 'border-indigo-600');

                tabs.forEach(tab => tab.classList.add('hidden'));
                document.getElementById(btn.dataset.tab).classList.remove('hidden');
            });
        });
    </script>

    <style>
        .tab-btn.active {
            background: #f0f4ff;
            font-weight: 600;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inc = document.getElementById('increment-btn');
            const dec = document.getElementById('decrement-btn');
            const qtyInput = document.getElementById('product-quantity');
            const max = parseInt(qtyInput.getAttribute('max'));
            const checkoutQty = document.getElementById('checkout-qty');
            const cartQty = document.getElementById('cart-qty');

            function sync() {
                checkoutQty.value = qtyInput.value;
                cartQty.value = qtyInput.value;
            }

            inc.addEventListener('click', () => {
                let val = parseInt(qtyInput.value);
                if (val < max) qtyInput.value = val + 1;
                sync();
            });

            dec.addEventListener('click', () => {
                let val = parseInt(qtyInput.value);
                if (val > 1) qtyInput.value = val - 1;
                sync();
            });

            sync();
        });
    </script>
@endsection
