# CLAUDE.md — Apotek Website

> Dokumen ini adalah pedoman kerja untuk Claude Code. Baca seluruh dokumen ini sebelum mengubah kode. Tujuan utama: mengembangkan proyek apotek yang sudah ada menjadi aplikasi web full-stack yang matang, konsisten, aman, mudah digunakan, dan dapat diuji. Jangan menganggap proyek ini sebagai greenfield.

---

## 1. Peran dan tujuan

Bertindak sebagai **senior full-stack engineer, software architect, dan UI/UX-minded developer**.

Tanggung jawab:
- Memahami implementasi yang sudah ada sebelum membuat perubahan.
- Memperbaiki bug dan fondasi arsitektur sebelum menambahkan fitur baru.
- Menjaga konsistensi antara database, model, validasi, controller, route, dan Blade view.
- Membuat antarmuka yang rapi, responsif, accessible, dan terasa seperti produk sungguhan.
- Mengimplementasikan fitur secara end-to-end, bukan sekadar membuat tombol atau tampilan palsu.
- Menulis dan menjalankan test yang relevan.
- Menjelaskan perubahan, alasan teknis, risiko, dan hasil verifikasi secara jujur.

**Jangan**:
- Mengganti stack teknologi tanpa alasan kuat dan persetujuan eksplisit.
- Menghapus fitur, tabel, model, route, atau file hanya karena terlihat tidak terpakai sebelum menelusuri dependensinya.
- Membuat tombol, chart, filter, search, pagination, atau form yang hanya dekoratif.
- Mengarang data bisnis, stok, transaksi, rating, atau status yang tidak didukung database.
- Mengklaim test/build berhasil jika belum menjalankannya.
- Menyelesaikan semua pekerjaan dengan satu refactor besar yang sulit direview.
- Menambahkan package hanya untuk hal yang dapat diselesaikan secara sederhana dengan stack yang tersedia.

## 2. Ringkasan codebase saat ini

Informasi berikut diperoleh dari ZIP yang diberikan. Verifikasi ulang file aktual sebelum implementasi karena kode dapat berubah.

### Stack yang terdeteksi

| Bagian | Teknologi |
|---|---|
| Backend | PHP `^8.3`, Laravel Framework `^13.8` |
| Frontend | Laravel Blade |
| CSS | Tailwind CSS `^4.3.3` |
| Build | Vite `^8.0.0`, Laravel Vite Plugin |
| JavaScript | Vanilla JavaScript, entry `resources/js/app.js` |
| Database default | SQLite (`.env.example`) |
| Testing | PHPUnit `^12.5.12`, Laravel testing |
| Auth | Laravel session authentication |

Jangan mengubah stack ini menjadi React, Vue, Inertia, Livewire, atau SPA lain tanpa kebutuhan nyata dan persetujuan pemilik proyek. Prioritaskan Blade + Tailwind + JavaScript yang sudah tersedia.

### Struktur penting

```text
app/
  Http/Controllers/
    AuthController.php
    FirstController.php
    MedicineController.php
  Models/
    Category.php
    Medicine.php
    Obat.php
    User.php

database/
  migrations/
    0001_01_01_000000_create_users_table.php
    2026_07_29_094759_create_obats_table.php
    2026_10_27_094359_create_categories_table.php
    2026_10_28_094420_create_medicines_table.php
  seeders/DatabaseSeeder.php

resources/
  css/app.css
  js/app.js
  views/
    components/
      navbar.blade.php
      footer.blade.php
    auth/
      login.blade.php
      register.blade.php
    medicine/
      medicine.blade.php
      create.blade.php
      edit.blade.php
      detail.blade.php
    index.blade.php

routes/web.php
vite.config.js
composer.json
package.json
```

### Domain dan database yang terdeteksi

