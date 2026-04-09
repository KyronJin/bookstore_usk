@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.orders.index') }}" class="text-gray-500 hover:text-primary transition">
            <i class="fa-solid fa-arrow-left text-lg"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-dark">Detail Pesanan</h1>
            <p class="text-gray-500 text-sm font-mono">{{ $order->order_code }}</p>
        </div>
        <div class="ml-auto">
            <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 flex items-center transition shadow-sm">
                <i class="fa-solid fa-print mr-2"></i> Cetak Invoice
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Items -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 font-bold text-dark">Buku yang Dipesan</div>
                @foreach($order->items as $item)
                <div class="flex items-center gap-4 p-5 border-b border-gray-50 last:border-b-0">
                    <img src="{{ $item->book->image_url ?: 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=100' }}"
                         alt="{{ $item->book->title }}" class="w-16 h-20 object-cover rounded-lg">
                    <div class="flex-grow">
                        <p class="font-semibold text-dark">{{ $item->book->title }}</p>
                        <p class="text-sm text-gray-500">{{ $item->book->author }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $item->quantity }} × Rp {{ number_format((float)$item->price, 0, ',', '.') }}</p>
                    </div>
                    <p class="font-bold text-primary">Rp {{ number_format((float)$item->subtotal, 0, ',', '.') }}</p>
                </div>
                @endforeach
                <div class="p-5 bg-gray-50 space-y-2 text-sm">
                    <div class="flex justify-between text-gray-600"><span>Subtotal</span><span>Rp {{ number_format((float)$order->total_price, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between text-gray-600"><span>Pajak (12%)</span><span>Rp {{ number_format((float)$order->tax, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between text-gray-600"><span>Ongkos Kirim</span><span>Rp {{ number_format((float)$order->shipping_cost, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between font-bold text-dark text-base border-t border-gray-200 pt-2">
                        <span>Total Pembayaran</span>
                        <span class="text-primary">Rp {{ number_format((float)$order->grand_total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Info -->
        <div class="space-y-6">
            <!-- Update Status -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-bold text-dark mb-4">Update Status Pesanan</h3>
                @php $color = $order->status_color; @endphp
                <p class="text-sm text-gray-600 mb-3">Status saat ini:
                    <span class="font-bold
                        @if($color==='yellow') text-yellow-600
                        @elseif($color==='blue') text-blue-600
                        @elseif($color==='purple') text-purple-600
                        @elseif($color==='green') text-green-600
                        @elseif($color==='red') text-red-600
                        @else text-gray-600 @endif">
                        {{ $order->status_label }}
                    </span>
                </p>
                <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="space-y-3">
                    @csrf @method('PATCH')
                    <select name="status" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Sedang Diproses</option>
                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Sedang Dikirim</option>
                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Sudah Diterima</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    <button type="submit" class="w-full bg-primary text-white py-2.5 rounded-xl font-medium hover:bg-blue-800 transition">
                        <i class="fa-solid fa-check mr-2"></i> Update Status
                    </button>
                </form>
            </div>

            <!-- Customer Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-bold text-dark mb-4">Informasi Pelanggan</h3>
                <div class="space-y-2 text-sm">
                    <p class="text-gray-600"><span class="font-medium text-dark">Nama:</span> {{ $order->user->name }}</p>
                    <p class="text-gray-600"><span class="font-medium text-dark">Email:</span> {{ $order->user->email }}</p>
                    <p class="text-gray-600"><span class="font-medium text-dark">Telepon:</span> {{ $order->user->phone ?: '-' }}</p>
                </div>
            </div>

            <!-- Shipping Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-bold text-dark mb-4">Pengiriman & Pembayaran</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="font-medium text-dark block mb-1">Metode Bayar:</span> 
                        @if($order->payment_method === 'cod')
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold flex inline-flex items-center"><i class="fa-solid fa-money-bill-wave mr-1.5"></i> COD (Bayar di Tempat)</span>
                        @else
                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-bold flex inline-flex items-center"><i class="fa-solid fa-building-columns mr-1.5"></i> Transfer Bank</span>
                            <div class="mt-2 bg-blue-50 border border-blue-100 p-3 rounded-lg">
                                <span class="text-xs text-gray-500 font-medium block">Bank Pengirim:</span>
                                <span class="font-bold text-dark">{{ $order->transfer_bank_name ?: '-' }}</span>
                                <span class="text-xs text-gray-500 font-medium block mt-2">Atas Nama:</span>
                                <span class="font-bold text-dark">{{ $order->transfer_account_name ?: '-' }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="pt-2 border-t border-gray-100">
                        <span class="font-medium text-dark block mb-1">Alamat:</span>
                        <p class="text-gray-600">{{ $order->shipping_address }}</p>
                    </div>
                    @if($order->notes)
                    <div class="pt-2 border-t border-gray-100">
                        <span class="font-medium text-dark block mb-1">Catatan:</span>
                        <p class="text-gray-600 italic bg-yellow-50 p-3 rounded-lg border border-yellow-100 text-xs">{{ $order->notes }}</p>
                    </div>
                    @endif
                    <div class="pt-2 border-t border-gray-100">
                        <span class="font-medium text-dark block mb-1">Waktu Dipesan:</span> 
                        <p class="text-gray-600">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
