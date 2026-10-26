# BTNG 2026 — Backend RESTful API (Showroom Mobil)

[![Laravel 12](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP 8.2+](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Scramble](https://img.shields.io/badge/Scramble-API_Docs-06B6D4?style=for-the-badge&logo=openapiinitiative&logoColor=white)](https://scramble.dedoc.co)
[![Tests Passing](https://img.shields.io/badge/Tests-13%20Passed-success?style=for-the-badge&logo=checkmarx)](https://phpunit.de)

Tugas Akhir **BTNG (Bina Talenta Net Generation) 2026** Divisi Website — Kelas Backend UKM DNCC Universitas Dian Nuswantoro.

Proyek ini merupakan implementasi RESTful API mandiri untuk mengelola katalog data **Showroom & Rental Mobil ("Veloce Auto")** yang dirancang selaras dengan integrasi frontend peserta BTNG 2026. Dibangun dengan framework **Laravel 12**, basis data **MySQL**, format envelope seragam via **API Resource**, serta dokumentasi interaktif **Scramble** di `/docs/api`.

---

## 🔗 Tautan Penting

- **Repository GitHub:** [https://github.com/Arrsiixx7/BTNG-Backend-ArkaanTaqiyyuddin](https://github.com/Arrsiixx7/BTNG-Backend-ArkaanTaqiyyuddin)
- **Live Dokumentasi API (Scramble):** [https://btng-backend-arkaantaqiyyuddin.cleverapps.io/docs/api](https://btng-backend-arkaantaqiyyuddin.cleverapps.io/docs/api)
- **Live Base Endpoint:** [https://btng-backend-arkaantaqiyyuddin.cleverapps.io/api/mobil](https://btng-backend-arkaantaqiyyuddin.cleverapps.io/api/mobil)
- **Integrasi Frontend Pasangan:** [https://github.com/Arrsiixx7/BTNG-Frontend-ArkaanTaqiyyuddin](https://github.com/Arrsiixx7/BTNG-Frontend-ArkaanTaqiyyuddin)

---

## ✨ Fitur & Keunggulan

1. **Kelengkapan CRUD 100%:** Mengelola entitas mobil secara utuh (Create, Read, Update, Delete) sesuai standar HTTP semantik.
2. **Standarisasi Respon API Resource:** Semua respon dibungkus seragam (*envelope pattern*) dengan kunci `message` dan `data`.
3. **Validasi Input Kuat:** Menggunakan `$request->validate()` dengan pesan berbahasa Indonesia dan status kode `422 Unprocessable Content`.
4. **Dokumentasi Interaktif Scramble:** Dokumentasi OpenAPI 3.1 interaktif di `/docs/api` yang mendukung fitur *Try It Out* langsung di browser.
5. **Pencarian & Multi-Filtering (Nilai Tambah):** Mendukung pencarian teks (`?search=...`) dan filter spesifik (`?brand=...`, `?status=...`, `?bahan_bakar=...`, `?transmisi=...`).
6. **Automated Testing:** Dilengkapi 13 skenario Feature & Unit Test (75 assertions) dengan tingkat kelulusan 100%.
7. **Cloud Deployment Ready:** Dikonfigurasi untuk Clever Cloud dengan `.clever.cloud/php.json` dan dukungan otomatis database MySQL eksternal/add-on.

---

## 🚗 Skema Atribut Entitas Mobil

| Atribut | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BigInteger (PK) | Auto-increment ID unik |
| `nama` | String | Model mobil (contoh: *Avanza 1.5 G CVT*) |
| `brand` | String | Merk pabrikan (contoh: *Toyota, Honda, Hyundai*) |
| `harga` | UnsignedBigInteger | Nominal harga dalam Rupiah (contoh: *275000000*) |
| `tahun` | Integer | Tahun perakitan kendaraan (contoh: *2024*) |
| `transmisi` | String | Jenis transmisi (*Manual, Matic, CVT*) |
| `bahan_bakar` | String | Bahan bakar (*Bensin, Diesel, Hybrid, Listrik*) |
| `status` | String | Status unit (*tersedia* / *sold*) |
| `foto` | Text (URL) | Tautan langsung foto unit mobil beresolusi tinggi |
| `created_at` | Timestamp | Waktu data dibuat |
| `updated_at` | Timestamp | Waktu data terakhir diperbarui |

---

## 📡 Daftar Endpoint API

| Method | Endpoint | Deskripsi | Status Sukses | Status Gagal |
| :--- | :--- | :--- | :---: | :---: |
| `GET` | `/api/mobil` | Mengambil seluruh daftar unit mobil | `200 OK` | `500 Server Error` |
| `GET` | `/api/mobil/{id}` | Mengambil detail 1 unit mobil spesifik | `200 OK` | `404 Not Found` |
| `POST` | `/api/mobil` | Menambahkan unit mobil baru | `201 Created` | `422 Unprocessable` |
| `PUT/PATCH` | `/api/mobil/{id}` | Memperbarui informasi data mobil | `200 OK` | `404 / 422` |
| `DELETE` | `/api/mobil/{id}` | Menghapus unit mobil dari database | `200 OK` | `404 Not Found` |
| `GET` | `/docs/api` | Halaman dokumentasi interaktif Scramble | `200 OK` | - |

---

## 📦 Contoh Format JSON

### 1. Respon Sukses Banyak Data (GET `/api/mobil` - 200 OK)
```json
{
  "message": "Daftar mobil berhasil diambil",
  "data": [
    {
      "id": 1,
      "nama": "Avanza 1.5 G CVT",
      "brand": "Toyota",
      "harga": 275000000,
      "tahun": 2024,
      "transmisi": "CVT",
      "bahan_bakar": "Bensin",
      "status": "tersedia",
      "foto": "https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=800&q=80",
      "created_at": "2026-10-27T00:53:00.000000Z",
      "updated_at": "2026-10-27T00:53:00.000000Z"
    }
  ]
}
```

### 2. Respon Sukses Tambah Data (POST `/api/mobil` - 201 Created)
```json
{
  "message": "Data mobil berhasil ditambahkan",
  "data": {
    "id": 9,
    "nama": "Civic Type R FL5",
    "brand": "Honda",
    "harga": 1425000000,
    "tahun": 2024,
    "transmisi": "Manual",
    "bahan_bakar": "Bensin",
    "status": "tersedia",
    "foto": "https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?w=800&q=80"
  }
}
```

### 3. Respon Gagal Validasi (POST `/api/mobil` - 422 Unprocessable Content)
```json
{
  "message": "Kolom nama mobil wajib diisi. (and 1 more error)",
  "errors": {
    "nama": ["Kolom nama mobil wajib diisi."],
    "harga": ["Kolom harga harus berupa angka."]
  }
}
```

---

## 💻 Panduan Instalasi Lokal

### Prasyarat
- PHP >= 8.2 (ekstensi `pdo`, `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`)
- Composer >= 2.x
- MySQL / MariaDB (atau SQLite untuk uji cepat)

### Langkah Pemasangan:
```bash
# 1. Clone repositori
git clone https://github.com/Arrsiixx7/BTNG-Backend-ArkaanTaqiyyuddin.git
cd BTNG-Backend-ArkaanTaqiyyuddin

# 2. Install dependensi PHP
composer install

# 3. Salin environment file
cp .env.example .env

# 4. Generate Application Encryption Key
php artisan key:generate

# 5. Konfigurasi kredensial database di .env (contoh MySQL):
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=btng_backend
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Jalankan migrasi dan seeder awal (minimal 8 unit mobil)
php artisan migrate --seed

# 7. Jalankan server lokal
php artisan serve
```

Aplikasi dapat diakses di:
- Base API: `http://localhost:8000/api/mobil`
- Dokumentasi Scramble: `http://localhost:8000/docs/api`

---

## 🧪 Menjalankan Automated Tests

Proyek ini telah dilengkapi dengan unit & feature testing menyeluruh:
```bash
php artisan test
```

Hasil eksekusi:
```text
PASS  Tests\Unit\ExampleTest
✓ that true is true

PASS  Tests\Feature\ExampleTest
✓ the application returns a successful response

PASS  Tests\Feature\MobilApiTest
✓ can get all mobils
✓ can search and filter mobils
✓ can get single mobil detail
✓ get single mobil not found returns 404
✓ can create new mobil
✓ create mobil validation error returns 422
✓ can update mobil
✓ update mobil not found returns 404
✓ can delete mobil
✓ delete mobil not found returns 404
✓ docs api route is accessible

Tests:    13 passed (75 assertions)
Duration: 0.45s
```

---

## ☁️ Panduan Deploy ke Clever Cloud

1. Buat aplikasi baru dengan tipe **PHP** di konsol Clever Cloud.
2. Tambahkan add-on **MySQL** atau tautkan database eksternal (TiDB / Aiven).
3. Atur **Environment Variables** di Clever Cloud:
   - `APP_ENV` = `production`
   - `APP_DEBUG` = `false`
   - `APP_KEY` = *(Kunci dari `php artisan key:generate --show`)*
   - `APP_URL` = `https://[app-name].cleverapps.io`
   - `CC_WEBROOT` = `/public`
   - `CC_POST_BUILD_HOOK` = `php artisan migrate --force && php artisan db:seed --force`
4. Deploy kode via Git:
   ```bash
   git remote add clever git+ssh://git@push-[zone].services.clever-cloud.com/[app-id].git
   git push clever main:master
   ```

---

## 📮 Koleksi Postman

File koleksi Postman siap import tersedia di:
`postman/BTNG_Showroom_Mobil_API.postman_collection.json`

---

## 👤 Pengembang

- **Nama:** Arkaan Taqiyyuddin
- **NIM:** UKM DNCC UDINUS
- **Divisi:** Website (Backend Class) — BTNG 2026
