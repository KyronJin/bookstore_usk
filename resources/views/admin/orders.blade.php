@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-dark">Manajemen Pesanan</h1>
            <p class="text-gray-500 text-sm mt-1">Lihat dan kelola semua pesanan pelanggan</p>
        </div>

        <!-- Filter Bar -->
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari kode pesanan atau nama pelanggan..."
                class="flex-grow border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
            <select name="status"
                class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Diproses</option>
                <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Dikirim</option>
                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
            <button type="submit"
                class="bg-secondary text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-blue-600 transition">Filter</button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.orders.index') }}"
                    class="border border-gray-300 text-gray-600 px-5 py-2.5 rounded-xl text-sm hover:bg-gray-50 transition">Reset</a>
            @endif
        </form>

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3 text-left">Kode Pesanan</th>
                            <th class="px-6 py-3 text-left">Pelanggan</th>
                            <th class="px-6 py-3 text-left">Pembayaran</th>
                            <th class="px-6 py-3 text-left">Total</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">Tanggal</th>
                            <th class="px-6 py-3 text-center">Ubah Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($orders as $order)
                            @php $color = $order->status_color; @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                        class="font-mono font-semibold text-secondary hover:underline">{{ $order->order_code }}</a>
                                </td>
                                <td class="px-6 py-4 text-gray-700">{{ $order->user->name }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-xs">{{ $order->payment_method === 'cod' ? 'COD' : 'Transfer' }}</span>
                                </td>
                                <td class="px-6 py-4 font-semibold">Rp
                                    {{ number_format((float) $order->grand_total, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-bold
                                        @if($color === 'yellow') bg-yellow-100 text-yellow-700
                                        @elseif($color === 'blue') bg-blue-100 text-blue-700
                                        @elseif($color === 'purple') bg-purple-100 text-purple-700
                                        @elseif($color === 'green') bg-green-100 text-green-700
                                        @elseif($color === 'red') bg-red-100 text-red-700
                                        @else bg-gray-100 text-gray-700 @endif">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    @if($order->status === 'delivered')
                                        <span class="text-xs text-gray-500 italic"><i
                                                class="fa-solid fa-lock text-gray-400 mr-1"></i> Status Final</span>
                                    @else
                                        <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="flex gap-2">
                                            @csrf @method('PATCH')
                                            <select name="status"
                                                class="border border-gray-300 rounded-lg px-2 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-secondary">
                                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending
                                                </option>
                                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>
                                                    Diproses</option>
                                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Dikirim
                                                </option>
                                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>
                                                    Selesai</option>
                                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>
                                                    Dibatalkan</option>
                                            </select>
                                            <button type="submit"
                                                class="bg-primary text-white px-2 py-1.5 rounded-lg text-xs hover:bg-blue-800 transition">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                    <i class="fa-solid fa-cart-shopping text-3xl mb-2"></i><br>Belum ada pesanan masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-100">
                {{ $orders->appends(request()->all())->links() }}
            </div>
        </div>
    </div>
@endsection