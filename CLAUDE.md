# PPDB SMK Plus Pelita Nusantara (Bogor) - Backend Documentation

Aplikasi Penerimaan Peserta Didik Baru (PPDB) SMK Plus Pelita Nusantara berbasis Laravel 12 dengan frontend dinamis terintegrasi dan Admin Dashboard modern (Password-Only Authentication).

---

## 🔐 Kredensial & Autentikasi Admin

Admin Dashboard dilindungi dengan mekanisme **Password Only Authentication** melalui middleware `admin.auth`:

- **URL Login Admin**: `/ppdb/login`
- **Master Password**: `adminpenus2026`
- **Konfigurasi .env**:
  ```env
  ADMIN_PASSWORD=adminpenus2026
  ```
- **Session Key**: `admin_authenticated = true`
- **Logout URL**: `/ppdb/logout` (Mendukung `POST` & fallback `GET`)

---

## 🧭 Daftar Rute (Routes)

Semua rute diawali dengan prefix `/ppdb`:

### 🌐 Rute Publik (Calon Siswa & Wali Murid)
| Method | URL | Deskripsi |
|---|---|---|
| `GET` | `/ppdb` | Formulir Pendaftaran Online & Hero info Gelombang Aktif |
| `POST` | `/ppdb/daftar` | Submission pendaftaran calon peserta didik |
| `GET` | `/ppdb/akomodasi` | Rincian biaya pendidikan, SPP bulanan, DSP, nomor rekening & WA CS |
| `GET` | `/ppdb/pengumuman` | Warta pengumuman resmi, filter kategori, lampiran download |
| `GET` | `/ppdb/cek-status` | Pencarian status seleksi via NISN, No. Registrasi, atau Nama |
| `GET` | `/ppdb/cetak-kartu/{id}` | Cetak resmi Kartu Tanda Peserta (Format A4 / Print-ready) |

### 🔒 Rute Admin Dashboard (Dilindungi `admin.auth`)
| Method | URL | Deskripsi |
|---|---|---|
| `GET` | `/ppdb/login` | Halaman login admin (Password-only) |
| `POST` | `/ppdb/login` | Proses autentikasi password |
| `POST` | `/ppdb/logout` | Mengakhiri sesi admin |
| `GET` | `/ppdb/dashboard` | Statistik & KPI (total pendaftar, per jurusan, per jalur, status seleksi) |
| `GET` | `/ppdb/dashboard/pendaftar` | Manajemen data pendaftar (Filter, Pencarian, Pagination) |
| `GET` | `/ppdb/dashboard/pendaftar/export` | **Export Data Pendaftar ke format CSV** (UTF-8 BOM Excel-ready) |
| `GET` | `/ppdb/dashboard/pendaftar/{id}` | Detail lengkap biodata, wali, asal sekolah, ukuran seragam, dan riwayat |
| `PUT` | `/ppdb/dashboard/pendaftar/{id}` | Edit data biodata & preferensi jurusan/jalur siswa |
| `PATCH` | `/ppdb/dashboard/pendaftar/{id}/status` | Ubah status seleksi & input catatan verifikasi/observasi |
| `DELETE` | `/ppdb/dashboard/pendaftar/{id}` | Hapus data pendaftar |
| `GET` | `/ppdb/dashboard/gelombang` | Daftar & manajemen gelombang PPDB (kuota, tanggal, biaya formulir) |
| `POST` | `/ppdb/dashboard/gelombang` | Tambah gelombang baru |
| `PUT` | `/ppdb/dashboard/gelombang/{id}` | Edit gelombang |
| `PATCH` | `/ppdb/dashboard/gelombang/{id}/aktifkan` | **1-Click Aktifkan Gelombang** (Otomatis menonaktifkan gelombang lain) |
| `DELETE` | `/ppdb/dashboard/gelombang/{id}` | Hapus gelombang |
| `GET` | `/ppdb/dashboard/pengumuman` | Manajemen warta & SK pengumuman seleksi |
| `POST` | `/ppdb/dashboard/pengumuman` | Publikasikan pengumuman baru (Support file upload PDF/Gambar) |
| `PUT` | `/ppdb/dashboard/pengumuman/{id}` | Edit isi pengumuman |
| `PATCH` | `/ppdb/dashboard/pengumuman/{id}/pin` | Pin/Unpin pengumuman teratas |
| `DELETE` | `/ppdb/dashboard/pengumuman/{id}` | Hapus pengumuman & lampiran terkait |
| `GET` | `/ppdb/dashboard/akomodasi` | Pengaturan tarif PPDB, skema angsuran DSP, rekening bank resmi, dan kontak |
| `POST` | `/ppdb/dashboard/akomodasi` | Simpan perubahan tarif & daftar rekening |

