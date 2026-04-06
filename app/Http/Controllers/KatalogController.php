<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('books')->get();

        $query = Book::with('category');

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('publisher', 'like', "%{$search}%");
            });
        }

        // Filter kategori
        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        // Filter stok
        if ($request->filled('stok') && $request->stok === 'tersedia') {
            $query->where('stock', '>', 0);
        }

        // Urutan / sort
        $sort = $request->get('sort', 'terbaru');
        match ($sort) {
            'harga_terendah' => $query->orderBy('price', 'asc'),
            'harga_tertinggi' => $query->orderBy('price', 'desc'),
            'az'             => $query->orderBy('title', 'asc'),
            'za'             => $query->orderBy('title', 'desc'),
            'terbaru'        => $query->orderBy('created_at', 'desc'),
            default          => $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc'),
        };

        $books = $query->paginate(12)->withQueryString();

        $activeCategory = $request->category
            ? $categories->firstWhere('slug', $request->category)
            : null;

        return view('katalog', compact('books', 'categories', 'activeCategory', 'sort'));
    }
}
