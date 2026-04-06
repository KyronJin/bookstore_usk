@extends('layouts.app')

@section('title', 'Katalog Buku - PustakaBiru')

@section('content')
<div class="bg-gray-50 min-h-screen">

    <!-- Page Header -->
    <div class="bg-gradient-to-r from-primary to-blue-700 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-bold mb-2">Katalog Buku</h1>
                    <p class="text-blue-200">Temukan buku favoritmu dari koleksi kami</p>
                </div>
                <!-- Search Bar di Header -->
                <form action="{{ route('katalog') }}" method="GET" class="flex gap-2 w-full md:w-auto">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                    <div class="flex items-center bg-white/15 backdrop-blur-sm border border-white/30 rounded-xl px-4 py-2.5 flex-grow md:w-72 focus-within:bg-white/25 transition">
                        <i class="fa-solid fa-magnifying-glass text-white/70 mr-3 text-sm"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari judul, penulis..." 
                               class="bg-transparent w-full text-white placeholder-white/60 focus:outline-none text-sm">
                    </div>
                    <button type="submit" class="bg-accent hover:bg-yellow-500 text-white px-5 py-2.5 rounded-xl font-medium transition text-sm whitespace-nowrap">
                        Cari
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">

            <!-- ===== SIDEBAR FILTER ===== -->
            <aside class="lg:w-64 flex-shrink-0">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sticky top-20">

                    <!-- Clear filter button -->
                    @if(request('search') || request('category') || request('sort') || request('stok'))
                    <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-100">
                        <span class="text-sm font-semibold text-dark">Filter Aktif</span>
                        <a href="{{ route('katalog') }}" class="text-xs text-red-500 hover:text-red-700 font-medium transition flex items-center gap-1">
                            <i class="fa-solid fa-xmark"></i> Reset Semua
                        </a>
                    </div>
                    @endif

                    <!-- Kategori (Dropdown) -->
                    <div class="mb-6">
                        <h3 class="text-sm font-bold text-dark uppercase tracking-wider mb-3">
                            <i class="fa-solid fa-tags text-secondary mr-1"></i> Kategori
                        </h3>
                        <div class="relative">
                            <select
                                onchange="window.location.href = this.value"
                                class="w-full appearance-none bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl px-4 py-3 pr-10 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent cursor-pointer transition hover:border-secondary">
                                <option value="{{ route('katalog', array_merge(request()->except('category', 'page'), [])) }}"
                                    {{ !request('category') ? 'selected' : '' }}>
                                    Semua Kategori
                                </option>
                                @foreach($categories as $cat)
                                <option
                                    value="{{ route('katalog', array_merge(request()->except('category', 'page'), ['category' => $cat->slug])) }}"
                                    {{ request('category') === $cat->slug ? 'selected' : '' }}>
                                    {{ $cat->name }} ({{ $cat->books_count }})
                                </option>
                                @endforeach
                            </select>
                            <!-- Arrow icon -->
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fa-solid fa-chevron-down text-gray-400 text-xs"></i>
                            </div>
                        </div>
                        @if(request('category') && $activeCategory)
                        <p class="text-xs text-secondary font-medium mt-2 flex items-center gap-1">
                            <i class="fa-solid fa-filter text-xs"></i>
                            Filter: {{ $activeCategory->name }}
                        </p>
                        @endif
                    </div>


                    <!-- Ketersediaan Stok -->
                    <div class="mb-6">
                        <h3 class="text-sm font-bold text-dark uppercase tracking-wider mb-3">
                            <i class="fa-solid fa-box text-secondary mr-1"></i> Ketersediaan
                        </h3>
                        <div class="relative">
                            <select
                                onchange="window.location.href = this.value"
                                class="w-full appearance-none bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl px-4 py-3 pr-10 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent cursor-pointer transition hover:border-secondary">
                                <option value="{{ route('katalog', array_merge(request()->except('stok', 'page'), [])) }}"
                                    {{ !request('stok') ? 'selected' : '' }}>
                                    Semua (Termasuk Habis)
                                </option>
                                <option value="{{ route('katalog', array_merge(request()->except('stok', 'page'), ['stok' => 'tersedia'])) }}"
                                    {{ request('stok') === 'tersedia' ? 'selected' : '' }}>
                                    Stok Tersedia
                                </option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fa-solid fa-chevron-down text-gray-400 text-xs"></i>
                            </div>
                        </div>
                    </div>


                    <!-- Urutkan -->
                    <div>
                        <h3 class="text-sm font-bold text-dark uppercase tracking-wider mb-3">
                            <i class="fa-solid fa-arrow-up-wide-short text-secondary mr-1"></i> Urutkan
                        </h3>
                        <div class="relative">
                            <select
                                onchange="window.location.href = this.value"
                                class="w-full appearance-none bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl px-4 py-3 pr-10 focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent cursor-pointer transition hover:border-secondary">
                                @foreach(['terbaru' => 'Terbaru', 'az' => 'Judul A → Z', 'za' => 'Judul Z → A', 'harga_terendah' => 'Harga Terendah', 'harga_tertinggi' => 'Harga Tertinggi'] as $val => $label)
                                <option
                                    value="{{ route('katalog', array_merge(request()->except('sort', 'page'), ['sort' => $val])) }}"
                                    {{ $sort === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fa-solid fa-chevron-down text-gray-400 text-xs"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </aside>

            <!-- ===== MAIN CONTENT ===== -->
            <div class="flex-1 min-w-0">

                <!-- Toolbar: hasil + filter aktif -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
                    <div>
                        <p class="text-gray-600 text-sm">
                            Menampilkan <span class="font-bold text-dark">{{ $books->firstItem() ?? 0 }}–{{ $books->lastItem() ?? 0 }}</span>
                            dari <span class="font-bold text-dark">{{ $books->total() }}</span> buku
                            @if(request('search'))
                                untuk pencarian "<span class="text-secondary font-medium">{{ request('search') }}</span>"
                            @endif
                            @if($activeCategory)
                                dalam kategori "<span class="text-secondary font-medium">{{ $activeCategory->name }}</span>"
                            @endif
                        </p>
                    </div>

                    <!-- Mobile Sort Dropdown -->
                    <div class="sm:hidden w-full">
                        <select onchange="window.location=this.value" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                            @foreach(['terbaru' => 'Terbaru', 'az' => 'Judul A-Z', 'za' => 'Judul Z-A', 'harga_terendah' => 'Harga Terendah', 'harga_tertinggi' => 'Harga Tertinggi'] as $val => $label)
                            <option value="{{ route('katalog', array_merge(request()->except('sort', 'page'), ['sort' => $val])) }}" {{ $sort === $val ? 'selected' : '' }}>
                                Urut: {{ $label }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @if($books->isEmpty())
                <!-- Empty state -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 py-20 text-center">
                    <i class="fa-solid fa-book-open text-6xl text-gray-200 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-400 mb-2">Buku tidak ditemukan</h3>
                    <p class="text-gray-400 text-sm mb-6">Coba ubah kata kunci atau hapus filter yang aktif.</p>
                    <a href="{{ route('katalog') }}" class="inline-block bg-secondary text-white px-6 py-2.5 rounded-full text-sm font-medium hover:bg-blue-600 transition">
                        Lihat Semua Buku
                    </a>
                </div>
                @else
                <!-- Book Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($books as $book)
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col group border border-gray-100 hover:-translate-y-1">
                        <!-- Gambar -->
                        <div class="relative overflow-hidden bg-gray-100" style="height:220px">
                            <img src="{{ $book->image_url ?: 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=400' }}"
                                 alt="{{ $book->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out">

                            <!-- Badge -->
                            <div class="absolute top-2 left-2 flex flex-col gap-1">
                                @if($book->is_featured)
                                <span class="bg-accent text-white text-xs font-bold px-2 py-0.5 rounded-full shadow">⭐ Pilihan</span>
                                @endif
                            </div>

                            @if($book->stock <= 0)
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                                <span class="bg-red-500 text-white text-sm font-bold px-3 py-1 rounded-full shadow">Stok Habis</span>
                            </div>
                            @elseif($book->stock <= 5)
                            <div class="absolute bottom-2 left-2">
                                <span class="bg-orange-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow">Sisa {{ $book->stock }}</span>
                            </div>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="p-4 flex flex-col flex-grow">
                            <a href="{{ route('katalog', ['category' => $book->category->slug]) }}"
                               class="text-xs text-secondary font-semibold uppercase tracking-wider mb-1 hover:underline">
                                {{ $book->category->name }}
                            </a>
                            <h3 class="text-sm font-bold text-dark mb-0.5 line-clamp-2 leading-snug">{{ $book->title }}</h3>
                            <p class="text-xs text-gray-500 mb-3">{{ $book->author }}</p>

                            @if($book->description)
                            <p class="text-xs text-gray-400 line-clamp-2 mb-3">{{ $book->description }}</p>
                            @endif

                            <!-- Harga + Tombol -->
                            <div class="mt-auto flex items-center justify-between pt-3 border-t border-gray-50">
                                <span class="text-lg font-bold text-primary">Rp {{ number_format((float)$book->price, 0, ',', '.') }}</span>

                                @if($book->stock > 0)
                                <button onclick="addToCart({{ $book->id }})"
                                        class="bg-primary hover:bg-secondary text-white h-9 px-4 rounded-lg flex items-center gap-1.5 text-xs font-semibold transition shadow-sm hover:shadow-md"
                                        title="Tambah ke Keranjang">
                                    <i class="fa-solid fa-cart-plus"></i>
                                    <span class="hidden sm:inline">Tambah</span>
                                </button>
                                @else
                                <button disabled class="bg-gray-200 text-gray-400 h-9 px-4 rounded-lg text-xs font-semibold cursor-not-allowed">
                                    Habis
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-10 flex justify-center">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-2 py-1">
                        {{ $books->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
