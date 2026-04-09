@extends('layouts.app')

@section('title', 'Katalog Buku - JeBook')

@section('content')
    <div class="bg-gray-50 min-h-screen">

        <!-- Page Header -->
        <div class="bg-primary text-white py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold mb-1">Katalog Buku</h1>
                        <p class="text-blue-200 text-sm">Temukan buku favoritmu dari koleksi kami</p>
                    </div>
                    <!-- Search Bar -->
                    <form action="{{ route('katalog') }}" method="GET" class="flex gap-2 w-full md:w-auto">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif
                        <div class="flex items-center bg-white/15 border border-white/30 rounded-lg px-3 py-2 flex-grow md:w-64">
                            <i class="fa-solid fa-magnifying-glass text-white/60 mr-2 text-sm"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari judul, penulis..."
                                class="bg-transparent w-full text-white placeholder-white/50 focus:outline-none text-sm">
                        </div>
                        <button type="submit"
                            class="bg-white text-primary px-4 py-2 rounded-lg font-medium transition text-sm hover:bg-gray-100">
                            Cari
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col lg:flex-row gap-8">

                <!-- ===== SIDEBAR FILTER ===== -->
                <aside class="lg:w-60 flex-shrink-0">
                    <div class="bg-white rounded-lg border border-gray-200 p-5 sticky top-20">

                        <!-- Clear filter button -->
                        @if(request('search') || request('category') || request('sort') || request('stok'))
                            <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-100">
                                <span class="text-sm font-semibold text-dark">Filter Aktif</span>
                                <a href="{{ route('katalog') }}"
                                    class="text-xs text-red-500 hover:text-red-700 font-medium transition">
                                    Reset
                                </a>
                            </div>
                        @endif

                        <!-- Kategori -->
                        <div class="mb-5">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Kategori</h3>
                            <select onchange="window.location.href = this.value"
                                class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-secondary focus:border-secondary cursor-pointer">
                                <option
                                    value="{{ route('katalog', array_merge(request()->except('category', 'page'), [])) }}"
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
                        </div>

                        <!-- Ketersediaan Stok -->
                        <div class="mb-5">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Ketersediaan</h3>
                            <select onchange="window.location.href = this.value"
                                class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-secondary focus:border-secondary cursor-pointer">
                                <option
                                    value="{{ route('katalog', array_merge(request()->except('stok', 'page'), [])) }}"
                                    {{ !request('stok') ? 'selected' : '' }}>
                                    Semua (Termasuk Habis)
                                </option>
                                <option
                                    value="{{ route('katalog', array_merge(request()->except('stok', 'page'), ['stok' => 'tersedia'])) }}"
                                    {{ request('stok') === 'tersedia' ? 'selected' : '' }}>
                                    Stok Tersedia
                                </option>
                            </select>
                        </div>

                        <!-- Urutkan -->
                        <div>
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Urutkan</h3>
                            <select onchange="window.location.href = this.value"
                                class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:ring-1 focus:ring-secondary focus:border-secondary cursor-pointer">
                                @foreach(['terbaru' => 'Terbaru', 'az' => 'Judul A → Z', 'za' => 'Judul Z → A', 'harga_terendah' => 'Harga Terendah', 'harga_tertinggi' => 'Harga Tertinggi'] as $val => $label)
                                    <option
                                        value="{{ route('katalog', array_merge(request()->except('sort', 'page'), ['sort' => $val])) }}"
                                        {{ $sort === $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </aside>

                <!-- ===== MAIN CONTENT ===== -->
                <div class="flex-1 min-w-0">

                    <!-- Toolbar -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
                        <p class="text-gray-500 text-sm">
                            Menampilkan <span class="font-medium text-dark">{{ $books->firstItem() ?? 0 }}–{{ $books->lastItem() ?? 0 }}</span>
                            dari <span class="font-medium text-dark">{{ $books->total() }}</span> buku
                            @if(request('search'))
                                untuk "<span class="text-dark font-medium">{{ request('search') }}</span>"
                            @endif
                            @if($activeCategory)
                                dalam <span class="text-dark font-medium">{{ $activeCategory->name }}</span>
                            @endif
                        </p>

                        <!-- Mobile Sort -->
                        <div class="sm:hidden w-full">
                            <select onchange="window.location=this.value"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-secondary">
                                @foreach(['terbaru' => 'Terbaru', 'az' => 'Judul A-Z', 'za' => 'Judul Z-A', 'harga_terendah' => 'Harga Terendah', 'harga_tertinggi' => 'Harga Tertinggi'] as $val => $label)
                                    <option
                                        value="{{ route('katalog', array_merge(request()->except('sort', 'page'), ['sort' => $val])) }}"
                                        {{ $sort === $val ? 'selected' : '' }}>
                                        Urut: {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if($books->isEmpty())
                        <!-- Empty state -->
                        <div class="bg-white rounded-lg border border-gray-200 py-16 text-center">
                            <i class="fa-solid fa-book-open text-5xl text-gray-200 mb-4"></i>
                            <h3 class="text-lg font-semibold text-gray-400 mb-2">Buku tidak ditemukan</h3>
                            <p class="text-gray-400 text-sm mb-5">Coba ubah kata kunci atau hapus filter yang aktif.</p>
                            <a href="{{ route('katalog') }}"
                                class="inline-block bg-primary text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-blue-800 transition">
                                Lihat Semua Buku
                            </a>
                        </div>
                    @else
                        <!-- Book Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5">
                            @foreach($books as $book)
                                <div class="bg-white rounded-lg overflow-hidden flex flex-col border border-gray-100 hover:shadow-md transition">
                                    <!-- Gambar -->
                                    <div class="relative overflow-hidden bg-gray-100" style="height:200px">
                                        <img src="{{ $book->image_url ?: 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=400' }}"
                                            alt="{{ $book->title }}"
                                            class="w-full h-full object-cover">

                                        @if($book->stock <= 0)
                                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                                <span class="bg-red-500 text-white text-xs font-medium px-3 py-1 rounded">Stok Habis</span>
                                            </div>
                                        @elseif($book->stock <= 5)
                                            <div class="absolute bottom-2 left-2">
                                                <span class="bg-orange-500 text-white text-xs font-medium px-2 py-0.5 rounded">Sisa {{ $book->stock }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Info -->
                                    <div class="p-4 flex flex-col flex-grow">
                                        <a href="{{ route('katalog', ['category' => $book->category->slug]) }}"
                                            class="text-xs text-secondary font-medium mb-1 hover:underline">
                                            {{ $book->category->name }}
                                        </a>
                                        <h3 class="text-sm font-semibold text-dark mb-0.5 line-clamp-2">{{ $book->title }}</h3>
                                        <p class="text-xs text-gray-400 mb-3">{{ $book->author }}</p>

                                        @if($book->description)
                                            <p class="text-xs text-gray-400 line-clamp-2 mb-3">{{ $book->description }}</p>
                                        @endif

                                        <!-- Harga + Tombol -->
                                        <div class="mt-auto flex items-center justify-between pt-3 border-t border-gray-50">
                                            <span class="text-base font-bold text-primary">Rp {{ number_format((float) $book->price, 0, ',', '.') }}</span>

                                            @if($book->stock > 0)
                                                <button onclick="addToCart({{ $book->id }})"
                                                    class="bg-primary hover:bg-blue-800 text-white h-8 px-3 rounded-lg flex items-center gap-1 text-xs font-medium transition"
                                                    title="Tambah ke Keranjang">
                                                    <i class="fa-solid fa-cart-plus"></i>
                                                    <span class="hidden sm:inline">Tambah</span>
                                                </button>
                                            @else
                                                <button disabled
                                                    class="bg-gray-100 text-gray-400 h-8 px-3 rounded-lg text-xs cursor-not-allowed">
                                                    Habis
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-8 flex justify-center">
                            <div class="bg-white rounded-lg border border-gray-200 px-2 py-1">
                                {{ $books->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection