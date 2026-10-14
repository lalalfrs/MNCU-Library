# Spesifikasi Desain UI — Halaman Utama

Dokumen ini menjelaskan desain **halaman utama** aplikasi, mencakup tampilan desktop, mobile, navbar, dan sidebar. Dokumen ini ditujukan untuk developer sebagai acuan implementasi.

> **Catatan:** Teks dalam kurung siku seperti `[Nama Aplikasi]` adalah placeholder yang diisi sesuai kebutuhan.
>
> Nilai warna, ukuran, dan jarak di dokumen ini diambil dari screenshot desain, sehingga merupakan **estimasi**. Jika ada file desain asli (Figma, dsb.), nilai dari file tersebut yang dipakai.

---

## Daftar Isi

1. [Referensi Screenshot](#1-referensi-screenshot)
2. [Design Tokens](#2-design-tokens)
3. [Layout & Breakpoint](#3-layout--breakpoint)
4. [Navbar](#4-navbar)
5. [Sidebar (Drawer Menu)](#5-sidebar-drawer-menu)
6. [Page Header](#6-page-header)
7. [Kartu Statistik](#7-kartu-statistik)
8. [Jadwal Kuliah Hari Ini](#8-jadwal-kuliah-hari-ini)
9. [Presensi Mahasiswa & Kegiatan Perkuliahan](#9-presensi-mahasiswa--kegiatan-perkuliahan)
10. [Quick Action](#10-quick-action)
11. [Footer](#11-footer)
12. [State & Interaksi](#12-state--interaksi)
13. [Aksesibilitas](#13-aksesibilitas)
14. [Checklist Implementasi](#14-checklist-implementasi)

---

## 1. Referensi Screenshot

Simpan screenshot desain di folder `docs/screenshots/` dengan nama berikut agar gambar di bawah tampil:

| No | File | Keterangan |
|----|------|------------|
| 1 | `desktop-dashboard.png` | Tampilan umum dashboard (desktop) |
| 2 | `desktop-sidebar.png` | Sidebar / drawer menu (desktop) |
| 3 | `mobile-dashboard.png` | Tampilan umum dashboard (mobile) |
| 4 | `mobile-sidebar.png` | Sidebar / drawer menu (mobile) |

![Desktop Dashboard](./screenshots/desktop-dashboard.png)
![Desktop Sidebar](./screenshots/desktop-sidebar.png)
![Mobile Dashboard](./screenshots/mobile-dashboard.png)
![Mobile Sidebar](./screenshots/mobile-sidebar.png)

---

## 2. Design Tokens

### 2.1 Warna

#### Warna Utama

| Token | Hex (estimasi) | Penggunaan |
|-------|----------------|------------|
| `--color-primary` | `#0F2A6B` | Logo, judul aplikasi di navbar, nama user, judul sidebar, garis pemisah header |
| `--color-text` | `#111827` | Judul kartu, teks menu sidebar |
| `--color-text-secondary` | `#374151` | Deskripsi kartu, judul halaman |
| `--color-text-muted` | `#6B7280` | Label "Semester ini", ID pengguna, teks bantuan kecil |
| `--color-bg` | `#FFFFFF` | Background halaman & kartu |
| `--color-bg-subtle` | `#F8FAFC` | Background kotak empty state |
| `--color-border` | `#E5E7EB` | Border kartu |
| `--color-track` | `#EEF2F7` | Track progress bar (bagian kosong) |
| `--color-overlay` | `rgba(15, 23, 42, 0.45)` | Backdrop saat sidebar terbuka |

#### Warna Aksen per Komponen

| Komponen | Warna Teks/Link | Gradient Garis Bawah |
|----------|-----------------|----------------------|
| Total Mata Kuliah Diampu | `#7C3AED` (ungu) | `#8B5CF6 → #D946EF` |
| Total Mahasiswa Diajar | `#2563EB` (biru) | `#0EA5E9 → #2563EB` |
| Total SKS Diampu | `#059669` (hijau) | `#10B981 → #14B8A6` |
| Total Materi Diunggah | `#E11D48` (merah) | `#F43F5E → #EC4899` |
| Presensi Mahasiswa | `#7C3AED` (ungu) | `#7C3AED → #D946EF` |
| Kegiatan Perkuliahan | `#EA580C` (oranye) | `#F97316 → #F59E0B` |
| Quick Action — Input Nilai | `#7E3AA8` (ungu) | – |
| Quick Action — Kelola Materi | `#F05A3C` (coral) | – |
| Quick Action — Laporan BKD | `#5BB5C0` (teal) | – |

#### Badge

| Badge | Background | Border | Teks |
|-------|------------|--------|------|
| Total (Jadwal) | `#F0F9FF` | `#E0F2FE` | `#0369A1` |
| Kehadiran (Presensi) | `#F5F3FF` | `#EDE9FE` | `#7C3AED` |
| Terlaksana (Kegiatan) | `#FFF7ED` | `#FFEDD5` | `#EA580C` |

### 2.2 Tipografi

- **Font family:** `Lexend`, fallback `system-ui, -apple-system, sans-serif` (estimasi dari screenshot; konfirmasi ke desainer).
- Sumber: Google Fonts.

| Elemen | Ukuran Desktop | Ukuran Mobile | Weight |
|--------|----------------|---------------|--------|
| Judul aplikasi (navbar) | 22px | 16px | 500 |
| Judul halaman | 28px | 18px | 400 |
| Tahun akademik "2026/2027" | 24px | 16px | 600 |
| Semester "Semester Ganjil" | 18px | 14px | 500 |
| Judul section (mis. "Jadwal Kuliah Hari Ini") | 20px | 16px | 500 |
| Deskripsi section | 16px | 13px | 400 |
| Label kartu statistik | 14px | 12px | 400 |
| Angka kartu statistik | 36px | 30px | 500 |
| Angka badge (mis. "0%") | 24px | 18px | 600 |
| Label badge | 13px | 11px | 400 |
| Teks kecil / helper | 13px | 11px | 400 |
| Teks tombol | 16px | 14px | 600 |
| Label quick action | 22px | 18px | 500 |
| Item menu sidebar | 17px | 15px | 400 |
| Sub-menu sidebar | 16px | 14px | 400 |

### 2.3 Radius, Shadow & Spacing

| Token | Nilai | Penggunaan |
|-------|-------|------------|
| `--radius-sm` | 8px | Badge, kotak empty state |
| `--radius-md` | 12px | Kartu statistik, tombol |
| `--radius-lg` | 16px | Navbar, container section |
| `--radius-pill` | 999px | Progress bar, garis aksen |
| `--shadow-sm` | `0 1px 3px rgba(0,0,0,0.06)` | Kartu default |
| `--shadow-md` | `0 4px 14px rgba(0,0,0,0.08)` | Navbar, kartu saat hover |
| Skala spacing | 4, 8, 12, 16, 20, 24, 32, 40px | Padding & gap |

---

## 3. Layout & Breakpoint

### 3.1 Breakpoint

| Nama | Lebar | Keterangan |
|------|-------|------------|
| Mobile | < 768px | Semua konten 1 kolom |
| Tablet | 768px – 1023px | Kartu statistik 2 kolom, lainnya mengikuti desktop jika muat |
| Desktop | ≥ 1024px | Layout penuh seperti screenshot 1 |

### 3.2 Struktur Halaman (Desktop)

```
┌──────────────────────────────────────────────────────────────┐
│ NAVBAR  [☰] [Logo] [Nama Aplikasi]               [User ▾]   │
└──────────────────────────────────────────────────────────────┘
  [Judul Halaman]                              📅 2026/2027
                                               Semester Ganjil
  ────────────────────────────────────────────────────────────
  ┌────────────────────────────────────────────────────────┐
  │ [Stat 1]   [Stat 2]   [Stat 3]   [Stat 4]              │
  └────────────────────────────────────────────────────────┘
  ┌────────────────────────────────────────────────────────┐
  │ Jadwal Kuliah Hari Ini                        [Total]  │
  │ [ empty state / daftar jadwal ]                        │
  └────────────────────────────────────────────────────────┘
  ┌───────────────────────────┐ ┌──────────────────────────┐
  │ Presensi Mahasiswa        │ │ Kegiatan Perkuliahan     │
  └───────────────────────────┘ └──────────────────────────┘
  ┌──────────────┐ ┌──────────────┐ ┌──────────────┐
  │ Input Nilai  │ │ Kelola Materi│ │ Laporan BKD  │
  └──────────────┘ └──────────────┘ └──────────────┘
──────────────────────────────────────────────────────────────
  FOOTER: 2026 © Hak Cipta [Nama Institusi].
```

### 3.3 Aturan Kontainer

| Properti | Desktop | Mobile |
|----------|---------|--------|
| Navbar margin dari tepi layar | 24px | 12px |
| Padding horizontal page header | 50px | 16px |
| Lebar maks konten (kartu) | 1570px, center | 100% − 24px |
| Gap antar section | 40px | 32px |
| Grid kartu statistik | 4 kolom, gap 20px | 1 kolom, gap 16px |
| Grid Presensi & Kegiatan | 2 kolom, gap 30px | 1 kolom, gap 24px |
| Grid quick action | 3 kolom, gap 30px | 1 kolom, gap 20px |

---

## 4. Navbar

Navbar berbentuk **floating card**: tidak menempel penuh ke tepi layar, punya margin, sudut membulat, dan bayangan halus.

### 4.1 Desktop

```
┌──────────────────────────────────────────────────────────────────┐
│ ☰   [Logo] [Nama Aplikasi]                [Nama Pengguna] (👤) ▾ │
│                                           [ID Pengguna]         │
└──────────────────────────────────────────────────────────────────┘
```

| Properti | Nilai |
|----------|-------|
| Tinggi | 70px |
| Background | `#FFFFFF` |
| Radius | 16px |
| Shadow | `--shadow-md` |
| Padding horizontal | 28px |
| Posisi | `sticky`, `top: 16px`, `z-index: 40` |

**Sisi kiri (urutan):**

1. **Tombol hamburger** (☰) — ikon 24px, warna `--color-primary`, area klik minimal 40×40px. Membuka sidebar.
2. **Logo** aplikasi — tinggi 32px.
3. **Judul aplikasi** — `[Nama Aplikasi]`, warna `--color-primary`, 22px. Gap logo–judul 12px.

**Sisi kanan (urutan):**

1. **Blok identitas user** (rata kanan):
   - Nama pengguna — 16px, weight 500, warna `--color-primary`.
   - ID pengguna (mis. nomor induk) — 14px, warna `--color-text-muted`.
2. **Avatar** — lingkaran 48px, border 2px `#E5E7EB`. Jika belum ada foto, pakai avatar placeholder abu-abu.
3. **Chevron** (▾) — 16px, warna `--color-text-muted`. Menandakan dropdown.

Seluruh blok kanan (nama + avatar + chevron) adalah **satu tombol** yang membuka dropdown user.

### 4.2 Mobile

```
┌─────────────────────────────────┐
│ ☰  [Logo] [Nama Singkat]   (👤) │
└─────────────────────────────────┘
```

| Perubahan dari desktop | Nilai |
|------------------------|-------|
| Tinggi | 56px |
| Padding horizontal | 16px |
| Judul aplikasi | Diganti versi singkat **`[Nama Singkat Aplikasi]`**, 16px |
| Logo | Tinggi 26px |
| Nama & ID pengguna | **Disembunyikan** |
| Chevron | **Disembunyikan** |
| Avatar | 40px, tetap bisa diklik untuk membuka dropdown |

### 4.3 Dropdown User

Isi dropdown tidak terlihat di screenshot. Usulan berikut mengikuti gaya desain yang sama dan **perlu dikonfirmasi**:

```
┌──────────────────────────┐
│ [Nama Pengguna]          │
│ [ID Pengguna]            │
├──────────────────────────┤
│ 👤 Profil Saya           │
│ 🔑 Ubah Password         │
├──────────────────────────┤
│ ⎋  Keluar                │  ← warna merah #E11D48
└──────────────────────────┘
```

| Properti | Nilai |
|----------|-------|
| Lebar | 240px |
| Posisi | Di bawah tombol user, rata kanan, jarak 8px |
| Background | `#FFFFFF` |
| Radius | 12px |
| Shadow | `--shadow-md` + border 1px `--color-border` |
| Tinggi item | 44px, padding 12px 16px |
| Hover item | Background `#F3F4F6` |
| Mobile | Header dropdown menampilkan nama & ID pengguna (karena di navbar disembunyikan) |

Perilaku: tertutup saat klik di luar, tekan `Esc`, atau memilih item. Chevron berputar 180° saat terbuka.

---

## 5. Sidebar (Drawer Menu)

Sidebar adalah **drawer off-canvas** yang muncul dari kiri saat tombol hamburger diklik, baik di desktop maupun mobile. Konten halaman tertutup backdrop gelap transparan.

### 5.1 Struktur

```
┌───────────────────────────┐
│                       ✕   │
│          (logo)           │
│       [Judul Menu]        │
│ ───────────────────────── │
│ 📊 Dashboard              │
│ 📅 Jadwal Perkuliahan     │
│ 👩‍🏫 Perkuliahan        ⌃  │
│    • Input RPS            │
│    • Kelola Materi & Presensi │
│    • Input Nilai          │
│    • Forum Perkuliahan    │
│ 📋 Generate Laporan BKD   │
│ ✅ Review & Approve KRS   │
│ 👥 Layanan Mahasiswa   ⌄  │
│ ⭐ Poin Beasiswa Mhs   ⌄  │
│ 📄 Lihat Transkrip Mhs    │
│ 🎓 Skripsi Mahasiswa   ⌄  │
│ 🎓 Data Mahasiswa         │
│ 📢 Pengumuman & Info Akad │
│ ...                       │
└───────────────────────────┘
```

### 5.2 Ukuran & Gaya

| Properti | Desktop | Mobile |
|----------|---------|--------|
| Lebar | 400px | 82% layar (maks 320px) |
| Tinggi | 100vh | 100vh |
| Background | `#FFFFFF` | `#FFFFFF` |
| Shadow | `4px 0 24px rgba(0,0,0,0.12)` | sama |
| Scroll | Vertikal di dalam sidebar (`overflow-y: auto`) | sama |
| Backdrop | `--color-overlay` menutupi seluruh halaman | sama |

### 5.3 Header Sidebar

| Elemen | Spesifikasi |
|--------|-------------|
| Tombol tutup (✕) | Pojok kanan atas, ikon 22px, warna `#6B7280`, area klik 40×40px |
| Logo | Center, tinggi 110px (desktop) / 80px (mobile) |
| Judul | `[Judul Menu]`, center, 20px (desktop) / 17px (mobile), weight 600, warna `--color-text` |
| Garis pemisah | 1px, warna `#2563EB`, full width, margin bawah 24px |

### 5.4 Daftar Menu

| No | Menu | Tipe | Sub-menu |
|----|------|------|----------|
| 1 | Dashboard | Link | – |
| 2 | Jadwal Perkuliahan | Link | – |
| 3 | Perkuliahan | Grup (collapsible) | Input RPS, Kelola Materi & Presensi, Input Nilai, Forum Perkuliahan |
| 4 | Generate Laporan BKD | Link | – |
| 5 | Review & Approve KRS | Link | – |
| 6 | Layanan Mahasiswa | Grup (collapsible) | *Belum terlihat di desain — perlu dikonfirmasi* |
| 7 | Poin Beasiswa Mahasiswa | Grup (collapsible) | *Belum terlihat di desain — perlu dikonfirmasi* |
| 8 | Lihat Transkrip Mahasiswa | Link | – |
| 9 | Skripsi Mahasiswa | Grup (collapsible) | *Belum terlihat di desain — perlu dikonfirmasi* |
| 10 | Data Mahasiswa | Link | – |
| 11 | Pengumuman & Informasi Akademik | Link | – |
| 12 | *(menu berikutnya terpotong di screenshot — perlu dikonfirmasi)* | – | – |

### 5.5 Gaya Item Menu

| Properti | Item Utama | Sub-menu |
|----------|------------|----------|
| Tinggi | 52px (desktop) / 46px (mobile) | 44px / 40px |
| Padding kiri | 28px / 22px | 80px / 62px (sejajar teks item utama) |
| Ikon | Ilustrasi berwarna, 34×34px / 28×28px | Bullet "•" 6px, warna `--color-text` |
| Gap ikon–teks | 16px | 10px |
| Warna teks | `--color-text` | `#4B5563` |
| Chevron grup | Kanan, 18px. ⌄ saat tertutup, ⌃ saat terbuka (rotasi 180°, transisi 200ms) | – |
| Hover | Background `#F3F4F6` | Teks `--color-primary` |
| Aktif (halaman saat ini) | Background `#EEF2FF`, teks `--color-primary` weight 500, garis kiri 3px `--color-primary` | Teks `--color-primary`, weight 500 |

### 5.6 Perilaku

- **Buka:** klik hamburger → sidebar slide dari kiri (`transform: translateX(-100%) → 0`, 250ms ease-out), backdrop fade in.
- **Tutup:** klik ✕, klik backdrop, tekan `Esc`, atau klik item menu (link).
- **Grup collapsible:** klik baris grup untuk buka/tutup. Grup yang berisi halaman aktif otomatis terbuka saat sidebar dibuka.
- **Scroll body dikunci** (`overflow: hidden` pada `body`) selama sidebar terbuka.
- **Fokus keyboard** berpindah ke tombol ✕ saat sidebar terbuka, dan kembali ke tombol hamburger saat ditutup.

---

## 6. Page Header

```
[Judul Halaman]                                  📅 2026/2027
                                                 Semester Ganjil
────────────────────────────────────────────────────────────────
```

| Elemen | Spesifikasi |
|--------|-------------|
| Judul halaman | Kiri, `--color-text-secondary` |
| Ikon kalender | 22px, warna `#6366F1`, di kiri tahun akademik |
| Tahun akademik | Kanan, weight 600, `--color-text` |
| Semester | Kanan, di bawah tahun, rata kanan |
| Garis bawah | 2px, `--color-primary`, margin atas 24px |
| Margin atas dari navbar | 32px (desktop) / 24px (mobile) |

**Data dinamis:** tahun akademik & semester diambil dari periode akademik aktif.

---

## 7. Kartu Statistik

Empat kartu dibungkus satu **container** (border 1px, radius 16px, padding 24px, background putih).

### 7.1 Anatomi Kartu

```
┌────────────────────────────────────┐
│ [ikon]  Total Mata Kuliah Diampu   │
│         0                          │
│         Semester ini               │
│ ────────────────────────────────── │
│ Detail                    Lihat  › │
│ ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬ │  ← garis gradient
└────────────────────────────────────┘
```

| Elemen | Spesifikasi |
|--------|-------------|
| Kartu | Border 1px `--color-border`, radius 12px, padding 20px, background putih |
| Ikon | Kotak 60×60px, radius 12px, background lembut sesuai warna aksen (opacity ~8%), ilustrasi 36px di tengah |
| Label | Kanan ikon, gap 16px |
| Angka | Di bawah label |
| Keterangan | "Semester ini", `--color-text-muted` |
| Pemisah | 1px `--color-border`, margin vertikal 16px |
| Baris aksi | "Detail" (kiri, `--color-text`) dan "Lihat ›" (kanan, warna aksen, weight 500) |
| Garis aksen | Tinggi 4px, radius pill, gradient sesuai tabel warna, margin atas 12px |

### 7.2 Data Kartu

| Kartu | Label | Sumber Data | Link "Lihat" |
|-------|-------|-------------|--------------|
| 1 | Total Mata Kuliah Diampu | Jumlah mata kuliah yang diampu pengguna di semester aktif | Daftar mata kuliah |
| 2 | Total Mahasiswa Diajar | Jumlah mahasiswa unik di seluruh kelas pengguna | Daftar mahasiswa |
| 3 | Total SKS Diampu | Total SKS dari mata kuliah yang diampu | Rincian SKS |
| 4 | Total Materi Diunggah | Jumlah materi yang diunggah semester ini | Kelola Materi |

### 7.3 State Hover

Pada screenshot desktop, kartu ke-3 menunjukkan **state hover**:
- Border berubah ke warna aksen kartu (mis. `#A7F3D0` untuk hijau).
- Shadow naik ke `--shadow-md`.
- Opsional: `transform: translateY(-2px)`, transisi 200ms.

Seluruh kartu dapat diklik (bukan hanya teks "Lihat").

---

## 8. Jadwal Kuliah Hari Ini

```
┌────────────────────────────────────────────────────────┐
│ Jadwal Kuliah Hari Ini                       ┌───────┐ │
│ Ringkasan jadwal perkuliahan untuk hari ini. │ Total │ │
│                                              │   0   │ │
│                                              └───────┘ │
│ ┌────────────────────────────────────────────────────┐ │
│ │        Tidak ada jadwal kuliah untuk hari ini.     │ │
│ │          Nikmati waktunya — cek lagi besok ya.     │ │
│ └────────────────────────────────────────────────────┘ │
│ ──────────────────────────────────────────────────────  │ ← garis tipis
└────────────────────────────────────────────────────────┘
```

| Elemen | Spesifikasi |
|--------|-------------|
| Container | Border 1px, radius 16px, padding 30px (desktop) / 20px (mobile) |
| Judul & deskripsi | Kiri atas |
| Badge "Total" | Kanan atas, ukuran ~64×64px, berisi label "Total" dan angka |
| Empty state | Kotak background `--color-bg-subtle`, border 1px, radius 8px, padding 24px, teks center. Baris 1: 16px `--color-text`. Baris 2: 13px `--color-text-muted` |
| Garis bawah | 4px, warna `--color-track`, radius pill, margin atas 20px |

### 8.1 State Berisi Data (usulan, perlu dikonfirmasi)

Desain hanya menampilkan empty state. Saat ada jadwal, tampilkan list item dengan gaya serupa:

```
┌────────────────────────────────────────────────────┐
│ 08:00 – 10:30   Pemrograman Web Lanjut     [Masuk] │
│                 Kelas A · Ruang 3.04 · 3 SKS       │
└────────────────────────────────────────────────────┘
```

- Satu item per jadwal, background putih, border 1px, radius 8px, gap 12px.
- Badge "Total" menampilkan jumlah jadwal hari ini.
- Garis bawah dapat berfungsi sebagai progress (jumlah kelas yang sudah selesai ÷ total kelas hari ini).

---

## 9. Presensi Mahasiswa & Kegiatan Perkuliahan

Dua kartu berdampingan di desktop, bertumpuk di mobile. Strukturnya sama, hanya warna aksen berbeda.

```
┌───────────────────────────────────────────────┐
│ Presensi Mahasiswa                ┌─────────┐ │
│ Persentase kehadiran mahasiswa... │Kehadiran│ │
│                                   │   0%    │ │
│                                   └─────────┘ │
│ Progress                                   0% │
│ ▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭▭ │
│ Belum ada mahasiswa yang melakukan presensi   │
│                                               │
│ [Rekap Presensi]       Lihat ringkasan & detail │
│ ▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬▬ │
└───────────────────────────────────────────────┘
```

| Elemen | Spesifikasi |
|--------|-------------|
| Kartu | Border 1px, radius 16px, padding 30px (desktop) / 22px (mobile) |
| Kartu Kegiatan | Border 1px `#FED7AA` (oranye muda) + shadow tipis oranye |
| Badge persentase | Kanan atas, lihat tabel Badge |
| Progress bar | Tinggi 8px, track `--color-track`, isi warna aksen, radius pill |
| Teks status | 13px, `--color-text-muted`, di bawah progress bar |
| Tombol utama | Tinggi 48px, padding 0 20px, radius 10px, teks putih weight 600 |
| Teks pendukung | Kanan, sejajar tombol, 14px, `--color-text-muted` |
| Garis aksen bawah | 4px, gradient sesuai warna kartu |

### 9.1 Data

| Kartu | Badge | Rumus Persentase | Tombol | Teks Kosong |
|-------|-------|------------------|--------|-------------|
| Presensi Mahasiswa | Kehadiran | Total kehadiran ÷ total presensi seharusnya × 100 | **Rekap Presensi** (`#7C3AED`) | "Belum ada mahasiswa yang melakukan presensi" |
| Kegiatan Perkuliahan | Terlaksana | Pertemuan terlaksana ÷ total pertemuan (s.d. pertemuan akhir) × 100 | **Lihat Detail** (`#EA580C`) | "Belum ada kegiatan perkuliahan" |

Saat data ada, teks status diganti dengan ringkasan, misalnya "128 dari 150 kehadiran tercatat" atau "6 dari 16 pertemuan terlaksana".

### 9.2 Hover Tombol

- Background lebih gelap ~8% (ungu `#6D28D9`, oranye `#C2410C`).
- Transisi 150ms.

---

## 10. Quick Action

Tiga kartu pintasan di bagian bawah.

```
┌──────────────┐ ┌──────────────┐ ┌──────────────┐
│     📝       │ │     🖥️       │ │     📄       │
│ Input Nilai  │ │ Kelola Materi│ │ Laporan BKD  │
└──────────────┘ └──────────────┘ └──────────────┘
```

| Elemen | Spesifikasi |
|--------|-------------|
| Kartu | Tinggi 155px (desktop) / 130px (mobile), border 1px, radius 12px, shadow `--shadow-sm`, konten center |
| Ikon | Ilustrasi 52px, margin bawah 12px |
| Label | Warna sesuai tabel warna aksen |
| Hover | Shadow `--shadow-md`, `translateY(-2px)` |

| Kartu | Tujuan |
|-------|--------|
| Input Nilai | Halaman Perkuliahan → Input Nilai |
| Kelola Materi | Halaman Perkuliahan → Kelola Materi & Presensi |
| Laporan BKD | Halaman Generate Laporan BKD |

Gunakan elemen `<a>` agar seluruh kartu bisa diklik dan bisa dibuka di tab baru.

---

## 11. Footer

| Properti | Nilai |
|----------|-------|
| Teks | "2026 © Hak Cipta [Nama Institusi]." (tahun dinamis) |
| Perataan | Center |
| Ukuran | 16px (desktop) / 13px (mobile) |
| Warna | `--color-text-muted` |
| Background | `#FFFFFF` |
| Border atas | 1px `--color-border` |
| Padding | 20px vertikal |
| Margin atas | 32px dari konten terakhir |

---

## 12. State & Interaksi

### 12.1 Ringkasan State

| Komponen | Loading | Kosong | Berisi | Error |
|----------|---------|--------|--------|-------|
| Kartu statistik | Skeleton pada angka | Tampilkan `0` | Angka | Tampilkan `–` + tooltip "Gagal memuat" |
| Jadwal hari ini | Skeleton 2 baris | Empty state (lihat §8) | List jadwal | Pesan error + tombol "Coba lagi" |
| Presensi / Kegiatan | Skeleton progress bar | `0%` + teks kosong | Persentase + ringkasan | `–` + pesan error |

### 12.2 Animasi

| Interaksi | Durasi | Easing |
|-----------|--------|--------|
| Sidebar buka/tutup | 250ms | ease-out |
| Backdrop fade | 200ms | ease |
| Chevron rotasi | 200ms | ease |
| Hover kartu | 200ms | ease |
| Isi progress bar (saat load) | 600ms | ease-out |

Hormati preferensi `prefers-reduced-motion: reduce` dengan menonaktifkan animasi transform.

---

## 13. Aksesibilitas

- Tombol hamburger: `aria-label="Buka menu"`, `aria-expanded`, `aria-controls="sidebar"`.
- Tombol ✕: `aria-label="Tutup menu"`.
- Sidebar: `role="dialog"`, `aria-modal="true"`, fokus terjebak di dalam sidebar saat terbuka.
- Grup menu collapsible: tombol dengan `aria-expanded`.
- Tombol user: `aria-haspopup="menu"`, `aria-expanded`.
- Progress bar: `role="progressbar"` dengan `aria-valuenow`, `aria-valuemin="0"`, `aria-valuemax="100"`.
- Ikon dekoratif: `alt=""` atau `aria-hidden="true"`.
- Kontras: label quick action teal (`#5BB5C0`) di atas putih kontrasnya rendah. Pertimbangkan versi lebih gelap (`#3B9AA6`) untuk teks.
- Area klik minimal 40×40px untuk semua elemen interaktif di mobile.

---

## 14. Checklist Implementasi

- [ ] Design tokens (warna, font, radius, shadow) didefinisikan sebagai CSS variables / config Tailwind
- [ ] Font Lexend dimuat
- [ ] Navbar desktop & mobile (judul singkat, nama/ID pengguna tersembunyi di mobile)
- [ ] Dropdown user (konfirmasi isi menu)
- [ ] Sidebar drawer + backdrop + grup collapsible + state aktif
- [ ] Konfirmasi sub-menu: Layanan Mahasiswa, Poin Beasiswa Mahasiswa, Skripsi Mahasiswa, dan menu di bawah "Pengumuman & Informasi Akademik"
- [ ] Page header dengan tahun akademik & semester dinamis
- [ ] 4 kartu statistik + hover state + link
- [ ] Jadwal kuliah hari ini (empty state & state berisi)
- [ ] Kartu Presensi & Kegiatan dengan progress bar
- [ ] 3 kartu quick action
- [ ] Footer dengan tahun dinamis
- [ ] Responsif: mobile, tablet, desktop
- [ ] Loading, empty, dan error state
- [ ] Aksesibilitas (ARIA, fokus keyboard, `Esc`)
- [ ] Uji di Chrome, Safari (iOS), dan Firefox
