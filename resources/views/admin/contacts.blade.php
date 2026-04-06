@extends('layouts.admin')

@section('title', 'Kotak Masuk')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-dark">Kotak Masuk</h1>
            <p class="text-gray-500 text-sm mt-1">Pesan masuk dari halaman Contact Us</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3 text-left">Pengirim</th>
                        <th class="px-6 py-3 text-left">Subjek</th>
                        <th class="px-6 py-3 text-left">Pesan</th>
                        <th class="px-6 py-3 text-left">Tanggal</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($contacts as $contact)
                    <tr class="hover:bg-gray-50 align-top">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-dark">{{ $contact->name }}</p>
                            <p class="text-xs text-gray-500">{{ $contact->email }}</p>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $contact->subject }}
                        </td>
                        <td class="px-6 py-4 max-w-sm text-gray-600">
                            {{ $contact->message }}
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500 whitespace-nowrap">
                            {{ $contact->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST"
                                  onsubmit="return confirm('Hapus pesan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 py-1.5 px-3 rounded-lg text-xs font-medium transition" title="Hapus Pesan">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <i class="fa-solid fa-envelope-open text-3xl mb-2"></i><br>Belum ada pesan masuk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $contacts->links() }}
        </div>
    </div>
</div>
@endsection
