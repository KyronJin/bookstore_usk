<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;

// =================== PUBLIC ROUTES ===================
// Rute yang bisa diakses oleh siapa saja tanpa perlu login
Route::get('/', [HomeController::class, 'index'])->name('home'); // Menampilkan halaman beranda utama
Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog'); // Menampilkan daftar/katalog buku beserta filter
Route::get('/about', fn() => view('about'))->name('about'); // Menampilkan halaman informasi "Tentang Kami"
Route::get('/contact', [ContactController::class, 'index'])->name('contact'); // Menampilkan halaman form kontak
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store'); // Memproses pengiriman pesan kontak dari user

// =================== USER AUTH ROUTES (tamu saja) ===================
// Hanya bisa diakses oleh pengunjung yang BELUM login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login'); // Menampilkan form login pelanggan
    Route::post('/login', [AuthController::class, 'login']); // Memproses validasi otentikasi login
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register'); // Menampilkan form pendaftaran akun
    Route::post('/register', [AuthController::class, 'register']); // Memproses pendaftaran akun baru
});

// Memproses permintaan logout khusus user (harus sudah login)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// =================== USER ROUTES (harus login sebagai user) ===================
Route::middleware('auth')->group(function () {
    // Keranjang & Transaksi
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index'); // Menampilkan isi keranjang belanja
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add'); // Menambahkan buku ke dalam keranjang
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update'); // Mengubah jumlah item/kuantitas buku di keranjang
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove'); // Menghapus suatu item dari keranjang
    
    // Checkout & Pesanan
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout'); // Menampilkan halaman ringkasan checkout
    Route::post('/checkout', [CartController::class, 'placeOrder'])->name('checkout.place'); // Menyimpan pesanan dan bukti bayar ke database
    Route::get('/orders', [CartController::class, 'orders'])->name('orders.index'); // Menampilkan halaman daftar riwayat pesanan user
    Route::get('/orders/{order}/invoice', [CartController::class, 'invoice'])->name('orders.invoice'); // Mencetak invoice pesanan
});

// =================== ADMIN AUTH ===================
// Rute khusus untuk portal Admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login'); // Menampilkan form login admin
    Route::post('/login', [AdminAuthController::class, 'login']); // Memproses otentikasi admin
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout'); // Memproses logout akun admin
});

// =================== ADMIN PANEL (harus login + role admin) ===================
// Area sensitif, hanya akun dengan role admin yang diizinkan masuk
Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard'); // Menampilkan ringkasan statistik (Dashboard Admin)

    // FITUR: Kelola Kategori
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index'); // Menampilkan daftar kategori buku
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store'); // Menyimpan data kategori baru
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update'); // Memperbarui data kategori yang ada
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy'); // Menghapus kategori pilihan

    // FITUR: Kelola Data Buku
    Route::get('/books', [BookController::class, 'index'])->name('books.index'); // Menampilkan daftar semua buku
    Route::post('/books', [BookController::class, 'store'])->name('books.store'); // Menambahkan rincian buku baru ke database
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update'); // Mengupdate informasi dan foto buku
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy'); // Menghapus buku

    // FITUR: Kelola Pengguna
    Route::get('/users', [UserController::class, 'index'])->name('users.index'); // Melihat daftar seluruh pelanggan terdaftar
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy'); // Menghapus akun pelanggan (beserta pesanannya)

    // FITUR: Kelola Pesanan / Transaksi
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index'); // Menampilkan seluruh antrean tagihan/pesanan pelanggan
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show'); // Melihat rincian spesifik dan bukti transfer pesanan
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status'); // Mengubah progres status pesanan (misal: 'Dikemas')
    Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice'); // Mencetak invoice pesanan admin

    // FITUR: Kotak Masuk (Pesan Kontak)
    Route::get('/contacts', [AdminContactController::class, 'index'])->name('contacts.index'); // Membaca kotak masuk pertanyaan dari halaman 'Contact Us'
    Route::delete('/contacts/{contact}', [AdminContactController::class, 'destroy'])->name('contacts.destroy'); // Menghapus pesan masuk (sudah dibaca)
});
