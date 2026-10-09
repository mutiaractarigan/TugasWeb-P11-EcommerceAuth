<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Produk</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('products.index') }}" class="flex flex-wrap gap-2 mb-6">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..."
                       class="rounded-md border-gray-300 text-sm">
                <select name="category" class="rounded-md border-gray-300 text-sm">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>
                            {{ $category->name }} ({{ $category->products_count }})
                        </option>
                    @endforeach
                </select>
                <x-primary-button>Filter</x-primary-button>
            </form>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @forelse ($products as $product)
                    <div class="bg-white shadow-sm rounded-lg p-4">
                        <p class="text-xs text-gray-500">{{ $product->category->name }}</p>
                        <h3 class="font-semibold text-gray-800">{{ $product->name }}</h3>
                        <p class="mt-1 text-indigo-600 font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <p class="mt-1 text-xs text-gray-500">
                            Stok: {{ $product->stock }}
                            @if ($product->reviews_count)
                                · ★ {{ number_format($product->reviews_avg_rating, 1) }} ({{ $product->reviews_count }})
                            @endif
                        </p>
                    </div>
                @empty
                    <p class="text-gray-500">Produk tidak ditemukan.</p>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
