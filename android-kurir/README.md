# PRIANGAN KILAT EXPRESS – APLIKASI ANDROID KURIR

## 1. DESKRIPSI PROJECT

Priangan Kilat Express merupakan aplikasi ekspedisi yang dikembangkan untuk mendukung proses pengiriman paket. Aplikasi Android Kurir dibuat sebagai bagian dari sistem Priangan Kilat Express untuk membantu kurir mengakses data paket yang ditugaskan, melihat informasi pengiriman, memperbarui status paket, serta memantau uang Cash on Delivery (COD).

Aplikasi Android ini terintegrasi dengan backend Priangan Kilat Express menggunakan REST API yang dikembangkan dengan Laravel.

## 2. TEKNOLOGI YANG DIGUNAKAN

Teknologi dan tools yang digunakan dalam pengembangan aplikasi:

- Kotlin: Bahasa pemrograman aplikasi Android.
- Android Studio: Integrated Development Environment (IDE).
- Jetpack Compose: Membuat tampilan antarmuka aplikasi.
- Material 3: Komponen desain antarmuka.
- Retrofit 2.11.0: Menghubungkan aplikasi dengan REST API.
- Gson Converter 2.11.0: Mengonversi respons JSON menjadi objek Kotlin.
- OkHttp Logging Interceptor 4.12.0: Library pendukung komunikasi HTTP.
- Laravel: Backend dan penyedia REST API.
- Laravel Sanctum: Autentikasi API pada backend.
- MySQL: Database yang digunakan oleh backend.

## 3. IDENTITAS DAN KONFIGURASI APLIKASI

- Nama aplikasi: Priangan Kilat Express
- Modul: Aplikasi Kurir Android
- Package Name: com.example.priangankilatexpress
- Application ID: com.example.priangankilatexpress
- Bahasa pemrograman: Kotlin
- Minimum SDK: API 26
- Target SDK: API 36
- Compile SDK: API 37 (minor API level 1)
- Version Code: 1
- Version Name: 1.0
- Java Compatibility: Java 11

## 4. FITUR APLIKASI

### 4.1 LOGIN KURIR

Kurir dapat masuk ke aplikasi menggunakan kode kurir dan password yang telah terdaftar pada backend. Proses login dilakukan melalui REST API dan menghasilkan token autentikasi.

### 4.2 GANTI PASSWORD

Aplikasi menyediakan fitur untuk mengganti password dengan memasukkan password lama, password baru, dan konfirmasi password baru.

### 4.3 DASHBOARD

Dashboard merupakan halaman utama aplikasi yang menampilkan informasi kurir, jumlah paket dalam pengiriman, serta ringkasan COD yang belum disetorkan.

### 4.4 DAFTAR PAKET

Menampilkan daftar paket yang ditugaskan kepada kurir melalui endpoint API. Informasi yang ditampilkan meliputi nomor resi, nama penerima, alamat, kota tujuan, dan status pengiriman.

### 4.5 DETAIL PAKET

Menampilkan informasi lebih lanjut mengenai paket yang dipilih, termasuk nomor resi dan status pengiriman.

### 4.6 UPDATE STATUS PENGIRIMAN

Kurir dapat memperbarui status paket menjadi:

- Terkirim
- Gagal Kirim

Apabila memilih status Gagal Kirim, kurir diwajibkan mengisi catatan kegagalan sebelum mengirim pembaruan ke backend.

Validasi urutan status dan aturan pengiriman tetap ditangani oleh backend.

### 4.7 INFORMASI COD

Menu COD menampilkan daftar paket COD yang telah berhasil dikirim oleh kurir, nilai COD setiap paket, status penyetoran, serta total uang COD yang belum disetorkan.

Fitur ini digunakan untuk membantu kurir memantau uang COD yang masih menjadi tanggung jawabnya.

### 4.8 PROFIL KURIR

Menampilkan informasi nama dan kode kurir serta menyediakan akses ke fitur ganti password dan logout.

### 4.9 LOGOUT

