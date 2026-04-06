<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PustakaBiru - Toko Buku Online')</title>
    <meta name="description" content="PustakaBiru - Toko buku online terpercaya dengan koleksi terlengkap">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                        light: '#F8FAFC',
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js" defer></script>
    <style>body { font-family: 'Inter', sans-serif; } .glass { background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); }</style>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light text-dark flex flex-col min-h-screen">

    <!-- Flash Messages -->
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="fixed top-4 right-4 z-[999] bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-3">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        <button @click="show = false" class="ml-2 text-white/80 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
    </div>
    @endif
    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         class="fixed top-4 right-4 z-[999] bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-3">
        <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
        <button @click="show = false" class="ml-2 text-white/80 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
    </div>
    @endif
    @if(session('warning'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         class="fixed top-4 right-4 z-[999] bg-yellow-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-3">
        <i class="fa-solid fa-triangle-exclamation"></i> {{ session('warning') }}
        <button @click="show = false" class="ml-2 text-white/80 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
    </div>
    @endif

    <!-- Navigation -->
    <nav class="glass fixed w-full z-50 border-b border-gray-200" x-data="{ mobileOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-primary flex items-center gap-2">
                        <i class="fa-solid fa-book-open"></i> PustakaBiru
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-primary transition font-medium {{ request()->routeIs('home') ? 'text-primary font-semibold' : '' }}">Home</a>
                    <a href="{{ route('katalog') }}" class="text-gray-600 hover:text-primary transition font-medium {{ request()->routeIs('katalog') ? 'text-primary font-semibold' : '' }}">Katalog</a>
                    <a href="{{ route('about') }}" class="text-gray-600 hover:text-primary transition font-medium {{ request()->routeIs('about') ? 'text-primary font-semibold' : '' }}">About Us</a>
                    <a href="{{ route('contact') }}" class="text-gray-600 hover:text-primary transition font-medium {{ request()->routeIs('contact') ? 'text-primary font-semibold' : '' }}">Contact</a>

                    @auth
                        <!-- Cart Icon (hanya user biasa) -->
                        @if(auth()->user()->isUser())
                        <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-primary transition">
                            <i class="fa-solid fa-cart-shopping text-xl"></i>
                            @php $cartCount = collect(session('cart', []))->sum('quantity'); @endphp
                            @if($cartCount > 0)
                            <span class="absolute -top-2 -right-2 bg-accent text-white text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $cartCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('orders.index') }}" class="text-gray-600 hover:text-primary transition font-medium">Pesanan Saya</a>
                        @endif

                        <div class="border-l border-gray-300 h-6 mx-2"></div>

                        <!-- User Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-primary transition">
                                <img class="h-8 w-8 rounded-full border border-gray-300 object-cover"
                                     src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=1E3A8A&color=fff"
                                     alt="Avatar">
                                <span>{{ auth()->user()->name }}</span>
                                <i class="fa-solid fa-chevron-down text-xs"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50" style="display:none">
                                @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fa-solid fa-gauge-high w-4"></i> Admin Panel
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                @else
                                <a href="{{ route('orders.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fa-solid fa-box w-4"></i> Pesanan Saya
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                @endif
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <i class="fa-solid fa-right-from-bracket w-4"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="border-l border-gray-300 h-6 mx-2"></div>
                        <a href="{{ route('login') }}" class="text-primary font-medium hover:text-secondary transition">Login</a>
                        <a href="{{ route('register') }}" class="bg-primary hover:bg-secondary text-white px-5 py-2 rounded-full font-medium transition shadow-md">Daftar</a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileOpen = !mobileOpen" class="text-gray-600 p-2">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileOpen" x-transition class="md:hidden bg-white border-t border-gray-100 pb-4" style="display:none">
            <div class="px-4 pt-2 space-y-2">
                <a href="{{ route('home') }}" class="block py-2 text-gray-700 hover:text-primary font-medium">Home</a>
                <a href="{{ route('katalog') }}" class="block py-2 text-gray-700 hover:text-primary font-medium">Katalog</a>
                <a href="{{ route('about') }}" class="block py-2 text-gray-700 hover:text-primary font-medium">About Us</a>
                <a href="{{ route('contact') }}" class="block py-2 text-gray-700 hover:text-primary font-medium">Contact</a>
                @auth
                    @if(auth()->user()->isUser())
                    <a href="{{ route('cart.index') }}" class="block py-2 text-gray-700 hover:text-primary font-medium">Keranjang</a>
                    <a href="{{ route('orders.index') }}" class="block py-2 text-gray-700 hover:text-primary font-medium">Pesanan Saya</a>
                    @else
                    <a href="{{ route('admin.dashboard') }}" class="block py-2 text-gray-700 hover:text-primary font-medium">Admin Panel</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" class="pt-2 border-t border-gray-100">
                        @csrf
                        <button type="submit" class="text-red-600 font-medium py-2">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block py-2 text-primary font-medium">Login</a>
                    <a href="{{ route('register') }}" class="block py-2 text-primary font-medium">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow pt-16">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-white flex items-center gap-2 mb-4">
                        <i class="fa-solid fa-book-open"></i> PustakaBiru
                    </a>
                    <p class="text-gray-400 text-sm">Menyediakan berbagai macam buku pilihan dengan harga terbaik dan kualitas original.</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Tautan</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition">Home</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-white transition">About Us</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-white transition">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Akun</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        @guest
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Login</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition">Daftar</a></li>
                        @else
                        <li><a href="{{ route('cart.index') }}" class="hover:text-white transition">Keranjang</a></li>
                        <li><a href="{{ route('orders.index') }}" class="hover:text-white transition">Pesanan Saya</a></li>
                        @endguest
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Metode Pembayaran</h4>
                    <div class="flex gap-4">
                        <i class="fa-solid fa-money-bill-wave text-3xl text-gray-400" title="COD"></i>
                        <i class="fa-solid fa-building-columns text-3xl text-gray-400" title="Transfer Bank"></i>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} PustakaBiru. All rights reserved.
            </div>
        </div>


    <script>
        // Set up universal SweetAlert toast
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        function addToCart(bookId) {
            fetch("{{ route('cart.add') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ book_id: bookId })
            })
            .then(async response => {
                const data = await response.json();
                if (response.status === 401) {
                    // Not logged in
                    Swal.fire({
                        icon: 'info',
                        title: 'Harap Login',
                        text: 'Silakan login terlebih dahulu untuk menambahkan buku ke keranjang.',
                        confirmButtonText: 'Pergi ke Login',
                        confirmButtonColor: '#1E3A8A', // primary color
                        showCancelButton: true,
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('login') }}";
                        }
                    });
                    return;
                }
                
                if (response.ok) {
                    // Success
                    Toast.fire({
                        icon: 'success',
                        title: data.message || 'Berhasil ditambahkan!'
                    });
                    
                    // Optionally update cart count bubble if it's on page
                    let cartBadge = document.querySelector('.fa-cart-shopping').nextElementSibling;
                    if(cartBadge && cartBadge.tagName === 'SPAN'){
                        cartBadge.innerText = parseInt(cartBadge.innerText) + 1;
                    }
                } else {
                    // Application error (e.g., out of stock)
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: data.message || 'Terjadi kesalahan.',
                        confirmButtonColor: '#1E3A8A'
                    });
                }
            })
            .catch(error => {
                console.error("Error adding to cart:", error);
                Toast.fire({
                    icon: 'error',
                    title: 'Gagal terhubung ke server.'
                });
            });
        }
    </script>
</body>
</html>
