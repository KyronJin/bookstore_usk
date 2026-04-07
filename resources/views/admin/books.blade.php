@extends('layouts.admin')

@section('title', 'Data Buku')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-dark">Data Buku</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola koleksi buku di toko Anda</p>
        </div>
        <button onclick="document.getElementById('modal-add').classList.remove('hidden')"
                class="bg-primary hover:bg-blue-800 text-white px-5 py-2 rounded-xl font-medium transition flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah Buku
        </button>
    </div>

    <!-- Filter Bar -->
    <form action="{{ route('admin.books.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau penulis..."
               class="flex-grow border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
        <select name="category_id" class="border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-secondary text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-blue-600 transition">Cari</button>
        @if(request('search') || request('category_id'))
        <a href="{{ route('admin.books.index') }}" class="border border-gray-300 text-gray-600 px-5 py-2.5 rounded-xl text-sm hover:bg-gray-50 transition">Reset</a>
        @endif
    </form>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">Buku</th>
                        <th class="px-6 py-3 text-left">Kategori</th>
                        <th class="px-6 py-3 text-left">Harga</th>
                        <th class="px-6 py-3 text-left">Stok</th>
                        <th class="px-6 py-3 text-left">Pilihan</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($books as $book)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $book->image_url ?: 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?q=80&w=80' }}"
                                     alt="{{ $book->title }}" class="w-10 h-14 object-cover rounded-lg flex-shrink-0">
                                <div>
                                    <p class="font-semibold text-dark line-clamp-1">{{ $book->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $book->author }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs font-medium">{{ $book->category->name }}</span>
                        </td>
                        <td class="px-6 py-4 font-semibold">Rp {{ number_format((float)$book->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="{{ $book->stock <= 5 ? 'text-red-600 font-bold' : 'text-gray-700' }}">{{ $book->stock }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($book->is_featured)
                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs font-bold">⭐ Pilihan</span>
                            @else
                            <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <button onclick='openBookEdit({{ json_encode($book) }})'
                                        class="bg-accent/10 hover:bg-accent/20 text-accent py-1.5 px-3 rounded-lg text-xs font-medium transition">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form action="{{ route('admin.books.destroy', $book) }}" method="POST"
                                      class="delete-form" data-title="{{ $book->title }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 py-1.5 px-3 rounded-lg text-xs font-medium transition">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        <i class="fa-solid fa-book text-3xl mb-2"></i><br>Belum ada buku. Tambahkan buku pertama Anda!
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $books->appends(request()->all())->links() }}
        </div>
    </div>
</div>

<!-- Modal Tambah Buku -->
<div id="modal-add" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-xl my-4">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-dark">Tambah Buku Baru</h3>
            <button onclick="document.getElementById('modal-add').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        <form action="{{ route('admin.books.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Buku *</label>
                    <input type="text" name="title" value="{{ old('_method') !== 'PUT' ? old('title') : '' }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    @if(old('_method') !== 'PUT') @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Penulis *</label>
                    <input type="text" name="author" value="{{ old('_method') !== 'PUT' ? old('author') : '' }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    @if(old('_method') !== 'PUT') @error('author')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Penerbit</label>
                    <input type="text" name="publisher" value="{{ old('_method') !== 'PUT' ? old('publisher') : '' }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                    <select name="category_id" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (old('_method') !== 'PUT' && old('category_id') == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp) *</label>
                    <input type="number" name="price" value="{{ old('_method') !== 'PUT' ? old('price') : '' }}" required min="0" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    @if(old('_method') !== 'PUT') @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok *</label>
                    <input type="number" name="stock" value="{{ old('_method') !== 'PUT' ? old('stock', 0) : 0 }}" required min="0" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    @if(old('_method') !== 'PUT') @error('stock')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror @endif
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">URL Gambar</label>
                    <input type="url" name="image_url" value="{{ old('_method') !== 'PUT' ? old('image_url') : '' }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-secondary" placeholder="https://...">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-secondary resize-none">{{ old('_method') !== 'PUT' ? old('description') : '' }}</textarea>
                </div>
                <div class="col-span-2 flex items-center gap-2">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ (old('_method') !== 'PUT' && old('is_featured')) ? 'checked' : '' }} class="h-4 w-4 text-secondary rounded">
                    <label for="is_featured" class="text-sm font-medium text-gray-700">Tampilkan sebagai buku pilihan (featured)</label>
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')"
                        class="flex-1 border border-gray-300 text-gray-700 py-2.5 rounded-xl font-medium hover:bg-gray-50 transition">Batal</button>
                <button type="submit" class="flex-1 bg-primary text-white py-2.5 rounded-xl font-medium hover:bg-blue-800 transition">Simpan Buku</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Buku -->
<div id="modal-edit" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-xl my-4">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-dark">Edit Buku</h3>
            <button onclick="document.getElementById('modal-edit').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        <form id="form-edit" action="{{ old('_method') === 'PUT' ? '/admin/books/'.old('book_id') : '' }}" method="POST" class="p-6 space-y-4">
            @csrf @method('PUT')
            <input type="hidden" name="book_id" id="edit-id" value="{{ old('book_id') }}">
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Buku *</label>
                    <input type="text" id="edit-title" name="title" value="{{ old('_method') === 'PUT' ? old('title') : '' }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    @if(old('_method') === 'PUT') @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Penulis *</label>
                    <input type="text" id="edit-author" name="author" value="{{ old('_method') === 'PUT' ? old('author') : '' }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    @if(old('_method') === 'PUT') @error('author')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Penerbit</label>
                    <input type="text" id="edit-publisher" name="publisher" value="{{ old('_method') === 'PUT' ? old('publisher') : '' }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                    <select id="edit-category" name="category_id" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (old('_method') === 'PUT' && old('category_id') == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp) *</label>
                    <input type="number" id="edit-price" name="price" value="{{ old('_method') === 'PUT' ? old('price') : '' }}" required min="0" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    @if(old('_method') === 'PUT') @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok *</label>
                    <input type="number" id="edit-stock" name="stock" value="{{ old('_method') === 'PUT' ? old('stock') : '' }}" required min="0" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    @if(old('_method') === 'PUT') @error('stock')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror @endif
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">URL Gambar</label>
                    <input type="url" id="edit-image" name="image_url" value="{{ old('_method') === 'PUT' ? old('image_url') : '' }}" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea id="edit-description" name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-secondary resize-none">{{ old('_method') === 'PUT' ? old('description') : '' }}</textarea>
                </div>
                <div class="col-span-2 flex items-center gap-2">
                    <input type="checkbox" id="edit-featured" name="is_featured" value="1" {{ (old('_method') === 'PUT' && old('is_featured')) ? 'checked' : '' }} class="h-4 w-4 text-secondary rounded">
                    <label for="edit-featured" class="text-sm font-medium text-gray-700">Buku Pilihan (Featured)</label>
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')"
                        class="flex-1 border border-gray-300 text-gray-700 py-2.5 rounded-xl font-medium hover:bg-gray-50 transition">Batal</button>
                <button type="submit" class="flex-1 bg-accent text-white py-2.5 rounded-xl font-medium hover:bg-yellow-500 transition">Update Buku</button>
            </div>
        </form>
    </div>
</div>

<script>
function openBookEdit(book) {
    document.getElementById('form-edit').action = '/admin/books/' + book.id;
    document.getElementById('edit-id').value = book.id;
    document.getElementById('edit-title').value = book.title;
    document.getElementById('edit-author').value = book.author;
    document.getElementById('edit-publisher').value = book.publisher || '';
    document.getElementById('edit-category').value = book.category_id;
    document.getElementById('edit-price').value = book.price;
    document.getElementById('edit-stock').value = book.stock;
    document.getElementById('edit-image').value = book.image_url || '';
    document.getElementById('edit-description').value = book.description || '';
    document.getElementById('edit-featured').checked = book.is_featured;
    document.getElementById('modal-edit').classList.remove('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    @if($errors->any())
        @if(old('_method') === 'PUT')
            document.getElementById('modal-edit').classList.remove('hidden');
        @else
            document.getElementById('modal-add').classList.remove('hidden');
        @endif
    @endif

    // Konfirmasi hapus menggunakan SweetAlert
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const title = this.dataset.title;
            Swal.fire({
                title: 'Hapus Buku?',
                text: "Apakah Anda yakin ingin menghapus buku '" + title + "'?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
});
</script>
@endsection
