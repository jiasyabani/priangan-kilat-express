# PRIANGAN KILAT EXPRESS
## SISTEM INFORMASI MANAJEMEN EKSPEDISI

---

# 1. RINGKASAN PROYEK

Priangan Kilat Express merupakan sistem informasi manajemen ekspedisi berbasis website yang dilengkapi dengan REST API untuk mendukung aplikasi Android Kurir.

Sistem ini dikembangkan untuk membantu pengelolaan operasional pengiriman barang, mulai dari pencatatan paket, pengelolaan pelanggan dan kurir, perhitungan ongkos kirim, penugasan kurir, pemantauan status pengiriman, pengelolaan COD, hingga penyajian laporan.

Sistem memiliki tiga jenis pengguna utama:

1. Admin, bertugas mengelola paket, pelanggan, kurir, penugasan pengiriman, perubahan status tertentu, serta setoran COD.
2. Manajer, bertugas melihat laporan operasional dan mengelola tarif pengiriman.
3. Kurir, menggunakan aplikasi Android untuk melihat paket yang ditugaskan, memperbarui status pengiriman, dan melihat informasi COD.

Pelanggan dapat menggunakan halaman tracking publik untuk memeriksa status pengiriman menggunakan nomor resi.

Backend sistem dikembangkan menggunakan Laravel dan database MySQL. Aplikasi Android Kurir berkomunikasi dengan backend melalui REST API.

# 2. TEKNOLOGI YANG DIGUNAKAN

A. WEBSITE DAN BACKEND

| Teknologi | Fungsi |
|---|---|
| PHP 8.3 atau lebih baru | Bahasa pemrograman backend |
| Laravel 13 | Framework website dan backend |
| HTML | Membuat struktur halaman website |
| CSS | Mengatur tampilan website |
| JavaScript | Menambahkan interaksi halaman dan perhitungan ongkir |
| MySQL | Database relasional |
| Laravel Sanctum | Autentikasi token REST API |
| Composer | Mengelola dependensi PHP |
| Vite | Mengelola aset frontend |

B. APLIKASI ANDROID

| Teknologi | Fungsi |
|---|---|
| Kotlin | Bahasa pemrograman Android |
| Jetpack Compose | Membuat antarmuka Android |
| Retrofit | Menghubungkan Android dengan REST API |
| Gson Converter | Mengolah respons JSON |
| OkHttp Logging Interceptor | Membantu pemeriksaan komunikasi API selama pengembangan |

C. PERANGKAT PENGEMBANGAN

- Visual Studio Code
- Android Studio
- XAMPP
- phpMyAdmin
- Composer
- Postman
- Google Chrome
- Git dan GitHub

# 3. CARA MENJALANKAN SERVER

A. PERSYARATAN

- PHP versi 8.3 atau lebih baru.
- Composer.
- MySQL atau XAMPP.
- Node.js dan NPM.
- Git.

B. LANGKAH INSTALASI

1. Clone repository:

```bash
git clone https://github.com/jiasyabani/priangan-kilat-express.git
```

2. Masuk ke folder proyek:

```bash
cd priangan-kilat-express
```

3. Instal dependensi Laravel:

```bash
composer install
```

4. Instal dependensi frontend:

```bash
npm install
```

5. Buat file konfigurasi:

```bash
copy .env.example .env
```

6. Buat application key:

```bash
php artisan key:generate
```

7. Buat database MySQL dengan nama:

```text
priangan_kilat_express
```

