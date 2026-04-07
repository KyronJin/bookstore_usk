@extends('layouts.app')

@section('title', 'Tentang Kami - JeBook')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h1 class="text-4xl font-bold text-primary mb-4">Tentang JeBook</h1>
            <p class="text-lg text-gray-600">
                JeBook adalah destinasi utama bagi para pencinta literasi. Kami percaya bahwa setiap buku memiliki kekuatan untuk mengubah perspektif dan menginspirasi dunia.
            </p>
        </div>

        <!-- Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-16">
            <div class="rounded-2xl overflow-hidden shadow-xl">
                <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=800&auto=format&fit=crop" alt="Toko Buku Fisik" class="w-full h-full object-cover">
            </div>
            <div>
                <h2 class="text-3xl font-bold text-dark mb-6">Misi Kami</h2>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-primary mt-1">
                            <i class="fa-solid fa-book-open-reader"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-semibold text-dark">Akses Mudah ke Pengetahuan</h3>
                            <p class="mt-1 text-gray-500">Menyediakan berbagai macam buku dari semua genre dengan mudah, cepat, dan aman untuk menjangkau setiap pembaca.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-primary mt-1">
                            <i class="fa-solid fa-hand-holding-heart"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-semibold text-dark">Pelayanan Terbaik</h3>
                            <p class="mt-1 text-gray-500">Kami berkomitmen memberikan pelayanan prima dari pencarian buku hingga buku tiba di tangan Anda tercinta.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-primary mt-1">
                            <i class="fa-solid fa-earth-americas"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-semibold text-dark">Mendukung Komunitas Literasi</h3>
                            <p class="mt-1 text-gray-500">Aktif berkolaborasi dengan penulis lokal dan penerbit untuk membudayakan minat baca di pelosok negeri.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team -->
        <div class="mt-20 text-center">
            <h2 class="text-3xl font-bold text-dark mb-10">Tim Kami</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                <!-- Team Member -->
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                    <img src="https://ui-avatars.com/api/?name=Budi+S&background=random" alt="Founder" class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-gray-100">
                    <h3 class="text-lg font-bold text-dark">Budi Santoso</h3>
                    <p class="text-secondary text-sm">Founder & CEO</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                    <img src="https://ui-avatars.com/api/?name=Siti+A&background=random" alt="Manager" class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-gray-100">
                    <h3 class="text-lg font-bold text-dark">Siti Aminah</h3>
                    <p class="text-secondary text-sm">Operations Manager</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                    <img src="https://ui-avatars.com/api/?name=Andi+W&background=random" alt="Curator" class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-gray-100">
                    <h3 class="text-lg font-bold text-dark">Andi Wijaya</h3>
                    <p class="text-secondary text-sm">Head Curator</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                    <img src="https://ui-avatars.com/api/?name=Rina+K&background=random" alt="Support" class="w-24 h-24 rounded-full mx-auto mb-4 border-4 border-gray-100">
                    <h3 class="text-lg font-bold text-dark">Rina Kartika</h3>
                    <p class="text-secondary text-sm">Customer Support</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
