<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // Menampilkan halaman daftar semua buku beserta filternya
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Book::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $books = $query->latest()->paginate(10);
        return view('admin.books', compact('books', 'categories'));
    }

    // Menyimpan data buku baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title'       => ['required', 'string', 'max:255'],
            'author'      => ['required', 'string', 'max:255'],
            'publisher'   => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'image_url'   => ['nullable', 'url'],
            'is_featured' => ['boolean'],
        ]);

        Book::create($request->only([
            'category_id', 'title', 'author', 'publisher',
            'description', 'price', 'stock', 'image_url',
        ]) + ['is_featured' => $request->boolean('is_featured')]);

        return back()->with('success', 'Buku "' . $request->title . '" berhasil ditambahkan.');
    }

    // Memperbarui informasi buku yang sudah ada
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title'       => ['required', 'string', 'max:255'],
            'author'      => ['required', 'string', 'max:255'],
            'publisher'   => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'image_url'   => ['nullable', 'url'],
            'is_featured' => ['boolean'],
        ]);

        $book->update($request->only([
            'category_id', 'title', 'author', 'publisher',
            'description', 'price', 'stock', 'image_url',
        ]) + ['is_featured' => $request->boolean('is_featured')]);

        return back()->with('success', 'Buku berhasil diperbarui.');
    }

    // Menghapus buku dari basis data
    public function destroy(Book $book)
    {
        $title = $book->title;
        $book->delete();
        return back()->with('success', 'Buku "' . $title . '" berhasil dihapus.');
    }
}
