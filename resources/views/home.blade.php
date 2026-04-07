@extends('layouts.app')

@section('title', 'JeBook - Toko Buku Online Terpercaya')

@section('content')

<!-- ===== HERO SECTION ===== -->
<section class="relative bg-primary overflow-hidden min-h-[80vh] flex items-center">
    <!-- Background gradient overlay -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-900 via-primary to-blue-700 z-0"></div>
    <!-- Decorative circles -->
    <div class="absolute top-10 right-10 w-72 h-72 bg-accent/10 rounded-full blur-3xl z-0"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-white/5 rounded-full blur-3xl z-0"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div>
            <span class="inline-flex items-center bg-accent/20 text-accent border border-accent/30 text-sm font-semibold px-4 py-1.5 rounded-full mb-6">
                <i class="fa-solid fa-star mr-2 text-xs"></i> Toko Buku Online #1 Terpercaya
            </span>
            <h1 class="text-5xl md:text-6xl font-extrabold text-white leading-tight mb-6">
                Baca Lebih Banyak,<br>
                <span class="text-accent">Tahu Lebih Luas.</span>
            </h1>
            <p class="text-lg text-blue-200 mb-10 leading-relaxed max-w-lg">
                Jelajahi ribuan koleksi buku — dari fiksi, non-fiksi, edukasi, hingga teknologi. Pengiriman cepat, harga terjangkau.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('katalog') }}"
                   class="bg-accent hover:bg-yellow-500 text-dark font-bold px-8 py-4 rounded-full transition shadow-xl shadow-accent/30 transform hover:-translate-y-1 flex items-center gap-2">
                    <i class="fa-solid fa-book-open"></i> Lihat Katalog Buku
                </a>
                @guest
                <a href="{{ route('register') }}"
                   class="bg-white/10 hover:bg-white/20 border border-white/30 text-white font-semibold px-8 py-4 rounded-full transition flex items-center gap-2 backdrop-blur-sm">
                    <i class="fa-solid fa-user-plus"></i> Daftar Gratis
                </a>
                @endguest
            </div>
        </div>

        <!-- Hero image (floating books) -->
        <div class="hidden md:flex justify-center relative">
            <div class="relative w-full max-w-sm">
                <!-- Main book image -->
                <div class="rounded-2xl overflow-hidden shadow-2xl border-4 border-white/20 transform -rotate-3 hover:rotate-0 transition duration-700">
                    <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=600" alt="Koleksi Buku" class="w-full h-80 object-cover">
                </div>
                <!-- Floating card 1 -->
                <div class="absolute -top-6 -right-6 bg-white rounded-xl p-3 shadow-xl border border-gray-100">
                    <div class="flex items-center gap-2">
                        <div class="bg-green-100 rounded-lg p-2"><i class="fa-solid fa-box text-green-600"></i></div>
                        <div>
                            <p class="text-xs text-gray-500">Total Buku</p>
                            <p class="text-lg font-bold text-dark">{{ $totalBooks }}+ Judul</p>
                        </div>
                    </div>
                </div>
                <!-- Floating card 2 -->
                <div class="absolute -bottom-4 -left-4 bg-white rounded-xl p-3 shadow-xl border border-gray-100">
                    <div class="flex items-center gap-2">
                        <div class="bg-yellow-100 rounded-lg p-2"><i class="fa-solid fa-truck-fast text-yellow-600"></i></div>
                        <div>
                            <p class="text-xs text-gray-500">Pengiriman</p>
                            <p class="font-bold text-dark text-sm">COD & Transfer</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave bottom -->
    <div class="absolute bottom-0 left-0 right-0 z-10">
        <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 60L60 50C120 40 240 20 360 20C480 20 600 40 720 45C840 50 960 40 1080 33C1200 27 1320 33 1380 37L1440 40V60H0Z" fill="#F8FAFC"/>
        </svg>
    </div>
</section>

<!-- ===== KATEGORI CEPAT ===== -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-dark mb-3">Jelajahi Berdasarkan Kategori</h2>
            <p class="text-gray-500">Pilih kategori favoritmu untuk mulai menjelajahi koleksi</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
            @php
                $iconMap = [
                    'Fiksi' => ['icon' => 'fa-wand-magic-sparkles', 'color' => 'from-purple-500 to-pink-500'],
                    'Non-Fiksi' => ['icon' => 'fa-brain', 'color' => 'from-blue-500 to-cyan-500'],
                    'Edukasi' => ['icon' => 'fa-graduation-cap', 'color' => 'from-green-500 to-teal-500'],
                    'Teknologi' => ['icon' => 'fa-microchip', 'color' => 'from-orange-500 to-yellow-500'],
                    'Bisnis' => ['icon' => 'fa-briefcase', 'color' => 'from-red-500 to-orange-500'],
                ];
            @endphp
            @foreach($categories as $cat)
            @php
                $mapItem = $iconMap[$cat->name] ?? ['icon' => 'fa-book', 'color' => 'from-gray-500 to-gray-600'];
            @endphp
            <a href="{{ route('katalog', ['category' => $cat->slug]) }}"
               class="group bg-white hover:shadow-lg rounded-2xl p-5 text-center border border-gray-100 transition-all duration-300 hover:-translate-y-1">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br {{ $mapItem['color'] }} flex items-center justify-center mx-auto mb-3 shadow-md group-hover:scale-110 transition-transform">
                    <i class="fa-solid {{ $mapItem['icon'] }} text-white text-xl"></i>
                </div>
                <p class="font-bold text-dark text-sm">{{ $cat->name }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $cat->books_count }} buku</p>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- ===== BUKU PILIHAN (Featured) ===== -->
