@extends('layouts.app')

@section('title', 'Login - JeBook')

@section('content')
<div class="bg-gray-50 min-h-[calc(100vh-4rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="bg-primary text-center py-6">
            <i class="fa-solid fa-book-open text-3xl text-white mb-2"></i>
            <h2 class="text-2xl font-bold text-white">Selamat Datang</h2>
            <p class="mt-1 text-blue-200 text-sm">Masuk untuk melanjutkan ke JeBook</p>
        </div>
        <div class="p-6">
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-5">
                <div class="flex items-center gap-1.5 text-red-700 text-sm font-medium mb-0.5">
                    <i class="fa-solid fa-circle-exclamation text-xs"></i> Login Gagal
                </div>
                @foreach($errors->all() as $error)
                <p class="text-red-600 text-sm">{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-envelope text-gray-400 text-sm"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required
                               value="{{ old('email') }}"
                               class="block w-full pl-9 text-sm border border-gray-200 rounded-lg py-2.5 px-3 focus:ring-1 focus:ring-secondary focus:border-secondary {{ $errors->has('email') ? 'border-red-400' : '' }}"
                               placeholder="you@example.com">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-400 text-sm"></i>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                               class="block w-full pl-9 text-sm border border-gray-200 rounded-lg py-2.5 px-3 focus:ring-1 focus:ring-secondary focus:border-secondary"
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-600">Ingat Saya</label>
                    </div>
                </div>

                <div>
                    <button type="submit" id="btn-login"
                            class="w-full py-2.5 px-4 rounded-lg text-sm font-medium text-white bg-primary hover:bg-blue-800 transition">
                        <i class="fa-solid fa-right-to-bracket mr-1.5"></i> Masuk
                    </button>
                </div>
            </form>

            <div class="mt-5 text-center">
                <p class="text-sm text-gray-500">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-medium text-primary hover:text-blue-800 transition">Daftar sekarang</a>
                </p>
            </div>

            <!-- Info akun demo -->
            <div class="mt-5 p-3 bg-gray-50 rounded-lg border border-gray-100">
                <p class="text-xs text-gray-500 font-medium mb-1"><i class="fa-solid fa-circle-info mr-1"></i> Akun Demo:</p>
                <p class="text-xs text-gray-500">Admin: <strong>admin@JeBook.com</strong> / <strong>admin123</strong></p>
                <p class="text-xs text-gray-500">User: <strong>budi@gmail.com</strong> / <strong>user123</strong></p>
            </div>
        </div>
    </div>
</div>
@endsection
