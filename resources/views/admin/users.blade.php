@extends('layouts.admin')

@section('title', 'Data User')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-dark">Data User</h1>
        <p class="text-gray-500 text-sm mt-1">Daftar pengguna terdaftar di JeBook</p>
    </div>

    <!-- Search -->
    <form action="{{ route('admin.users.index') }}" method="GET" class="flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
               class="flex-grow border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
        <button type="submit" class="bg-secondary text-white px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-blue-600 transition">Cari</button>
        @if(request('search'))
        <a href="{{ route('admin.users.index') }}" class="border border-gray-300 text-gray-600 px-5 py-2.5 rounded-xl text-sm hover:bg-gray-50 transition">Reset</a>
        @endif
    </form>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">User</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">No. Telepon</th>
                        <th class="px-6 py-3 text-left">Total Pesanan</th>
                        <th class="px-6 py-3 text-left">Bergabung</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img class="h-9 w-9 rounded-full object-cover border border-gray-200"
                                     src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=3B82F6&color=fff" alt="">
                                <span class="font-semibold text-dark">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $user->phone ?: '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs font-bold">{{ $user->orders_count }} pesanan</span>
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                  class="delete-form" data-name="{{ $user->name }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 px-3 py-1.5 rounded-lg text-xs font-medium transition">
                                    <i class="fa-solid fa-trash mr-1"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        <i class="fa-solid fa-users text-3xl mb-2"></i><br>Belum ada user terdaftar.
                    </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $users->appends(request()->all())->links() }}
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const name = this.dataset.name;
            Swal.fire({
                title: 'Hapus User?',
                text: "Apakah Anda yakin ingin menghapus user '" + name + "'? Semua data pesanannya juga akan ikut terhapus!",
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
