@extends('layouts.app')

@section('title', 'Products')
@section('page_title', 'Products')
@section('breadcrumb', 'Home » Products')
@section('content')

    <section
        class="relative bg-blue-50 py-6 md:py-8 border-b border-gray-200 mt-6 mx-2 md:mx-4 rounded-2xl md:rounded-3xl overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('/images/bg.png');">
        </div>
        <div class="absolute inset-0 bg-blue-200/20"></div>
        <div class="relative mx-4 md:mx-6 py-8 md:py-12 text-black ">
            <h1 class="text-2xl md:text-4xl font-extrabold ml-0 md:ml-12">
                @if ($selectedCategory)
                    {{ $selectedCategory->category_name }}
                @else
                    Products
                @endif
            </h1>

            @if (isset($recentSearches) && count($recentSearches) > 0)
                <div class="mt-6 md:mt-8 ml-0 md:ml-12">
                    <h2 class="text-base md:text-lg font-semibold mb-4">Recent Searches</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($recentSearches as $search)
                            <a href="{{ route('products', ['search' => $search]) }}"
                                class="bg-white/20 text-black px-3 py-1 rounded-full text-sm hover:bg-white/30 transition">
                                {{ $search }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>



    <section class="py-6 md:py-10">
        <div class="px-6 grid grid-cols-1 lg:grid-cols-4 gap-6 md:gap-10">

            <aside class="space-y-6 border-r border-gray-200 pr-6 hidden lg:block">

                <div x-data="{ open: true }" class="border border-gray-200 rounded-xl p-4 shadow-sm">
                    <button @click="open = !open"
                        class="w-full flex justify-between items-center text-left text-lg font-bold text-gray-700">
                        Categories
                        <svg :class="open ? '' : 'rotate-180'" class="w-5 h-5 transition-transform" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <ul x-show="open" x-collapse class="mt-4 space-y-3 text-gray-700">

                        <li>
                            <a href="{{ route('products') }}"
                                class="flex justify-between items-center px-3 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition">
                                <span>All Categories</span>
                                <span class="text-gray-500">{{ $categories->sum('products_count') }}</span>
                            </a>
                        </li>

                        @foreach ($categories as $category)
                            <li>
                                <a href="{{ route('products', ['category' => $category->id]) }}"
                                    class="flex justify-between items-center px-3 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition">
                                    <span>{{ $category->category_name }}</span>
                                    <span class="text-gray-500">{{ $category->products_count }}</span>
                                </a>
                            </li>
                        @endforeach

                    </ul>
                </div>

            </aside>

            <div class="lg:col-span-3">

                <div
                    class="flex flex-col md:flex-row md:flex-wrap md:items-center justify-between gap-4 mb-6 md:mb-8 bg-white p-4 rounded-xl border border-gray-200 shadow-sm">

                    <form method="GET" action="{{ route('products') }}" class="flex items-center gap-2 w-full md:w-auto">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search products..."
                            class="border border-gray-300 rounded-lg px-4 py-2 text-sm w-full md:w-64 focus:ring focus:ring-blue-200">

                        <button type="submit"
                            class="bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700 transition flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1112 4.5a7.5 7.5 0 014.65 12.15z" />
                            </svg>
                        </button>
                    </form>

                    <form method="GET" class="flex items-center gap-4 w-full md:w-auto">

                        @if (request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        @if (request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif

                        <select name="category" onchange="this.form.submit()"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-200 w-full md:w-auto">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </form>

                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

                    @forelse ($products as $product)
                        <div
                            class="bg-white rounded-2xl p-4 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 relative">


                            @php
                                $badge = '';
                                $badgeColor = 'bg-gray-500';

                          
                                if ($product->old_price && $product->selling_price < $product->old_price) {
                                    $discountPercent = round((($product->old_price - $product->selling_price) / $product->old_price) * 100);
                                    if ($discountPercent >= 20) {
                                        $badge = 'Sale ' . $discountPercent . '%';
                                        $badgeColor = 'bg-red-500';
                                    } elseif ($discountPercent >= 10) {
                                        $badge = 'Diskon ' . $discountPercent . '%';
                                        $badgeColor = 'bg-orange-500';
                                    }
                                }

                                elseif ($product->created_at && $product->created_at->diffInDays() <= 7) {
                                    $badge = 'Baru';
                                    $badgeColor = 'bg-green-500';
                                }

                                elseif ($product->avgRating >= 4.5) {
                                    $badge = 'Terlaris';
                                    $badgeColor = 'bg-blue-500';
                                }

                                else {
                                    $badge = 'Hot';
                                    $badgeColor = 'bg-red-500';
                                }
                            @endphp

                            <span
                                class="{{ $badgeColor }} text-white text-xs px-3 py-1 rounded-full absolute top-3 left-3">
                                {{ $badge }}
                            </span>

                            <a href="{{ route('product.show', $product->id) }}" class="block mt-2">
                                <img src="{{ asset('storage/' . $product->image) }}"
                                    class="w-full h-48 object-contain mx-auto" alt="{{ $product->product_name }}">
                            </a>


                            <p class="text-xs text-gray-500 mt-3">
                                {{ $product->category->category_name }}
                            </p>

                            <h3 class="font-semibold text-gray-800 text-sm leading-tight mt-1 line-clamp-2">
                                {{ $product->product_name }}
                            </h3>


                            <div class="flex items-center mt-2">
                                @php $avg = round($product->avgRating); @endphp

                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $avg ? 'text-yellow-400' : 'text-gray-300' }}"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.947a1
                                                                                                                    1 0 00.95.69h4.148c.969 0 1.371 1.24.588
                                                                                                                    1.81l-3.36 2.44a1 1 0 00-.364 1.118l1.287
                                                                                                                    3.948c.3.921-.755 1.688-1.538
                                                                                                                    1.118l-3.36-2.44a1 1 0 00-1.175
                                                                                                                    0l-3.36 2.44c-.783.57-1.838-.197-1.538-1.118l1.286-3.947a1
                                                                                                                    1 0 00-.364-1.118L2.037 9.374c-.783-.57-.38-1.81.588-1.81h4.148a1
                                                                                                                    1 0 00.95-.69l1.286-3.947z" />
                                    </svg>
                                @endfor
                                <span class="text-xs text-gray-400 ml-1">({{ $product->ratingCount }})</span>
                            </div>

                            <div class="mt-3 flex items-center justify-between">
                                <div>
                                    <div class="text-lg font-bold text-gray-600">
                                        Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                                    </div>

                                    @if ($product->old_price)
                                        <div class="text-sm text-gray-400 line-through">
                                            Rp {{ number_format($product->old_price, 0, ',', '.') }}
                                        </div>
                                    @endif
                                </div>

                                <form action="{{ route('addToCart') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">

                                    <button type="submit"
                                        class="flex items-center gap-1 px-3 py-2 bg-black text-white rounded-lg font-semibold text-sm hover:bg-gray-600 hover:text-white transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" class="w-4 h-4">
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

                @if ($products->hasPages())
                    <div class="mt-10">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @endif

            </div>

        </div>
    </section>

@endsection
