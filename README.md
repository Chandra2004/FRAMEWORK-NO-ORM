# THE-FRAMEWORK - MVC Native PHP Framework

## 📌 Pengenalan

**THE-FRAMEWORK** adalah framework PHP berbasis MVC (Model-View-Controller) yang dibuat oleh **Chandra Tri A**. Framework ini dirancang untuk memberi struktur yang bersih dan terorganisir pada aplikasi PHP, dengan fitur-fitur utama:

- Manajemen namespace dinamis PSR‑4
- Blade Templating
- Migrasi dan seeding database
- Artisan CLI untuk scaffolding dan manajemen proyek
- Support folder `resources/Views` dan fallback ke `services/`
- Upload file terstruktur di folder `private-uploads/`

## 🚀 Instalasi

### Langkah-langkah

1. **Clone Proyek**:
   ```bash
   git clone https://github.com/Chandra2004/FRAMEWORK.git
   cd FRAMEWORK
   ```

2. **Install Dependensi**:
   ```bash
   composer install
   ```

3. **Setup Proyek Awal**:
   ```bash
   php artisan setup
   ```
   - Perintah ini akan membuat `.env`, dan mempersiapkan struktur awal.

4. **Jalankan Server**:
   ```bash
   php artisan serve
   ```
   Akses di `http://localhost:8080`.

### Persyaratan
- PHP 8.0+
- Composer
- MySQL (atau kompatibel)

## 📂 Struktur Direktori
```
FRAMEWORK/
├── app/
│   ├── App/
│   │   ├── Blueprint.php
│   │   ├── CacheManager.php
│   │   ├── Config.php
│   │   ├── Database.php
│   │   ├── Logging.php
│   │   ├── Model.php
│   │   ├── QueryBuilder.php
│   │   ├── RateLimiter.php
│   │   ├── Router.php
│   │   ├── Schema.php
│   │   ├── SessionManager.php
│   │   └── View.php
│   ├── Config/
│   │   ├── EmailHandler.php
│   │   └── ImageHandler.php
│   ├── Console/
│   │   ├── Commands/
│   │   │   └── ServeCommand.php
│   │   └── CommandInterface.php
│   ├── Database/
│   │   ├── Seeder.php
│   │   └── Migration.php
│   ├── Helpers/
│   │   ├── Helper.php
│   │   └── helpers.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Services/
│   │   │   │   ├── DebugController.php
│   │   │   │   ├── ErrorController.php
│   │   │   │   └── FileController.php
│   │   │   ├── Controller.php
│   │   │   └── HomeController.php
│   ├── Middleware/
│   │   ├── AuthMiddleware.php
│   │   ├── CsrfMiddleware.php
│   │   ├── Middleware.php
│   │   ├── ValidationMiddleware.php
│   │   └── WAFMiddleware.php
│   ├── Models/
│   │   ├── Seeders/
│   │   │   └── UserSeeder.php
│   │   └── HomeModel.php                 
│   └── BladeInit.php
├── bootstrap/
│   ├── app.php
├── database/
│   ├── migrations/
│   │   └── UsersTable.php
│   └── seeders/
│       └── UserSeeder.php
├── private-uploads/
│   ├── dummy/
│   └── user-pictures/
├── resources/
│   ├── css/
│   ├── js/
│   └── Views/
│       └── (...file blade di sini)
├── routes/
│   ├── web.php
├── services/
│   ├── error/
│   │   ├── 404.blade.php
│   │   ├── 500.blade.php
│   │   ├── maintenance.blade.php
│   │   └── payment.blade.php
│   └── debug/
│       ├── exception.blade.php
│       └── fatal.blade.php
├── vendor/
├── .env
├── .env.example
├── .gitignore
├── .htaccess 
├── index.php 
├── artisan
├── composer.json
├── composer.lock
└── README.md
```

## 🔧 Perintah Artisan
```ini
  config:clear             Menghapus cache konfigurasi
  make:controller          Membuat kelas controller baru
  make:middleware          Membuat kelas middleware baru
  make:migration           Membuat file migrasi baru
  make:model               Membuat kelas model baru
  make:seeder              Membuat file seeder baru di database/seeders
  migrate                  Menjalankan migrasi database
  migrate:fresh            Menghapus semua tabel dan menjalankan ulang migrasi
  migrate:rollback         Membatalkan semua migrasi dengan menghapus semua tabel database
  route:cache              Menyimpan cache rute aplikasi
  seed                     Menjalankan seeder database
  serve                    Menjalankan aplikasi pada server pengembangan PHP
  setup                    Menjalankan pengaturan awal (env, kunci, autoload)
```
> Semua file yang dihasilkan akan menggunakan namespace sesuai PSR‑4 di `composer.json`.

## 🌐 Konfigurasi ENV

Sesuaikan file `.env`:
```ini
APP_ENV=local
APP_DEBUG=false
APP_NAME=TheFramework

BASE_URL=http://localhost:8080

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=the_framework
DB_USER=root
DB_PASS=

DB_TIMEZONE=+07:00

ENCRYPTION_KEY=generated_key_here
APP_KEY=generated_app_key_here

```

## 🌐 Konfigurasi Jika Menggunakan Google Project IDX

Sesuaikan file `idx/dev.nix`:
```
{ pkgs, ... }: {
  channel = "stable-24.05";

  packages = [
    pkgs.php83
    pkgs.php83Extensions.curl
    pkgs.php83Extensions.fileinfo
    pkgs.php83Extensions.mbstring
    pkgs.php83Extensions.openssl
    pkgs.php83Extensions.pdo_mysql
    pkgs.php83Extensions.tokenizer
    pkgs.php83Extensions.xml
    pkgs.php83Packages.composer
    pkgs.nodejs_20
    pkgs.python3
    pkgs.tailwindcss
  ];

services.mysql = {
  enable = true;
  package = pkgs.mariadb;
};


  env = {
    PHP_PATH = "${pkgs.php83}/bin/php";
    COMPOSER_ALLOW_SUPERUSER = "1";
  };

  idx = {
    extensions = [
      # Laravel & Blade
      "amirmarmul.laravel-blade-vscode"
      "onecentlin.laravel-blade"
      "shufo.vscode-blade-formatter"
      "codingyu.laravel-goto-view"
      "stef-k.laravel-goto-controller"
      "ahinkle.laravel-model-snippets"

      # Tailwind & Frontend
      "bradlc.vscode-tailwindcss"
      "imgildev.vscode-tailwindcss-snippets"
      "esbenp.prettier-vscode"

      # PHP & Debugging
      "bmewburn.vscode-intelephense-client"
      "xdebug.php-debug"

      # Database
      "cweijan.vscode-database-client2"
      "formulahendry.vscode-mysql"

      # API Testing
      "rangav.vscode-thunder-client"

      # Utils
      "yandeu.five-server"
    ];

    previews = {
      enable = true;
      previews = {
        web = {
          command = ["php" "artisan" "serve" "--host=0.0.0.0" "--port=$PORT"];
          manager = "web";
        };
      };
    };
  };
}
```

## 🤝 Kontribusi

Kami terbuka untuk kontribusi! Silakan buat pull request atau hubungi:

- WhatsApp: 085730676143
- Email   : chandratriantomo123@gmail.com
- Website : https://www.the-framework.ct.ws