- `users`: memiliki `name`, `email`, `role`, `password`, dan timestamp.
- `categories`: memiliki `name`, `description`, dan timestamp.
- `medicines`: memiliki `name`, `price`, `description`, `expired`, `medicine_image`, `category_id`, dan timestamp.
- `obats`: tabel lama dengan `nama_obat`, `harga`, `tanggal_kadaluarsa`, dan timestamp.

**Penting:** migration `medicines` mewajibkan `category_id`. Model `Medicine` saat ini belum memasukkan `category_id` ke `$fillable`, dan controller/form yang ada juga belum mengelola kategori. Ini adalah inkonsistensi yang harus diperbaiki melalui migration/model/validation/form/controller secara konsisten.

---

## 3. Temuan audit awal — masalah yang harus ditangani

Gunakan daftar ini sebagai backlog awal, bukan asumsi bahwa semua masalah masih ada. Periksa kembali sebelum mengubah.

### P0 — Correctness dan keamanan

1. `MedicineController::store()`:
   - Mengambil input dengan nama `a`, `b`, `c`, `d`.
   - Tidak melakukan validasi request yang memadai.
   - Mengasumsikan file gambar selalu ada.
   - Memindahkan file langsung ke folder `public/gambars` menggunakan nama asli.
   - Tidak menyimpan `category_id`, padahal kolom tersebut wajib.
2. `MedicineController::update()`:
   - Mengisi atribut `image`, sedangkan kolom yang benar adalah `medicine_image`.
   - Tidak menangani upload gambar baru secara konsisten.
   - Tidak melakukan validasi.
3. `MedicineController::destroy()`:
   - Route penghapusan menggunakan GET. Ubah menjadi DELETE dan gunakan `@method('DELETE')` serta CSRF.
   - Pastikan file gambar lama ditangani dengan aman setelah record dihapus.
4. `MedicineController::show()`, `edit()`, dan `destroy()`:
   - Menggunakan `find()` tanpa penanganan not-found yang eksplisit. Utamakan `findOrFail()` atau route model binding.
5. `AuthController::logout()`:
   - Harus memanggil logout, invalidate session, dan regenerate CSRF token.
6. Authorization:
   - Route CRUD saat ini dilindungi middleware `Auth`, tetapi belum ada role/permission policy yang jelas.
   - Pastikan user biasa tidak otomatis memperoleh hak admin.
   - Tentukan kebutuhan role secara eksplisit sebelum membuat dashboard admin dan pembatasan akses.
7. Upload file:
   - Validasi MIME/type, ukuran, dan nama file.
   - Gunakan Laravel Storage pada disk `public` dan URL dari storage.
   - Jangan mempercayai nama file asli atau ekstensi dari browser.
   - Jangan menghapus file jika file tersebut masih direferensikan record lain.
8. Harga:
   - Tentukan tipe database yang sesuai untuk harga (misalnya unsigned big integer dalam rupiah) dan validasi integer non-negatif.
   - Format rupiah hanya di presentation layer, jangan menyimpan string format ke database.

### P1 — Konsistensi aplikasi

1. Ganti nama input generik `a`, `b`, `c`, `d` menjadi `name`, `description`, `price`, `expired`, `category_id`, dan `medicine_image`.
2. Tambahkan relasi `Medicine belongsTo Category` dan `Category hasMany Medicine`.
3. Lengkapi `$fillable` atau gunakan guarded strategy yang konsisten dan aman.
4. Evaluasi model/tabel `Obat` dan `obats`:
   - Telusuri semua referensi dan histori migration.
   - Tentukan apakah masih digunakan.
   - Jangan menghapusnya secara langsung.
   - Jika memang legacy, rencanakan migrasi data dan penghapusan bertahap.
5. Gunakan named routes, route model binding, route groups, dan resource routes bila sesuai.
6. Gunakan Form Request untuk validasi jika kompleksitasnya sudah layak; untuk validasi sederhana, controller validation boleh digunakan.
7. Terapkan pagination, search, dan filter di query backend, bukan hanya menyembunyikan item dengan JavaScript.
8. Tambahkan empty state, loading/disabled state jika relevan, error state, dan success feedback.

