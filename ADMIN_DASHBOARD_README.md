# Dashboard Admin Smart Hospital

## Deskripsi
Dashboard admin untuk sistem Smart Hospital yang menyediakan tampilan komprehensif untuk mengelola rumah sakit. Dashboard ini dirancang dengan modern UI/UX dan terintegrasi dengan Barba.js untuk transisi halaman yang mulus.

## Fitur Utama

### 1. Statistik Real-time
- **Jumlah Pasien Hari Ini**: Menampilkan total pasien yang terdaftar hari ini
- **Dokter Aktif**: Menampilkan jumlah dokter yang sedang online/praktik
- **Janji Temu Hari Ini**: Menampilkan total janji temu yang dijadwalkan
- **Pendapatan Hari Ini**: Menampilkan total pendapatan rumah sakit hari ini

### 2. List Dokter yang Hadir
- Menampilkan daftar dokter yang sedang online
- Informasi spesialisasi dokter
- Status online dengan indikator visual
- Avatar dengan inisial dokter

### 3. Aktivitas Terbaru
- Timeline aktivitas sistem
- Kategori aktivitas (janji temu, pasien, dokter, pembayaran, sistem)
- Timestamp untuk setiap aktivitas
- Icon yang sesuai dengan jenis aktivitas

### 4. Grafik dan Visualisasi
- **Grafik Pasien**: Line chart untuk statistik pasien 7 hari terakhir
- **Grafik Pendapatan**: Bar chart untuk pendapatan 7 hari terakhir
- Responsive dan interaktif

### 5. Aksi Cepat
- Tambah Dokter: Navigasi ke halaman pendaftaran dokter
- Laporan Harian: Akses ke laporan harian
- Pengaturan: Konfigurasi sistem

## Struktur File

```
resources/views/admins/dashboard/
├── beranda.blade.php          # Template utama dashboard

public/
├── js/
│   └── admin-dashboard.js     # JavaScript untuk dashboard
└── css/
    └── admin-dashboard.css    # CSS custom untuk dashboard
```

## Teknologi yang Digunakan

### Frontend
- **Tailwind CSS**: Framework CSS untuk styling
- **Chart.js**: Library untuk grafik dan visualisasi
- **Barba.js**: Library untuk transisi halaman SPA-like
- **Alpine.js**: Framework JavaScript reaktif

### Backend
- **Laravel**: Framework PHP
- **Blade**: Template engine Laravel

## Integrasi Barba.js

Dashboard ini terintegrasi dengan Barba.js untuk memberikan pengalaman SPA (Single Page Application):

### Hooks yang Digunakan
- `barba.hooks.before()`: Cleanup sebelum transisi halaman
- `barba.hooks.after()`: Setup setelah transisi halaman
- `barba.hooks.beforeEnter()`: Reset state sebelum masuk ke dashboard

### Fitur Barba.js
- Transisi halaman yang mulus
- State management yang proper
- Cleanup chart dan event listener
- Auto-refresh data setiap 5 menit

## API Endpoints (Simulated)

Saat ini dashboard menggunakan data simulasi. Untuk implementasi nyata, gunakan endpoint berikut:

### Statistik
```javascript
// GET /api/admin/dashboard/statistics
{
  "patientsToday": 45,
  "activeDoctors": 12,
  "appointments": 23,
  "revenue": 8500000
}
```

### Dokter Aktif
```javascript
// GET /api/admin/dashboard/doctors
[
  {
    "name": "Dr. Sarah Johnson",
    "specialty": "Kardiologi",
    "status": "online",
    "avatar": "SJ"
  }
]
```

### Aktivitas Terbaru
```javascript
// GET /api/admin/dashboard/activities
[
  {
    "type": "appointment",
    "message": "Janji temu baru dengan Dr. Sarah Johnson",
    "time": "5 menit yang lalu"
  }
]
```

## Styling dan Komponen

### CSS Classes Utama
- `.dashboard-card`: Card utama dengan hover effect
- `.stat-card`: Card statistik dengan gradient border
- `.doctor-item`: Item dokter dengan hover effect
- `.activity-item`: Item aktivitas
- `.chart-container`: Container untuk grafik
- `.quick-action-btn`: Button aksi cepat

### Animasi
- Hover effects pada cards
- Pulse animation untuk status online
- Smooth transitions
- Loading spinners

### Responsive Design
- Mobile-first approach
- Grid layout yang adaptif
- Chart yang responsive
- Sidebar yang collapsible

## Penggunaan

### 1. Akses Dashboard
```php
// Route
Route::get('/admin/dashboard', [AdminsDashboardController::class, 'index'])
    ->name('admins.dashboard');
```

### 2. Controller
```php
public function index()
{
    return view('admins.dashboard.beranda');
}
```

### 3. Middleware
```php
// Pastikan user adalah admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminsDashboardController::class, 'index']);
});
```

## Konfigurasi

### 1. Auto-refresh
```javascript
// Set interval refresh (default: 5 menit)
setInterval(() => {
    this.loadDashboardData();
}, 300000);
```

### 2. Chart Configuration
```javascript
// Chart.js options
options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false }
    }
}
```

### 3. Barba.js Configuration
```javascript
// Setup hooks
barba.hooks.after(() => {
    if (window.location.pathname.includes('/admin/dashboard')) {
        this.setup();
    }
});
```

## Troubleshooting

### 1. Chart Tidak Muncul
- Pastikan Chart.js sudah dimuat
- Cek console untuk error JavaScript
- Pastikan canvas element ada di DOM

### 2. Data Tidak Update
- Cek network tab untuk API calls
- Pastikan endpoint API berfungsi
- Cek console untuk error

### 3. Barba.js Tidak Bekerja
- Pastikan Barba.js sudah dimuat
- Cek konfigurasi hooks
- Pastikan route pattern sesuai

## Pengembangan Selanjutnya

### 1. Real-time Updates
- Implementasi WebSocket untuk update real-time
- Live notifications
- Auto-refresh yang lebih cerdas

### 2. Filtering dan Search
- Filter berdasarkan tanggal
- Search dokter/pasien
- Export data

### 3. Advanced Analytics
- Prediksi trend
- Machine learning insights
- Custom reports

### 4. Mobile App
- Progressive Web App (PWA)
- Native mobile app
- Offline support

## Kontribusi

Untuk berkontribusi pada pengembangan dashboard admin:

1. Fork repository
2. Buat branch fitur baru
3. Implementasi perubahan
4. Test thoroughly
5. Submit pull request

## Lisensi

Proyek ini menggunakan lisensi MIT. Lihat file LICENSE untuk detail lebih lanjut. 