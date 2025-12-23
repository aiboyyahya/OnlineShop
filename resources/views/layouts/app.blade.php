<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', store()->store_name ?? 'SHOP')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-gray-800">

    @if (auth()->check() && empty(auth()->user()->phone_number))
        <div class="bg-red-500 py-2 text-white text-center px-4">
            Silakan lengkapi nomor telepon di profil kamu
            <a href="{{ route('profil') }}" class="underline font-semibold">Disini</a>
        </div>
    @endif

    <nav class="bg-white shadow-sm border-b sticky top-0 z-[8000]">
        <div class="px-4 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <a href="{{ route('home') }}" class="flex items-center space-x-2 hover:opacity-80 transition-opacity">
                    <img src="{{ asset('storage/' . (store()->logo ?? 'default.png')) }}"
                        class="h-8 w-8 object-contain">
                    <span class="text-xl lg:text-2xl font-bold">{{ store()->store_name ?? 'OnlineShop' }}</span>
                </a>

                <div class="flex justify-center">
                    <form action="{{ route('products') }}" method="GET"
                        class="flex items-center bg-gray-100 rounded-lg px-4 py-2 mx-auto w-full max-w-xs sm:max-w-sm md:max-w-md">
                        <input type="text" name="search" placeholder="Search products..."
                            class="bg-transparent outline-none flex-1 text-sm w-full">
                        <button type="submit" class="ml-2">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1112 4.5a7.5 7.5 0 014.65 12.15z" />
                            </svg>
                        </button>
                    </form>

                </div>

                <div class="flex items-center space-x-4 lg:space-x-5 text-gray-700">

                    <div class="hidden lg:flex items-center space-x-8 font-medium text-sm">

                        <a href="{{ url('/') }}"
                            class="hover:text-gray-900 {{ request()->is('/') ? 'text-black font-semibold' : 'text-gray-600' }}">
                            Home
                        </a>

                        <div class="relative categories-dropdown">
                            <button class="flex items-center space-x-1 text-gray-600 hover:text-gray-900">
                                <span>Categories</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div
                                class="categories-menu absolute mt-2 hidden bg-white shadow-lg border rounded-md w-40 text-sm py-2 z-[9999]">
                                @if (isset($categories) && $categories->count())
                                    @foreach ($categories as $category)
                                        <a href="{{ route('products', ['category' => $category->id]) }}"
                                            class="block px-4 py-2 hover:bg-gray-100">
                                            {{ $category->category_name }}
                                        </a>
                                    @endforeach
                                @else
                                    <span class="block px-4 py-2 text-gray-500">No categories</span>
                                @endif
                            </div>
                        </div>

                        <a href="{{ url('/produk') }}"
                            class="text-gray-600 hover:text-gray-900 {{ request()->is('produk*') ? 'text-black font-semibold' : '' }}">
                            Products
                        </a>

                        <a href="{{ route('articles.index') }}"
                            class="text-gray-600 hover:text-gray-900 {{ request()->is('articles*') ? 'text-black font-semibold' : '' }}">
                            Articles
                        </a>

                        <a href="{{ route('kontak') }}"
                            class="text-gray-600 hover:text-gray-900 {{ request()->is('kontak') ? 'text-black font-semibold' : '' }}">
                            Contact
                        </a>
                    </div>

                    <div class="relative profile-dropdown hidden lg:flex items-center">
                        <button class="hover:text-black flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </button>

                        <div
                            class="profile-menu absolute left-1/2 transform -translate-x-1/2 mt-40 hidden bg-white shadow-lg border rounded-md w-40 text-sm py-2 z-[9999] text-gray-700">
                            @auth
                                <a href="{{ route('profil') }}" class="block px-4 py-2 hover:bg-gray-100">Profil</a>
                                <a href="{{ route('orders') }}" class="block px-4 py-2 hover:bg-gray-100">Pesanan</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 hover:bg-gray-100">Logout</button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-gray-100">Login</a>
                                <a href="{{ route('register.form') }}"
                                    class="block px-4 py-2 hover:bg-gray-100">Register</a>
                            @endauth
                        </div>
                    </div>

                    <a href="{{ route('reviews.index') }}"
                        class="hover:text-black relative hidden lg:flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                        </svg>


                        @php $reviewCount = \App\Models\Rating::count(); @endphp
                        @if ($reviewCount > 0)
                            <span
                                class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-4 h-4 flex items-center justify-center">{{ $reviewCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('cart') }}" class="hover:text-black relative flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>

                        @php $cartCount = count(session('cart', [])); @endphp
                        @if ($cartCount > 0)
                            <span
                                class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <button id="mobile-menu-btn" class="lg:hidden hover:text-black flex items-center">
                        <svg id="hamburger-icon" class="w-6 h-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div id="mobile-sidebar" class="hidden lg:hidden fixed inset-0 bg-black/50 z-[7999]"></div>
    <div id="mobile-menu"
        class="hidden lg:hidden fixed left-0 top-16 bottom-0 w-64 bg-white shadow-lg z-[9999] overflow-y-auto">
        <div class="p-4 space-y-4">
            <div class="border-b pb-4 mb-4 lg:hidden">
                @auth
                    <p class="font-semibold text-gray-900 mb-3">{{ auth()->user()->name ?? 'User' }}</p>
                    <div class="space-y-2 text-sm">
                        <a href="{{ route('profil') }}" class="block px-3 py-2 hover:bg-gray-100 rounded">Profil</a>
                        <a href="{{ route('orders') }}" class="block px-3 py-2 hover:bg-gray-100 rounded">Pesanan</a>
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-3 py-2 hover:bg-gray-100 rounded">Logout</button>
                        </form>
                    </div>
                @else
                    <div class="space-y-2 text-sm">
                        <a href="{{ route('login') }}" class="block px-3 py-2 hover:bg-gray-100 rounded">Login</a>
                        <a href="{{ route('register.form') }}"
                            class="block px-3 py-2 hover:bg-gray-100 rounded">Register</a>
                    </div>
                @endauth
            </div>
            <div>
                <button id="mobile-categories-btn"
                    class="w-full flex items-center justify-between px-4 py-2 text-gray-700 hover:text-black font-medium rounded hover:bg-gray-100">
                    <span>Categories</span>
                    <svg id="mobile-categories-icon" class="w-4 h-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="mobile-categories-menu" class="hidden pl-4 space-y-2 mt-2">
                    @if (isset($categories) && $categories->count())
                        @foreach ($categories as $category)
                            <a href="{{ route('products', ['category' => $category->id]) }}"
                                class="block px-4 py-2 text-gray-600 hover:text-black rounded hover:bg-gray-100">{{ $category->category_name }}</a>
                        @endforeach
                    @else
                        <span class="block px-4 py-2 text-gray-500">No categories</span>
                    @endif
                </div>
            </div>
            <div>
                <button id="mobile-products-btn"
                    class="w-full flex items-center justify-between px-4 py-2 text-gray-700 hover:text-black font-medium rounded hover:bg-gray-100">
                    <span>Products</span>
                    <svg id="mobile-products-icon" class="w-4 h-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="mobile-products-menu" class="hidden pl-4 space-y-2 mt-2">
                    <a href="{{ url('/produk') }}"
                        class="block px-4 py-2 text-gray-600 hover:text-black rounded hover:bg-gray-100">All
                        Products</a>
                </div>
            </div>

            <div>
                <button id="mobile-pages-btn"
                    class="w-full flex items-center justify-between px-4 py-2 text-gray-700 hover:text-black font-medium rounded hover:bg-gray-100">
                    <span>Pages</span>
                    <svg id="mobile-pages-icon" class="w-4 h-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="mobile-pages-menu" class="hidden pl-4 space-y-2 mt-2">
                    <a href="{{ route('profil') }}"
                        class="block px-4 py-2 text-gray-600 hover:text-black rounded hover:bg-gray-100">Profil</a>
                    <a href="{{ route('articles.index') }}"
                        class="block px-4 py-2 text-gray-600 hover:text-black rounded hover:bg-gray-100">Articles</a>
                    <a href="{{ route('kontak') }}"
                        class="block px-4 py-2 text-gray-600 hover:text-black rounded hover:bg-gray-100">Contact</a>
                </div>
            </div>
        </div>
    </div>

    <main class="min-h-screen px-6">
        <div
            class="bg-gray-100 rounded-lg shadow-sm py-2 p-5 mb-8
           flex flex-row items-center justify-between
           w-full max-w-full mx-auto mt-2">

            <h1 class="text-lg sm:text-xl font-semibold text-gray-800 px-12 sm:px-0">
                @yield('page_title', 'Page Title')
            </h1>

            <p class="text-xs sm:text-sm md:text-base text-gray-400">
                @yield('breadcrumb', 'Home » Page')
            </p>
        </div>


        @yield('content')
    </main>


    <footer class="bg-[#1C2434] text-gray-300 pt-12 pb-6 ">
        <div class="px-4 sm:px-4 lg:px-6 max-w-7xl mx-auto">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-6">

                <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                        <img src="{{ asset('storage/' . (store()->logo ?? 'default.png')) }}"
                            class="h-10 w-10 object-contain">
                        <span class="text-2xl font-bold text-white">{{ store()->store_name ?? 'Mantu' }}</span>
                    </div>
                    <p class="text-sm leading-relaxed text-gray-400">
                        The {{ store()->store_name }} is the biggest market of grocery products. Get your daily needs
                        from our store.
                    </p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-white mb-4">Category</h3>
                    <ul class="space-y-2 text-sm">
                        @if (isset($categories) && $categories->count())
                            @foreach ($categories as $category)
                                <li><a href="{{ route('products', ['category' => $category->id]) }}"
                                        class="hover:text-white">{{ $category->category_name }}</a></li>
                            @endforeach
                        @else
                            <li><span class="text-gray-400">No categories available</span></li>
                        @endif
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-white mb-4">Contact</h3>
                    <div class="space-y-3 text-sm">
                        @if (store()->address)
                            <div class="flex items-start space-x-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-5 w-5 mt-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                </svg>
                                <p>{{ store()->address }}</p>
                            </div>
                        @endif

                        @if (store()->whatsapp)
                            <div class="flex items-start space-x-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-1" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M12 2a10 10 0 00-8.94 14.36L2 22l5.78-1.9A10 10 0 1012 2zm0 18a8 8 0 01-4.28-1.22l-.31-.2-3.43 1.13 1.14-3.27-.21-.32A8 8 0 1112 20zm4.26-5.47c-.23-.12-1.37-.68-1.58-.76s-.37-.12-.53.12-.62.76-.76.92-.28.17-.51.05a6.54 6.54 0 01-1.92-1.19 7.12 7.12 0 01-1.31-1.62c-.14-.23 0-.35.1-.47s.23-.28.35-.42a1.56 1.56 0 00.23-.39.43.43 0 00-.02-.41c-.06-.12-.53-1.27-.73-1.74s-.39-.4-.53-.41h-.45a.86.86 0 00-.62.29A2.62 2.62 0 007 9.83 4.57 4.57 0 007.92 12a10.62 10.62 0 003.61 3.61 7.87 7.87 0 002.47 1c.26.04.5.03.68-.02a2.1 2.1 0 001.37-1.1 1.72 1.72 0 00.12-1c-.05-.09-.21-.15-.41-.25z" />
                                </svg>
                                <p>{{ store()->whatsapp }}</p>
                            </div>
                        @endif

                        <div class="space-y-2">
                            @if (store()->facebook)
                                <div class="flex items-start space-x-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-1" fill="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.988H8.078V12h2.36V9.797c0-2.332 1.393-3.622 3.522-3.622.999 0 2.043.177 2.043.177v2.248h-1.151c-1.136 0-1.493.704-1.493 1.425V12h2.543l-.406 2.89h-2.137v6.988C18.343 21.128 22 16.991 22 12z" />
                                    </svg>
                                    <p>{{ store()->facebook }}</p>
                                </div>
                            @endif

                            @if (store()->instagram)
                                <div class="flex items-start space-x-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-1" fill="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M7 2C4.243 2 2 4.243 2 7v10c0 2.757 2.243 5 5 5h10c2.757 0 5-2.243 5-5V7c0-2.757-2.243-5-5-5H7zm10 2c1.654 0 3 1.346 3 3v10c0 1.654-1.346 3-3 3H7c-1.654 0-3-1.346-3-3V7c0-1.654 1.346-3 3-3h10zM12 7a5 5 0 100 10 5 5 0 000-10zm0 2a3 3 0 110 6 3 3 0 010-6zm4.5-3a1.5 1.5 0 11-3-.001 1.5 1.5 0 013 .001z" />
                                    </svg>
                                    <p>{{ store()->instagram }}</p>
                                </div>
                            @endif

                            @if (store()->tiktok)
                                <div class="flex items-start space-x-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5 mt-1"
                                        fill="currentColor">
                                        <path
                                            d="M16.5 3c.2 1.3 1.1 2.3 2.3 2.6.3.1.6.1.9.2v2.3c-.4 0-.8-.1-1.2-.2-.5-.1-1-.3-1.5-.6v6.6c0 3-2.2 5.7-5.3 6-3.3.4-6.2-2-6.6-5.2-.4-3.3 2-6.2 5.2-6.6.4-.1.9-.1 1.3 0v2.4c-.3-.1-.6-.1-.9 0-1.6.2-2.8 1.7-2.6 3.4.2 1.6 1.7 2.8 3.4 2.6 1.5-.2 2.6-1.5 2.6-3V3h3.4z" />
                                    </svg>
                                    <p>{{ store()->tiktok }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-white mb-4">Profile</h3>
                    <div class="space-y-3 text-sm">
                        @auth
                            <p class="text-gray-400">Welcome back!</p>
                            <div class="text-gray-300">
                                <p><strong>Name:</strong> {{ Auth::user()->name }}</p>
                                <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                            </div>
                            @if (store()->address)
                                <div class="mt-3">
                                    <p><strong>Alamat :</strong> {{ store()->address }}</p>
                                </div>
                            @endif
                        @else
                            <p class="text-gray-400">Please login to access your account.</p>
                            <div class="space-y-2 mt-3">
                                <a href="{{ route('login') }}" class="block hover:text-white">Login</a>
                                <a href="{{ route('register.form') }}" class="block hover:text-white">Register</a>
                            </div>
                        @endauth
                    </div>
                </div>

            </div>

            <div
                class="border-t border-gray-700 mt-10 pt-6 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-sm text-gray-400">
                    Copyright © 2025
                    <span class="text-white font-semibold">{{ store()->store_name ?? 'The Mantu' }}</span>
                    all rights reserved.
                </p>

                <div class="flex items-center space-x-4">
                    @if (store()->whatsapp)
                        <a href="https://wa.me/{{ store()->whatsapp }}" target="_blank"
                            class="bg-green-500 text-white px-4 py-2 rounded-full shadow-lg flex items-center gap-2 hover:bg-green-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M12 2a10 10 0 00-8.94 14.36L2 22l5.78-1.9A10 10 0 1012 2zm0 18a8 8 0 01-4.28-1.22l-.31-.2-3.43 1.13 1.14-3.27-.21-.32A8 8 0 1112 20zm4.26-5.47c-.23-.12-1.37-.68-1.58-.76s-.37-.12-.53.12-.62.76-.76.92-.28.17-.51.05a6.54 6.54 0 01-1.92-1.19 7.12 7.12 0 01-1.31-1.62c-.14-.23 0-.35.1-.47s.23-.28.35-.42a1.56 1.56 0 00.23-.39.43.43 0 00-.02-.41c-.06-.12-.53-1.27-.73-1.74s-.39-.4-.53-.41h-.45a.86.86 0 00-.62.29A2.62 2.62 0 007 9.83 4.57 4.57 0 007.92 12a10.62 10.62 0 003.61 3.61 7.87 7.87 0 002.47 1c.26.04.5.03.68-.02a2.1 2.1 0 001.37-1.1 1.72 1.72 0 00.12-1c-.05-.09-.21-.15-.41-.25z" />
                            </svg>
                            <span class="font-semibold text-sm">Hubungi Kami</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </footer>



    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const mobileMenu = document.getElementById('mobile-menu');
            const hamburgerIcon = document.getElementById('hamburger-icon');
            const closeIcon = document.getElementById('close-icon');

            function toggleMobileMenu() {
                const isHidden = mobileMenu.classList.contains('hidden');
                if (isHidden) {
                    mobileMenu.classList.remove('hidden');
                    mobileSidebar.classList.remove('hidden');
                    hamburgerIcon.classList.add('hidden');
                    closeIcon.classList.remove('hidden');
                } else {
                    mobileMenu.classList.add('hidden');
                    mobileSidebar.classList.add('hidden');
                    hamburgerIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                }
            }

            mobileMenuBtn.addEventListener('click', toggleMobileMenu);
            mobileSidebar.addEventListener('click', toggleMobileMenu);

            // Close menu when a link is clicked
            document.querySelectorAll('#mobile-menu a, #mobile-menu button[type="submit"]').forEach(link => {
                link.addEventListener('click', function() {
                    if (!this.querySelector('svg')) {
                        toggleMobileMenu();
                    }
                });
            });

            // ==========================================
            // Mobile Submenu Toggle
            // ==========================================
            function initMobileSubmenu(btnId, menuId, iconId) {
                const btn = document.getElementById(btnId);
                const menu = document.getElementById(menuId);
                const icon = document.getElementById(iconId);

                if (!btn || !menu || !icon) return;

                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const isHidden = menu.classList.contains('hidden');
                    if (isHidden) {
                        menu.classList.remove('hidden');
                        icon.style.transform = 'rotate(180deg)';
                    } else {
                        menu.classList.add('hidden');
                        icon.style.transform = 'rotate(0deg)';
                    }
                });
            }

            initMobileSubmenu('mobile-categories-btn', 'mobile-categories-menu', 'mobile-categories-icon');
            initMobileSubmenu('mobile-products-btn', 'mobile-products-menu', 'mobile-products-icon');
            initMobileSubmenu('mobile-pages-btn', 'mobile-pages-menu', 'mobile-pages-icon');

            // ==========================================
            // Desktop Dropdown Management System
            // ==========================================

            // Helper function untuk manage hover-delay dropdowns
            function initHoverDelayDropdown(dropdownSelector, menuSelector, delayMs = 500) {
                const dropdown = document.querySelector(dropdownSelector);
                const menu = document.querySelector(menuSelector);
                let timeoutId;

                if (!dropdown || !menu) return;

                dropdown.addEventListener('mouseenter', function() {
                    clearTimeout(timeoutId);
                    menu.classList.remove('hidden');
                    menu.style.display = 'block';
                });

                dropdown.addEventListener('mouseleave', function() {
                    timeoutId = setTimeout(() => {
                        menu.classList.add('hidden');
                        menu.style.display = 'none';
                    }, delayMs);
                });

                // Close on link click
                const links = menu.querySelectorAll('a');
                links.forEach(link => {
                    link.addEventListener('click', () => {
                        clearTimeout(timeoutId);
                        menu.classList.add('hidden');
                        menu.style.display = 'none';
                    });
                });
            }

            // 1. Categories Dropdown - Hover dengan Delay 500ms
            initHoverDelayDropdown('.categories-dropdown', '.categories-menu', 500);

            // 2. Products Dropdown - Hover dengan Delay 500ms
            initHoverDelayDropdown('.products-dropdown', '.products-menu', 500);

            // 3. Pages Dropdown - Hover dengan Delay 500ms
            initHoverDelayDropdown('.pages-dropdown', '.pages-menu', 500);

            // 4. Profile Dropdown - Click Toggle
            const profileDropdown = document.querySelector('.profile-dropdown');
            const profileMenu = document.querySelector('.profile-menu');
            const profileButton = profileDropdown ? profileDropdown.querySelector('button') : null;

            if (profileDropdown && profileMenu && profileButton) {
                // Toggle menu on button click
                profileButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isHidden = profileMenu.classList.contains('hidden');
                    if (isHidden) {
                        profileMenu.classList.remove('hidden');
                        profileMenu.style.display = 'block';
                    } else {
                        profileMenu.classList.add('hidden');
                        profileMenu.style.display = 'none';
                    }
                });

                // Close on outside click
                document.addEventListener('click', function(e) {
                    if (!profileDropdown.contains(e.target)) {
                        profileMenu.classList.add('hidden');
                        profileMenu.style.display = 'none';
                    }
                });

                // Close on Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        if (!profileMenu.classList.contains('hidden')) {
                            profileMenu.classList.add('hidden');
                            profileMenu.style.display = 'none';
                        }
                    }
                });

                // Close on link click
                const profileLinks = profileMenu.querySelectorAll('a');
                profileLinks.forEach(link => {
                    link.addEventListener('click', () => {
                        profileMenu.classList.add('hidden');
                        profileMenu.style.display = 'none';
                    });
                });
            }

            // Debugging
            console.log('✓ Navbar system initialized');
            console.log('Mobile Menu:', mobileMenu ? '✓' : '✗');
            console.log('Categories:', document.querySelector('.categories-dropdown') ? '✓' : '✗');
            console.log('Products:', document.querySelector('.products-dropdown') ? '✓' : '✗');
            console.log('Pages:', document.querySelector('.pages-dropdown') ? '✓' : '✗');
            console.log('Profile:', profileDropdown ? '✓' : '✗');
        });
    </script>


</body>

</html>
