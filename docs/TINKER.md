# Dokumentasi 5 Query Tinker

Jalankan `php artisan tinker`, lalu ketik query di bawah satu per satu dan ambil screenshot hasilnya.
Simpan screenshot di folder `docs/` (misalnya `docs/tinker-1.png`).

> Contoh hasil di bawah diambil setelah `php artisan migrate:fresh --seed`.
> Angka di laptopmu akan sedikit berbeda karena data dibuat acak oleh factory.

---

## 1. Scope — produk aktif, stok ada, harga Rp100.000–Rp300.000

```php
App\Models\Product::active()->inStock()->priceBetween(100000, 300000)->orderBy('price')->take(5)->get(['name', 'price', 'stock']);
```

Memakai 3 scope di model `Product`: `active()`, `inStock()`, `priceBetween()`.

```
"Celana Kulot Highwaist"          109000  stok 56
"Flat Shoes Wanita"               115000  stok 42
"Serum Vitamin C Somethinc 20ml"  115000  stok 141
"Teflon Wajan Anti Lengket 24cm"  119000  stok 146
"Dumbbell Vinyl 2 x 2kg"          120000  stok 148
```

## 2. Relasi hasMany + withCount — jumlah produk per kategori

```php
App\Models\Category::withCount('products')->pluck('products_count', 'name');
```

```
"Elektronik" => 10, "Fashion Pria" => 10, "Fashion Wanita" => 10,
"Rumah Tangga" => 10, "Kesehatan & Kecantikan" => 10, "Olahraga" => 10
```

## 3. Relasi bertingkat — pesanan milik user@toko.test beserta item & pembayaran

```php
$u = App\Models\User::where('email', 'user@toko.test')->first();
$u->orders()->with('items.product', 'payment')->get();
```

Relasi yang dipakai: `User → orders → items → product` dan `Order → payment`.

```
INV-45938054  shipped  Rp179.000    e_wallet       1x Dress Midi Motif Bunga
INV-47435782  pending  Rp3.036.000  transfer_bank  1x Keyboard Mechanical Rexus Legionaire MX5,
                                                   2x Dispenser Air Galon Bawah, ...
```

## 4. Agregat relasi — 5 produk terlaris

```php
App\Models\Product::withSum('orderItems', 'quantity')->orderByDesc('order_items_sum_quantity')->take(5)->get(['id', 'name']);
```

```
Keyboard Mechanical Rexus Legionaire MX5   terjual 13
Tas Selempang Wanita Mini                  terjual 11
Tempat Sampah Injak 12 Liter               terjual 9
...
```

## 5. Scope Order — jumlah order selesai & total pendapatan

```php
App\Models\Order::status('completed')->count();
App\Models\Order::whereIn('status', ['paid', 'shipped', 'completed'])->sum('total_amount');
```

```
8
"34288000"
```

---

## Bonus: Demo Eager Loading (masalah N+1)

```php
use Illuminate\Support\Facades\DB;

DB::enableQueryLog();
App\Models\Order::all()->each(fn ($o) => $o->user->name);
count(DB::getQueryLog());        // 46 query (1 + 1 per order)

DB::flushQueryLog();
App\Models\Order::with('user')->get()->each(fn ($o) => $o->user->name);
count(DB::getQueryLog());        // 2 query saja
```

Tanpa eager loading, setiap `$o->user` memicu 1 query baru (N+1).
Dengan `with('user')`, semua user diambil sekaligus dalam 1 query tambahan.
