@extends('layouts.app')

@section('title', 'Hubungi Admin - JeBook')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-primary mb-4">Hubungi Admin</h1>
            <p class="text-lg text-gray-600">
                Punya pertanyaan tentang pesanan, ketersediaan buku, atau kerjasama? Jangan ragu untuk menghubungi kami melalui form di bawah ini.
            </p>
        </div>

        <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <!-- Contact Info -->
                <div class="bg-primary text-white p-10 flex flex-col justify-between">
                    <div>
                        <h2 class="text-2xl font-bold mb-6">Informasi Kontak</h2>
                        <ul class="space-y-6">
                            <li class="flex items-start">
                                <i class="fa-solid fa-location-dot mt-1.5 w-6 text-accent"></i>
                                <div>
                                    <p class="font-semibold text-lg">Alamat</p>
                                    <p class="text-blue-100 text-sm mt-1">Jl. Pendidikan No. 123,<br>Kota Mahasiswa, 40123<br>Indonesia</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <i class="fa-solid fa-phone mt-1 w-6 text-accent"></i>
                                <div>
                                    <p class="font-semibold text-lg">Telepon / WhatsApp</p>
                                    <p class="text-blue-100 text-sm mt-1">+62 812-3456-7890</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <i class="fa-solid fa-envelope mt-1 w-6 text-accent"></i>
                                <div>
                                    <p class="font-semibold text-lg">Email</p>
                                    <p class="text-blue-100 text-sm mt-1">support@JeBook.com</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="mt-12">
                        <div class="flex space-x-4">
                            <a href="#" class="h-10 w-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center hover:bg-opacity-40 transition">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                            <a href="#" class="h-10 w-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center hover:bg-opacity-40 transition">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            <a href="#" class="h-10 w-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center hover:bg-opacity-40 transition">
                                <i class="fa-brands fa-twitter"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="p-10">
                    <h2 class="text-2xl font-bold text-dark mb-6">Kirim Pesan</h2>
                    
                    @if(session('success'))
                    <div class="mb-6 bg-green-50 text-green-700 border border-green-200 rounded-lg p-4 flex items-start gap-3">
                        <i class="fa-solid fa-circle-check mt-0.5"></i>
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-5" id="contactForm">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" id="name" name="name" value="{{ old('name', auth()->check() ? auth()->user()->name : '') }}" placeholder="John Doe" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-secondary focus:border-secondary transition @error('name') border-red-500 @enderror" required @auth readonly @endauth>
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', auth()->check() ? auth()->user()->email : '') }}" placeholder="john@example.com" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-secondary focus:border-secondary transition @error('email') border-red-500 @enderror" required @auth readonly @endauth>
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subjek</label>
                            <input type="text" id="subject" name="subject" value="{{ old('subject') }}" placeholder="Pertanyaan Pesanan" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-secondary focus:border-secondary transition @error('subject') border-red-500 @enderror" required>
                            @error('subject') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Pesan Anda</label>
                            <textarea id="message" name="message" rows="4" placeholder="Tulis pesan Anda di sini..." class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-secondary focus:border-secondary transition resize-none @error('message') border-red-500 @enderror" required>{{ old('message') }}</textarea>
                            @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <button type="submit" class="w-full bg-primary hover:bg-blue-800 text-white font-semibold py-3 rounded-lg shadow-md transition">
                            Kirim Pesan Sekarang
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
