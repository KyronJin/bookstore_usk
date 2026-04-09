@extends('layouts.app')

@section('title', 'Hubungi Admin - JeBook')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-10">
            <h1 class="text-3xl font-bold text-dark mb-3">Hubungi Admin</h1>
            <p class="text-gray-500">
                Punya pertanyaan tentang pesanan, ketersediaan buku, atau kerjasama? Jangan ragu untuk menghubungi kami melalui form di bawah ini.
            </p>
        </div>

        <div class="max-w-4xl mx-auto bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <!-- Contact Info -->
                <div class="bg-primary text-white p-8">
                    <div>
                        <h2 class="text-xl font-bold mb-5">Informasi Kontak</h2>
                        <ul class="space-y-5">
                            <li class="flex items-start">
                                <i class="fa-solid fa-location-dot mt-1 w-5 text-blue-200"></i>
                                <div class="ml-3">
                                    <p class="font-medium">Alamat</p>
                                    <p class="text-blue-200 text-sm mt-0.5">Jl. Pendidikan No. 123,<br>Kota Mahasiswa, 40123<br>Indonesia</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <i class="fa-solid fa-phone mt-1 w-5 text-blue-200"></i>
                                <div class="ml-3">
                                    <p class="font-medium">Telepon / WhatsApp</p>
                                    <p class="text-blue-200 text-sm mt-0.5">+62 812-3456-7890</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <i class="fa-solid fa-envelope mt-1 w-5 text-blue-200"></i>
                                <div class="ml-3">
                                    <p class="font-medium">Email</p>
                                    <p class="text-blue-200 text-sm mt-0.5">support@JeBook.com</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="mt-10">
                        <div class="flex space-x-3">
                            <a href="#" class="h-9 w-9 bg-white/15 rounded-lg flex items-center justify-center hover:bg-white/25 transition text-sm">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                            <a href="#" class="h-9 w-9 bg-white/15 rounded-lg flex items-center justify-center hover:bg-white/25 transition text-sm">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            <a href="#" class="h-9 w-9 bg-white/15 rounded-lg flex items-center justify-center hover:bg-white/25 transition text-sm">
                                <i class="fa-brands fa-twitter"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="p-8">
                    <h2 class="text-xl font-bold text-dark mb-5">Kirim Pesan</h2>
                    
                    @if(session('success'))
                    <div class="mb-5 bg-green-50 text-green-700 border border-green-200 rounded-lg p-3 flex items-start gap-2">
                        <i class="fa-solid fa-circle-check mt-0.5 text-sm"></i>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-4" id="contactForm">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" id="name" name="name" value="{{ old('name', auth()->check() ? auth()->user()->name : '') }}" placeholder="John Doe" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-1 focus:ring-secondary focus:border-secondary transition @error('name') border-red-400 @enderror" required @auth readonly @endauth>
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', auth()->check() ? auth()->user()->email : '') }}" placeholder="john@example.com" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-1 focus:ring-secondary focus:border-secondary transition @error('email') border-red-400 @enderror" required @auth readonly @endauth>
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-1">
                                <i class="fa-brands fa-whatsapp text-green-500"></i> Nomor WhatsApp
                            </label>
                            <input type="text" id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="08xxxxxxxxxx" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-1 focus:ring-secondary focus:border-secondary transition @error('whatsapp') border-red-400 @enderror">
                            @error('whatsapp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            <p class="text-xs text-gray-400 mt-1">Opsional — agar admin dapat membalas via WhatsApp</p>
                        </div>
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subjek</label>
                            <input type="text" id="subject" name="subject" value="{{ old('subject') }}" placeholder="Pertanyaan Pesanan" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-1 focus:ring-secondary focus:border-secondary transition @error('subject') border-red-400 @enderror" required>
                            @error('subject') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Pesan Anda</label>
                            <textarea id="message" name="message" rows="4" placeholder="Tulis pesan Anda di sini..." class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:ring-1 focus:ring-secondary focus:border-secondary transition resize-none @error('message') border-red-400 @enderror" required>{{ old('message') }}</textarea>
                            @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <button type="submit" class="w-full bg-primary hover:bg-blue-800 text-white font-medium py-2.5 rounded-lg transition text-sm">
                            Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@guest
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('contactForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    icon: 'info',
                    title: 'Harap Login',
                    text: 'Silakan login terlebih dahulu untuk mengirim pesan.',
                    confirmButtonText: 'Pergi ke Login',
                    confirmButtonColor: '#1E3A8A',
                    showCancelButton: true,
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('login') }}";
                    }
                });
            });
        }
    });
</script>
@endguest

@endsection
