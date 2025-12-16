@extends('layouts.app')

@section('title', 'Checkout')
@section('page_title', 'Checkout')
@section('breadcrumb', 'Home » Checkout ')
@section('content')
    <section class="py-16 min-h-screen">
        <div class="max-w-6xl mx-auto p-10 grid grid-cols-1 md:grid-cols-2 gap-12">

            <div>
                <form id="checkout-form" action="{{ route('checkout') }}" method="POST" class="space-y-8">
                    @csrf

                    <div class="border border-gray-200 rounded-3xl p-8 shadow-sm bg-white">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Shipping Information</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Recipient Name</label>
                                <input type="text" name="recipient_name" required
                                    value="{{ old('recipient_name', Auth::user()->name ?? '') }}"
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-gray-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input type="tel" name="phone_number" required
                                    value="{{ old('phone_number', Auth::user()->phone_number ?? '') }}"
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-gray-500">
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Address</label>
                            <textarea name="address" rows="3" required
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-gray-500">{{ old('address', Auth::user()->address ?? '') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Province</label>
                                <select id="province_select"
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-gray-500">
                                    <option value="">Select Province</option>
                                </select>
                                <p id="province_status" class="text-xs text-gray-500 mt-1 hidden">Loading...</p>
                                <p id="province_error" class="text-xs text-red-500 mt-1 hidden"></p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">City / Regency</label>
                                <select id="city_select"
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-gray-500">
                                    <option value="">Select City</option>
                                </select>
                                <p id="city_status" class="text-xs text-gray-500 mt-1 hidden">Loading...</p>
                                <p id="city_error" class="text-xs text-red-500 mt-1 hidden"></p>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">District</label>
                            <select id="district_select"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-gray-500">
                                <option value="">Select District</option>
                            </select>
                            <p id="district_status" class="text-xs text-gray-500 mt-1 hidden">Loading...</p>
                            <p id="district_error" class="text-xs text-red-500 mt-1 hidden"></p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Courier</label>
                                <select id="courier" name="courier"
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-gray-500">
                                    <option value="">Select Courier</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Service</label>
                                <select id="courier_service" name="courier_service"
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-gray-500">
                                    <option value="">Select Service</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                                <input type="text" name="postal_code" id="postal_code" required
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-gray-500">
                            </div>
                        </div>

                        <input type="hidden" id="province_name" name="province">
                        <input type="hidden" id="city_name" name="city">
                        <input type="hidden" id="district_name" name="district">
                        <input type="hidden" id="province_id" name="province_id">
                        <input type="hidden" id="city_id" name="city_id">
                        <input type="hidden" id="district_id" name="district_id">
                        <input type="hidden" id="shipping_cost" name="shipping_cost" value="0">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                            <textarea name="notes" rows="3" placeholder="Add a note for the seller..."
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-gray-500"></textarea>
                        </div>
                    </div>
                </form>
            </div>

            <div class="flex flex-col max-w-lg h-fit space-y-8">

                <div class="border border-gray-200 rounded-3xl p-8 shadow-sm bg-white">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">Order Details</h2>

                    <ul class="space-y-6">
                        @php $total = 0; @endphp
                        @php $items = isset($directCheckout) ? $directCheckout : $cart; @endphp

                        @foreach ($items as $id => $item)
                            @php
                                $subtotal = $item['selling_price'] * $item['quantity'];
                                $total += $subtotal;
                            @endphp

                            <li class="flex gap-4 items-center border-b border-gray-100 pb-4">
                                <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : 'https://via.placeholder.com/100' }}"
                                    class="w-24 h-20 object-cover rounded-xl">
                                <div>
                                    <p class="text-lg font-semibold text-gray-900">{{ $item['name'] }}</p>
                                    <p class="text-sm text-gray-600">Category:
                                        {{ \App\Models\Product::find($id)?->category?->category_name ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-600">Quantity: {{ $item['quantity'] }}</p>
                                </div>
                                <div class="ml-auto font-semibold text-gray-900">
                                    Rp{{ number_format($subtotal, 0, ',', '.') }}
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-8 border-t pt-6">
                        <h3 class="text-xl font-semibold mb-4">Order Summary</h3>

                        <div class="flex justify-between mb-2">
                            <span>Subtotal</span>
                            <span id="display-subtotal">Rp{{ number_format($total, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between mb-6">
                            <span>Shipping Cost</span>
                            <span id="display-shipping">Rp0</span>
                        </div>

                        <div class="flex justify-between text-lg font-bold border-t pt-4">
                            <span>Total</span>
                            <span id="display-total">Rp{{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="border border-gray-200 rounded-3xl p-8 shadow-sm bg-white">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6">Payment Method</h2>

                    <label
                        class="flex items-center space-x-3 cursor-pointer border border-gray-200 p-4 rounded-xl bg-gray-50 hover:bg-gray-100">
                        <input type="radio" name="payment_method" value="midtrans" checked>
                        <span class="font-medium text-gray-900">Midtrans (QRIS / Virtual Account / E-Wallet)</span>
                    </label>
                </div>

                <button type="submit" form="checkout-form"
                    class="mt-6 w-full bg-black text-white font-semibold py-4 rounded-xl hover:bg-gray-700 transition">
                    Confirm Order
                </button>
            </div>

        </div>
    </section>


    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {

            const provinceSelect = document.getElementById("province_select");
            const citySelect = document.getElementById("city_select");
            const districtSelect = document.getElementById("district_select");
            const courierSelect = document.getElementById("courier");
            const serviceSelect = document.getElementById("courier_service");
            const costInput = document.getElementById("shipping_cost");
            const displayShipping = document.getElementById("display-shipping");
            const displayTotal = document.getElementById("display-total");

            const originCityId = {{ $originCityId }};
            const totalWeight = {!! $totalWeight !!};

            async function loadProvinces() {
                const res = await fetch(`/rajaongkir/provinces`);
                const data = await res.json();
                provinceSelect.innerHTML = '<option value="">Select Province</option>';

                data.rajaongkir.results.forEach(p => {
                    const option = document.createElement("option");
                    option.value = p.province_id || p.id;
                    option.textContent = p.province || p.name;
                    provinceSelect.appendChild(option);
                });
            }

            async function loadCities(provinceId) {
                const res = await fetch(`/rajaongkir/cities?province_id=${provinceId}`);
                const data = await res.json();

                citySelect.innerHTML = '<option value="">Select City</option>';
                districtSelect.innerHTML = '<option value="">Select District</option>';

                data.rajaongkir.results.forEach(c => {
                    const option = document.createElement("option");
                    option.value = c.city_id;
                    option.textContent = c.city_name;
                    citySelect.appendChild(option);
                });
            }

            async function loadDistricts(cityId) {
                const res = await fetch(`/rajaongkir/districts?city_id=${cityId}`);
                const data = await res.json();

                districtSelect.innerHTML = '<option value="">Select District</option>';

                data.rajaongkir.results.forEach(d => {
                    const option = document.createElement("option");
                    option.value = d.subdistrict_id || d.district_id;
                    option.textContent = d.subdistrict_name || d.district_name;
                    districtSelect.appendChild(option);
                });
            }

            function loadCouriers() {
                courierSelect.innerHTML = `
                <option value="">Select Courier</option>
                <option value="jne">JNE</option>
                <option value="tiki">TIKI</option>
                <option value="pos">POS Indonesia</option>
                <option value="jnt">J&T Express</option>
            `;
            }

            async function loadCourierServices() {
                const courier = courierSelect.value;
                const destination = districtSelect.value || citySelect.value;

                if (!courier || !destination) {
                    serviceSelect.innerHTML = '<option value="">Select Service</option>';
                    costInput.value = 0;
                    updateTotal();
                    return;
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const res = await fetch(`/rajaongkir/cost`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: JSON.stringify({
                        origin: originCityId,
                        destination,
                        weight: totalWeight,
                        courier
                    })
                });

                const data = await res.json();
                serviceSelect.innerHTML = '<option value="">Select Service</option>';

                if (Array.isArray(data.data)) {
                    data.data.forEach(service => {
                        const option = document.createElement("option");
                        option.value = service.service;
                        option.textContent =
                            `${service.name} - ${service.description} (Rp ${service.cost.toLocaleString()})`;
                        option.dataset.cost = service.cost;
                        serviceSelect.appendChild(option);
                    });
                }
            }

            function updateTotal() {
                const subtotal = parseInt(document.getElementById("display-subtotal").textContent.replace(/[^\d]/g,
                    '')) || 0;
                const shipping = parseInt(costInput.value) || 0;
                const total = subtotal + shipping;

                displayShipping.textContent = `Rp${shipping.toLocaleString()}`;
                displayTotal.textContent = `Rp${total.toLocaleString()}`;
            }

            provinceSelect.addEventListener("change", e => {
                document.getElementById("province_id").value = e.target.value;
                document.getElementById("province_name").value = e.target.selectedOptions[0].textContent;
                loadCities(e.target.value);
            });

            citySelect.addEventListener("change", e => {
                document.getElementById("city_id").value = e.target.value;
                document.getElementById("city_name").value = e.target.selectedOptions[0].textContent;
                loadDistricts(e.target.value);
            });

            districtSelect.addEventListener("change", e => {
                document.getElementById("district_id").value = e.target.value;
                document.getElementById("district_name").value = e.target.selectedOptions[0].textContent;
                loadCourierServices();
            });

            courierSelect.addEventListener("change", loadCourierServices);

            serviceSelect.addEventListener("change", e => {
                const cost = e.target.selectedOptions[0]?.dataset.cost || 0;
                costInput.value = cost;
                updateTotal();
            });

            loadProvinces();
            loadCouriers();
        });
    </script>

@endsection
