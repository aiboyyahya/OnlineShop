@extends('layouts.app')

@section('title', 'Beranda')
@section('page_title', 'Home')
@section('breadcrumb', 'Home')

@section('content')

    <section class="pt-3 pb-4 bg-white mx-3 md:mx-4">

        <div
            class="relative overflow-hidden rounded-2xl shadow-xl w-full h-[300px] sm:h-[350px] md:h-[410px] lg:h-[450px] group">

            <div id="heroSlider" class="relative w-full h-full">

                <div class="hero-slide absolute inset-0 opacity-100 transition-opacity duration-700">
                    <div class="w-full h-full bg-cover bg-center bg-no-repeat"
                        style="background-image: url('{{ asset('images/slide1.png') }}')">

                        <div class="w-full h-full bg-[#0a0d2d]/50 grid grid-cols-1 md:grid-cols-2">
                            <div
                                class="flex flex-col justify-center px-4 sm:px-8 md:px-16 text-white space-y-3 md:space-y-4">
                                <h2 class="text-2xl sm:text-3xl md:text-5xl font-extrabold">Diskon 20%</h2>
                                <p class="text-sm sm:text-lg md:text-xl opacity-80">Koleksi helm terbaru minggu ini — Stok
                                    terbatas!
                                </p>
                                <a href="/produk"
                                    class="bg-blue-700 text-white px-4 py-2 md:px-6 md:py-3 font-bold w-fit rounded-2xl hover:bg-blue-400 text-sm md:text-base">
                                    Belanja Sekarang
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="hero-slide absolute inset-0 opacity-0 transition-opacity duration-700">
                    <div class="w-full h-full bg-cover bg-center bg-no-repeat"
                        style="background-image: url('{{ asset('images/slide2.png') }}')">

                        <div class="w-full h-full bg-[#142053]/50 grid grid-cols-1 md:grid-cols-2">
                            <div
                                class="flex flex-col justify-center px-4 sm:px-8 md:px-16 text-white space-y-3 md:space-y-4">
                                <h2 class="text-2xl sm:text-3xl md:text-5xl font-extrabold">Koleksi Terbaru</h2>
                                <p class="text-sm sm:text-lg md:text-xl opacity-80">Helm keren dengan desain modern &
                                    kualitas premium.
                                </p>
                                <a href="/produk"
                                    class="bg-blue-700 text-white px-4 py-2 md:px-6 md:py-3 font-bold w-fit rounded-2xl hover:bg-blue-400 text-sm md:text-base">
                                    Lihat Produk
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="hero-slide absolute inset-0 opacity-0 transition-opacity duration-700">
                    <div class="w-full h-full bg-cover bg-center bg-no-repeat"
                        style="background-image: url('{{ asset('images/slide.png') }}')">

                        <div class="w-full h-full bg-[#1b0e2d]/50 grid grid-cols-1 md:grid-cols-2">
                            <div
                                class="flex flex-col justify-center px-4 sm:px-8 md:px-16 text-white space-y-3 md:space-y-4">
                                <h2 class="text-2xl sm:text-3xl md:text-5xl font-extrabold">Gratis Ongkir</h2>
                                <p class="text-sm sm:text-lg md:text-xl opacity-80">Untuk pembelian minimal Rp 700.000.</p>
                                <a href="/produk"
                                    class="bg-blue-700 text-white px-4 py-2 md:px-6 md:py-3 font-bold w-fit rounded-2xl hover:bg-blue-400 text-sm md:text-base">
                                    Dapatkan Promo
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <button id="prevSlide"
                class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/60 hover:bg-white rounded-2xl p-3 shadow-md opacity-100 md:opacity-0 md:group-hover:opacity-100 transition">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <button id="nextSlide"
                class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/60 hover:bg-white rounded-full p-3 shadow-md opacity-100 md:opacity-0 md:group-hover:opacity-100 transition">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                <button class="hero-dot w-3 h-3 rounded-full bg-white/40"></button>
                <button class="hero-dot w-3 h-3 rounded-full bg-white/40"></button>
                <button class="hero-dot w-3 h-3 rounded-full bg-white/40"></button>
            </div>

        </div>

    </section>

    <section class="pt-4 pb-4 bg-white mx-4 md:mx-6 mt-6">
        <div class="relative group border-b border-gray-200 pb-12">

            <div id="categoryScroll" class="flex gap-4 md:gap-8 pb-2 flex-nowrap overflow-x-hidden">

                @forelse($categories as $category)
                    <a href="{{ route('products', ['category' => $category->id]) }}"
                        class="flex-shrink-0 w-40 sm:w-48 md:w-56 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl p-3 md:p-5 relative overflow-hidden hover:shadow-lg transition-shadow duration-300">

                        <span
                            class="absolute top-1 md:top-2 right-2 md:right-4 text-2xl md:text-3xl font-extrabold text-gray-300 select-none">
                            {{ str_pad($category->products_count, 2, '0', STR_PAD_LEFT) }}%
                        </span>

                        <span
                            class="inline-block bg-gray-600 text-white px-2 md:px-3 py-1 text-xs md:text-sm font-semibold rounded-lg">
                            {{ $category->products_count }}
                        </span>

                        <h3 class="mt-3 md:mt-5 text-blue-600 text-xs md:text-sm capitalize">{{ $category->category_name }}
                        </h3>
                        <h2 class="text-lg md:text-2xl font-bold text-gray-900 -mt-1 line-clamp-1">
                            {{ $category->category_name }}</h2>
                    </a>

                @empty
                    <p class="text-gray-500 text-center py-8">Belum ada kategori tersedia.</p>
                @endforelse

            </div>

            <button id="scrollLeft"
                class="absolute left-0 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white shadow-md rounded-full p-2 z-10 transition-opacity duration-200 opacity-0 group-hover:opacity-100">
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <button id="scrollRight"
                class="absolute right-0 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white shadow-md rounded-full p-2 z-10 transition-opacity duration-200 opacity-0 group-hover:opacity-100">
                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </section>

    <section class="py-12 bg-white mx-3 md:mx-4 mt-4 border-b border-gray-200">
        <div class="max-w-full mx-auto px-2 md:px-5 lg:px-4">

            <div class="flex flex-col md:flex-row md:items-center justify-center mb-10 gap-6">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-extrabold tracking-tight">
                    Popular Products
                </h2>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 sm:gap-8">

                @forelse ($products as $product)
                    <div
                        class="bg-white rounded-2xl p-3 sm:p-4 border border-gray-200 shadow-sm hover:shadow-md transition duration-300 relative">

                        @php
                            $badge = '';
                            $badgeColor = 'bg-gray-500';

                            if ($product->old_price && $product->selling_price < $product->old_price) {
                                $discountPercent = round(
                                    (($product->old_price - $product->selling_price) / $product->old_price) * 100,
                                );
                                if ($discountPercent >= 20) {
                                    $badge = 'Sale ' . $discountPercent . '%';
                                    $badgeColor = 'bg-red-500';
                                } elseif ($discountPercent >= 10) {
                                    $badge = 'Diskon ' . $discountPercent . '%';
                                    $badgeColor = 'bg-orange-500';
                                }
                            } elseif ($product->created_at && $product->created_at->diffInDays() <= 7) {
                                $badge = 'Baru';
                                $badgeColor = 'bg-green-500';
                            } elseif ($product->avgRating >= 4.5) {
                                $badge = 'Terlaris';
                                $badgeColor = 'bg-blue-500';
                            } else {
                                $badge = 'Hot';
                                $badgeColor = 'bg-red-500';
                            }
                        @endphp

                        <span
                            class="{{ $badgeColor }} text-white text-xs px-2 py-1 rounded-full absolute top-2 left-2 sm:top-3 sm:left-3">
                            {{ $badge }}
                        </span>

                        <a href="{{ route('product.show', $product->id) }}" class="block mt-2">
                            <img src="{{ asset('storage/' . $product->image) }}"
                                class="w-full h-36 sm:h-48 object-contain mx-auto" alt="{{ $product->product_name }}">
                        </a>

                        <p class="text-[10px] sm:text-xs text-gray-500 mt-2">
                            {{ $product->category->category_name }}
                        </p>

                        <h3 class="font-semibold text-gray-800 text-xs sm:text-sm leading-tight mt-1 line-clamp-2">
                            {{ $product->product_name }}
                        </h3>

                        <div class="flex items-center mt-1 sm:mt-2">
                            @php $avg = round($product->avgRating); @endphp
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 {{ $i <= $avg ? 'text-yellow-400' : 'text-gray-300' }}"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.947a1 1 0 00.95.69h4.148c.969 0 1.371 1.24.588 1.81l-3.36 2.44a1 1 0 00-.364 1.118l1.287 3.948c.3.921-.755 1.688-1.538 1.118l-3.36-2.44a1 1 0 00-1.175 0l-3.36 2.44c-.783.57-1.838-.197-1.538-1.118l1.286-3.947a1 1 0 00-.364-1.118L2.037 9.374c-.783-.57-.38-1.81.588-1.81h4.148a1 1 0 00.95-.69l1.286-3.947z" />
                                </svg>
                            @endfor
                            <span class="text-[10px] sm:text-xs text-gray-400 ml-1">({{ $product->ratingCount }})</span>
                        </div>

                        <div class="mt-2 sm:mt-3 flex items-center justify-between">

                            <div>
                                <div class="text-base sm:text-lg font-bold text-gray-600">
                                    Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                                </div>

                                @if ($product->old_price)
                                    <div class="text-xs sm:text-sm text-gray-400 line-through">
                                        Rp {{ number_format($product->old_price, 0, ',', '.') }}
                                    </div>
                                @endif
                            </div>

                            <form action="{{ route('addToCart') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">

                                <button type="submit"
                                    class="flex items-center gap-1 px-2 py-1.5 sm:px-3 sm:py-2 bg-black text-white rounded-lg font-semibold text-[10px] sm:text-sm hover:bg-gray-700 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" class="w-3 h-3 sm:w-4 sm:h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                    </svg>
                                    Add
                                </button>
                            </form>
                        </div>

                    </div>
                @empty
                    <p class="text-gray-500 text-center col-span-full">Belum ada produk tersedia.</p>
                @endforelse
            </div>
        </div>
    </section>


    <section class="py-10 bg-white mx-3 md:mx-4 mt-6 border-b border-gray-200 ">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-gray-900">Customer Reviews</h2>
                <p class="text-lg text-gray-600 mt-4">What our customers say about our products</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($recentReviews as $review)
                    <div
                        class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-all duration-300">
                        <div class="flex items-center mb-4">
                            @if ($review->customer->profile_photo)
                                <img src="{{ asset('storage/' . $review->customer->profile_photo) }}"
                                    alt="{{ $review->customer->name }}"
                                    class="w-12 h-12 rounded-full object-cover mr-4 border-2 border-gray-200">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mr-4">
                                    <span class="text-gray-600 font-semibold text-lg">
                                        {{ substr($review->customer->name ?? 'U', 0, 1) }}
                                    </span>
                                </div>
                            @endif
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $review->customer->name ?? 'Anonymous' }}</h4>
                                <div class="flex items-center">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.947a1 1 0 00.95.69h4.148c.969 0 1.371 1.24.588 1.81l-3.36 2.44a1 1 0 00-.364 1.118l1.287 3.948c.3.921-.755 1.688-1.538 1.118l-3.36-2.44a1 1 0 00-1.175 0l-3.36 2.44c-.783.57-1.838-.197-1.538-1.118l1.286-3.947a1 1 0 00-.364-1.118L2.037 9.374c-.783-.57-.38-1.81.588-1.81h4.148a1 1 0 00.95-.69l1.286-3.947z" />
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <p class="text-gray-700 mb-4 italic">"{{ Str::limit($review->comment, 150) }}"</p>

                        <div class="text-sm text-gray-500">
                            <span class="font-medium">{{ $review->product->product_name }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Reviews Yet</h3>
                        <p class="text-gray-600">Be the first to leave a review on our products!</p>
                    </div>
                @endforelse
            </div>

        </div>
    </section>

    <section class="py-8 md:py-12 bg-white px-4 md:px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">


            <div
                class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-200 text-center hover:shadow-md transition">
                <div
                    class="w-12 h-12 md:w-14 md:h-14 mx-auto mb-4 flex items-center justify-center bg-blue-50 rounded-full">

                    <svg class="w-6 h-6 md:w-8 md:h-8 text-blue-600" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 7h11v6h-11z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M14 13h3l3-3V7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <circle cx="7.5" cy="18.5" r="1.5" fill="currentColor" />
                        <circle cx="18.5" cy="18.5" r="1.5" fill="currentColor" />
                    </svg>
                </div>
                <h3 class="text-base md:text-lg font-semibold text-gray-800">Gratis Pengiriman</h3>
                <p class="text-xs md:text-sm text-gray-500 mt-2">
                    Gratis pengiriman untuk pesanan di atas Rp 700.000 atau sesuai kebijakan toko
                </p>
            </div>


            <div
                class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-200 text-center hover:shadow-md transition">
                <div
                    class="w-12 h-12 md:w-14 md:h-14 mx-auto mb-4 flex items-center justify-center bg-green-50 rounded-full">

                    <svg class="w-6 h-6 md:w-8 md:h-8 text-green-600" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 13v-1a8 8 0 0116 0v1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M4 15v2a2 2 0 002 2h1v-6H6a2 2 0 00-2 2z" fill="currentColor" />
                        <path d="M20 15v2a2 2 0 01-2 2h-1v-6h1a2 2 0 012 2z" fill="currentColor" />
                    </svg>
                </div>
                <h3 class="text-base md:text-lg font-semibold text-gray-800">Dukungan 24/7</h3>
                <p class="text-xs md:text-sm text-gray-500 mt-2">
                    Tim kami siap membantu lewat WhatsApp atau kontak toko kapan saja
                </p>
            </div>


            <div
                class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-200 text-center hover:shadow-md transition">
                <div
                    class="w-12 h-12 md:w-14 md:h-14 mx-auto mb-4 flex items-center justify-center bg-yellow-50 rounded-full">

                    <svg class="w-6 h-6 md:w-8 md:h-8 text-yellow-600" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 12a9 9 0 10-3.2 6.7L21 12z" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M21 3v6h-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
                <h3 class="text-base md:text-lg font-semibold text-gray-800">Pengembalian Mudah</h3>
                <p class="text-xs md:text-sm text-gray-500 mt-2">
                    Pengembalian dalam 30 hari sesuai syarat dan ketentuan toko
                </p>
            </div>


            <div
                class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-gray-200 text-center hover:shadow-md transition">
                <div
                    class="w-12 h-12 md:w-14 md:h-14 mx-auto mb-4 flex items-center justify-center bg-indigo-50 rounded-full">

                    <svg class="w-6 h-6 md:w-8 md:h-8 text-indigo-600" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <rect x="2" y="7" width="20" height="12" rx="2" stroke="currentColor"
                            stroke-width="1.5" />
                        <path d="M16 11v2M8 11v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M7 7V6a5 5 0 0110 0v1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
                <h3 class="text-base md:text-lg font-semibold text-gray-800">Pembayaran Aman</h3>
                <p class="text-xs md:text-sm text-gray-500 mt-2">
                    Metode pembayaran terenkripsi dan terjamin oleh provider pembayaran kami
                </p>
            </div>

        </div>
    </section>





    <script>
        // Preload images
        function preloadImages() {
            const images = [
                '{{ asset('images/slide1.png') }}',
                '{{ asset('images/slide2.png') }}',
                '{{ asset('images/slide.png') }}'
            ];
            images.forEach(src => {
                const img = new Image();
                img.src = src;
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            preloadImages();

            const slides = document.querySelectorAll('.hero-slide');
            const prevBtn = document.getElementById('prevSlide');
            const nextBtn = document.getElementById('nextSlide');
            const dots = document.querySelectorAll('.hero-dot');

            if (!slides.length) return;

            let current = 0;
            let timer = null;
            const interval = 4000;

            function show(index) {
                current = (index + slides.length) % slides.length;
                slides.forEach((s, i) => {
                    if (i === current) {
                        s.classList.remove('opacity-0');
                        s.classList.add('opacity-100');
                    } else {
                        s.classList.remove('opacity-100');
                        s.classList.add('opacity-0');
                    }
                });

                dots.forEach((d, i) => {
                    if (i === current) {
                        d.classList.remove('bg-white/40');
                        d.classList.add('bg-white');
                    } else {
                        d.classList.remove('bg-white');
                        d.classList.add('bg-white/40');
                    }
                });
            }

            function next() {
                show(current + 1);
            }

            function prev() {
                show(current - 1);
            }

            function start() {
                stop();
                timer = setInterval(next, interval);
            }

            function stop() {
                if (timer) {
                    clearInterval(timer);
                    timer = null;
                }
            }

            if (nextBtn) nextBtn.addEventListener('click', function() {
                next();
                start();
            });
            if (prevBtn) prevBtn.addEventListener('click', function() {
                prev();
                start();
            });

            dots.forEach((dot, idx) => dot.addEventListener('click', function() {
                show(idx);
                start();
            }));

            show(0);
            start();
        });
        document.addEventListener('DOMContentLoaded', function() {
            const scrollContainer = document.getElementById('categoryScroll');
            const scrollLeftBtn = document.getElementById('scrollLeft');
            const scrollRightBtn = document.getElementById('scrollRight');

            if (scrollLeftBtn && scrollRightBtn && scrollContainer) {
                const scrollAmount = 300;

                scrollLeftBtn.addEventListener('click', function() {
                    scrollContainer.scrollBy({
                        left: -scrollAmount,
                        behavior: 'smooth'
                    });
                });

                scrollRightBtn.addEventListener('click', function() {
                    scrollContainer.scrollBy({
                        left: scrollAmount,
                        behavior: 'smooth'
                    });
                });
            }
        });
    </script>



@endsection
