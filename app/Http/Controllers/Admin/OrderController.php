<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Menampilkan antrean seluruh pesanan yang masuk beserta filternya
    public function index(Request $request)
    {
        $query = Order::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $orders = $query->latest()->paginate(15);
        return view('admin.orders', compact('orders'));
    }

    // Membuka halaman detail untuk satu pesanan spesifik (melihat bukti transfer, item buku)
    public function show(Order $order)
    {
        $order->load('user', 'items.book');
        return view('admin.order_detail', compact('order'));
    }

    // Mengubah status progres dari pesanan (Pending -> Processing -> Shipped, dst.)
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'in:pending,processing,shipped,delivered,cancelled'],
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan #' . $order->order_code . ' berhasil diubah menjadi "' . $order->status_label . '".');
    }
}
