<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Kategori untuk grid navigasi cepat
        $categories = Category::withCount('books')->get();

        // Buku pilihan (featured) — tampil max 4 di homepage
        $featuredBooks = Book::with('category')
            ->where('is_featured', true)
            ->where('stock', '>', 0)
            ->latest()
            ->take(4)
            ->get();

        // Total buku untuk floating badge di hero
        $totalBooks = Book::count();

        return view('home', compact('categories', 'featuredBooks', 'totalBooks'));
    }
}
