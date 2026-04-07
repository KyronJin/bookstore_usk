<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1E3A8A',    /* Blue-900 */
                        secondary: '#3B82F6',  /* Blue-500 */
                        accent: '#F59E0B',     /* Amber */
                        dark: '#0F172A',       /* Slate-900 */
                        light: '#F8FAFC',      /* Slate-50 */
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js" defer></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 flex flex-col md:flex-row min-h-screen font-sans" x-data="{ sidebarOpen: false }">

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

    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" class="fixed inset-0 z-20 bg-black bg-opacity-50 transition-opacity md:hidden" @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed z-30 inset-y-0 left-0 w-64 transition duration-300 transform bg-dark text-white overflow-y-auto md:translate-x-0 md:static md:inset-auto">
        <div class="flex items-center justify-center p-6 border-b border-gray-700">
            <a href="/admin" class="text-2xl font-bold flex items-center gap-2">
                <i class="fa-solid fa-book-open text-secondary"></i>
                <span class="text-white">Admin Panel</span>
            </a>
        </div>

        <nav class="p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-primary border-l-4 border-secondary text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} flex items-center px-4 py-3 rounded-md transition">
                <i class="fa-solid fa-gauge-high w-6"></i> Dashboard
            </a>
            <p class="px-4 pt-4 pb-2 text-xs uppercase text-gray-500 font-semibold">Master Data</p>
            <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories*') ? 'bg-primary border-l-4 border-secondary text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} flex items-center px-4 py-3 rounded-md transition">
                <i class="fa-solid fa-tags w-6"></i> Kategori Buku
            </a>
            <a href="{{ route('admin.books.index') }}" class="{{ request()->routeIs('admin.books*') ? 'bg-primary border-l-4 border-secondary text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} flex items-center px-4 py-3 rounded-md transition">
                <i class="fa-solid fa-book w-6"></i> Data Buku
            </a>
            <p class="px-4 pt-4 pb-2 text-xs uppercase text-gray-500 font-semibold">Transaksi & Info</p>
            <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders*') ? 'bg-primary border-l-4 border-secondary text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} flex items-center px-4 py-3 rounded-md transition">
                <i class="fa-solid fa-cart-shopping w-6"></i> Pesanan
            </a>
            <a href="{{ route('admin.contacts.index') }}" class="{{ request()->routeIs('admin.contacts*') ? 'bg-primary border-l-4 border-secondary text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} flex items-center px-4 py-3 rounded-md transition">
                <i class="fa-solid fa-envelope w-6"></i> Kotak Masuk
            </a>
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users*') ? 'bg-primary border-l-4 border-secondary text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }} flex items-center px-4 py-3 rounded-md transition">
                <i class="fa-solid fa-users w-6"></i> Data User
            </a>
        </nav>
    </aside>


    <!-- Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navbar -->
        <header class="flex items-center justify-between p-4 bg-white border-b border-gray-200">
            <div class="flex items-center">
                <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none md:hidden">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <div class="ml-4 md:hidden font-semibold text-lg text-gray-700">Admin Panel</div>
            </div>
            
            <div class="flex items-center gap-4">
                <button class="text-gray-500 hover:text-primary transition">
                    <i class="fa-regular fa-bell text-xl"></i>
                </button>
                
                <div x-data="{ dropdownOpen: false }" class="relative">
                    <button @click="dropdownOpen = !dropdownOpen" class="flex items-center gap-2 focus:outline-none">
                        <img class="h-8 w-8 rounded-full object-cover border border-gray-300" src="https://ui-avatars.com/api/?name=Admin&background=1E3A8A&color=fff" alt="Admin avatar">
                        <span class="hidden md:block text-sm font-medium text-gray-700">Administrator</span>
                        <i class="fa-solid fa-chevron-down text-xs text-gray-500"></i>
                    </button>

                    <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50 left-auto" style="display: none;">
                        <a href="{{ route('home') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" target="_blank">
                            <i class="fa-solid fa-arrow-up-right-from-square w-4 text-gray-400"></i> Lihat Website
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <i class="fa-solid fa-right-from-bracket w-4"></i> Logout Admin
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>


        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>
