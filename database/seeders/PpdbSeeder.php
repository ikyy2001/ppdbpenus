<?php

namespace Database\Seeders;

use App\Models\PpdbAnnouncement;
use App\Models\PpdbFeeSetting;
use App\Models\PpdbRegistration;
use App\Models\PpdbWave;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PpdbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. SEED GELOMBANG PPDB
        $wave1 = PpdbWave::updateOrCreate(
            ['nama' => 'Gelombang 1'],
            [
                'tahun_ajaran' => '2027/2028',
                'tanggal_mulai' => Carbon::parse('2026-09-01'),
                'tanggal_selesai' => Carbon::parse('2026-11-30'),
                'kuota' => 350,
                'biaya_formulir' => 150000,
                'is_active' => true,
                'deskripsi' => 'Gelombang Pendaftaran Utama (Early Bird) dengan potongan uang gedung Rp 500.000 dan prioritas pilihan jurusan.',
            ]
        );

        $wave2 = PpdbWave::updateOrCreate(
            ['nama' => 'Gelombang 2'],
            [
                'tahun_ajaran' => '2027/2028',
                'tanggal_mulai' => Carbon::parse('2026-12-01'),
                'tanggal_selesai' => Carbon::parse('2027-02-28'),
                'kuota' => 250,
                'biaya_formulir' => 175000,
                'is_active' => false,
                'deskripsi' => 'Gelombang Reguler Tahap 1 untuk kuota umum semua kompetensi keahlian.',
            ]
        );

        $wave3 = PpdbWave::updateOrCreate(
            ['nama' => 'Gelombang 3'],
            [
                'tahun_ajaran' => '2027/2028',
                'tanggal_mulai' => Carbon::parse('2027-03-01'),
                'tanggal_selesai' => Carbon::parse('2027-06-30'),
                'kuota' => 150,
                'biaya_formulir' => 200000,
                'is_active' => false,
                'deskripsi' => 'Gelombang Terakhir / Penutupan Kuota Sisa PPDB.',
            ]
        );

        // Pastikan semua pendaftar memiliki relasi ke gelombang 1 jika belum
        PpdbRegistration::whereNull('gelombang_id')->update(['gelombang_id' => $wave1->id]);

        // 2. SEED PENGUMUMAN DINAMIS
        $announcements = [
            [
                'judul' => 'Pengumuman Hasil Verifikasi Berkas Seleksi Administrasi PPDB Gelombang 1',
                'nomor_sk' => '084/PPDB-SMKPNB/IX/2026',
                'tanggal' => Carbon::parse('2026-09-20'),
                'kategori' => 'Hasil Seleksi & Kelulusan',
                'badge' => 'PENTING',
                'is_pinned' => true,
                'ringkasan' => 'Berdasarkan hasil sidang pleno panitia PPDB SMK Plus Pelita Nusantara, berikut penetapan kelolosan seleksi berkas administrasi calon siswa baru dan jadwal tes wawancara minat bakat.',
                'isiLengkap' => "### KEPUTUSAN PANITIA PENERIMAAN PESERTA DIDIK BARU (PPDB)\n### SMK PLUS PELITA NUSANTARA BOGOR\n**NOMOR: 084/PPDB-SMKPNB/IX/2026**\n\nTentang:\n**PENETAPAN HASIL SELEKSI ADMINISTRASI CALON PESERTA DIDIK BARU GELOMBANG 1 TAHUN AJARAN 2027/2028**\n\nMenimbang hasil verifikasi kelengkapan berkas fisik dan digital yang diunggah calon siswa per tanggal 1 hingga 19 September 2026, Panitia PPDB menetapkan:\n\n1. **Hasil Rekapitulasi Berkas Masuk:**\n   - Jumlah Formulir Masuk: **342 Pendaftar**\n   - Dinyatakan Memenuhi Syarat (MS / Lolos Berkas): **318 Calon Siswa**\n   - Perlu Perbaikan Berkas Dokumen: **24 Calon Siswa**\n\n2. **Tindak Lanjut Peserta Lolos Administrasi:**\n   - Peserta yang dinyatakan lolos wajib mencetak **Kartu Tanda Peserta PPDB** melalui portal Cek Status NISN.\n   - Peserta dijadwalkan hadir mengikuti **Observasi Minat & Bakat Kejuruan** pada Sabtu, 28 September 2026 sesuai sesi masing-masing.\n\n3. **Peserta yang Memerlukan Perbaikan Berkas:**\n   - Diberikan waktu perbaikan scan rapor dan dokumen identitas hingga Rabu, 25 September 2026 pukul 16.00 WIB melalui akun portal PPDB atau menghubungi sekretariat.",
                'file_nama' => 'SK_Kelulusan_Administrasi_Gel1_Penus_2027.pdf',
                'file_path' => 'https://images.lekar.co.id/file/pelita/infografis_pelita_nusantara.pdf',
                'file_ukuran' => '1.8 MB',
                'action_link' => '/ppdb/cek-status',
                'action_text' => 'Cek Status NISN Anda',
            ],
            [
                'judul' => 'Jadwal dan Panduan Teknis Pelaksanaan Observasi Minat & Bakat Siswa Gelombang 1',
                'nomor_sk' => '081/PPDB-SMKPNB/IX/2026',
                'tanggal' => Carbon::parse('2026-09-18'),
                'kategori' => 'Tes Observasi & Wawancara',
                'badge' => 'TERBARU',
                'is_pinned' => false,
                'ringkasan' => 'Pelaksanaan observasi potensi kejuruan, tes minat bakat digital, dan wawancara kepribadian calon siswa baru akan dilaksanakan secara luring (offline) bertempat di Kampus Penus Cibinong.',
                'isiLengkap' => "### PANDUAN OBSERVASI MINAT & BAKAT KEJURUAN\n**SMK PLUS PELITA NUSANTARA BOGOR**\n\nKepada Yth. Calon Siswa dan Orang Tua/Wali Murid,\n\nPanitia PPDB menginformasikan tata cara dan panduan pelaksanaan kegiatan Observasi Minat dan Bakat:\n\n1. **Hari & Tanggal Pelaksanaan:**\n   - Hari: **Sabtu, 28 September 2026**\n   - Sesi Pagi: 08.00 – 11.30 WIB (PPLG, Animasi, DKV)\n   - Sesi Siang: 12.30 – 16.00 WIB (TJKT, BC, AKL, MPLB)\n\n2. **Tempat:**\n   - Kampus SMK Plus Pelita Nusantara, Jl. Raya Golf Ciriung No. 1, Cibinong.\n   - Ruang Uji: Lab Komputer 1-4 dan Gedung Multimedia Kreatif.\n\n3. **Tata Tertib & Perlengkapan Wajib:**\n   - Mengenakan seragam asal SMP/MTs (rapi dan sopan) atau kemeja putih celana panjang gelap serta sepatu tertutup.\n   - Membawa cetakan Formulir Pendaftaran / Kartu Peserta PPDB.\n   - Membawa alat tulis (pensil 2B, pulpen, penghapus).\n   - Khusus pilihan jurusan **DKV dan Animasi**, diperbolehkan membawa portofolio karya gambar/desain (jika ada).",
                'file_nama' => 'Juknis_Observasi_MinatBakat_Gel1.pdf',
                'file_path' => 'https://images.lekar.co.id/file/pelita/infografis_pelita_nusantara.pdf',
                'file_ukuran' => '920 KB',
                'action_link' => null,
                'action_text' => null,
            ],
            [
                'judul' => 'Alur dan Petunjuk Teknis Daftar Ulang Peserta Lolos Jalur Reguler & Prestasi',
                'nomor_sk' => '078/PPDB-SMKPNB/IX/2026',
                'tanggal' => Carbon::parse('2026-09-15'),
                'kategori' => 'Daftar Ulang & Seragam',
                'badge' => 'PENTING',
                'is_pinned' => false,
                'ringkasan' => 'Informasi tata cara penyelesaian daftar ulang, pembayaran uang pangkal / DSP, pengukuran seragam sekolah 5 stel, serta penyerahan berkas fisik asli.',
                'isiLengkap' => "### PETUNJUK TEKNIS DAFTAR ULANG PESERTA LULUS\n**SMK PLUS PELITA NUSANTARA BOGOR**\n\nBagi calon siswa yang telah dinyatakan LULUS SELEKSI AKHIR, harap memperhatikan tenggat waktu dan prosedur daftar ulang berikut:\n\n1. **Batas Waktu Daftar Ulang:**\n   - Tanggal: **15 September s.d. 05 Oktober 2026**\n   - Layanan loket fisik buka: Senin – Sabtu, pukul 08.00 – 15.00 WIB.\n\n2. **Dokumen Fisik yang Wajib Diserahkan:**\n   - Fotokopi Ijazah / Surat Keterangan Lulus (SKL) SMP legalisir (2 lembar).\n   - Fotokopi Akta Kelahiran dan Kartu Keluarga (2 lembar).\n   - Pas foto ukuran 3x4 (latar merah) sebanyak 4 lembar.\n   - Surat Pernyataan Tata Tertib bermaterai Rp 10.000 (disediakan sekolah).\n\n3. **Pengukuran Seragam Sekolah:**\n   - Pengukuran langsung di Butik Tata Busana Kampus Penus.\n   - Meliputi: Seragam Putih Abu, Seragam Kotak-Kotak Khas Penus, Baju Praktek Jurusan, Baju Olahraga, dan Jas Almamater.",
                'file_nama' => 'Buku_Panduan_Daftar_Ulang_PPDB_2027.pdf',
                'file_path' => 'https://images.lekar.co.id/file/pelita/infografis_pelita_nusantara.pdf',
                'file_ukuran' => '2.4 MB',
                'action_link' => '/ppdb/akomodasi',
                'action_text' => 'Lihat Rincian Biaya & Rekening',
            ],
            [
                'judul' => 'Program Beasiswa Jalur Prestasi Akademik & Tahfidz Quran 2027/2028',
                'nomor_sk' => '072/PPDB-SMKPNB/IX/2026',
                'tanggal' => Carbon::parse('2026-09-10'),
                'kategori' => 'Informasi Beasiswa',
                'badge' => 'BEASISWA',
                'is_pinned' => false,
                'ringkasan' => 'Kesempatan beasiswa bebas biaya DSP 100% dan bebas SPP bulanan bagi siswa berprestasi ranking 1 umum, juara OSN/O2SN/FLS2N, dan penghafal minimal 3 Juz Al-Quran.',
                'isiLengkap' => "### PROGRAM BEASISWA UNGGULAN PELITA NUSANTARA\n\nSMK Plus Pelita Nusantara memberikan apresiasi setinggi-tingginya kepada putra-putri berprestasi melalui jalur:\n1. **Beasiswa Prestasi Akademik**: Bebas DSP 100% untuk peringkat 1 umum SMP/MTs.\n2. **Beasiswa Minat Bakat Non-Akademik**: Juara 1-3 tingkat Kabupaten/Kota bidang IT, Robotik, Desain Grafis, Esports, dan Seni.\n3. **Beasiswa Tahfidz**: Bebas SPP hingga 3 tahun penuh bagi hafizh minimal 3 Juz bersanad/teruji.\n\nSyarat pengajuan: Unggah piagam kejuaraan atau syahadah tahfidz saat mendaftar online.",
                'file_nama' => 'Panduan_Beasiswa_Penus_2027.pdf',
                'file_path' => 'https://images.lekar.co.id/file/pelita/infografis_pelita_nusantara.pdf',
                'file_ukuran' => '1.1 MB',
                'action_link' => '/ppdb',
                'action_text' => 'Daftar Jalur Prestasi Sekarang',
            ],
        ];

        foreach ($announcements as $item) {
            PpdbAnnouncement::updateOrCreate(
                ['judul' => $item['judul']],
                [
                    'nomor_sk' => $item['nomor_sk'],
                    'tanggal' => $item['tanggal'],
                    'kategori' => $item['kategori'],
                    'badge' => $item['badge'],
                    'is_pinned' => $item['is_pinned'],
                    'ringkasan' => $item['ringkasan'],
                    'isi_lengkap' => $item['isiLengkap'],
                    'file_nama' => $item['file_nama'],
                    'file_path' => $item['file_path'],
                    'file_ukuran' => $item['file_ukuran'],
                    'action_link' => $item['action_link'],
                    'action_text' => $item['action_text'],
                    'is_active' => true,
                ]
            );
        }

        // 3. SEED SETTING BIAYA / AKOMODASI
        PpdbFeeSetting::set('biaya_formulir', 150000, 'Biaya Pembelian Formulir', 'biaya');
        PpdbFeeSetting::set('dsp_cash', 5850000, 'Dana Sumbangan Pendidikan (Cash Lunas)', 'biaya');
        PpdbFeeSetting::set('dsp_angsuran_1', 2500000, 'DSP Tahap 1 (Saat Daftar Ulang)', 'biaya');
        PpdbFeeSetting::set('dsp_angsuran_2', 1850000, 'DSP Tahap 2 (Sebelum Semester 1)', 'biaya');
        PpdbFeeSetting::set('dsp_angsuran_3', 1500000, 'DSP Tahap 3 (Sebelum Semester 2)', 'biaya');
        PpdbFeeSetting::set('spp_bulanan', 450000, 'SPP Bulanan Semua Jurusan', 'biaya');
        PpdbFeeSetting::set('biaya_seragam', 1250000, 'Paket 5 Stel Seragam Lengkap + Jas Almamater', 'biaya');
        PpdbFeeSetting::set('potongan_gelombang_1', 500000, 'Diskon Early Bird Gelombang 1', 'biaya');

        // Rekening Pembayaran
        $rekening = [
            [
                'bank' => 'Bank Syariah Indonesia (BSI)',
                'nomor' => '7188-299-102',
                'atas_nama' => 'YAYASAN PELITA NUSANTARA PPDB',
                'badge' => 'Rekening Utama',
                'logo' => 'BSI'
            ],
            [
                'bank' => 'Bank Rakyat Indonesia (BRI)',
                'nomor' => '0421-01-002931-50-8',
                'atas_nama' => 'SMK PLUS PELITA NUSANTARA',
                'badge' => 'Alternatif',
                'logo' => 'BRI'
            ],
            [
                'bank' => 'Bank Mandiri',
                'nomor' => '133-00-2491029-1',
                'atas_nama' => 'PPDB PELITA NUSANTARA',
                'badge' => 'Transfer ATM / Livin',
                'logo' => 'MANDIRI'
            ]
        ];
        PpdbFeeSetting::set('daftar_rekening', $rekening, 'Daftar Rekening Bank Resmi', 'rekening');

        // Kontak Helpdesk
        PpdbFeeSetting::set('kontak_wa', '6281283921029', 'Nomor WhatsApp Helpdesk Panitia', 'kontak');
        PpdbFeeSetting::set('email_cs', 'ppdb@smkpelitanusantara.sch.id', 'Email Sekretariat PPDB', 'kontak');
        PpdbFeeSetting::set('telepon_kantor', '(021) 8790-1234', 'Telepon Kantor Kampus', 'kontak');
        PpdbFeeSetting::set('alamat_kampus', 'Jl. Raya Golf Ciriung No. 1, Cibinong, Kab. Bogor', 'Alamat Kampus Fisik', 'kontak');

        // 4. SEED DATA PENDAFTAR REALISTIK CONTOH
        $pendaftarData = [
            [
                'nomor_registrasi' => 'PPDB-2027-10492',
                'gelombang_id' => $wave1->id,
                'nama_lengkap' => 'Muhammad Rizky Pratama',
                'nama_panggilan' => 'Rizky',
                'nisn' => '0081293841',
                'nomor_kk' => '3201012903020001',
                'tempat_lahir' => 'Bogor',
                'tanggal_lahir_hari' => '15',
                'tanggal_lahir_bulan' => '4',
                'tanggal_lahir_tahun' => '2011',
                'jenis_kelamin' => 'L',
                'alamat_lengkap' => 'Jl. Mayor Oking Jaya Atmaja No. 45, RT 02/05, Ciriung, Cibinong',
                'sekolah_pilihan_level' => 'SMK',
                'sekolah_pilihan_unit' => 'SMK Plus Pelita Nusantara Bogor',
                'tipe_pendaftar' => 'Siswa Baru',
                'kelas_pilihan' => 'Kelas X (Sepuluh)',
                'jurusan' => 'Pengembangan Perangkat Lunak dan Gim (PPLG)',
                'jalur_seleksi' => 'Jalur Reguler',
                'asal_sekolah' => 'SMP Negeri 1 Cibinong',
                'nomor_kontak_pendaftar' => '081289123891',
                'nomor_kontak_ortu' => '081389028192',
                'email' => 'rizky.pratama@gmail.com',
                'jenis_layanan' => ['Full Day School'],
                'sumber_info' => ['Media Sosial Instagram', 'Teman / Alumni'],
                'alasan_minat' => 'Fasilitas Lab Komputer Lengkap & Kurikulum Industri Game',
                'ukuran_seragam' => 'L',
                'status' => 'lulus_seleksi',
                'catatan' => 'Lolos seleksi wawancara dan tes logika pemrograman dengan nilai A.',
            ],
            [
                'nomor_registrasi' => 'PPDB-2027-28491',
                'gelombang_id' => $wave1->id,
                'nama_lengkap' => 'Siti Aisyah Rahmadani',
                'nama_panggilan' => 'Aisyah',
                'nisn' => '0089201928',
                'nomor_kk' => '3201011802050003',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir_hari' => '22',
                'tanggal_lahir_bulan' => '8',
                'tanggal_lahir_tahun' => '2011',
                'jenis_kelamin' => 'P',
                'alamat_lengkap' => 'Perumahan Puri Nirwana 3 Blok BD No. 12, Karadenan, Cibinong',
                'sekolah_pilihan_level' => 'SMK',
                'sekolah_pilihan_unit' => 'SMK Plus Pelita Nusantara Bogor',
                'tipe_pendaftar' => 'Siswa Baru',
                'kelas_pilihan' => 'Kelas X (Sepuluh)',
                'jurusan' => 'Desain Komunikasi Visual (DKV)',
                'jalur_seleksi' => 'Jalur Prestasi',
                'asal_sekolah' => 'SMP IT Al-Madinah Cibinong',
                'nomor_kontak_pendaftar' => '085718290123',
                'nomor_kontak_ortu' => '081298301920',
                'email' => 'aisyah.siti@yahoo.com',
                'jenis_layanan' => ['Full Day School'],
                'sumber_info' => ['Guru / BK Sekolah Asal'],
                'alasan_minat' => 'Tertarik dunia ilustrasi digital dan studio multimedia',
                'ukuran_seragam' => 'M',
                'status' => 'terverifikasi',
                'catatan' => 'Portofolio desain grafis memenuhi syarat jalur prestasi.',
            ],
            [
                'nomor_registrasi' => 'PPDB-2027-39102',
                'gelombang_id' => $wave1->id,
                'nama_lengkap' => 'Dimas Arya Saputra',
                'nama_panggilan' => 'Dimas',
                'nisn' => '0083920194',
                'nomor_kk' => '3201010101010005',
                'tempat_lahir' => 'Bogor',
                'tanggal_lahir_hari' => '05',
                'tanggal_lahir_bulan' => '11',
                'tanggal_lahir_tahun' => '2010',
                'jenis_kelamin' => 'L',
                'alamat_lengkap' => 'Kp. Cipayung RT 03/01, Sukaraja, Kabupaten Bogor',
                'sekolah_pilihan_level' => 'SMK',
                'sekolah_pilihan_unit' => 'SMK Plus Pelita Nusantara Bogor',
                'tipe_pendaftar' => 'Siswa Baru',
                'kelas_pilihan' => 'Kelas X (Sepuluh)',
                'jurusan' => 'Teknik Jaringan Komputer dan Telekomunikasi (TJKT)',
                'jalur_seleksi' => 'Jalur Reguler',
                'asal_sekolah' => 'SMP Negeri 2 Sukaraja',
                'nomor_kontak_pendaftar' => '087819203912',
                'nomor_kontak_ortu' => '081920391209',
                'email' => 'dimas.arya@gmail.com',
                'jenis_layanan' => ['Full Day School'],
                'sumber_info' => ['Brosur / Spanduk'],
                'alasan_minat' => 'Ingin menjadi Cyber Security dan Network Engineer',
                'ukuran_seragam' => 'XL',
                'status' => 'menunggu_verifikasi',
                'catatan' => 'Menunggu verifikasi scan kartu keluarga asli.',
            ],
            [
                'nomor_registrasi' => 'PPDB-2027-48192',
                'gelombang_id' => $wave1->id,
                'nama_lengkap' => 'Anindya Putri Kirana',
                'nama_panggilan' => 'Anin',
                'nisn' => '0091829301',
                'nomor_kk' => '3271011505080004',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir_hari' => '19',
                'tanggal_lahir_bulan' => '2',
                'tanggal_lahir_tahun' => '2011',
                'jenis_kelamin' => 'P',
                'alamat_lengkap' => 'Jl. Padjajaran No. 88, Bantarjati, Kota Bogor',
                'sekolah_pilihan_level' => 'SMK',
                'sekolah_pilihan_unit' => 'SMK Plus Pelita Nusantara Bogor',
                'tipe_pendaftar' => 'Siswa Baru',
                'kelas_pilihan' => 'Kelas X (Sepuluh)',
                'jurusan' => 'Animasi',
                'jalur_seleksi' => 'Jalur Reguler',
                'asal_sekolah' => 'SMP Negeri 4 Kota Bogor',
                'nomor_kontak_pendaftar' => '081398201948',
                'nomor_kontak_ortu' => '081283910291',
                'email' => 'anindya.kirana@gmail.com',
                'jenis_layanan' => ['Full Day School'],
                'sumber_info' => ['Media Sosial Instagram', 'Website Sekolah'],
                'alasan_minat' => 'Bakat menggambar karakter anime dan 3D modelling',
                'ukuran_seragam' => 'S',
                'status' => 'terverifikasi',
                'catatan' => 'Berkas lengkap. Berhak mengikuti observasi.',
            ],
            [
                'nomor_registrasi' => 'PPDB-2027-59201',
                'gelombang_id' => $wave1->id,
                'nama_lengkap' => 'Fajar Nugraha',
                'nama_panggilan' => 'Fajar',
                'nisn' => '0084729103',
                'nomor_kk' => '3201021008060002',
                'tempat_lahir' => 'Depok',
                'tanggal_lahir_hari' => '30',
                'tanggal_lahir_bulan' => '7',
                'tanggal_lahir_tahun' => '2010',
                'jenis_kelamin' => 'L',
                'alamat_lengkap' => 'Jl. Raya Cilodong No. 15, Kalimulya, Depok',
                'sekolah_pilihan_level' => 'SMK',
                'sekolah_pilihan_unit' => 'SMK Plus Pelita Nusantara Bogor',
                'tipe_pendaftar' => 'Siswa Baru',
                'kelas_pilihan' => 'Kelas X (Sepuluh)',
                'jurusan' => 'Broadcasting dan Perfilman (BC)',
                'jalur_seleksi' => 'Jalur Reguler',
                'asal_sekolah' => 'SMP Negeri 8 Depok',
                'nomor_kontak_pendaftar' => '089678129034',
                'nomor_kontak_ortu' => '081290341829',
                'email' => 'fajar.bc@gmail.com',
                'jenis_layanan' => ['Full Day School'],
                'sumber_info' => ['Kunjungan Roadshow Sekolah'],
                'alasan_minat' => 'Tertarik videografi, editing kamera, dan broadcast studio',
                'ukuran_seragam' => 'L',
                'status' => 'lulus_seleksi',
                'catatan' => 'Dinyatakan Lulus Seleksi Utama Broadcasting.',
            ],
            [
                'nomor_registrasi' => 'PPDB-2027-61029',
                'gelombang_id' => $wave1->id,
                'nama_lengkap' => 'Nabila Zahra Farhana',
                'nama_panggilan' => 'Nabila',
                'nisn' => '0089301928',
                'nomor_kk' => '3201011409070005',
                'tempat_lahir' => 'Bogor',
                'tanggal_lahir_hari' => '12',
                'tanggal_lahir_bulan' => '9',
                'tanggal_lahir_tahun' => '2011',
                'jenis_kelamin' => 'P',
                'alamat_lengkap' => 'Perum Graha Kartika Pratama Blok D5, Ciriung, Cibinong',
                'sekolah_pilihan_level' => 'SMK',
                'sekolah_pilihan_unit' => 'SMK Plus Pelita Nusantara Bogor',
                'tipe_pendaftar' => 'Siswa Baru',
                'kelas_pilihan' => 'Kelas X (Sepuluh)',
                'jurusan' => 'Akuntansi dan Keuangan Lembaga (AKL)',
                'jalur_seleksi' => 'Jalur Prestasi',
                'asal_sekolah' => 'MTs Negeri 1 Bogor',
                'nomor_kontak_pendaftar' => '081289301923',
                'nomor_kontak_ortu' => '081390182938',
                'email' => 'nabila.zahra@outlook.com',
                'jenis_layanan' => ['Full Day School'],
                'sumber_info' => ['Keluarga / Saudara'],
                'alasan_minat' => 'Prospek kerja perbankan syariah dan akuntan publik',
                'ukuran_seragam' => 'M',
                'status' => 'terverifikasi',
                'catatan' => 'Verifikasi nilai matematika rapor semester 1-5 rata-rata 88.',
            ],
            [
                'nomor_registrasi' => 'PPDB-2027-72910',
                'gelombang_id' => $wave1->id,
                'nama_lengkap' => 'Bagas Prasetyo Utomo',
                'nama_panggilan' => 'Bagas',
                'nisn' => '0092819201',
                'nomor_kk' => '3201012010050001',
                'tempat_lahir' => 'Bogor',
                'tanggal_lahir_hari' => '08',
                'tanggal_lahir_bulan' => '6',
                'tanggal_lahir_tahun' => '2011',
                'jenis_kelamin' => 'L',
                'alamat_lengkap' => 'Jl. Kayumanis No. 23, Tanah Sareal, Kota Bogor',
                'sekolah_pilihan_level' => 'SMK',
                'sekolah_pilihan_unit' => 'SMK Plus Pelita Nusantara Bogor',
                'tipe_pendaftar' => 'Siswa Baru',
                'kelas_pilihan' => 'Kelas X (Sepuluh)',
                'jurusan' => 'Manajemen Perkantoran dan Layanan Bisnis (MPLB)',
                'jalur_seleksi' => 'Jalur Reguler',
                'asal_sekolah' => 'SMP Bina Bangsa Mandiri',
                'nomor_kontak_pendaftar' => '085819203948',
                'nomor_kontak_ortu' => '081283920194',
                'email' => 'bagas.prasetyo@gmail.com',
                'jenis_layanan' => ['Full Day School'],
                'sumber_info' => ['Teman / Alumni'],
                'alasan_minat' => 'Ingin mendalami administrasi perkantoran digital',
                'ukuran_seragam' => 'L',
                'status' => 'menunggu_verifikasi',
                'catatan' => 'Menunggu verifikasi pembayaran biaya pendaftaran.',
            ],
            [
                'nomor_registrasi' => 'PPDB-2027-83912',
                'gelombang_id' => $wave1->id,
                'nama_lengkap' => 'Kevin Christian Wijaya',
                'nama_panggilan' => 'Kevin',
                'nisn' => '0087381920',
                'nomor_kk' => '3201011112080003',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir_hari' => '17',
                'tanggal_lahir_bulan' => '1',
                'tanggal_lahir_tahun' => '2011',
                'jenis_kelamin' => 'L',
                'alamat_lengkap' => 'Komplek Acropolis Blok C3 No. 9, Karadenan, Cibinong',
                'sekolah_pilihan_level' => 'SMK',
                'sekolah_pilihan_unit' => 'SMK Plus Pelita Nusantara Bogor',
                'tipe_pendaftar' => 'Siswa Baru',
                'kelas_pilihan' => 'Kelas X (Sepuluh)',
                'jurusan' => 'Pengembangan Perangkat Lunak dan Gim (PPLG)',
                'jalur_seleksi' => 'Jalur Reguler',
                'asal_sekolah' => 'SMP Kristen Penabur Bogor',
                'nomor_kontak_pendaftar' => '081298492019',
                'nomor_kontak_ortu' => '08119830192',
                'email' => 'kevin.wijaya@gmail.com',
                'jenis_layanan' => ['Full Day School'],
                'sumber_info' => ['Media Sosial Instagram'],
                'alasan_minat' => 'Belajar web development & game programming',
                'ukuran_seragam' => 'M',
                'status' => 'lulus_seleksi',
                'catatan' => 'Lulus seleksi wawancara dan tes masuk.',
            ]
        ];

        foreach ($pendaftarData as $p) {
            PpdbRegistration::updateOrCreate(
                ['nomor_registrasi' => $p['nomor_registrasi']],
                $p
            );
        }
    }
}
