@extends('layouts.app')

@section('title', 'Tentang Kami - JeBook')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-14">
            <h1 class="text-3xl font-bold text-dark mb-3">Tentang JeBook</h1>
            <p class="text-gray-500">
                JeBook adalah destinasi utama bagi para pencinta literasi. Kami percaya bahwa setiap buku memiliki kekuatan untuk mengubah perspektif dan menginspirasi dunia.
            </p>
        </div>

        <!-- Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center mb-14">
            <div class="rounded-xl overflow-hidden">
                <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?q=80&w=800&auto=format&fit=crop" alt="Toko Buku Fisik" class="w-full h-full object-cover">
            </div>
            <div>
                <h2 class="text-2xl font-bold text-dark mb-5">Misi Kami</h2>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-9 w-9 rounded-lg bg-primary/10 flex items-center justify-center text-primary mt-0.5">
                            <i class="fa-solid fa-book-open-reader text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="font-semibold text-dark">Akses Mudah ke Pengetahuan</h3>
                            <p class="mt-0.5 text-gray-500 text-sm">Menyediakan berbagai macam buku dari semua genre dengan mudah, cepat, dan aman untuk menjangkau setiap pembaca.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-9 w-9 rounded-lg bg-primary/10 flex items-center justify-center text-primary mt-0.5">
                            <i class="fa-solid fa-hand-holding-heart text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="font-semibold text-dark">Pelayanan Terbaik</h3>
                            <p class="mt-0.5 text-gray-500 text-sm">Kami berkomitmen memberikan pelayanan prima dari pencarian buku hingga buku tiba di tangan Anda tercinta.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-9 w-9 rounded-lg bg-primary/10 flex items-center justify-center text-primary mt-0.5">
                            <i class="fa-solid fa-earth-americas text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="font-semibold text-dark">Mendukung Komunitas Literasi</h3>
                            <p class="mt-0.5 text-gray-500 text-sm">Aktif berkolaborasi dengan penulis lokal dan penerbit untuk membudayakan minat baca di pelosok negeri.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
