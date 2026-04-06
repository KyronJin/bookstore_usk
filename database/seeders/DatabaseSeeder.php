<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // === AKUN ADMIN ===
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@pustakabiru.com',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
            'phone'    => '081234567890',
        ]);

        // === AKUN USER ===
        User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'budi@gmail.com',
            'password' => Hash::make('user123'),
            'role'     => 'user',
            'phone'    => '082111222333',
            'address'  => 'Jl. Merdeka No. 10, Jakarta',
        ]);

        User::create([
            'name'     => 'Siti Rahayu',
            'email'    => 'siti@gmail.com',
            'password' => Hash::make('user123'),
            'role'     => 'user',
            'phone'    => '083444555666',
            'address'  => 'Jl. Kenanga No. 25, Bandung',
        ]);

        // === KATEGORI BUKU ===
        $fiksi      = Category::create(['name' => 'Fiksi',      'slug' => 'fiksi',      'description' => 'Novel, cerpen, dan karya fiksi lainnya']);
        $nonFiksi   = Category::create(['name' => 'Non-Fiksi',  'slug' => 'non-fiksi',  'description' => 'Biografi, memoar, jurnalisme, dan sejenisnya']);
        $edukasi    = Category::create(['name' => 'Edukasi',    'slug' => 'edukasi',    'description' => 'Buku pelajaran, referensi akademis, dan ilmu pengetahuan']);
        $teknologi  = Category::create(['name' => 'Teknologi',  'slug' => 'teknologi',  'description' => 'Pemrograman, IT, dan dunia digital']);
        $bisnis     = Category::create(['name' => 'Bisnis',     'slug' => 'bisnis',     'description' => 'Kewirausahaan, keuangan, dan manajemen']);

        // === DATA BUKU ===
        $books = [
            [
                'category_id' => $fiksi->id,
                'title'       => 'Negeri Di Ujung Daun',
                'author'      => 'Jane Doe',
                'publisher'   => 'Gramedia',
                'description' => 'Sebuah kisah tentang perjalanan panjang menemukan jati diri di tengah kekacauan dunia modern.',
                'price'       => 85000,
                'stock'       => 25,
                'image_url'   => 'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?q=80&w=400&auto=format&fit=crop',
                'is_featured' => true,
            ],
            [
                'category_id' => $fiksi->id,
                'title'       => 'Sayap Sayap Patah',
                'author'      => 'Ahmad Fuadi',
                'publisher'   => 'Mizan',
                'description' => 'Novel inspiratif tentang perjuangan hidup dan harapan yang tidak pernah padam.',
                'price'       => 90000,
                'stock'       => 18,
                'image_url'   => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?q=80&w=400&auto=format&fit=crop',
                'is_featured' => false,
            ],
            [
                'category_id' => $teknologi->id,
                'title'       => 'Mastering Web Dev 2026',
                'author'      => 'John Smith',
                'publisher'   => 'O\'Reilly',
                'description' => 'Panduan lengkap pengembangan web modern dari dasar hingga mahir, mencakup HTML, CSS, JavaScript, dan framework terkini.',
                'price'       => 120000,
                'stock'       => 30,
                'image_url'   => 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?q=80&w=400&auto=format&fit=crop',
                'is_featured' => true,
            ],
            [
                'category_id' => $teknologi->id,
                'title'       => 'Laravel: From Zero to Hero',
                'author'      => 'Taylor Otwell',
                'publisher'   => 'Tech Books',
                'description' => 'Kuasai Laravel framework PHP paling populer dari dasar hingga deployment.',
                'price'       => 135000,
                'stock'       => 20,
                'image_url'   => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?q=80&w=400&auto=format&fit=crop',
                'is_featured' => false,
            ],
            [
                'category_id' => $edukasi->id,
                'title'       => 'Sejarah Dunia Tersembunyi',
                'author'      => 'Alan Turing',
                'publisher'   => 'Erlangga',
                'description' => 'Mengupas fakta-fakta tersembunyi di balik peristiwa besar dalam sejarah dunia.',
                'price'       => 95000,
                'stock'       => 15,
                'image_url'   => 'https://images.unsplash.com/photo-1532012197267-da84d127e765?q=80&w=400&auto=format&fit=crop',
                'is_featured' => true,
            ],
            [
                'category_id' => $bisnis->id,
                'title'       => 'Investasi Pintar untuk Pemula',
                'author'      => 'Robert Kiyosaki',
                'publisher'   => 'Gramedia',
                'description' => 'Panduan investasi cerdas untuk pemula yang ingin meraih kebebasan finansial.',
                'price'       => 110000,
                'stock'       => 22,
                'image_url'   => 'https://images.unsplash.com/photo-1611532736597-de2d4265fba3?q=80&w=400&auto=format&fit=crop',
                'is_featured' => true,
            ],
            [
                'category_id' => $nonFiksi->id,
                'title'       => 'Atomic Habits',
                'author'      => 'James Clear',
                'publisher'   => 'Bentang Pustaka',
                'description' => 'Cara mudah dan terbukti untuk membangun kebiasaan baik dan menghilangkan kebiasaan buruk.',
                'price'       => 98000,
                'stock'       => 40,
                'image_url'   => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?q=80&w=400&auto=format&fit=crop',
                'is_featured' => true,
            ],
            [
                'category_id' => $nonFiksi->id,
                'title'       => 'Sapiens: Riwayat Singkat Umat Manusia',
                'author'      => 'Yuval Noah Harari',
                'publisher'   => 'KPG',
                'description' => 'Menelusuri perjalanan spesies manusia dari masa prasejarah hingga era modern.',
                'price'       => 125000,
                'stock'       => 12,
                'image_url'   => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?q=80&w=400&auto=format&fit=crop',
                'is_featured' => false,
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
