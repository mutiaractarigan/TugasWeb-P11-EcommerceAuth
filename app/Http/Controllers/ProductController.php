<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->active()                          // scope
            ->with('category')                  // eager loading → hindari N+1
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->when($request->category, fn ($q, $slug) => $q->whereRelation('category', 'slug', $slug))
            ->when($request->search, fn ($q, $search) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::withCount('products')->orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }
}
