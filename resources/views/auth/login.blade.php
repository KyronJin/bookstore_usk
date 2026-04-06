@extends('layouts.app')

@section('title', 'Login - PustakaBiru')

@section('content')
<div class="bg-gray-50 min-h-[calc(100vh-4rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-primary text-center py-8">
            <i class="fa-solid fa-book-open text-4xl text-white mb-3"></i>
            <h2 class="text-3xl font-extrabold text-white">Selamat Datang</h2>
            <p class="mt-2 text-blue-200 text-sm">Masuk untuk melanjutkan ke PustakaBiru</p>
        </div>
        <div class="p-8">
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-center gap-2 text-red-700 font-medium mb-1">
                    <i class="fa-solid fa-circle-exclamation"></i> Login Gagal
                </div>
                @foreach($errors->all() as $error)
                <p class="text-red-600 text-sm">{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required
                               value="{{ old('email') }}"
                               class="focus:ring-secondary focus:border-secondary block w-full pl-10 sm:text-sm border-gray-300 rounded-lg py-3 border px-4 {{ $errors->has('email') ? 'border-red-400' : '' }}"
                               placeholder="you@example.com">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                               class="focus:ring-secondary focus:border-secondary block w-full pl-10 sm:text-sm border-gray-300 rounded-lg py-3 border px-4"
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-secondary focus:ring-secondary border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">Ingat Saya</label>
                    </div>
                </div>

                <div>
                    <button type="submit" id="btn-login"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition">
                        <i class="fa-solid fa-right-to-bracket mr-2"></i> Masuk
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-medium text-accent hover:text-yellow-600 transition">Daftar sekarang</a>
                </p>
            </div>

            <!-- Info akun demo -->
            <div class="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-100">
                <p class="text-xs text-blue-600 font-semibold mb-2"><i class="fa-solid fa-circle-info mr-1"></i> Akun Demo:</p>
                <p class="text-xs text-blue-500">Admin: <strong>admin@pustakabiru.com</strong> / <strong>admin123</strong></p>
                <p class="text-xs text-blue-500">User: <strong>budi@gmail.com</strong> / <strong>user123</strong></p>
            </div>
        </div>
    </div>
</div>
@endsection
