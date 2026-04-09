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

        $oldStatus = $order->status;
        $newStatus = $request->status;

        if ($oldStatus === 'cancelled') {
            return back()->with('error', 'Status pesanan yang sudah dibatalkan tidak dapat diubah lagi.');
        }

        // Jika berubah dari pending (stok belum dihitung) ke status proses/selesai, cek stok dulu
        if ($oldStatus === 'pending' && in_array($newStatus, ['processing', 'shipped', 'delivered'])) {
            // Cek terlebih dahulu apakah stok seluruh buku dalam tagihan ini mumpuni (tidak minus jika diproses)
            foreach ($order->items as $item) {
                if ($item->book && $item->book->stock < $item->quantity) {
                    return back()->with('stock_error', "Stok buku '{$item->book->title}' saat ini tidak cukup (Sisa {$item->book->stock}). Anda harus merestock buku ini atau membatalkan pesanan terkait.");
                }
            }
            
            // Jika semua stok buku aman, sahkan status dan potong renteng
            $order->update(['status' => $newStatus]);
            foreach ($order->items as $item) {
                if ($item->book) {
                    $item->book->decrement('stock', $item->quantity);
                }
            }
        } 
        // Jika pesanan dibatalkan atau dikembalikan ke pending tapi stoknya sudah telanjur dikurangi sebelumnya, kembalikan stoknya
        elseif (in_array($oldStatus, ['processing', 'shipped', 'delivered']) && in_array($newStatus, ['cancelled', 'pending'])) {
            $order->update(['status' => $newStatus]);
            foreach ($order->items as $item) {
                if ($item->book) {
                    $item->book->increment('stock', $item->quantity);
                }
            }
        } 
        else {
            // Update status biasa (misal dari processing ke shipped) tanpa ubah stok
            $order->update(['status' => $newStatus]);
        }

        return back()->with('success', 'Status pesanan #' . $order->order_code . ' berhasil diubah menjadi "' . $order->status_label . '".');
    }

    // Mencetak invoice pesanan
    public function invoice(Order $order)
    {
        $order->load(['items.book', 'user']);
        return view('orders.invoice', compact('order'));
    }
}
