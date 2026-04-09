@extends('layouts.app')

@section('title', 'JeBook - Toko Buku Online Terpercaya')

@section('content')

<!-- ===== HERO SECTION ===== -->
<section class="bg-primary py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div>
            <p class="text-blue-200 text-sm font-medium mb-4">Toko Buku Online Terpercaya</p>
            <h1 class="text-4xl md:text-5xl font-bold text-white leading-tight mb-5">
                Baca Lebih Banyak,<br>
                Tahu Lebih Luas.
            </h1>
            <p class="text-blue-200 mb-8 leading-relaxed max-w-lg">
                Jelajahi ribuan koleksi buku — dari fiksi, non-fiksi, edukasi, hingga teknologi. Pengiriman cepat, harga terjangkau.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('katalog') }}"
                   class="bg-white text-primary font-semibold px-6 py-3 rounded-lg transition hover:bg-gray-100 flex items-center gap-2 text-sm">
                    <i class="fa-solid fa-book-open"></i> Lihat Katalog Buku
                </a>
                @guest
                <a href="{{ route('register') }}"
                   class="border border-white/40 text-white font-medium px-6 py-3 rounded-lg transition hover:bg-white/10 flex items-center gap-2 text-sm">
                    <i class="fa-solid fa-user-plus"></i> Daftar Gratis
                </a>
                @endguest
            </div>
        </div>

        <!-- Hero image -->
        <div class="hidden md:flex justify-center">
            <div class="w-full max-w-sm">
                <div class="rounded-xl overflow-hidden shadow-lg">
                    <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=600" alt="Koleksi Buku" class="w-full h-72 object-cover">
                </div>
                <div class="flex gap-4 mt-4">
                    <div class="bg-white/10 rounded-lg px-4 py-3 flex-1 text-center">
                        <p class="text-white font-bold text-lg">{{ $totalBooks }}+</p>
                        <p class="text-blue-200 text-xs">Judul Buku</p>
                    </div>
                    <div class="bg-white/10 rounded-lg px-4 py-3 flex-1 text-center">
                        <p class="text-white font-bold text-lg">COD</p>
                        <p class="text-blue-200 text-xs">& Transfer</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== KATEGORI CEPAT ===== -->
<section class="py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-dark mb-2">Jelajahi Berdasarkan Kategori</h2>
            <p class="text-gray-500 text-sm">Pilih kategori favoritmu untuk mulai menjelajahi koleksi</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
            @php
                $iconMap = [
                    'Fiksi' => 'fa-wand-magic-sparkles',
                    'Non-Fiksi' => 'fa-brain',
                    'Edukasi' => 'fa-graduation-cap',
                    'Teknologi' => 'fa-microchip',
                    'Bisnis' => 'fa-briefcase',
                ];
            @endphp
            @foreach($categories as $cat)
            @php
                $icon = $iconMap[$cat->name] ?? 'fa-book';
            @endphp
            <a href="{{ route('katalog', ['category' => $cat->slug]) }}"
               class="bg-white hover:bg-gray-50 rounded-xl p-4 text-center border border-gray-100 transition">
                <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center mx-auto mb-2">
                    <i class="fa-solid {{ $icon }} text-primary text-sm"></i>
                </div>
                <p class="font-semibold text-dark text-sm">{{ $cat->name }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $cat->books_count }} buku</p>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- ===== BUKU PILIHAN (Featured) ===== -->
@if($featuredBooks->isNotEmpty())
<section class="py-14 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h2 class="text-2xl font-bold text-dark mb-1">Buku Pilihan Kami</h2>
                <p class="text-gray-500 text-sm">Koleksi terbaik yang direkomendasikan oleh tim kami</p>
            </div>
            <a href="{{ route('katalog') }}" class="hidden sm:flex items-center gap-1 text-secondary hover:text-blue-700 text-sm font-medium transition">
                Lihat Semua <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5">
            @foreach($featuredBooks as $book)
            <div class="bg-white rounded-lg overflow-hidden flex flex-col border border-gray-100 hover:shadow-md transition">
                <div class="relative overflow-hidden bg-gray-100" style="height:200px">
                    <img src="{{ $book->image_url ?: 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=400' }}"
                         alt="{{ $book->title }}" class="w-full h-full object-cover">
                    @if($book->stock <= 5 && $book->stock > 0)
                    <div class="absolute bottom-2 left-2 bg-orange-500 text-white text-xs font-medium px-2 py-0.5 rounded">Sisa {{ $book->stock }}</div>
                    @endif
                </div>
                <div class="p-4 flex-grow flex flex-col">
                    <span class="text-xs text-secondary font-medium mb-1">{{ $book->category->name }}</span>
                    <h3 class="text-sm font-semibold text-dark line-clamp-2 mb-1">{{ $book->title }}</h3>
                    <p class="text-xs text-gray-400 mb-3">{{ $book->author }}</p>
                    <div class="mt-auto flex items-center justify-between">
                        <span class="text-base font-bold text-primary">Rp {{ number_format((float)$book->price, 0, ',', '.') }}</span>
                        @if($book->stock > 0)
                        <button onclick="addToCart({{ $book->id }})" class="bg-primary hover:bg-blue-800 text-white h-8 w-8 rounded-lg flex items-center justify-center transition text-sm" title="Tambah ke Keranjang">
                            <i class="fa-solid fa-cart-plus text-xs"></i>
                        </button>
                        @else
                        <span class="text-xs text-gray-400">Stok Habis</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-6 text-center sm:hidden">
            <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 bg-primary text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-800 transition">
                Lihat Semua Buku <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- ===== WHY US ===== -->
<section class="py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-2xl font-bold text-dark mb-2">Mengapa Pilih JeBook?</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-book text-primary text-lg"></i>
                </div>
                <h3 class="font-semibold text-dark mb-1">Koleksi Lengkap</h3>
                <p class="text-gray-500 text-sm">Ribuan judul buku dari berbagai genre siap untuk kamu jelajahi setiap hari.</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-money-bill-wave text-primary text-lg"></i>
                </div>
                <h3 class="font-semibold text-dark mb-1">Harga Terjangkau</h3>
                <p class="text-gray-500 text-sm">Kami menjamin harga yang bersahabat tanpa mengorbankan kualitas buku.</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <i class="fa-solid fa-truck-fast text-primary text-lg"></i>
                </div>
                <h3 class="font-semibold text-dark mb-1">Pengiriman Aman</h3>
                <p class="text-gray-500 text-sm">Dikemas dengan rapi dan dikirimkan langsung ke pintu rumahmu dengan aman.</p>
            </div>
        </div>
    </div>
</section>

<!-- ===== CTA BANNER ===== -->
<section class="bg-gray-800">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-14 text-center">
        <h2 class="text-2xl font-bold text-white mb-3">Siap Mulai Membaca?</h2>
        <p class="text-gray-300 text-sm mb-6">Daftar sekarang dan dapatkan akses ke seluruh koleksi buku kami.</p>
        <div class="flex justify-center gap-3 flex-wrap">
            <a href="{{ route('katalog') }}" class="bg-white text-dark font-semibold text-sm px-6 py-3 rounded-lg transition hover:bg-gray-100">
                <i class="fa-solid fa-book-open mr-2"></i> Jelajahi Katalog
            </a>
            @guest
            <a href="{{ route('register') }}" class="border border-white/40 text-white font-medium text-sm px-6 py-3 rounded-lg transition hover:bg-white/10">
                <i class="fa-solid fa-user-plus mr-2"></i> Daftar Gratis
            </a>
            @endguest
        </div>
    </div>
</section>

@endsection