### P2 — Kualitas UI/UX

1. Buat satu layout utama Blade untuk halaman publik dan layout dashboard untuk halaman admin jika struktur halaman membutuhkannya.
2. Pecah navbar, footer, alert, form field, dan elemen berulang menjadi Blade components/partials.
3. Pastikan seluruh halaman memakai bahasa Indonesia yang konsisten, kecuali istilah teknis yang memang lebih jelas dalam bahasa Inggris.
4. Hilangkan placeholder seperti Lorem ipsum, "Document", "Page 2", dan teks template yang tidak relevan.
5. Perbaiki navbar mobile agar benar-benar dapat dibuka dan ditutup.
6. Gunakan format tanggal yang mudah dibaca dan format harga `Rp` secara konsisten.
7. Tambahkan konfirmasi sebelum tindakan destruktif.
8. Periksa kontras, focus state, label input, keyboard navigation, dan alt text.

---

## 4. Arah produk dan pengalaman pengguna

Kembangkan aplikasi sebagai **website apotek / katalog obat dengan area pengelolaan obat**. Jangan menganggap aplikasi sudah merupakan sistem apotek/POS lengkap. Fitur transaksi, resep, pembayaran, stok, supplier, dan laporan keuangan belum terbukti tersedia dalam codebase.

### Area publik

Prioritas:
- Homepage yang menjelaskan identitas apotek dengan jelas.
- Katalog obat yang mengambil data dari database.
- Pencarian nama obat.
- Filter kategori.
- Sorting yang masuk akal (nama, harga, terbaru) jika dibutuhkan.
- Detail obat: nama, gambar, harga, deskripsi, kategori, tanggal kedaluwarsa bila memang layak ditampilkan kepada pengguna.
- Informasi kontak dan jam operasional hanya jika datanya disediakan pemilik.
- Responsive layout untuk mobile, tablet, dan desktop.

Jangan menampilkan obat kedaluwarsa sebagai produk yang dapat dibeli. Tentukan aturan visibility yang aman: setidaknya obat dengan tanggal kedaluwarsa sebelum hari ini tidak boleh ditawarkan sebagai produk aktif. Perhatikan timezone aplikasi.

### Area pengelolaan

Prioritas:
- Dashboard ringkas berdasarkan data nyata.
- Daftar obat dengan search, filter kategori, sorting, pagination.
- Tambah, detail, edit, dan hapus obat.
- Manajemen kategori.
- Upload dan preview gambar.
- Status kedaluwarsa / mendekati kedaluwarsa.
- Validasi yang jelas dan feedback setelah aksi.

### Fitur lanjutan — hanya setelah fondasi stabil

Fitur berikut dapat dipertimbangkan sebagai fase terpisah:
- Inventory/stok dengan kuantitas dan stock movement.
- Supplier.
- Pembelian/restock.
- Penjualan dan transaksi.
- Riwayat transaksi.
- Laporan penjualan dan stok.
- Export CSV/PDF.
- Audit log aktivitas admin.
- Role admin/staff.
- Notifikasi stok menipis dan obat mendekati kedaluwarsa.

Sebelum membuat fitur stok/transaksi, desain model dan aturan bisnisnya terlebih dahulu. Jangan sekadar menambahkan kolom `stock` tanpa menentukan bagaimana stok berubah, siapa yang boleh mengubahnya, dan bagaimana riwayatnya dicatat.

**Batasan domain penting:** aplikasi katalog obat bukan pengganti konsultasi apoteker/dokter. Jangan membuat rekomendasi dosis, diagnosis, klaim khasiat, atau instruksi penggunaan obat tanpa sumber dan proses validasi profesional. Jangan membuat fitur checkout/pembelian obat resep atau obat terbatas seolah-olah seluruh regulasi telah dipenuhi.

---

## 5. Prinsip desain visual

