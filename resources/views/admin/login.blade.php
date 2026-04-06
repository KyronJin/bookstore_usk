<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — PustakaBiru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1E3A8A',
                        secondary: '#3B82F6',
                        accent: '#F59E0B',
                        dark: '#0F172A',
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js" defer></script>
</head>
<body class="bg-dark min-h-screen flex items-center justify-center px-4" style="font-family: 'Inter', sans-serif;">

    <!-- Background pattern -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-secondary/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-primary/20 rounded-full blur-3xl"></div>
    </div>

    <div class="w-full max-w-md relative z-10">

        <!-- Flash Messages -->
        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="mb-4 bg-green-500/20 border border-green-500/40 text-green-400 px-4 py-3 rounded-xl flex items-center gap-3 text-sm">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="mb-4 bg-red-500/20 border border-red-500/40 text-red-400 px-4 py-3 rounded-xl flex items-center gap-3 text-sm">
            <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
        </div>
        @endif

        <!-- Card -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl shadow-2xl overflow-hidden">

            <!-- Header -->
            <div class="bg-gradient-to-r from-primary to-blue-800 p-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/10 rounded-2xl mb-4">
                    <i class="fa-solid fa-shield-halved text-3xl text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-white">Admin Panel</h1>
                <p class="text-blue-200 text-sm mt-1">PustakaBiru Administrator</p>
            </div>

            <!-- Form -->
            <div class="p-8">
                @if($errors->any())
                <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 mb-6">
                    @foreach($errors->all() as $error)
                    <p class="text-red-400 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation text-xs"></i> {{ $error }}
                    </p>
                    @endforeach
                </div>
                @endif

                <form action="{{ route('admin.login') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Email Administrator</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-envelope text-gray-500 text-sm"></i>
                            </div>
                            <input type="email" name="email" id="email" required
                                   value="{{ old('email') }}"
                                   autocomplete="email"
                                   class="w-full bg-gray-800 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-700' }} text-white rounded-xl pl-11 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent placeholder-gray-600 transition"
                                   placeholder="admin@pustakabiru.com">
                        </div>
                    </div>

                    <!-- Password -->
                    <div x-data="{ showPass: false }">
                        <label class="block text-sm font-medium text-gray-400 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fa-solid fa-lock text-gray-500 text-sm"></i>
                            </div>
                            <input :type="showPass ? 'text' : 'password'" name="password" id="password" required
                                   autocomplete="current-password"
                                   class="w-full bg-gray-800 border border-gray-700 text-white rounded-xl pl-11 pr-12 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent placeholder-gray-600 transition"
                                   placeholder="••••••••">
                            <button type="button" @click="showPass = !showPass"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-gray-300 transition">
                                <i :class="showPass ? 'fa-eye-slash' : 'fa-eye'" class="fa-solid text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-secondary rounded border-gray-600 bg-gray-800 focus:ring-secondary">
                        <label for="remember" class="ml-2 text-sm text-gray-400">Ingat Saya</label>
                    </div>

                    <!-- Submit -->
                    <button type="submit" id="btn-admin-login"
                            class="w-full bg-primary hover:bg-blue-800 text-white font-semibold py-3.5 px-6 rounded-xl transition duration-200 flex items-center justify-center gap-2 shadow-lg shadow-primary/30">
                        <i class="fa-solid fa-right-to-bracket"></i> Masuk ke Admin Panel
                    </button>
                </form>

                <!-- Back link -->
                <div class="mt-6 pt-6 border-t border-gray-800 text-center">
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-300 text-sm transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke Website
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer note -->
        <p class="text-center text-gray-600 text-xs mt-6">
            Halaman ini hanya untuk administrator yang berwenang.
        </p>
    </div>

</body>
</html>
