<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'Pengguna' => User::count(),
            'Produk aktif' => Product::active()->count(),
            'Order' => Order::count(),
            'Pendapatan' => 'Rp ' . number_format(Order::whereIn('status', ['paid', 'shipped', 'completed'])->sum('total_amount'), 0, ',', '.'),
        ];

        // Eager loading: user & payment ikut diambil dalam 1 query tambahan masing-masing
        $latestOrders = Order::with(['user', 'payment'])->latest()->take(10)->get();

        $users = User::orderByRaw("CASE role WHEN 'admin' THEN 1 WHEN 'editor' THEN 2 ELSE 3 END")->orderBy('name')->paginate(10);

        return view('admin.index', compact('stats', 'latestOrders', 'users'));
    }
}
