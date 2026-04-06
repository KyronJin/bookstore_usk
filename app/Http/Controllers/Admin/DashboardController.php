<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'    => User::where('role', 'user')->count(),
            'total_books'    => Book::count(),
            'total_orders'   => Order::count(),
            'total_revenue'  => Order::whereIn('status', ['delivered'])->sum('total_price'),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'low_stock'      => Book::where('stock', '<=', 5)->count(),
        ];

        $latestOrders = Order::with('user')->latest()->take(5)->get();
        $featuredBooks = Book::with('category')->where('is_featured', true)->take(4)->get();

        return view('admin.dashboard', compact('stats', 'latestOrders', 'featuredBooks'));
    }
}
