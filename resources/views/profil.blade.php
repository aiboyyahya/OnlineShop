@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page_title', 'Profil Saya')
@section('breadcrumb', 'Home » Profil Saya')

@section('content')
    <section class="min-h-screen bg-white py-12 px-6">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div class="bg-white p-6 rounded-2xl shadow-sm flex items-center gap-5">
                        <div class="bg-blue-600 rounded-xl w-20 h-20 flex items-center justify-center overflow-hidden">
                            @if ($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                    class="w-full h-full object-cover rounded-xl">
                            @else
                                <span class="text-white text-3xl font-bold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold">Foto Profil</h3>
                            <p class="text-gray-500 text-sm">Klik foto untuk mengganti</p>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm flex gap-5">
                        <div class="bg-blue-600 text-white p-4 rounded-xl text-xl flex items-center justify-center">
                            👤
                        </div>
                        <div>
                            <h3 class="text-gray-600 text-sm">Nama Lengkap</h3>
                            <p class="text-gray-900 text-lg font-semibold">{{ $user->name }}</p>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm flex gap-5">
                        <div class="bg-blue-600 text-white p-4 rounded-xl text-xl flex items-center justify-center">
                            ✉️
                        </div>
                        <div>
                            <h3 class="text-gray-600 text-sm">Email</h3>
                            <p class="text-gray-900 text-lg font-semibold">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm flex gap-5">
                        <div class="bg-blue-600 text-white p-4 rounded-xl text-xl flex items-center justify-center">
                            📞
                        </div>
                        <div>
                            <h3 class="text-gray-600 text-sm">Nomor Telepon</h3>
                            <p class="text-gray-900 text-lg font-semibold">
                                {{ $user->phone_number ?? 'Belum diatur' }}
                            </p>
                        </div>
                    </div>

                </div>
                <div>
                    <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data"
                        class="bg-white p-8 rounded-2xl shadow-sm space-y-6">
                        @csrf
                        @method('PUT')

                        <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*">
                        <div class="space-y-4">
                            <input type="text" name="name" placeholder="Nama Lengkap"
                                value="{{ old('name', $user->name) }}"
                                class="w-full px-4 py-3 rounded-xl border-gray-200 shadow-sm focus:ring-blue-500">

                            <input type="email" name="email" placeholder="Email"
                                value="{{ old('email', $user->email) }}"
                                class="w-full px-4 py-3 rounded-xl border-gray-200 shadow-sm focus:ring-blue-500">

                            <input type="text" name="phone_number" placeholder="Nomor Telepon"
                                value="{{ old('phone_number', $user->phone_number) }}"
                                class="w-full px-4 py-3 rounded-xl border-gray-200 shadow-sm focus:ring-blue-500">

                            <input type="text" name="address" placeholder="Alamat"
                                value="{{ old('address', $user->address) }}"
                                class="w-full px-4 py-3 rounded-xl border-gray-200 shadow-sm focus:ring-blue-500">
                        </div>

                        <div class="space-y-4">
                            <input type="password" name="password" placeholder="Password Baru"
                                class="w-full px-4 py-3 rounded-xl border-gray-200 shadow-sm focus:ring-blue-500">

                            <input type="password" name="password_confirmation" placeholder="Konfirmasi Password"
                                class="w-full px-4 py-3 rounded-xl border-gray-200 shadow-sm focus:ring-blue-500">
                        </div>

                        <button
                            class="px-8 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </section>

    <script>
        document.querySelector('.bg-blue-600.rounded-xl.w-20').addEventListener('click', () => {
            document.getElementById('profile_photo').click();
        });
    </script>
@endsection