8. Sesuaikan konfigurasi database pada file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=priangan_kilat_express
DB_USERNAME=root
DB_PASSWORD=
```

9. Jalankan migration:

```bash
php artisan migrate
```

10. Jalankan seeder akun Admin dan Manajer:

```bash
php artisan db:seed --class=UserSeeder
```

11. Jalankan server Laravel:

```bash
php artisan serve
```

12. Jalankan frontend apabila diperlukan:

```bash
npm run dev
```

Website dapat diakses melalui:

```text
http://127.0.0.1:8000
```

C. KONFIGURASI PUSAT WILAYAH

Sistem memiliki service `PusatWilayahService` untuk memeriksa zona tujuan melalui layanan Pusat Wilayah.

Service mengirim permintaan ke endpoint `/wilayah` dengan parameter kota dan header API Key.

Jika layanan tidak tersedia, respons tidak valid, atau kota tidak ditemukan, sistem menyediakan mekanisme pemilihan zona manual pada proses input paket.

Konfigurasi alamat layanan dan API Key disimpan pada konfigurasi lingkungan server. Informasi rahasia tidak dicantumkan dalam dokumentasi publik.

D. DATA AWAL CSV

Proyek menyediakan tiga file data awal:

| File | Kegunaan |
|---|---|
| `storage/app/data/pelanggan.csv` | Data pelanggan |
| `storage/app/data/kurir.csv` | Data kurir |
| `storage/app/data/paket_historis.csv` | Data paket historis |

Seeder yang tersedia:

```bash
php artisan db:seed --class=PelangganCsvSeeder
php artisan db:seed --class=KurirCsvSeeder
php artisan db:seed --class=PaketHistorisCsvSeeder
```

Urutan pengisian data adalah akun dasar, pelanggan, kurir, tarif, kemudian paket historis.

Catatan: Tarif harus tersedia terlebih dahulu di tabel `tarifs`. `TarifSeeder.php` pada proyek saat ini belum berisi data tarif, sehingga data tarif perlu disiapkan sebelum impor paket historis.

# 4. APLIKASI ANDROID KURIR

Aplikasi Android digunakan oleh Kurir untuk mengakses paket yang ditugaskan melalui REST API Laravel.

Fitur integrasi API yang tersedia pada backend meliputi:

- Login menggunakan kode kurir dan password.
- Penggantian password.
- Melihat daftar paket berstatus Dikirim milik kurir yang sedang login.
- Memperbarui status menjadi Terkirim atau Gagal Kirim.
- Melihat daftar paket COD yang telah terkirim.
- Melihat total COD yang belum disetor.
- Logout dan pencabutan token autentikasi.

A. KONFIGURASI API

Alamat server dikonfigurasi melalui `ApiClient.kt` pada bagian `BASE_URL`.

Contoh alamat server lokal:

```kotlin
private const val BASE_URL =
    "http://192.168.x.x:8000/api/"
