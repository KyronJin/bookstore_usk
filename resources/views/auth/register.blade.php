@extends('layouts.app')

@section('title', 'Daftar Akun - PustakaBiru')

@section('content')
<div class="bg-gray-50 min-h-[calc(100vh-4rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-secondary text-center py-8">
            <i class="fa-solid fa-user-plus text-4xl text-white mb-3"></i>
            <h2 class="text-3xl font-extrabold text-white">Buat Akun Baru</h2>
            <p class="mt-2 text-blue-100 text-sm">Bergabunglah dengan ribuan pembaca lainnya</p>
        </div>
        <div class="p-8">
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                @foreach($errors->all() as $error)
                <p class="text-red-600 text-sm flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-user text-gray-400"></i>
                        </div>
                        <input id="name" name="name" type="text" required value="{{ old('name') }}"
                               class="focus:ring-secondary focus:border-secondary block w-full pl-10 sm:text-sm border-gray-300 rounded-lg py-3 border px-4"
                               placeholder="Nama Lengkap Anda">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                               class="focus:ring-secondary focus:border-secondary block w-full pl-10 sm:text-sm border-gray-300 rounded-lg py-3 border px-4"
                               placeholder="you@example.com">
                    </div>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">No. Telepon <span class="text-gray-400">(opsional)</span></label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-phone text-gray-400"></i>
                        </div>
                        <input id="phone" name="phone" type="text" value="{{ old('phone') }}"
                               class="focus:ring-secondary focus:border-secondary block w-full pl-10 sm:text-sm border-gray-300 rounded-lg py-3 border px-4"
                               placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="new-password" required
                               class="focus:ring-secondary focus:border-secondary block w-full pl-10 sm:text-sm border-gray-300 rounded-lg py-3 border px-4"
                               placeholder="Minimal 6 karakter">
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-check-circle text-gray-400"></i>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               class="focus:ring-secondary focus:border-secondary block w-full pl-10 sm:text-sm border-gray-300 rounded-lg py-3 border px-4"
                               placeholder="Ulangi password">
                    </div>
                </div>

                <div>
                    <button type="submit" id="btn-register"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition mt-2">
                        <i class="fa-solid fa-user-plus mr-2"></i> Daftar Sekarang
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}" class="font-medium text-secondary hover:text-blue-700 transition">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