@if($featuredBooks->isNotEmpty())
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-10">
            <div>
                <h2 class="text-3xl font-bold text-dark mb-2">⭐ Buku Pilihan Kami</h2>
                <p class="text-gray-500">Koleksi terbaik yang direkomendasikan oleh tim kami</p>
            </div>
            <a href="{{ route('katalog') }}" class="hidden sm:flex items-center gap-1 text-secondary hover:text-blue-700 font-semibold text-sm transition">
                Lihat Semua <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($featuredBooks as $book)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col group border border-gray-100 hover:-translate-y-1">
                <div class="relative overflow-hidden bg-gray-100" style="height:220px">
                    <img src="{{ $book->image_url ?: 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=400' }}"
                         alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-2 right-2 bg-accent text-white text-xs font-bold px-2 py-1 rounded-full shadow">⭐ Pilihan</div>
                    @if($book->stock <= 5 && $book->stock > 0)
                    <div class="absolute bottom-2 left-2 bg-orange-500/90 text-white text-xs font-bold px-2 py-0.5 rounded-full">Sisa {{ $book->stock }}</div>
                    @endif
                </div>
                <div class="p-4 flex-grow flex flex-col">
                    <span class="text-xs text-secondary font-semibold uppercase tracking-wider mb-1">{{ $book->category->name }}</span>
                    <h3 class="text-sm font-bold text-dark line-clamp-2 mb-1 leading-snug">{{ $book->title }}</h3>
                    <p class="text-xs text-gray-500 mb-4">{{ $book->author }}</p>
                    <div class="mt-auto flex items-center justify-between">
                        <span class="text-lg font-bold text-primary">Rp {{ number_format((float)$book->price, 0, ',', '.') }}</span>
                        @if($book->stock > 0)
                        <button onclick="addToCart({{ $book->id }})" class="bg-primary hover:bg-secondary text-white h-9 w-9 rounded-lg flex items-center justify-center transition shadow-sm" title="Tambah ke Keranjang">
                            <i class="fa-solid fa-cart-plus text-sm"></i>
                        </button>
                        @else
                        <span class="text-xs text-gray-400 font-medium">Stok Habis</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-8 text-center sm:hidden">
            <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 bg-secondary text-white px-8 py-3 rounded-full font-semibold hover:bg-blue-600 transition">
                Lihat Semua Buku <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- ===== WHY US ===== -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-dark mb-3">Mengapa Pilih JeBook?</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center group">
                <div class="w-16 h-16 bg-primary rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-primary/30 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-book text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-dark mb-2">Koleksi Lengkap</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Ribuan judul buku dari berbagai genre siap untuk kamu jelajahi setiap hari.</p>
            </div>
            <div class="text-center group">
                <div class="w-16 h-16 bg-accent rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-accent/30 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-money-bill-wave text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-dark mb-2">Harga Terjangkau</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Kami menjamin harga yang bersahabat tanpa mengorbankan kualitas buku.</p>
            </div>
            <div class="text-center group">
                <div class="w-16 h-16 bg-green-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-green-500/30 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-truck-fast text-white text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-dark mb-2">Pengiriman Aman</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Dikemas dengan rapi dan dikirimkan langsung ke pintu rumahmu dengan aman.</p>
            </div>
        </div>
    </div>
</section>

<!-- ===== CTA BANNER ===== -->
<section class="bg-primary">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Siap Mulai Membaca?</h2>
        <p class="text-blue-200 mb-8">Daftar sekarang dan dapatkan akses ke seluruh koleksi buku kami.</p>
        <div class="flex justify-center gap-4 flex-wrap">
            <a href="{{ route('katalog') }}" class="bg-accent hover:bg-yellow-500 text-dark font-bold px-8 py-3.5 rounded-full transition shadow-lg">
                <i class="fa-solid fa-book-open mr-2"></i> Jelajahi Katalog
            </a>
            @guest
            <a href="{{ route('register') }}" class="bg-white/10 hover:bg-white/20 border border-white/30 text-white font-semibold px-8 py-3.5 rounded-full transition">
                <i class="fa-solid fa-user-plus mr-2"></i> Daftar Gratis
            </a>
            @endguest
        </div>
    </div>
</section>

@endsection