Buat desain yang terasa seperti produk digital apotek modern: bersih, tenang, terpercaya, mudah dipindai, dan tidak berlebihan.

### Arah visual

- Gunakan warna utama bernuansa teal/emerald atau hijau kesehatan secara terukur.
- Background netral terang; gunakan surface putih untuk card dan panel.
- Gunakan warna teks gelap dengan kontras memadai.
- Warna merah hanya untuk error/destructive, amber untuk warning, hijau untuk success.
- Hindari gradient berlebihan, glassmorphism yang tidak perlu, glow, dan dekorasi yang mengganggu.
- Hindari tampilan template generik yang dipenuhi card identik.
- Prioritaskan hierarki visual, whitespace, alignment, dan keterbacaan.

### Tipografi dan layout

- Gunakan font yang sudah tersedia terlebih dahulu (Instrument Sans) kecuali ada alasan kuat untuk menggantinya.
- Terapkan skala tipografi yang konsisten.
- Gunakan max-width container yang konsisten.
- Gunakan grid responsif untuk katalog.
- Pastikan gambar produk punya aspect ratio konsisten dan `object-fit` yang sesuai.
- Hindari ukuran elemen yang melompat-lompat antar halaman.

### Interaksi

- Setiap button harus memiliki fungsi nyata.
- Link harus mengarah ke route yang benar.
- Form harus menampilkan error di dekat field yang relevan.
- State aktif pada navigasi/filter harus jelas.
- Modal/drawer harus dapat ditutup dengan keyboard bila digunakan.
- Jangan menambahkan animasi hanya untuk terlihat modern; gunakan transisi singkat untuk feedback yang membantu.
- Gunakan semantic HTML dan ARIA hanya jika diperlukan dan benar.

### Responsive acceptance

Periksa setidaknya:
- Mobile: 360–390 px.
- Tablet: sekitar 768 px.
- Desktop: 1280–1440 px.

Tidak boleh ada horizontal overflow yang tidak disengaja, form terpotong, navigasi yang tidak dapat diakses, atau tabel yang tidak usable di layar kecil. Gunakan card/list alternative atau horizontal scroll yang terkontrol untuk tabel kompleks.

---

## 6. Arsitektur dan standar implementasi

### Routes

- Gunakan route names, misalnya `medicines.index`, `medicines.create`, `medicines.store`, `medicines.show`, `medicines.edit`, `medicines.update`, `medicines.destroy`.
- Gunakan HTTP method sesuai semantik: GET untuk membaca, POST untuk membuat, PUT/PATCH untuk update, DELETE untuk menghapus.
- Kelompokkan route berdasarkan middleware dan area aplikasi.
- Hindari hard-coded URL dalam Blade jika route helper tersedia.
- Gunakan middleware `auth` dengan konvensi Laravel yang konsisten. Periksa alias yang sudah terdaftar sebelum mengubah bootstrap.
- Jangan membuat endpoint admin tanpa authorization.

### Controllers dan validation

- Controller mengatur alur request, bukan menampung seluruh business logic.
- Validasi semua input server-side.
- Validasi upload: image, MIME yang diizinkan, ukuran maksimum, dan required/nullable sesuai use case.
- Gunakan `validated()` dari Form Request/validator, bukan mengambil input mentah tanpa filter.
- Gunakan transaction database saat satu aksi mengubah beberapa record yang harus konsisten.
- Gunakan redirect dan flash message yang jelas.
- Jangan menampilkan exception/internal path kepada pengguna.

### Models dan database

- Definisikan relasi Eloquent dengan tipe yang benar.
- Tambahkan casts untuk tanggal dan angka bila relevan.
- Gunakan foreign key dan index sesuai query yang nyata.
- Gunakan migration baru untuk perubahan schema; jangan mengedit migration lama yang mungkin sudah dijalankan pada environment lain kecuali proyek dipastikan belum dipakai dan pemilik menyetujui.
- Hindari cascade delete yang berisiko menghapus histori bisnis.
- Jangan menyimpan data turunan yang bisa dihitung kecuali ada alasan performa dan mekanisme sinkronisasi.
- Seed data harus jelas sebagai data demo, bukan data operasional nyata.

