@extends('layouts.app')

@section('title', 'Pesanan Saya - PustakaBiru')

@section('content')
<div class="bg-gray-50 py-12 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-dark mb-8"><i class="fa-solid fa-box mr-2 text-secondary"></i>Pesanan Saya</h1>

        @if($orders->isEmpty())
        <div class="text-center py-20 bg-white rounded-2xl shadow-sm border border-gray-100">
            <i class="fa-solid fa-box-open text-6xl text-gray-200 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-500 mb-2">Belum ada pesanan</h3>
            <p class="text-gray-400 mb-6">Mulai belanja dan temukan buku favorit Anda!</p>
            <a href="{{ route('home') }}" class="bg-secondary text-white px-8 py-3 rounded-full font-medium hover:bg-blue-600 transition">
                <i class="fa-solid fa-book mr-2"></i> Mulai Belanja
            </a>
        </div>
        @else
        <div class="space-y-6">
            @foreach($orders as $order)
            @php
                $colors = ['pending'=>'yellow','processing'=>'blue','shipped'=>'purple','delivered'=>'green','cancelled'=>'red'];
                $color = $colors[$order->status] ?? 'gray';
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Order Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-5 border-b border-gray-100 bg-gray-50">
                    <div>
                        <p class="font-bold text-dark">{{ $order->order_code }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <span class="mt-2 sm:mt-0 inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                        @if($color === 'yellow') bg-yellow-100 text-yellow-700
                        @elseif($color === 'blue') bg-blue-100 text-blue-700
                        @elseif($color === 'purple') bg-purple-100 text-purple-700
                        @elseif($color === 'green') bg-green-100 text-green-700
                        @elseif($color === 'red') bg-red-100 text-red-700
                        @else bg-gray-100 text-gray-700 @endif">
                        {{ $order->status_label }}
                    </span>
                </div>

                <!-- Order Items -->
                <div class="p-5">
                    @foreach($order->items as $item)
                    <div class="flex items-center gap-4 py-3 border-b border-gray-50 last:border-b-0">
                        <img src="{{ $item->book->image_url ?: 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=100' }}"
                             alt="{{ $item->book->title }}" class="w-12 h-16 object-cover rounded-lg flex-shrink-0">
                        <div class="flex-grow">
                            <p class="font-semibold text-dark text-sm">{{ $item->book->title }}</p>
                            <p class="text-xs text-gray-500">{{ $item->quantity }} × Rp {{ number_format((float)$item->price, 0, ',', '.') }}</p>
                        </div>
                        <span class="font-bold text-primary text-sm">Rp {{ number_format((float)$item->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>

                <!-- Order Footer -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center px-5 py-4 bg-gray-50 border-t border-gray-100">
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">Metode:</span>
                        {{ $order->payment_method === 'cod' ? 'Bayar di Tempat (COD)' : 'Transfer Bank' }}
                    </div>
                    <div class="mt-2 sm:mt-0 text-right">
                        <p class="text-xs text-gray-500">Total Pembayaran</p>
                        <p class="text-xl font-bold text-primary">Rp {{ number_format((float)$order->grand_total, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
