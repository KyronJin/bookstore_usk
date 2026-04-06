@extends('layouts.app')

@section('title', 'Checkout - PustakaBiru')

@section('content')
<div class="bg-gray-50 py-12 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-dark mb-8"><i class="fa-solid fa-bag-shopping mr-2 text-secondary"></i>Checkout</h1>

        <form action="{{ route('checkout.place') }}" method="POST">
            @csrf
            <input type="hidden" name="items" value="{{ $selectedItemsStr }}">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Left: Form -->
                <div class="lg:w-2/3 space-y-6">

                    <!-- Alamat Pengiriman -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-dark mb-4"><i class="fa-solid fa-location-dot mr-2 text-secondary"></i>Alamat Pengiriman</h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="shipping_address" rows="4" required
                                      class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent resize-none {{ $errors->has('shipping_address') ? 'border-red-400' : '' }}"
                                      placeholder="Contoh: Jl. Merdeka No. 10, RT 02/RW 05, Kelurahan Merdeka, Kecamatan Gambir, Jakarta Pusat 10110">{{ old('shipping_address', auth()->user()->address) }}</textarea>
                            @error('shipping_address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Pesanan <span class="text-gray-400">(opsional)</span></label>
                            <input type="text" name="notes"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-secondary"
                                   placeholder="Contoh: Tolong dibungkus rapi ya" value="{{ old('notes') }}">
                        </div>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6" x-data="{ payment: 'cod' }">
                        <h3 class="text-lg font-bold text-dark mb-4"><i class="fa-solid fa-credit-card mr-2 text-secondary"></i>Metode Pembayaran</h3>
                        
                        <div class="space-y-3">
                            <!-- COD Option -->
                            <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all"
                                   :class="payment === 'cod' ? 'border-green-400 bg-green-50' : 'border-gray-200 hover:bg-gray-50'">
                                <input type="radio" name="payment_method" value="cod" x-model="payment" class="sr-only">
                                <div class="bg-green-100 rounded-xl p-3 mr-4 flex-shrink-0" :class="payment === 'cod' ? 'bg-green-100' : 'bg-gray-100'">
                                    <i class="fa-solid fa-money-bill-wave text-xl" :class="payment === 'cod' ? 'text-green-600' : 'text-gray-400'"></i>
                                </div>
                                <div class="flex-grow">
                                    <p class="font-bold text-dark">Bayar di Tempat (COD)</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Bayar tunai ketika buku sampai.</p>
                                </div>
                                <i class="fa-solid fa-circle-check text-green-500 text-xl ml-auto" x-show="payment === 'cod'"></i>
                            </label>

                            <!-- Transfer Option -->
                            <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all"
                                   :class="payment === 'transfer' ? 'border-blue-400 bg-blue-50' : 'border-gray-200 hover:bg-gray-50'">
                                <input type="radio" name="payment_method" value="transfer" x-model="payment" class="sr-only">
                                <div class="rounded-xl p-3 mr-4 flex-shrink-0" :class="payment === 'transfer' ? 'bg-blue-100' : 'bg-gray-100'">
                                    <i class="fa-solid fa-building-columns text-xl" :class="payment === 'transfer' ? 'text-blue-600' : 'text-gray-400'"></i>
                                </div>
                                <div class="flex-grow">
                                    <p class="font-bold text-dark">Transfer Bank</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Transfer manual (BCA, Mandiri).</p>
                                </div>
                                <i class="fa-solid fa-circle-check text-blue-500 text-xl ml-auto" x-show="payment === 'transfer'" style="display:none"></i>
                            </label>
                        </div>

                        <!-- Bank Info Box (shown if transfer) -->
                        <div x-show="payment === 'transfer'" x-transition class="mt-4 space-y-4" style="display:none">
                            <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                                <p class="text-sm font-semibold text-dark mb-2">Tujuan Transfer (Rekening Toko):</p>
                                <ul class="text-sm text-gray-700 space-y-2 font-mono">
                                    <li class="flex justify-between items-center bg-white p-2 border border-gray-100 rounded shadow-sm">
                                        <span class="font-sans font-bold">BCA</span>
                                        <span class="font-bold text-primary text-base select-all">1234 5678 90</span>
                                    </li>
                                    <li class="flex justify-between items-center bg-white p-2 border border-gray-100 rounded shadow-sm">
                                        <span class="font-sans font-bold">Mandiri</span>
                                        <span class="font-bold text-primary text-base select-all">098 7654 321</span>
                                    </li>
                                </ul>
                                <p class="text-xs text-gray-500 mt-3 font-semibold"><i class="fa-solid fa-circle-info mr-1 text-blue-500"></i>A.N. PustakaBiru Indonesia.</p>
                            </div>
                            
                            <!-- Input Detail Bank Pengirim -->
                            <div class="p-4 bg-white border border-gray-200 rounded-xl">
                                <p class="text-sm font-semibold text-dark mb-3">Informasi Rekening Pengirim:</p>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Nama Bank Asal <span class="text-red-500" x-show="payment === 'transfer'">*</span></label>
                                        <select name="transfer_bank_name" x-bind:required="payment === 'transfer'"
                                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-secondary bg-white">
                                            <option value="" disabled selected>-- Pilih Bank --</option>
                                            <option value="BCA" {{ old('transfer_bank_name') == 'BCA' ? 'selected' : '' }}>BCA</option>
                                            <option value="Mandiri" {{ old('transfer_bank_name') == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                                            <option value="BNI" {{ old('transfer_bank_name') == 'BNI' ? 'selected' : '' }}>BNI</option>
                                            <option value="BRI" {{ old('transfer_bank_name') == 'BRI' ? 'selected' : '' }}>BRI</option>
                                            <option value="BSI" {{ old('transfer_bank_name') == 'BSI' ? 'selected' : '' }}>BSI (Bank Syariah Indonesia)</option>
                                            <option value="CIMB Niaga" {{ old('transfer_bank_name') == 'CIMB Niaga' ? 'selected' : '' }}>CIMB Niaga</option>
                                            <option value="Permata" {{ old('transfer_bank_name') == 'Permata' ? 'selected' : '' }}>Permata Bank</option>
                                            <option value="Danamon" {{ old('transfer_bank_name') == 'Danamon' ? 'selected' : '' }}>Danamon</option>
                                            <option value="Lainnya" {{ old('transfer_bank_name') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                        @error('transfer_bank_name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Nama Pemilik Rekening <span class="text-red-500" x-show="payment === 'transfer'">*</span></label>
                                        <input type="text" name="transfer_account_name" x-bind:required="payment === 'transfer'"
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-secondary"
                                               placeholder="Sesuai yang tertera di rekening" value="{{ old('transfer_account_name') }}">
                                        @error('transfer_account_name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Item Dipesan -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-dark mb-4"><i class="fa-solid fa-book mr-2 text-secondary"></i>Buku yang Dipesan</h3>
                        <div class="space-y-4">
                            @foreach($cart as $item)
                            <div class="flex items-center gap-4">
                                <img src="{{ $item['image_url'] ?: 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=100' }}"
                                     alt="{{ $item['title'] }}" class="w-14 h-18 object-cover rounded-lg shadow-sm flex-shrink-0">
                                <div class="flex-grow">
                                    <p class="font-semibold text-dark text-sm">{{ $item['title'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $item['author'] }}</p>
                                    <p class="text-xs text-gray-600 mt-1">{{ $item['quantity'] }} × Rp {{ number_format((float)$item['price'], 0, ',', '.') }}</p>
                                </div>
                                <span class="font-bold text-primary text-sm">Rp {{ number_format((float)$item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right: Summary -->
                <div class="lg:w-1/3">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                        <h3 class="text-lg font-bold text-dark mb-4">Ringkasan Pembayaran</h3>

                        <div class="space-y-3 text-sm text-gray-600 border-b border-gray-100 pb-4 mb-4">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span class="font-medium">Rp {{ number_format((float)$subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Ongkos Kirim</span>
                                <span class="font-medium">Rp {{ number_format((float)$shippingCost, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center mb-6">
                            <span class="font-bold text-dark">Total Pembayaran</span>
                            <span class="text-2xl font-bold text-primary">Rp {{ number_format((float)$total, 0, ',', '.') }}</span>
                        </div>

                        <button type="submit" class="w-full bg-accent hover:bg-yellow-500 text-white font-bold py-4 px-4 rounded-xl shadow-md transition transform hover:-translate-y-0.5">
                            <i class="fa-solid fa-check-circle mr-2"></i> Buat Pesanan
                        </button>

                        <a href="{{ route('cart.index') }}" class="block text-center mt-4 text-sm text-gray-500 hover:text-gray-700 transition">
                            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Keranjang
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