Kurir dapat keluar dari aplikasi. Aplikasi mengirim permintaan logout ke backend dan menghapus sesi lokal aplikasi.

## 5. INTEGRASI REST API

Aplikasi Android terhubung dengan backend melalui endpoint berikut:

| Method | Endpoint | Fungsi |
|---|---|---|
| POST | /api/kurir/login | Autentikasi kurir |
| POST | /api/kurir/ganti-password | Mengganti password |
| GET | /api/kurir/paket | Mengambil daftar paket kurir |
| GET | /api/kurir/cod | Mengambil informasi COD |
| PATCH | /api/kurir/paket/{paket}/status | Memperbarui status paket |
| POST | /api/kurir/logout | Mengakhiri sesi kurir |

Endpoint selain login menggunakan token autentikasi pada header Authorization dengan format Bearer Token.

## 6. STRUKTUR FOLDER DAN FILE

Struktur utama source code aplikasi:

```text
PrianganKilatExpress/
│
├── app/
│   ├── build.gradle.kts
│   └── src/
│       ├── main/
│       │   ├── AndroidManifest.xml
│       │   ├── java/
│       │   │   └── com/example/priangankilatexpress/
│       │   │       ├── ApiClient.kt
│       │   │       ├── ApiService.kt
│       │   │       ├── MainActivity.kt
│       │   │       ├── DashboardScreen.kt
│       │   │       ├── PaketScreen.kt
│       │   │       ├── PaketApiScreen.kt
│       │   │       ├── DetailPaketScreen.kt
│       │   │       ├── UpdateStatusScreen.kt
│       │   │       ├── UpdateStatusApiScreen.kt
│       │   │       ├── CodScreen.kt
│       │   │       ├── CodApiScreen.kt
│       │   │       ├── ChangePasswordScreen.kt
│       │   │       ├── ProfilScreen.kt
│       │   │       └── ui/theme/
│       │   │           ├── Color.kt
│       │   │           ├── Theme.kt
│       │   │           └── Type.kt
│       │   │
│       │   └── res/
│       │       ├── drawable/
│       │       ├── drawable-nodpi/
│       │       ├── mipmap/
│       │       ├── values/
│       │       └── xml/
│       │
│       ├── androidTest/
│       └── test/
│
├── gradle/
├── build.gradle.kts
├── gradle.properties
├── gradlew
├── gradlew.bat
└── settings.gradle.kts
```

## 7. KONFIGURASI KONEKSI BACKEND

Konfigurasi koneksi REST API terdapat pada file:

`app/src/main/java/com/example/priangankilatexpress/ApiClient.kt`

Alamat server yang digunakan pada project saat ini:

```kotlin
private const val BASE_URL =
    "http://192.168.43.161:8000/api/"
```

Alamat IP tersebut merupakan IPv4 laptop yang digunakan saat pengujian koneksi lokal.

Untuk menjalankan koneksi:

1. Pastikan backend Laravel sudah dijalankan.
2. Pastikan laptop dan perangkat Android berada pada jaringan Wi-Fi yang sama.
3. Jalankan server Laravel menggunakan perintah:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

4. Pastikan alamat BASE_URL pada ApiClient.kt sesuai dengan alamat IP laptop.
5. Jalankan aplikasi Android.

Apabila alamat IP laptop berubah, BASE_URL harus disesuaikan kembali.

## 8. CARA MENJALANKAN APLIKASI

### Persiapan

- Android Studio.
- JDK yang sesuai dengan konfigurasi Gradle.
- Perangkat Android atau Android Emulator.
- Backend Laravel Priangan Kilat Express yang sudah berjalan.

### Langkah menjalankan project

1. Buka Android Studio.
2. Pilih Open.
3. Pilih folder utama PrianganKilatExpress.
4. Tunggu proses Gradle Sync selesai.
5. Periksa konfigurasi BASE_URL pada ApiClient.kt.
6. Jalankan backend Laravel.
7. Hubungkan perangkat Android atau jalankan emulator.
8. Klik tombol Run pada Android Studio.
9. Login menggunakan akun kurir yang sudah terdaftar pada backend.