```

Alamat IP harus disesuaikan dengan alamat laptop yang menjalankan Laravel. Perangkat Android dan laptop harus berada pada jaringan yang dapat saling terhubung.

B. ENDPOINT API

| Method | Endpoint | Fungsi |
|---|---|---|
| POST | `/api/kurir/login` | Login Kurir |
| POST | `/api/kurir/ganti-password` | Mengganti password |
| GET | `/api/kurir/paket` | Melihat daftar paket |
| GET | `/api/kurir/cod` | Melihat rekap COD |
| PATCH | `/api/kurir/paket/{paket}/status` | Memperbarui status paket |
| POST | `/api/kurir/logout` | Logout Kurir |

C. SOURCE CODE DAN APK

Source code Android dan file APK tidak terdapat dalam ZIP backend yang diperiksa. Keduanya perlu disertakan pada repository akhir di lokasi yang sesuai dengan struktur pengumpulan UKK.

# 5. AKUN PENGUJIAN

Akun Admin dan Manajer dibuat melalui `UserSeeder.php`.

| Jenis Akun | Email / Username | Password |
|---|---|---|
| Admin | admin@priangankilat.test | AdminPKE2026! |
| Manajer | manajer@priangankilat.test | ManajerPKE2026! |

Akun Kurir dibuat melalui proses pengelolaan Kurir atau impor CSV.

Seeder Kurir menghasilkan kode Kurir dan password sementara secara otomatis. Password sementara ditampilkan pada terminal saat akun berhasil dibuat.

Kurir diwajibkan mengganti password sementara sebelum mengakses fitur utama aplikasi.

Catatan: Akun di atas merupakan akun pengujian dari kode seeder, bukan akun produksi.

# 6. STRUKTUR FOLDER DAN DATABASE

A. STRUKTUR BACKEND

```text
priangan-kilat-express/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   └── Middleware/
│   ├── Models/
│   └── Services/
├── bootstrap/
├── config/
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
├── resources/
│   └── views/
├── routes/
│   ├── api.php
│   └── web.php
├── storage/
│   └── app/
│       └── data/
├── tests/
├── composer.json
└── README.md
```

B. MODEL DATABASE

Model yang tersedia pada proyek:

- User
- Kurir
- Pelanggan
- Paket
- Tarif
- RiwayatStatusPaket
- SetoranCod

C. MIGRATION

Migration digunakan untuk membuat struktur tabel database, termasuk tabel pengguna, pelanggan, kurir, tarif, paket, riwayat status paket, setoran COD, dan token autentikasi.

D. SEEDER

Seeder yang tersedia:

- `DatabaseSeeder.php`
- `UserSeeder.php`
- `TarifSeeder.php`
- `PelangganCsvSeeder.php`
- `KurirCsvSeeder.php`
- `PaketHistorisCsvSeeder.php`

`DatabaseSeeder.php` saat ini memanggil `UserSeeder` untuk membuat akun Admin dan Manajer.

Seeder CSV dapat dijalankan secara terpisah menggunakan perintah Artisan sesuai urutan pengisian data.

# 7. KEPUTUSAN TEKNIS DAN ATURAN BISNIS

A. NOMOR RESI

Nomor resi disimpan sebagai data unik pada tabel paket. Format nomor resi mengikuti ketentuan penomoran pengiriman harian.

B. PERHITUNGAN ONGKOS KIRIM

Perhitungan ongkos kirim menggunakan zona tujuan, berat paket, dan jenis layanan.

Ketentuan perhitungan:
- Berat dibulatkan ke atas.
- Berat minimum yang diperhitungkan adalah 1 kg.
- Layanan Express dikenakan tambahan 50%.
- Tarif per zona disimpan pada tabel `tarifs`.
- Tarif per kg dan ongkir disimpan pada data paket sebagai salinan tarif ketika transaksi dibuat.

C. BIAYA COD

Biaya layanan COD dihitung sebesar 2% dari nilai COD dengan minimum Rp2.000.

Nilai COD, biaya COD, dan status setoran disimpan pada data paket.

D. STATUS PENGIRIMAN

Status paket yang tersedia pada database:

- Diterima
- Transit
- Dikirim
- Terkirim
- Gagal Kirim
- Retur

Status paket dicatat melalui tabel riwayat status untuk menyimpan status sebelumnya, status baru, pelaku, catatan, dan waktu perubahan.

E. PENUGASAN KURIR

Paket memiliki relasi dengan Kurir melalui `kurir_id`.

Penugasan dilakukan melalui fitur Admin. Paket yang ditugaskan dapat ditampilkan kepada Kurir melalui API berdasarkan identitas akun yang sedang login.

F. PEMBATASAN AKSES API

API Kurir menggunakan middleware `auth:sanctum`.

Sistem memeriksa peran dan status aktif Kurir sebelum memberikan akses data.

Kurir hanya dapat melihat paket yang memiliki `kurir_id` sesuai akun Kurir yang login.

Saat memperbarui status, sistem memeriksa kembali kepemilikan paket dan status awal paket.

G. INTEGRASI PUSAT WILAYAH

Integrasi dilakukan melalui `PusatWilayahService.php`.

Sistem memeriksa respons layanan dan memastikan zona yang diterima termasuk zona 1, 2, atau 3.

Jika layanan gagal dihubungi atau data zona tidak valid, sistem mengembalikan informasi bahwa layanan tidak tersedia sehingga proses dapat menggunakan penentuan zona manual.

H. IMPOR DATA CSV

Pengolahan data CSV dilakukan melalui seeder khusus.

Penanganan data meliputi:
- Pembersihan BOM pada header CSV.
- Pemeriksaan kesesuaian jumlah kolom.
- Normalisasi nomor telepon ke format 62.
- Pemeriksaan nomor telepon.
- Pencegahan data pelanggan dan kode kurir duplikat.
- Pencegahan nomor resi paket duplikat.
- Penandaan berat paket kosong agar perlu diverifikasi.

# 8. SELF-CHECK KRITERIA PENERIMAAN UKK

Daftar berikut mengikuti 14 kriteria penerimaan pada soal UKK RPL 2026/2027 Soal 2 Varian B Ekspedisi.

Status akhir perlu ditentukan melalui pengujian langsung terhadap sistem.

| No. | Kriteria Penerimaan | Status |
|---|---|---|
| 1 | Input paket, nomor resi otomatis, unik, reset harian, dan validasi telepon | Perlu verifikasi akhir |
| 2 | Ongkir otomatis berdasarkan zona dan berat, Express +50%, serta tarif lama tetap | Perlu verifikasi akhir |
| 3 | Status berurutan dan status akhir tidak dapat diubah kembali | Perlu verifikasi akhir |
| 4 | Riwayat status mencatat waktu, pelaku, dan catatan | Perlu verifikasi akhir |
| 5 | Gagal kirim maksimal dua kali dan Retur oleh Admin | Perlu verifikasi akhir |
| 6 | Penugasan maksimal 20 paket dan Kurir nonaktif tidak dapat ditugaskan | Perlu verifikasi akhir |
| 7 | Tracking publik dengan penyamaran nomor telepon | Perlu verifikasi akhir |
| 8 | Biaya COD, setoran per paket, dan laporan COD | Perlu verifikasi akhir |
| 9 | Pembatasan hak akses Admin dan Manajer | Perlu verifikasi akhir |
| 10 | Impor CSV menangani resi duplikat, berat kosong, dan telepon | Sebagian mekanisme tersedia; perlu verifikasi akhir |
| 11 | Integrasi Pusat Wilayah dan fallback zona manual | Service tersedia; perlu pengujian akhir |
| 12 | Login Kurir, ganti password, reset password Admin, dan logout | Endpoint API tersedia; perlu pengujian akhir |
| 13 | Kurir melihat paket tugas dan memperbarui status melalui APK | Endpoint API tersedia; perlu pengujian aplikasi |
| 14 | Kurir melihat paket COD terkirim dan total COD belum disetor | Endpoint API tersedia; perlu pengujian aplikasi |

# 9. KETERBATASAN DAN CATATAN PENGEMBANGAN

Berdasarkan pemeriksaan source code backend, beberapa hal masih perlu diperhatikan:

1. `TarifSeeder.php` belum berisi data tarif.
2. `DatabaseSeeder.php` baru memanggil `UserSeeder`, sehingga seeder CSV harus dijalankan secara terpisah.
3. Seeder paket historis membutuhkan data pelanggan, kurir, dan tarif yang sudah tersedia.
4. Source code Android dan file APK tidak terdapat dalam ZIP backend yang diperiksa.
5. Folder dokumentasi ERD dan UML tidak terdapat dalam ZIP backend yang diperiksa.
6. Seluruh kriteria penerimaan perlu diuji kembali melalui demonstrasi sistem.

# 10. REFERENSI DAN PENGGUNAAN AI

A. REFERENSI

1. Dokumen UKK RPL 2026/2027 Soal 2 Varian B Ekspedisi.
2. Dokumentasi Laravel: https://laravel.com/docs
3. Dokumentasi Laravel Sanctum: https://laravel.com/docs/sanctum
4. Dokumentasi Kotlin: https://kotlinlang.org/docs/
5. Android Developers: https://developer.android.com/
6. Dokumentasi Retrofit: https://square.github.io/retrofit/

B. PENGGUNAAN AI

AI digunakan sebagai alat bantu selama proses pengembangan, antara lain untuk:
- Membantu memahami konsep pemrograman.
- Membantu proses debugging dan identifikasi kesalahan.
- Memberikan referensi implementasi fitur.
- Membantu memahami integrasi backend dan REST API.
- Membantu penyusunan dokumentasi proyek.

Hasil bantuan AI disesuaikan kembali dengan kebutuhan proyek dan dipelajari selama proses pengembangan.

---

PRIANGAN KILAT EXPRESS
SISTEM INFORMASI MANAJEMEN EKSPEDISI
UKK REKAYASA PERANGKAT LUNAK
TAHUN PELAJARAN 2026/2027