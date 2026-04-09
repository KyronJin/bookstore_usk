<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends Controller
{
    // Tampilkan isi keranjang
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart', compact('cart'));
    }

    // Tambah buku ke keranjang
    public function add(Request $request)
    {
        // Jika ajax/json dan belum login, middleware auth akan otomatis handle dengan throw 401 error.
        // Namun karena rute ini sekarang pakai AJAX, kita set response manual jika bukan via auth middleware.
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Silakan login terlebih dahulu.'], 401);
            }
            return redirect()->route('login')
                ->with('warning', 'Silakan login terlebih dahulu untuk menambahkan buku ke keranjang.');
        }

        $book = Book::findOrFail($request->book_id);

        if ($book->stock <= 0) {
            if ($request->expectsJson()) return response()->json(['message' => 'Stok buku habis.'], 400);
            return back()->with('error', 'Stok buku habis.');
        }

        $cart = session()->get('cart', []);
        $id = $book->id;

        if (isset($cart[$id])) {
            $newQty = $cart[$id]['quantity'] + 1;
            if ($newQty > $book->stock) {
                if ($request->expectsJson()) return response()->json(['message' => 'Stok tidak mencukupi.'], 400);
                return back()->with('error', 'Stok tidak mencukupi.');
            }
            $cart[$id]['quantity'] = $newQty;
        } else {
            $cart[$id] = [
                'book_id'   => $book->id,
                'title'     => $book->title,
                'author'    => $book->author,
                'price'     => $book->price,
                'image_url' => $book->image_url,
                'quantity'  => 1,
            ];
        }

        session()->put('cart', $cart);
        
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => '"' . $book->title . '" berhasil ditambahkan ke keranjang.',
                'cart_count' => collect($cart)->sum('quantity')
            ]);
        }
        
        return back()->with('success', '"' . $book->title . '" berhasil ditambahkan ke keranjang.');
    }

    // Update quantity
    public function update(Request $request)
    {
        $request->validate(['book_id' => 'required', 'quantity' => 'required|integer|min:1']);
        $cart = session()->get('cart', []);
        $id = $request->book_id;

        if (isset($cart[$id])) {
            $book = Book::find($id);
            if ($request->quantity > $book->stock) {
                return back()->with('error', 'Stok tidak mencukupi.');
            }
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Keranjang berhasil diperbarui.');
    }

    // Hapus item dari keranjang
    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        unset($cart[$request->book_id]);
        session()->put('cart', $cart);
        return back()->with('success', 'Buku dihapus dari keranjang.');
    }

    // Halaman checkout
    public function checkout()
    {
        $cart = session()->get('cart', []);
        
        if (request()->has('items')) {
            $selectedIds = explode(',', request('items'));
            $cart = collect($cart)->filter(fn($item, $key) => in_array($key, $selectedIds))->toArray();
        }

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('warning', 'Pilih minimal satu buku untuk checkout.');
        }

        $subtotal = 0;
        // VALIDASI AWAL: Tarik harga terbaru dan cek stok untuk tampilan checkout
        foreach ($cart as $key => &$item) {
            $book = Book::find($item['book_id']);
            if (!$book) {
                 return redirect()->route('cart.index')->with('error', "Salah satu buku dalam pesanan Anda sudah tidak tersedia.");
            }
            if ($book->stock < $item['quantity']) {
                return redirect()->route('cart.index')->with('error', "Stok buku '{$book->title}' tidak mencukupi. Sisa stok: {$book->stock}.");
            }
            // Update harga di session view ke harga real-time
            $item['price'] = $book->price;
            $subtotal += $book->price * $item['quantity'];
        }

        $tax          = $subtotal * 0.12;
        $shippingCost = 0;
        $total        = $subtotal + $tax + $shippingCost;

        $selectedItemsStr = implode(',', array_keys($cart));

        return view('checkout', compact('cart', 'subtotal', 'tax', 'shippingCost', 'total', 'selectedItemsStr'));
    }

    // Proses pembuatan pesanan
    public function placeOrder(Request $request)
    {
        $request->validate([
            'shipping_address' => ['required', 'string', 'min:10'],
            'payment_method'   => ['required', 'in:cod,transfer'],
            'transfer_bank_name' => ['required_if:payment_method,transfer', 'nullable', 'string', 'max:50'],
            'transfer_account_name' => ['required_if:payment_method,transfer', 'nullable', 'string', 'max:100'],
            'notes'            => ['nullable', 'string', 'max:500'],
            'items'            => ['required', 'string'],
        ]);

        $cart = session()->get('cart', []);
        
        $selectedIds = explode(',', $request->items);
        $checkoutCart = collect($cart)->filter(fn($item, $key) => in_array($key, $selectedIds))->toArray();

        if (empty($checkoutCart)) {
            return redirect()->route('cart.index')->with('error', 'Tidak ada item yang dipilih untuk checkout.');
        }

        try {
            DB::beginTransaction();

            $subtotal = 0;
            $orderItemsData = [];

            // 1. Validasi stok & harga riil secara ketat dari database
            foreach ($checkoutCart as $key => $item) {
                // Gunakan lockForUpdate untuk mencegah race-condition oleh user lain di saat milidetik yang sama
                $book = Book::where('id', $item['book_id'])->lockForUpdate()->first();

                if (!$book) {
                    throw new \Exception("Buku '{$item['title']}' sudah tidak tersedia.");
                }

                if ($book->stock < $item['quantity']) {
                    throw new \Exception("Stok buku '{$book->title}' tidak mencukupi (Sisa {$book->stock}). Ada pengguna lain yang mungkin baru saja membeli produk tersebut.");
                }

                // Jangan ambil harga dari session, melainkan harga fresh dari $book->price
                $realPrice = $book->price;
                $subtotal += $realPrice * $item['quantity'];

                $orderItemsData[] = [
                    'book_id'  => $book->id,
                    'quantity' => $item['quantity'],
                    'price'    => $realPrice,
                    'cart_key' => $key
                ];
            }

            $tax          = $subtotal * 0.12;
            $shippingCost = 0;

            // 2. Buat Tagihan Induk
            $order = Order::create([
                'user_id'          => Auth::id(),
                'order_code'       => 'PB-' . strtoupper(Str::random(8)),
                'status'           => 'pending',
                'total_price'      => $subtotal,
                'tax'              => $tax,
                'shipping_cost'    => $shippingCost,
                'payment_method'   => $request->payment_method,
                'transfer_bank_name' => $request->payment_method === 'transfer' ? $request->transfer_bank_name : null,
                'transfer_account_name' => $request->payment_method === 'transfer' ? $request->transfer_account_name : null,
                'shipping_address' => $request->shipping_address,
                'notes'            => $request->notes,
            ]);

            // 3. Masukkan item dan bersihkan session
            foreach ($orderItemsData as $data) {
                OrderItem::create([
                    'order_id'  => $order->id,
                    'book_id'   => $data['book_id'],
                    'quantity'  => $data['quantity'],
                    'price'     => $data['price'],
                ]);
                
                unset($cart[$data['cart_key']]);
            }

            // Jika semua di atas berjalan mulus, komitmen transaksi (permanent saving)
            DB::commit();
            
            // Simpan sisa keranjang
            session()->put('cart', $cart);

        } catch (\Exception $e) {
            // Jika ada ERROR STOK atau QUERY, batalkan semua pencatatan database!
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', 'Pesanan dibatalkan: ' . $e->getMessage());
        }

        $msg = $request->payment_method === 'transfer' 
            ? 'Pesanan dibuat. Harap tunggu admin memverifikasi pembayaran Anda (Pesanan: ' . $order->order_code . ')'
            : 'Pesanan berhasil dibuat! Kode pesanan: ' . $order->order_code;

        return redirect()->route('orders.index')
            ->with('success', $msg);
    }

    // Riwayat pesanan user
    public function orders()
    {
        $orders = Auth::user()->orders()->with('items.book')->latest()->get();
        return view('orders.index', compact('orders'));
    }

    // Tampilkan invoice
    public function invoice(Order $order)
    {
        // Pastikan order milik user yang login
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $order->load(['items.book', 'user']);
        return view('orders.invoice', compact('order'));
    }

    // User membatalkan pesanan (Hanya jika pending)
    public function cancelOrder(Order $order)
    {
        // Pastikan order milik user yang login
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        if ($order->status !== 'pending') {
            return back()->with('error', 'Pesanan anda sudah diproses dan tidak dapat dibatalkan, silakan hubungi admin secara langsung.');
        }

        $order->update(['status' => 'cancelled']);

        return back()->with('success', 'Pesanan ' . $order->order_code . ' berhasil dibatalkan.');
    }
}
