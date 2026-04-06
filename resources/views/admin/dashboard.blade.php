@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-dark">Dashboard</h1>
        <p class="text-gray-500 text-sm mt-1">Selamat datang, {{ auth()->user()->name }}! Berikut ringkasan toko Anda hari ini.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
            <div class="bg-blue-100 rounded-xl p-3"><i class="fa-solid fa-users text-2xl text-secondary"></i></div>
            <div>
                <p class="text-sm text-gray-500">Total User</p>
                <p class="text-3xl font-bold text-dark">{{ $stats['total_users'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
            <div class="bg-purple-100 rounded-xl p-3"><i class="fa-solid fa-book text-2xl text-purple-600"></i></div>
            <div>
                <p class="text-sm text-gray-500">Total Buku</p>
                <p class="text-3xl font-bold text-dark">{{ $stats['total_books'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
            <div class="bg-yellow-100 rounded-xl p-3"><i class="fa-solid fa-cart-arrow-down text-2xl text-accent"></i></div>
            <div>
                <p class="text-sm text-gray-500">Total Pesanan</p>
                <p class="text-3xl font-bold text-dark">{{ $stats['total_orders'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
            <div class="bg-green-100 rounded-xl p-3"><i class="fa-solid fa-money-bill text-2xl text-green-600"></i></div>
            <div>
                <p class="text-sm text-gray-500">Total Pendapatan</p>
                <p class="text-2xl font-bold text-dark">Rp {{ number_format((float)$stats['total_revenue'], 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
            <div class="bg-orange-100 rounded-xl p-3"><i class="fa-solid fa-clock text-2xl text-orange-500"></i></div>
            <div>
                <p class="text-sm text-gray-500">Pesanan Pending</p>
                <p class="text-3xl font-bold text-dark">{{ $stats['pending_orders'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
            <div class="bg-red-100 rounded-xl p-3"><i class="fa-solid fa-triangle-exclamation text-2xl text-red-500"></i></div>
            <div>
                <p class="text-sm text-gray-500">Stok Menipis (≤ 5)</p>
                <p class="text-3xl font-bold text-dark">{{ $stats['low_stock'] }}</p>
            </div>
        </div>
    </div>

    <!-- Latest Orders -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex justify-between items-center p-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-dark">Pesanan Terbaru</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-secondary text-sm hover:underline">Lihat Semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">Kode Pesanan</th>
                        <th class="px-6 py-3 text-left">Pelanggan</th>
                        <th class="px-6 py-3 text-left">Total</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($latestOrders as $order)
                    @php $color = $order->status_color; @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-mono font-semibold text-dark">{{ $order->order_code }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $order->user->name }}</td>
                        <td class="px-6 py-4 font-semibold">Rp {{ number_format((float)$order->grand_total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 rounded-full text-xs font-bold
                                @if($color==='yellow') bg-yellow-100 text-yellow-700
                                @elseif($color==='blue') bg-blue-100 text-blue-700
                                @elseif($color==='purple') bg-purple-100 text-purple-700
                                @elseif($color==='green') bg-green-100 text-green-700
                                @elseif($color==='red') bg-red-100 text-red-700
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada pesanan masuk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
