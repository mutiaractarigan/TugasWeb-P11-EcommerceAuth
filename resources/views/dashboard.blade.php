<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>Halo, <strong>{{ Auth::user()->name }}</strong>! Kamu login sebagai
                        <x-role-badge :role="Auth::user()->role" />.</p>

                    <ul class="mt-4 list-disc list-inside text-sm text-gray-600">
                        <li>Semua role bisa melihat <a href="{{ route('products.index') }}" class="text-indigo-600 underline">produk</a> dan <a href="{{ route('posts.index') }}" class="text-indigo-600 underline">posts</a>.</li>
                        @if (Auth::user()->hasRole('admin', 'editor'))
                            <li>Kamu bisa <a href="{{ route('posts.create') }}" class="text-indigo-600 underline">menulis post baru</a>.</li>
                        @endif
                        @if (Auth::user()->isAdmin())
                            <li>Kamu bisa membuka <a href="{{ route('admin.index') }}" class="text-indigo-600 underline">panel admin</a>.</li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-lg mb-4">Pesanan Terakhir Saya</h3>

                    @forelse ($orders as $order)
                        <div class="border rounded-md p-4 mb-3">
                            <div class="flex justify-between text-sm">
                                <span class="font-semibold">{{ $order->order_number }}</span>
                                <span class="uppercase text-gray-500">{{ $order->status }}</span>
                            </div>
                            <ul class="mt-2 text-sm text-gray-600">
                                @foreach ($order->items as $item)
                                    <li>{{ $item->quantity }} × {{ $item->product->name }}</li>
                                @endforeach
                            </ul>
                            <p class="mt-2 text-sm">
                                Total: <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                                · Pembayaran: {{ $order->payment?->status ?? '-' }}
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Belum ada pesanan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