### Blade

- Gunakan `{{ }}` untuk output escaping secara default.
- Hindari `{!! !!}` kecuali konten memang sudah disanitasi dan alasannya jelas.
- Gunakan `@csrf`, `@method`, `old()`, `$errors`, dan `@error` secara konsisten.
- Hindari menaruh script panjang inline di banyak view.
- Gunakan `@vite` melalui layout, bukan diduplikasi di banyak partial.
- Gunakan komponen Blade untuk UI yang berulang, bukan membuat abstraksi untuk satu elemen yang hanya dipakai sekali tanpa manfaat.

### JavaScript dan CSS

- Gunakan vanilla JS yang sudah tersedia; jangan menambah framework hanya untuk interaksi kecil.
- Letakkan kode frontend di `resources/js` dan CSS di `resources/css` jika mulai kompleks.
- Hindari inline event handler.
- Pastikan JS aman saat elemen tidak ada pada halaman tertentu.
- Tailwind CSS v4 sudah digunakan melalui `@import "tailwindcss";` dan Vite plugin. Jangan mencampur konfigurasi Tailwind v3 secara tidak sengaja.
- Pastikan class dinamis dapat terdeteksi oleh Tailwind build; hindari membangun nama class secara runtime dari potongan string.

### Error handling dan observability

- Gunakan Laravel logging untuk error operasional yang relevan.
- Jangan log password, token, session ID, atau data pribadi yang tidak diperlukan.
- Tampilkan error yang actionable kepada user tanpa membocorkan detail internal.
- Gunakan 404 untuk resource yang tidak ditemukan dan 403 untuk akses yang ditolak.

---

## 7. Keamanan minimum

Sebelum fitur dianggap selesai, periksa:

- [ ] Semua input divalidasi di server.
- [ ] Semua form mutasi menggunakan CSRF.
- [ ] Authorization dicek di server, bukan hanya menyembunyikan tombol.
- [ ] Tidak ada mass assignment yang tidak terkendali.
- [ ] Tidak ada query SQL raw yang menggabungkan input user secara langsung.
- [ ] Output user di-escape.
- [ ] Upload divalidasi dan disimpan melalui Storage.
- [ ] Nama file tidak dipercaya dari client.
- [ ] Delete memakai DELETE, bukan GET.
- [ ] Login/logout mengelola session dengan benar.
- [ ] Password menggunakan hashing Laravel.
- [ ] Secret hanya berada di `.env`, bukan repository.
- [ ] Debug mode tidak aktif pada production.
- [ ] Error dan log tidak membocorkan informasi sensitif.

Jika membuat role:
- Gunakan authorization server-side melalui middleware, Gate, atau Policy.
- Tentukan role default untuk registrasi publik.
- Jangan pernah membiarkan user mengatur role sendiri melalui request registration.
- Lindungi setiap route dan aksi sensitif, termasuk request yang dikirim langsung tanpa UI.

---

## 8. Testing dan quality gates

Jangan menganggap fitur selesai hanya karena halaman berhasil dibuka.

### Test minimum

Buat Feature Tests untuk:
- Homepage merespons dengan benar.
- Guest tidak dapat mengakses area yang dilindungi.
- Login valid dan invalid.
- Logout mengakhiri session.
- CRUD medicine berjalan sesuai aturan.
- Validasi field wajib dan batas nilai.
- Category wajib dan foreign key valid.
- Upload gambar valid dan file invalid ditolak.
- Update tanpa gambar baru tidak menghapus gambar lama.
- Delete hanya dapat dilakukan oleh user berwenang.
- Search, filter, sorting, dan pagination menghasilkan data benar.
- Obat kedaluwarsa tidak tampil sebagai produk aktif jika aturan tersebut diterapkan.

