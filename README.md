📥 Instalasi
Ikuti langkah-langkah berikut untuk meng-clone dan menyiapkan proyek:

1. Kloning Repositori
Buka Terminal atau Git Bash, dan jalankan perintah berikut:
git clone https://github.com/dikialfin/treetan-test.git
cd treetan-test

2. Instalasi Dependensi
Unduh semua dependensi PHP yang diperlukan menggunakan Composer:
composer install

⚙️ Konfigurasi Lingkungan (.env)
Anda perlu membuat dan menyesuaikan file konfigurasi lingkungan (.env) agar aplikasi dapat terhubung ke database dan API Tripay Sandbox.

    1. Duplikasi File .env
    Buat salinan dari file contoh .env.example dan namai sebagai .env:
    cp .env.example .env

    2. Hasilkan Kunci Aplikasi
    Buat kunci aplikasi unik Laravel:
    php artisan key:generate

    3. Penyesuaian Variabel .env
    Edit file .env yang baru dibuat dan sesuaikan variabel di bawah ini (gunakan data Tripay Sandbox Anda):
    Database Configuration
    Sesuaikan dengan kredensial database lokal Anda:
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nama_database_anda       # Ganti dengan nama database yang Anda buat
    DB_USERNAME=root                     # Ganti dengan username database Anda
    DB_PASSWORD=                         # Ganti dengan password database Anda
    API_BASE_URL=https://tripay.co.id/api-sandbox
    PRIVATE_KEY=TuFSI-UFG0H-A2986-g5BtQ-eNJTo
    API_KEY=DEV-ntHMyNLwM3qlSfRwpuPZsbrlcptE9XCRHEvkmNmt
    KODE_MERCHANT=T46885

💾 Menjalankan Migrasi Database
Setelah konfigurasi database di .env selesai, jalankan migrasi untuk membuat tabel yang diperlukan:
php artisan migrate

▶️ Menjalankan Server Lokal
Jalankan aplikasi menggunakan server pengembangan bawaan Laravel:
php artisan serve

Daftar Endpoint : 
- Create Payment -> POST http://127.0.0.1:8000/api/payment/
- Get Payment -> GET http://127.0.0.1:8000/api/payment
- Callback URL -> POST http://127.0.0.1:8000/api/callback
- API Documentation -> GET http://127.0.0.1:8000/api/documentation/