# THE-FRAMEWORK 🚀

[![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4.svg?style=flat&logo=php&logoColor=white)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Author](https://img.shields.io/badge/Author-Chandra%20Tri%20A-blue)](https://github.com/Chandra2004)

**THE-FRAMEWORK** adalah framework PHP modern berbasis arsitektur **MVC (Model-View-Controller)** yang dirancang dari nol (_from scratch_) tanpa bergantung pada framework raksasa. Framework ini ringan, cepat, namun memiliki fitur setara framework modern seperti Laravel, namun dengan pendekatan **Native SQL (No ORM)** untuk performa maksimal dan pembelajaran mendalam tentang database.

Cocok untuk:

- Tugas Kuliah / Skripsi
- Pembelajaran Arsitektur Framework
- Aplikasi Web UMKM / Enterprise Skala Menengah

---

## ✨ Fitur Unggulan

- **🛡️ Secure & Robust**

  - Built-in **CSRF Protection**
  - **WAF (Web Application Firewall)** Middleware
  - Secure Session Management
  - SQL Injection Protection (via PDO Binding)

- **🏗️ Modern Architecture**

  - **MVC Pattern** yang ketat
  - **Service Container (Dependency Injection)**
  - **Middleware** System
  - **Blade Templating Engine** (menggunakan `illuminate/view`)

- **🛠️ Powerful CLI (Artisan)**

  - `php artisan serve` - Local development server
  - `php artisan make:controller` - Generate Controller
  - `php artisan make:model` - Generate Model
  - `php artisan make:migration` - Database Config as Code
  - `php artisan make:seeder` - Database Seeding dengan Timestamp Order
  - `php artisan db:seed` - Eksekusi Seeder cerdas

- **💾 Database (No ORM)**
  - Full Control dengan **Raw SQL** yang aman
  - Query Wrapper (`query`, `bind`, `resultSet`, `single`)
  - **Migration System** (Create & Rollback Tables)
  - **Seeding System** (Dummy Data Generator dengan Faker)

---

## 🚀 Memulai (Getting Started)

### 1. Prasyarat

Pastikan komputer kamu sudah terinstall:

- PHP >= 8.3
- Composer
- MySQL / MariaDB

### 2. Instalasi

Clone repository ini:

```bash
git clone https://github.com/Chandra2004/FRAMEWORK-NO-ORM.git
cd FRAMEWORK-NO-ORM
```

Install dependensi via Composer:

```bash
composer install
```

Setup Environment otomatis:

```bash
php artisan setup
```

> Perintah ini akan membuat file `.env`, generate `APP_KEY`, dan `ENCRYPTION_KEY`.

### 3. Konfigurasi Database

Buka file `.env`, sesuaikan dengan kredensial database kamu:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=nama_database_kamu
DB_USER=root
DB_PASS=
```

> **Penting:** Buat database kosong di MySQL/phpMyAdmin sesuai `DB_NAME` sebelum lanjut.

### 4. Jalankan Migrasi & Seeder (Opsional)

Jika ingin tabel dan data dummy otomatis:

```bash
php artisan migrate
php artisan db:seed
```

### 5. Jalankan Aplikasi

```bash
php artisan serve
```

Akses di browser: `http://localhost:8080`

---

## 📖 Panduan Penggunaan (Documentation)

### 1. Routing (`routes/web.php`)

Routing menggunakan sintaks modern dan mendukung Grouping.

```php
use TheFramework\App\Router;
use TheFramework\Http\Controllers\HomeController;

// Basic Route
Router::add('GET', '/', HomeController::class, 'index');

// Route dengan Parameter
Router::add('GET', '/user/{id}', UserController::class, 'show');

// Grouping (Prefix & Middleware)
Router::group(['prefix' => '/admin', 'middleware' => [AuthMiddleware::class]], function() {
    Router::add('GET', '/dashboard', AdminController::class, 'index');
});
```

### 2. Controller

Gunakan command untuk membuat controller:

```bash
php artisan make:controller ProductController
```

Controller mendukung **Dependency Injection**. Contoh:

```php
namespace TheFramework\Http\Controllers;

use TheFramework\App\Database;

class ProductController {
    private $db;

    // Database otomatis di-inject oleh Container
    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function index() {
        // Logika coding...
    }
}
```

### 3. Database & Model (No ORM)

Framework ini sengaja tidak menggunakan Eloquent/ORM agar kamu paham SQL.

**Membuat Model:**

```bash
php artisan make:model ProductModel
```

**Mendapatkan Data (SELECT):**

```php
$this->db->query("SELECT * FROM products WHERE stock > :stock");
$this->db->bind(':stock', 10);
$products = $this->db->resultSet(); // Array of Assoc
```

**Menyimpan Data (INSERT):**

```php
$this->db->query("INSERT INTO products (name, price) VALUES (:name, :price)");
$this->db->bind(':name', 'Laptop Gaming');
$this->db->bind(':price', 15000000);
$this->db->execute();
```

### 4. Views (Blade)

Simpan file view di `resources/Views`. Contoh `resources/Views/home.blade.php`:

```html
@extends('layouts.app') @section('content')
<h1>Daftar Produk</h1>
@foreach($products as $product)
<p>{{ $product['name'] }} - Rp {{ number_format($product['price']) }}</p>
@endforeach @endsection
```

Panggil dari Controller:

```php
return view('home', ['products' => $data]);
```

### 5. Membuat Database Seeder (Baru!)

Fitur Seeder sekarang mendukung timestamp agar urutan eksekusi terjamin.

**Buat Seeder:**

```bash
php artisan make:seeder ProductSeeder
```

File akan terbuat: `database/seeders/2026_01_03_120000_ProductSeeder.php`.

**Isi Seeder:**

```php
// ... use helper
public function run() {
    Seeder::setTable('products');
    Seeder::create([
        ['name' => 'Produk A', 'price' => 5000],
        ['name' => 'Produk B', 'price' => 10000]
    ]);
}
```

**Jalankan:**

```bash
php artisan db:seed
```

---

## ⚖️ Lisensi

Project ini dilisensikan di bawah [MIT License](LICENSE).
Dibuat dengan ❤️ oleh **Chandra Tri Antomo**.
