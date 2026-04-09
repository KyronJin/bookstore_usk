@extends('layouts.app')

@section('title', 'Checkout - JeBook')

@section('content')
<div class="bg-gray-50 py-10 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-dark mb-6"><i class="fa-solid fa-bag-shopping mr-2 text-gray-400"></i>Checkout</h1>

        <form action="{{ route('checkout.place') }}" method="POST">
            @csrf
            <input type="hidden" name="items" value="{{ $selectedItemsStr }}">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Left: Form -->
                <div class="lg:w-2/3 space-y-5">

                    <!-- Alamat Pengiriman -->
                    <div class="bg-white rounded-lg border border-gray-200 p-5">
                        <h3 class="font-bold text-dark mb-3 text-sm">Alamat Pengiriman</h3>
                        <div>
                            <label class="block text-sm text-gray-600 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="shipping_address" rows="3" required
                                      class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-secondary focus:border-secondary resize-none {{ $errors->has('shipping_address') ? 'border-red-400' : '' }}"
                                      placeholder="Contoh: Jl. Merdeka No. 10, RT 02/RW 05, Jakarta Pusat 10110">{{ old('shipping_address', auth()->user()->address) }}</textarea>
                            @error('shipping_address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label class="block text-sm text-gray-600 mb-1">Catatan Pesanan <span class="text-gray-400">(opsional)</span></label>
                            <input type="text" name="notes"
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-1 focus:ring-secondary"
                                   placeholder="Contoh: Tolong dibungkus rapi ya" value="{{ old('notes') }}">
                        </div>
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="bg-white rounded-lg border border-gray-200 p-5" x-data="{ payment: 'cod' }">
                        <h3 class="font-bold text-dark mb-3 text-sm">Metode Pembayaran</h3>
                        
                        <div class="space-y-2">
                            <!-- COD Option -->
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer transition"
                                   :class="payment === 'cod' ? 'border-primary bg-blue-50/50' : 'border-gray-200 hover:bg-gray-50'">
                                <input type="radio" name="payment_method" value="cod" x-model="payment" class="sr-only">
                                <div class="w-9 h-9 rounded-lg flex items-center justify-center mr-3 flex-shrink-0"
                                     :class="payment === 'cod' ? 'bg-primary/10' : 'bg-gray-100'">
                                    <i class="fa-solid fa-money-bill-wave text-sm" :class="payment === 'cod' ? 'text-primary' : 'text-gray-400'"></i>
                                </div>
                                <div class="flex-grow">
                                    <p class="font-medium text-sm text-dark">Bayar di Tempat (COD)</p>
                                    <p class="text-xs text-gray-400">Bayar tunai ketika buku sampai.</p>
                                </div>
                                <i class="fa-solid fa-circle-check text-primary ml-auto" x-show="payment === 'cod'"></i>
                            </label>

                            <!-- Transfer Option -->
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer transition"
                                   :class="payment === 'transfer' ? 'border-primary bg-blue-50/50' : 'border-gray-200 hover:bg-gray-50'">
                                <input type="radio" name="payment_method" value="transfer" x-model="payment" class="sr-only">
                                <div class="w-9 h-9 rounded-lg flex items-center justify-center mr-3 flex-shrink-0"
                                     :class="payment === 'transfer' ? 'bg-primary/10' : 'bg-gray-100'">
                                    <i class="fa-solid fa-building-columns text-sm" :class="payment === 'transfer' ? 'text-primary' : 'text-gray-400'"></i>
                                </div>
                                <div class="flex-grow">
                                    <p class="font-medium text-sm text-dark">Transfer Bank</p>
                                    <p class="text-xs text-gray-400">Transfer manual (BCA, Mandiri).</p>
                                </div>
                                <i class="fa-solid fa-circle-check text-primary ml-auto" x-show="payment === 'transfer'" style="display:none"></i>
                            </label>
                        </div>

                        <!-- Bank Info Box (shown if transfer) -->
                        <div x-show="payment === 'transfer'" x-transition class="mt-4 space-y-3" style="display:none">
                            <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                                <p class="text-sm font-medium text-dark mb-2">Tujuan Transfer:</p>
                                <ul class="text-sm text-gray-700 space-y-2">
                                    <li class="flex justify-between items-center bg-white p-2 border border-gray-100 rounded">
                                        <span class="font-medium text-sm">BCA</span>
                                        <span class="font-semibold text-primary select-all">1234 5678 90</span>
                                    </li>
                                    <li class="flex justify-between items-center bg-white p-2 border border-gray-100 rounded">
                                        <span class="font-medium text-sm">Mandiri</span>
                                        <span class="font-semibold text-primary select-all">098 7654 321</span>
                                    </li>
                                </ul>
                                <p class="text-xs text-gray-400 mt-2">A.N. JeBook Indonesia.</p>
                            </div>
                            
                            <!-- Input Detail Bank Pengirim -->
                            <div class="p-4 bg-white border border-gray-200 rounded-lg">
                                <p class="text-sm font-medium text-dark mb-3">Informasi Rekening Pengirim:</p>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-xs text-gray-600 mb-1">Nama Bank Asal <span class="text-red-500" x-show="payment === 'transfer'">*</span></label>
                                        <select name="transfer_bank_name" x-bind:required="payment === 'transfer'"
                                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-secondary bg-white">
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
                                        <label class="block text-xs text-gray-600 mb-1">Nama Pemilik Rekening <span class="text-red-500" x-show="payment === 'transfer'">*</span></label>
                                        <input type="text" name="transfer_account_name" x-bind:required="payment === 'transfer'"
                                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-secondary"
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
                    <div class="bg-white rounded-lg border border-gray-200 p-5">
                        <h3 class="font-bold text-dark mb-3 text-sm">Buku yang Dipesan</h3>
                        <div class="space-y-3">
                            @foreach($cart as $item)
                            <div class="flex items-center gap-3">
                                <img src="{{ $item['image_url'] ?: 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=100' }}"
                                     alt="{{ $item['title'] }}" class="w-12 h-16 object-cover rounded-lg flex-shrink-0">
                                <div class="flex-grow">
                                    <p class="font-medium text-dark text-sm">{{ $item['title'] }}</p>
                                    <p class="text-xs text-gray-400">{{ $item['author'] }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $item['quantity'] }} × Rp {{ number_format((float)$item['price'], 0, ',', '.') }}</p>
                                </div>
                                <span class="font-semibold text-primary text-sm">Rp {{ number_format((float)$item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right: Summary -->
                <div class="lg:w-1/3">
                    <div class="bg-white rounded-lg border border-gray-200 p-5 sticky top-24">
                        <h3 class="font-bold text-dark mb-4">Ringkasan Pembayaran</h3>

                        <div class="space-y-2 text-sm text-gray-600 border-b border-gray-100 pb-4 mb-4">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span class="font-medium">Rp {{ number_format((float)$subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Pajak (12%)</span>
                                <span class="font-medium">Rp {{ number_format((float)$tax, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Ongkos Kirim</span>
                                <span class="font-medium">Rp {{ number_format((float)$shippingCost, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center mb-5">
                            <span class="font-semibold text-dark text-sm">Total Pembayaran</span>
                            <span class="text-xl font-bold text-primary">Rp {{ number_format((float)$total, 0, ',', '.') }}</span>
                        </div>

                        <button type="submit" class="w-full bg-primary hover:bg-blue-800 text-white font-medium py-3 px-4 rounded-lg transition text-sm">
                            <i class="fa-solid fa-check-circle mr-1.5"></i> Buat Pesanan
                        </button>

                        <a href="{{ route('cart.index') }}" class="block text-center mt-3 text-sm text-gray-400 hover:text-gray-600 transition">
                            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Keranjang
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