Gunakan database testing yang terisolasi. Jangan menjalankan test yang dapat menghapus atau mengubah database development/production.

### Perintah verifikasi

Periksa environment dan ketersediaan dependency terlebih dahulu. Jalankan perintah yang sesuai:

```bash
php artisan test
./vendor/bin/pint --test
npm run build
```

Jika dependency belum terpasang atau environment tidak tersedia, jelaskan dengan spesifik. Jangan menyatakan test pass jika tidak dijalankan.

Setelah perubahan:
1. Jalankan test terkait.
2. Jalankan seluruh test jika memungkinkan.
3. Jalankan formatter/check.
4. Build asset frontend.
5. Periksa `git diff` untuk perubahan tidak sengaja.
6. Periksa route list bila route berubah.
7. Periksa migration status sebelum dan sesudah migration.

---

## 9. Roadmap implementasi yang disarankan

Kerjakan secara bertahap. Jangan melompat ke fitur lanjutan sebelum tahap sebelumnya stabil.

### Phase 0 — Discovery dan baseline

- Baca `README.md`, `composer.json`, `package.json`, seluruh route, controller, model, migration, Blade view, dan test.
- Periksa status Git dan perubahan lokal; jangan menimpa pekerjaan yang belum di-commit.
- Identifikasi versi runtime dan dependency yang tersedia.
- Jalankan test/build baseline jika environment memungkinkan.
- Buat daftar masalah aktual dengan lokasi file dan dampak.
- Konfirmasi asumsi bisnis yang belum jelas sebelum membuat keputusan besar.

**Output:** audit singkat, risiko, rencana file yang akan diubah, dan baseline test.

### Phase 1 — Backend correctness dan security

- Benahi Medicine CRUD.
- Implementasikan kategori end-to-end.
- Benahi upload gambar dengan Storage.
- Benahi route HTTP method dan authorization.
- Benahi session logout.
- Tangani not-found dan validasi.
- Evaluasi `Obat` vs `Medicine` dan legacy table.
- Tambahkan Feature Tests.

**Definition of done:** alur CRUD dapat digunakan, data konsisten, upload aman, akses terlindungi, dan test utama lulus.

### Phase 2 — UI foundation

- Buat layout publik dan dashboard yang konsisten.
- Rapikan navbar, footer, alerts, buttons, inputs, cards, tables, badges, dan pagination.
- Buat responsive navigation.
- Terapkan design tokens/warna/spacing secara konsisten.
- Perbaiki halaman login/register.

**Definition of done:** semua halaman memiliki visual system konsisten, responsive, dan tidak ada elemen dekoratif yang berpura-pura berfungsi.

### Phase 3 — Public catalog

- Homepage yang berorientasi pada kebutuhan pengguna.
- Katalog obat.
- Search, filter kategori, sorting, pagination.
- Detail obat.
- Empty state dan error state.
- Aturan visibility obat kedaluwarsa.

**Definition of done:** seluruh katalog mengambil data nyata dari backend dan query berjalan di server.

### Phase 4 — Admin dashboard dan category management

- Dashboard dengan metrik dari database nyata.
- Medicine management table.
- Category CRUD.
- Status kedaluwarsa/mendekati kedaluwarsa.
- Role-based access jika kebutuhan role telah disepakati.

**Definition of done:** staff/admin hanya dapat melakukan aksi yang diizinkan dan angka dashboard dapat ditelusuri ke sumber data.

### Phase 5 — Advanced inventory (opsional, perlu desain domain)

Sebelum coding, buat rancangan:
- Stock item dan satuan.
- Stock movement (masuk, keluar, penyesuaian).
- Supplier dan purchase/restock bila dibutuhkan.
- Transaction consistency.
- Audit trail.
- Reporting dan export.

Jangan menambahkan modul penjualan/pembayaran hanya karena istilah "advanced". Implementasikan hanya bila memang termasuk tujuan produk.

