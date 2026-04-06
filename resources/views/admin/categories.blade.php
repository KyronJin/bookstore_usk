@extends('layouts.admin')

@section('title', 'Kategori Buku')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-dark">Kategori Buku</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola kategori buku di toko Anda</p>
        </div>
        <button onclick="document.getElementById('modal-add').classList.remove('hidden')"
                class="bg-primary hover:bg-blue-800 text-white px-5 py-2 rounded-xl font-medium transition flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah Kategori
        </button>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($categories as $category)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col gap-2">
            <div class="flex items-center justify-between mb-2">
                <div class="bg-blue-100 rounded-xl p-2"><i class="fa-solid fa-tag text-secondary"></i></div>
                <span class="text-xs text-gray-400 font-mono">{{ $category->books_count }} buku</span>
            </div>
            <h3 class="text-lg font-bold text-dark">{{ $category->name }}</h3>
            <p class="text-sm text-gray-500 flex-grow">{{ $category->description ?: '-' }}</p>
            <div class="flex gap-2 mt-4">
                <button onclick='openEditModal({{ json_encode($category) }})'
                        class="flex-1 text-center bg-accent/10 hover:bg-accent/20 text-accent border border-accent/30 px-3 py-1.5 rounded-lg text-sm font-medium transition">
                    <i class="fa-solid fa-pen mr-1"></i> Edit
                </button>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                      onsubmit="return confirm('Hapus kategori {{ $category->name }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 px-3 py-1.5 rounded-lg text-sm font-medium transition">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-16 text-gray-400">
            <i class="fa-solid fa-tags text-4xl mb-3"></i>
            <p>Belum ada kategori. Tambahkan kategori pertama Anda!</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Tambah -->
<div id="modal-add" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-dark">Tambah Kategori Baru</h3>
            <button onclick="document.getElementById('modal-add').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form action="{{ route('admin.categories.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                <input type="text" name="name" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-secondary" placeholder="Contoh: Fiksi">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-secondary resize-none" placeholder="Deskripsi singkat kategori ini..."></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modal-add').classList.add('hidden')"
                        class="flex-1 border border-gray-300 text-gray-700 py-2.5 rounded-xl font-medium hover:bg-gray-50 transition">Batal</button>
                <button type="submit" class="flex-1 bg-primary text-white py-2.5 rounded-xl font-medium hover:bg-blue-800 transition">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="modal-edit" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-dark">Edit Kategori</h3>
            <button onclick="document.getElementById('modal-edit').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form id="form-edit" action="" method="POST" class="p-6 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori <span class="text-red-500">*</span></label>
                <input type="text" id="edit-name" name="name" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea id="edit-description" name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-secondary resize-none"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')"
                        class="flex-1 border border-gray-300 text-gray-700 py-2.5 rounded-xl font-medium hover:bg-gray-50 transition">Batal</button>
                <button type="submit" class="flex-1 bg-accent text-white py-2.5 rounded-xl font-medium hover:bg-yellow-500 transition">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(category) {
    document.getElementById('form-edit').action = '/admin/categories/' + category.id;
    document.getElementById('edit-name').value = category.name;
    document.getElementById('edit-description').value = category.description || '';
    document.getElementById('modal-edit').classList.remove('hidden');
}
</script>
@endsection