### Membuat file APK

Untuk membuat APK melalui Android Studio:

1. Pilih menu Build.
2. Pilih Build Bundle(s) / APK(s).
3. Pilih Build APK(s).
4. Tunggu proses build selesai.
5. File APK debug biasanya tersedia pada:

`app/build/outputs/apk/debug/app-debug.apk`

## 9. AKUN PENGUJIAN

Aplikasi menggunakan akun kurir yang terdaftar pada backend Priangan Kilat Express.

Data akun pengujian mengikuti data kurir yang tersedia pada database dan seeder backend.

Aplikasi tidak menyediakan pembuatan akun kurir secara mandiri. Pendaftaran dan pengelolaan akun dilakukan melalui sistem backend oleh Admin.

## 10. ALUR KERJA APLIKASI

1. Kurir membuka aplikasi dan memasukkan kode kurir serta password.
2. Aplikasi mengirim data login ke backend.
3. Backend memverifikasi akun dan mengembalikan token autentikasi.
4. Kurir mengakses dashboard.
5. Aplikasi mengambil daftar paket yang ditugaskan kepada kurir.
6. Kurir memilih paket untuk melihat detail pengiriman.
7. Kurir memperbarui status paket menjadi Terkirim atau Gagal Kirim.
8. Backend memvalidasi dan menyimpan pembaruan status beserta riwayatnya.
9. Kurir membuka menu COD untuk melihat paket COD yang sudah terkirim dan status penyetorannya.
10. Kurir melakukan logout setelah selesai menggunakan aplikasi.

## 11. PENGELOLAAN COD

Fitur COD pada aplikasi Android digunakan untuk memantau uang COD dari paket yang berhasil dikirim.

Informasi yang ditampilkan meliputi:

- Nomor resi paket.
- Nama penerima.
- Nilai COD.
- Status penyetoran.
- Total COD yang belum disetorkan.

Pencatatan setoran COD dilakukan melalui sistem backend oleh pihak yang berwenang. Aplikasi Android Kurir berfungsi untuk menampilkan informasi dan memantau status penyetoran, bukan sebagai fitur pencatatan setoran uang secara langsung.

## 12. CATATAN PENGEMBANGAN DAN BATASAN

1. Aplikasi Android merupakan bagian dari sistem Priangan Kilat Express dan membutuhkan backend Laravel yang aktif untuk menjalankan fitur berbasis API.
2. Alamat IP lokal pada ApiClient.kt digunakan untuk pengujian dalam jaringan lokal dan dapat berubah sesuai jaringan yang digunakan.
3. Penggunaan melalui jaringan publik memerlukan konfigurasi server dan HTTPS.
4. Data paket dan COD diperoleh dari backend, bukan disimpan sebagai data utama secara lokal.
5. Akun kurir harus tersedia dan aktif pada backend agar dapat digunakan untuk login.
6. Fitur pencatatan setoran COD dikelola melalui backend, sedangkan aplikasi Android menampilkan informasi COD milik kurir.

## 13. REFERENSI DAN PENGGUNAAN AI

Pengembangan aplikasi mengacu pada kebutuhan sistem Priangan Kilat Express berdasarkan soal UKK Rekayasa Perangkat Lunak Tahun Pelajaran 2026/2027.

Dokumentasi teknologi:
- Android Developers: https://developer.android.com/
- Kotlin Documentation: https://kotlinlang.org/docs/
- Jetpack Compose: https://developer.android.com/compose
- Retrofit: https://square.github.io/retrofit/
- Laravel: https://laravel.com/docs

AI digunakan sebagai alat bantu dalam proses pembelajaran, penyusunan dokumentasi, penjelasan kode, dan pemecahan masalah selama pengembangan aplikasi. Implementasi dan pengujian aplikasi tetap dilakukan pada project Android dan backend Priangan Kilat Express.