---

## 🗄️ Struktur Database & Model

1. **`ppdb_registrations`** (Model: `PpdbRegistration`):
   - Data biodata calon siswa, NISN, No KK, kontak siswa & orang tua, jurusan pilihan, kelas, jalur, ukuran seragam, catatan panitia.
   - Status: `menunggu_verifikasi`, `terverifikasi`, `lulus_seleksi`, `tidak_lulus`.
   - Relasi: `belongsTo(PpdbWave::class, 'gelombang_id')`.

2. **`ppdb_waves`** (Model: `PpdbWave`):
   - Manajemen gelombang penerimaan (Gelombang 1, Gelombang 2, Gelombang 3, dsb.).
   - Kuota target, tanggal buka, tanggal tutup, biaya formulir, status `is_active`.
   - Relasi: `hasMany(PpdbRegistration::class, 'gelombang_id')`.

3. **`ppdb_announcements`** (Model: `PpdbAnnouncement`):
   - Surat keputusan, jadwal tes seleksi, pengumuman kelulusan.
   - Kolom: `judul`, `nomor_sk`, `kategori`, `badge`, `tanggal`, `ringkasan`, `isi_lengkap`, `file_path`, `file_nama`, `file_ukuran`, `is_pinned`, `is_published`, `action_link`, `action_text`.

4. **`ppdb_fee_settings`** (Model: `PpdbFeeSetting`):
   - Key-value configuration: `biaya_formulir`, `dsp_cash`, `dsp_angsuran_1..3`, `spp_bulanan`, `biaya_seragam`, `potongan_gelombang_1`, `daftar_rekening`, `kontak_wa`, `email_cs`.
   - Helper methods: `PpdbFeeSetting::get($key, $default)`, `PpdbFeeSetting::set($key, $value, $label, $group)`.

---

## 🖨️ Fitur Cetak Kartu Tanda Peserta

- Rute: `/ppdb/cetak-kartu/{id}`
- Fitur:
  - Header resmi berlogo Yayasan & SMK Plus Pelita Nusantara.
  - Simulasi Barcode & QR Code verifikasi.
  - Rincian biodata, jalur seleksi, dan kompetensi keahlian.
  - Jadwal & petunjuk tes observasi / wawancara.
  - Kolom tanda tangan resmi Ketua Panitia PPDB & Calon Peserta Didik.
  - Tombol auto-print dengan `@media print` layout A4 rapi tanpa header browser yang mengganggu.

---

## 🧪 Testing

Semua fitur diuji secara end-to-end melalui Feature Test:

```bash
php artisan test --filter=PpdbBackendTest
```

Mencakup 13 test case komprehensif:
- Pengujian aksesibilitas halaman publik
- Validasi cetak kartu peserta
- Proteksi middleware auth pada dashboard
- Penolakan password salah
- Penerimaan master password yang benar
- Export CSV pendaftar
- Proses registrasi publik lengkap
- Update status pendaftar (lulus_seleksi, dsb.)
- Pergantian gelombang aktif 1-click
- CRUD, pin/unpin pengumuman & upload file
- Update tarif keuangan & daftar rekening transfer
- Pencarian status pendaftar via nomor registrasi / NISN
- Logout session
