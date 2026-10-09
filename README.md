# TugasWeb-P11-EcommerceAuth

Tugas Rutin 11 — E-Commerce DB + Secure Auth (Laravel 13 + Breeze).

## Cara Menjalankan

```bash
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
```

Buat database `tugasweb_p11` di phpMyAdmin, isi `DB_PASSWORD` di `.env`, lalu:

```bash
php artisan migrate:fresh --seed
php artisan serve
```

Buka http://localhost:8000

### Akun untuk testing

| Role | Email | Password |
|------|-------|----------|
| admin | admin@toko.test | password |
| editor | editor@toko.test | password |
| user | user@toko.test | password |

User yang mendaftar lewat halaman Register otomatis mendapat role `user`.

---

## Bagian A — Database & Eloquent

### 1. Migrations 7 tabel e-commerce + FK constraints

| Tabel | Foreign key | On delete |
|-------|-------------|-----------|
| `categories` | – | – |
| `products` | `category_id → categories` | RESTRICT |
| `addresses` | `user_id → users` | CASCADE |
| `orders` | `user_id → users`, `address_id → addresses` | CASCADE / SET NULL |
| `order_items` | `order_id → orders`, `product_id → products` | CASCADE / RESTRICT |
| `payments` | `order_id → orders` (unique, 1 order 1 pembayaran) | CASCADE |
| `reviews` | `user_id → users`, `product_id → products` (unique per pasangan) | CASCADE |

Tambahan: kolom `role` di `users` (migration `add_role_to_users_table`) dan tabel `posts` untuk PostPolicy.

### 2. Seeders + factories (50+ produk realistis)

- `ProductSeeder`: **60 produk** dengan nama & harga realistis (6 kategori × 10 produk)
- `UserSeeder`: 3 akun role + 25 pelanggan (Faker locale `id_ID`)
- `OrderSeeder`: setiap pelanggan punya 1–2 alamat dan 1–3 order; total order dihitung dari item
- `ReviewSeeder`: review hanya dari pelanggan yang order-nya sudah `completed`
- `PostSeeder`: 3 post admin + 5 post editor

Semua tabel punya factory di `database/factories/`.

### 3. Model + relationships + scope

| Model | Relasi | Scope |
|-------|--------|-------|
| `User` | hasMany addresses, orders, reviews, posts | – |
| `Category` | hasMany products | – |
| `Product` | belongsTo category, hasMany reviews & orderItems | `active()`, `inStock()`, `priceBetween()` |
| `Address` | belongsTo user, hasMany orders | – |
| `Order` | belongsTo user & address, hasMany items, hasOne payment, belongsToMany products | `status()` |
| `OrderItem` | belongsTo order & product | – |
| `Payment` | belongsTo order | – |
| `Review` | belongsTo user & product | – |
| `Post` | belongsTo user | – |

### 4. Dokumentasi 5 query Tinker

Lihat [docs/TINKER.md](docs/TINKER.md), termasuk demo eager loading (bonus).

---

## Bagian B — Auth & Security

### 5. Breeze (login / register / logout)

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
```

### 6. Multi-role + custom middleware

- Kolom `users.role`: `admin`, `editor`, `user` (default `user`). `role` **tidak** ada di `$fillable`, jadi tidak bisa disusupkan lewat form register.
- Middleware `app/Http/Middleware/RoleMiddleware.php`, didaftarkan sebagai alias `role` di `bootstrap/app.php`.
- Pemakaian: `->middleware('role:admin')` dan `->middleware('role:admin,editor')`.

### 7. PostPolicy (otorisasi edit/delete)

`app/Policies/PostPolicy.php`:

| Aksi | admin | editor | user |
|------|-------|--------|------|
| Lihat post | ✅ | ✅ | ✅ |
| Buat post | ✅ | ✅ | ❌ |
| Edit / hapus post sendiri | ✅ | ✅ | ❌ |
| Edit / hapus post orang lain | ✅ | ❌ | ❌ |

Dipakai di controller (`Gate::authorize(...)`) dan di view (`@can`) supaya tombol Edit/Hapus hanya muncul untuk yang berhak.

### 8. Route protection

| URL | Middleware | admin | editor | user | tamu |
|-----|-----------|-------|--------|------|------|
| `/dashboard`, `/products`, `/posts` | auth | ✅ | ✅ | ✅ | → login |
| `/posts/create` | auth, role:admin,editor | ✅ | ✅ | 403 | → login |
| `/posts/{id}/edit` | auth, role:admin,editor + PostPolicy | ✅ | hanya post sendiri | 403 | → login |
| `/admin` | auth, role:admin | ✅ | 403 | 403 | → login |

#### Testing dengan incognito (2 role sekaligus)

1. Jendela biasa: login sebagai `admin@toko.test` → buka `/admin` → **berhasil**.
2. Jendela incognito: login sebagai `user@toko.test` → buka `/admin` → **403 Forbidden**.
3. Di incognito, buka `/posts/create` → **403**. Di halaman `/posts`, tombol Edit/Hapus tidak muncul.
4. Login sebagai `editor@toko.test` → bisa edit post miliknya, tapi `/posts/1/edit` (post admin) → **403**.

Screenshot hasil testing bisa disimpan di folder `docs/`.

#### Automated test

```bash
php artisan test
```

`tests/Feature/RoleAccessTest.php` menguji semua aturan di atas, dan `tests/Feature/DatabaseSeederTest.php` menguji seeder.
