@extends('layouts.app')

@section('title', 'Daftar Akun - JeBook')

@section('content')
<div class="bg-gray-50 min-h-[calc(100vh-4rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="bg-primary text-center py-6">
            <i class="fa-solid fa-user-plus text-3xl text-white mb-2"></i>
            <h2 class="text-2xl font-bold text-white">Buat Akun Baru</h2>
            <p class="mt-1 text-blue-200 text-sm">Bergabunglah dengan ribuan pembaca lainnya</p>
        </div>
        <div class="p-6">
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-5">
                @foreach($errors->all() as $error)
                <p class="text-red-600 text-sm flex items-center gap-1"><i class="fa-solid fa-circle-exclamation text-xs"></i> {{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-user text-gray-400 text-sm"></i>
                        </div>
                        <input id="name" name="name" type="text" required value="{{ old('name') }}"
                               class="block w-full pl-9 text-sm border border-gray-200 rounded-lg py-2.5 px-3 focus:ring-1 focus:ring-secondary focus:border-secondary"
                               placeholder="Nama Lengkap Anda">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-envelope text-gray-400 text-sm"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                               class="block w-full pl-9 text-sm border border-gray-200 rounded-lg py-2.5 px-3 focus:ring-1 focus:ring-secondary focus:border-secondary"
                               placeholder="you@example.com">
                    </div>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">No. Telepon <span class="text-gray-400">(opsional)</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-phone text-gray-400 text-sm"></i>
                        </div>
                        <input id="phone" name="phone" type="text" value="{{ old('phone') }}"
                               class="block w-full pl-9 text-sm border border-gray-200 rounded-lg py-2.5 px-3 focus:ring-1 focus:ring-secondary focus:border-secondary"
                               placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-400 text-sm"></i>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="new-password" required
                               class="block w-full pl-9 text-sm border border-gray-200 rounded-lg py-2.5 px-3 focus:ring-1 focus:ring-secondary focus:border-secondary"
                               placeholder="Minimal 6 karakter">
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-check-circle text-gray-400 text-sm"></i>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               class="block w-full pl-9 text-sm border border-gray-200 rounded-lg py-2.5 px-3 focus:ring-1 focus:ring-secondary focus:border-secondary"
                               placeholder="Ulangi password">
                    </div>
                </div>

                <div>
                    <button type="submit" id="btn-register"
                            class="w-full py-2.5 px-4 rounded-lg text-sm font-medium text-white bg-primary hover:bg-blue-800 transition">
                        <i class="fa-solid fa-user-plus mr-1.5"></i> Daftar Sekarang
                    </button>
                </div>
            </form>

            <div class="mt-5 text-center">
                <p class="text-sm text-gray-500">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}" class="font-medium text-primary hover:text-blue-800 transition">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
