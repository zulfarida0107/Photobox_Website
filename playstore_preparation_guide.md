# Panduan Persiapan Rilis Aplikasi ke Google Play Store

Untuk merilis (hosting) aplikasi Flutter **MOLITAV** ke Google Play Store, Anda perlu mempersiapkan beberapa aspek administratif, konfigurasi teknis kode, aset desain, hingga proses build. Berikut adalah rincian lengkap apa saja yang perlu Anda siapkan.

---

## 1. Persiapan Akun & Administrasi
Sebelum masuk ke aspek teknis aplikasi, Anda wajib memiliki akun developer:
* **Google Play Console Developer Account**: Anda harus mendaftar di [Play Console](https://play.google.com/console/signup) menggunakan akun Google.
* **Biaya Registrasi**: Ada biaya pendaftaran sebesar **$25 USD** (sekali bayar seumur hidup).
* **Verifikasi Identitas**: Google mewajibkan verifikasi identitas resmi (KTP/Paspor/SIM) dan kartu kredit/debit untuk proses pembayaran.

---

## 2. Persiapan Teknis (Konfigurasi Flutter)

### A. Ganti Package Name (Application ID) yang Unik
Package name bawaan biasanya berformat `com.example.molitav`. Google Play Store tidak menerima package name yang mengandung kata `example`. Anda harus mengubahnya menjadi nama unik yang mencerminkan brand Anda (misal: `com.khanzaen.molitav`).
* **Di mana diubah?**: Buka berkas [build.gradle](file:///c:/Users/khanza%20haura/Downloads/MOLITAV-2.2.0-Main%20%28Source%20Code%29/Molitav-2.2.0-main/health_application/android/app/build.gradle) dan cari baris:
  ```groovy
  defaultConfig {
      applicationId "com.example.molitav" // Ubah ini
  }
  ```
> [!TIP]
> Gunakan package `change_app_package_name` di `pubspec.yaml` untuk mengubah package name di seluruh struktur folder Android secara otomatis.

### B. Siapkan App Icon Resmi
Ganti ikon default Flutter dengan ikon logo **MOLITAV**.
* Resolusi ikon yang direkomendasikan adalah **512x512 piksel** dalam format PNG tanpa transparansi (untuk listing store), serta berbagai resolusi untuk folder `mipmap` di Android.
* Anda bisa menggunakan package `flutter_launcher_icons` di `pubspec.yaml` untuk menghasilkan seluruh ukuran ikon Android/iOS secara otomatis.

### C. Konfigurasi Signing Key (Keystore)
Google Play Store mewajibkan setiap aplikasi di-sign (ditandatangani) dengan kunci digital yang aman sebelum diunggah.
1. **Generate Keystore**:
   Jalankan perintah ini di terminal (sesuaikan path dan nama key):
   ```bash
   keytool -genkey -v -keystore c:\Users\khanza haura\upload-keystore.jks -storetype PKCS12 -keyalg RSA -keysize 2048 -validity 10000 -alias upload
   ```
2. **Buat file `key.properties`**:
   Buat file `key.properties` di dalam folder `android/` untuk menyimpan referensi keystore:
   ```properties
   storePassword=<password-keystore-anda>
   keyPassword=<password-key-anda>
   keyAlias=upload
   storeFile=c:/Users/khanza haura/upload-keystore.jks
   ```
3. **Hubungkan di `build.gradle`**:
   Sesuaikan konfigurasi `signingConfigs` pada `android/app/build.gradle` agar membaca file `key.properties` saat proses release build.

### D. Tinjau Izin Aplikasi (Permissions)
Pastikan izin yang tertulis di berkas `AndroidManifest.xml` (terutama untuk Bluetooth karena aplikasi ini terhubung ke alat medis) sudah terkonfigurasi dengan benar beserta deskripsi izinnya jika diperlukan di Android terbaru.

---

## 3. Persiapan Aset & Informasi Listing Play Store
Google Play Store membutuhkan beberapa aset visual dan teks deskripsi untuk halaman unduhan aplikasi Anda:

| Aset / Informasi | Ketentuan Teknis | Keterangan |
| :--- | :--- | :--- |
| **Nama Aplikasi** | Maksimal 30 karakter | Nama yang akan tampil di Play Store |
| **Deskripsi Singkat** | Maksimal 80 karakter | Rangkuman singkat fitur utama aplikasi |
| **Deskripsi Lengkap** | Maksimal 4000 karakter | Penjelasan detail fitur, kegunaan, dan info medis aplikasi |
| **App Icon Store** | 512 x 512, PNG 32-bit, maks 1MB | Logo resolusi tinggi untuk halaman store |
| **Feature Graphic** | 1024 x 500, PNG/JPEG | Banner promosi di bagian atas halaman store |
| **Tangkapan Layar (Screenshots)** | Min. 2 screenshots untuk tipe HP, tablet 7", dan tablet 10" | Menampilkan antarmuka utama aplikasi (Home, Chart, Chatbot) |
| **Privacy Policy URL** | Link URL aktif | Kebijakan privasi data pengguna (karena aplikasi mengumpulkan data kesehatan) |

---

## 4. Proses Build & Unggah

### A. Tentukan Versi Aplikasi
Setiap rilis baru harus memiliki versi yang lebih tinggi. Konfigurasikan ini di file `pubspec.yaml`:
```yaml
version: 1.0.0+1  # 1.0.0 adalah versionName, 1 adalah versionCode
```

### B. Lakukan Build Android App Bundle (.aab)
Format unggahan terbaru ke Play Store adalah **App Bundle (AAB)**, bukan APK lagi. Jalankan perintah berikut di terminal proyek Flutter:
```bash
flutter build appbundle --release
```
Hasil build akan berada di direktori:
`build/app/outputs/bundle/release/app-release.aab`

---

## 5. Alur Pengunggahan di Google Play Console
1. Buat Aplikasi Baru di Dashboard Play Console.
2. Selesaikan kuisioner **Content Rating** (karena ini aplikasi kesehatan, jawab dengan jujur).
3. Tentukan **Target Audiens** (misal: 18 tahun ke atas).
4. Masukkan link **Kebijakan Privasi (Privacy Policy)**.
5. Unggah aset visual (Screenshots, Icon, Banner) dan tulis teks deskripsi.
6. Buat Rilis Baru di menu **Production** atau **Testing** (disarankan coba *Internal Testing* dahulu).
7. Seret dan unggah berkas `app-release.aab` Anda.
8. Klik **Review and Rollout** untuk mengirimkan aplikasi ke tim Google untuk ditinjau (review biasanya memakan waktu 1 - 7 hari kerja).
