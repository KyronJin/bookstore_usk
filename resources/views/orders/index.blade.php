@extends('layouts.app')

@section('title', 'Pesanan Saya - JeBook')

@section('content')
<div class="bg-gray-50 py-10 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-dark mb-6"><i class="fa-solid fa-box mr-2 text-gray-400"></i>Pesanan Saya</h1>

        @if($orders->isEmpty())
        <div class="text-center py-16 bg-white rounded-lg border border-gray-200">
            <i class="fa-solid fa-box-open text-5xl text-gray-200 mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-500 mb-2">Belum ada pesanan</h3>
            <p class="text-gray-400 text-sm mb-5">Mulai belanja dan temukan buku favorit Anda!</p>
            <a href="{{ route('home') }}" class="bg-primary text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-800 transition">
                <i class="fa-solid fa-book mr-1"></i> Mulai Belanja
            </a>
        </div>
        @else
        <div class="space-y-4">
            @foreach($orders as $order)
            @php
                $colors = ['pending'=>'yellow','processing'=>'blue','shipped'=>'purple','delivered'=>'green','cancelled'=>'red'];
                $icons = ['pending'=>'fa-clock','processing'=>'fa-box','shipped'=>'fa-truck-fast','delivered'=>'fa-circle-check','cancelled'=>'fa-circle-xmark'];
                $color = $colors[$order->status] ?? 'gray';
                $icon = $icons[$order->status] ?? 'fa-info-circle';
            @endphp
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <!-- Order Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 border-b border-gray-100 bg-gray-50">
                    <div>
                        <p class="font-semibold text-dark text-sm">{{ $order->order_code }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="mt-2 sm:mt-0 flex items-center gap-3">
                        <a href="{{ route('orders.invoice', $order) }}" target="_blank" class="text-xs text-primary hover:text-blue-800 font-medium transition" title="Cetak Invoice">
                            <i class="fa-solid fa-print"></i> Invoice
                        </a>
                        <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-medium
                            @if($color === 'yellow') bg-yellow-50 text-yellow-700
                            @elseif($color === 'blue') bg-blue-50 text-blue-700
                            @elseif($color === 'purple') bg-purple-50 text-purple-700
                            @elseif($color === 'green') bg-green-50 text-green-700
                            @elseif($color === 'red') bg-red-50 text-red-700
                            @else bg-gray-50 text-gray-700 @endif">
                            <i class="fa-solid {{ $icon }} mr-1"></i> {{ $order->status_label }}
                        </span>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="p-4">
                    @foreach($order->items as $item)
                    <div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-b-0">
                        <img src="{{ $item->book->image_url ?: 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=100' }}"
                             alt="{{ $item->book->title }}" class="w-10 h-14 object-cover rounded flex-shrink-0">
                        <div class="flex-grow">
                            <p class="font-medium text-dark text-sm">{{ $item->book->title }}</p>
                            <p class="text-xs text-gray-400">{{ $item->quantity }} × Rp {{ number_format((float)$item->price, 0, ',', '.') }}</p>
                        </div>
                        <span class="font-semibold text-primary text-sm">Rp {{ number_format((float)$item->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>

                <!-- Order Footer -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center px-4 py-3 bg-gray-50 border-t border-gray-100">
                    <div class="text-sm text-gray-500">
                        <span class="font-medium">Metode:</span>
                        {{ $order->payment_method === 'cod' ? 'COD' : 'Transfer Bank' }}
                    </div>
                    <div class="mt-2 sm:mt-0 text-right">
                        <p class="text-xs text-gray-400">Total</p>
                        <p class="text-lg font-bold text-primary">Rp {{ number_format((float)$order->grand_total, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