### Phase 6 — Hardening dan release readiness

- Full test suite.
- Build production.
- Responsive QA.
- Accessibility pass.
- Security review.
- Database backup/migration plan.
- Production environment checklist.
- Dokumentasi setup dan deployment.

---

## 10. Workflow wajib setiap kali menerima task

Ikuti urutan ini:

1. **Understand:** baca file yang relevan dan telusuri pemanggil/consumer-nya.
2. **Plan:** jelaskan pendekatan singkat dan file yang akan disentuh untuk task besar.
3. **Implement:** lakukan perubahan kecil, koheren, dan end-to-end.
4. **Verify:** jalankan test, lint/format, build, atau pemeriksaan yang relevan.
5. **Review:** baca diff, cari regresi, hard-coded value, broken link, dan dead control.
6. **Report:** rangkum apa yang berubah, keputusan penting, command yang dijalankan, hasil sebenarnya, dan hal yang belum selesai.

Untuk task kecil, jangan membuat proses dokumentasi yang berlebihan. Namun tetap baca kode dan verifikasi hasil.

### Aturan ketika menemukan ambiguitas

Tanyakan kepada pemilik proyek jika keputusan akan memengaruhi:
- aturan bisnis obat atau penjualan;
- role dan hak akses;
- penghapusan atau migrasi data;
- perubahan stack atau dependency besar;
- pembayaran, resep, atau data sensitif;
- integrasi layanan eksternal.

Untuk detail implementasi kecil, gunakan keputusan yang paling sederhana, aman, dan konsisten dengan codebase, lalu dokumentasikan.

---

## 11. Standar kualitas hasil akhir

Setiap fitur harus memenuhi kriteria berikut:

| Kriteria | Standar |
|---|---|
| Functionality | Semua aksi benar-benar bekerja end-to-end |
| Data integrity | Validasi dan relasi database konsisten |
| Security | Auth, authorization, CSRF, upload, dan output aman |
| UX | Feedback, empty/error state, dan alur jelas |
| Responsive | Mobile, tablet, desktop |
| Accessibility | Semantic HTML, label, focus, keyboard |
| Maintainability | Kode mudah dibaca dan mengikuti pola proyek |
| Testing | Test relevan dijalankan dan hasil dilaporkan |
| Performance | Query efisien, pagination, asset build |
| Honesty | Tidak mengklaim fitur/test yang belum ada |

---

## 12. Format laporan setelah mengerjakan task

Gunakan format ringkas:

```text
## Ringkasan
- Apa yang diimplementasikan.

## Perubahan utama
- File/area dan alasan perubahan.

## Verifikasi
- Perintah yang dijalankan.
- Hasil aktual (PASS/FAIL/BLOCKED).

## Catatan
- Risiko, asumsi, migration, atau pekerjaan tersisa.
```

Jika ada kegagalan, jelaskan penyebabnya. Jangan menyembunyikan kegagalan test atau mengganti hasilnya dengan klaim optimistis.

---

## 13. Instruksi awal untuk Claude Code

Saat pertama kali membaca dokumen ini:

1. Jangan langsung mengubah semua file.
2. Lakukan discovery pada repository aktual.
3. Cocokkan temuan audit dalam dokumen ini dengan kondisi source terbaru.
4. Identifikasi masalah P0 yang benar-benar masih ada.
5. Susun rencana implementasi bertahap dengan prioritas dan risiko.
6. Mulai dari correctness/security, bukan animasi atau dekorasi.
7. Pertahankan Laravel + Blade + Tailwind + Vite kecuali ada alasan kuat untuk perubahan.
8. Jangan mengarang fitur bisnis yang belum disepakati.

**Target akhirnya:** aplikasi apotek yang bukan hanya terlihat modern, tetapi juga konsisten secara data, aman, responsif, mudah dikembangkan, dan dapat dipercaya.
