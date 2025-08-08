# Admin Dashboard Database Integration

## Overview
Dashboard admin rumah sakit telah berhasil diintegrasikan dengan database Laravel menggunakan Blade templates. Dashboard ini menampilkan data real-time dari database termasuk statistik pasien, kehadiran dokter, pendapatan, dan aktivitas sistem.

## Database Structure

### Tables Created

#### 1. `appointments` - Tabel Janji Temu
- `id` - Primary Key
- `patient_id` - Foreign Key ke patients
- `doctor_id` - Foreign Key ke doctors
- `appointment_date` - Tanggal dan waktu janji temu
- `status` - Status janji temu (scheduled, in_progress, completed, cancelled)
- `notes` - Catatan janji temu
- `fee` - Biaya janji temu
- `created_at`, `updated_at` - Timestamps

#### 2. `doctor_attendance` - Tabel Kehadiran Dokter
- `id` - Primary Key
- `doctor_id` - Foreign Key ke doctors
- `date` - Tanggal kehadiran
- `check_in_time` - Waktu check-in
- `check_out_time` - Waktu check-out
- `status` - Status kehadiran (present, absent, late, half_day)
- `notes` - Catatan kehadiran
- `created_at`, `updated_at` - Timestamps

#### 3. `revenue` - Tabel Pendapatan
- `id` - Primary Key
- `date` - Tanggal pendapatan
- `total_revenue` - Total pendapatan hari itu
- `appointment_count` - Jumlah janji temu
- `consultation_fees` - Biaya konsultasi
- `medication_fees` - Biaya obat
- `procedure_fees` - Biaya prosedur
- `notes` - Catatan pendapatan
- `created_at`, `updated_at` - Timestamps

#### 4. `activities` - Tabel Aktivitas Sistem
- `id` - Primary Key
- `user_type` - Jenis user (admin, doctor, patient)
- `user_id` - ID user
- `action` - Aksi yang dilakukan
- `description` - Deskripsi aktivitas
- `ip_address` - IP address
- `metadata` - Data tambahan (JSON)
- `created_at`, `updated_at` - Timestamps

## Models

### 1. Appointment Model
```php
class Appointment extends Model
{
    // Relationships
    public function patient() { return $this->belongsTo(Patient::class); }
    public function doctor() { return $this->belongsTo(Doctor::class); }
    
    // Scopes
    public function scopeToday($query) { /* ... */ }
    public function scopeThisWeek($query) { /* ... */ }
    public function scopeThisMonth($query) { /* ... */ }
}
```

### 2. DoctorAttendance Model
```php
class DoctorAttendance extends Model
{
    protected $table = 'doctor_attendance';
    
    // Relationships
    public function doctor() { return $this->belongsTo(Doctor::class); }
    
    // Scopes
    public function scopeToday($query) { /* ... */ }
    public function scopePresent($query) { /* ... */ }
}
```

### 3. Revenue Model
```php
class Revenue extends Model
{
    protected $table = 'revenue';
    
    // Scopes
    public function scopeToday($query) { /* ... */ }
    public function scopeThisWeek($query) { /* ... */ }
    public function scopeThisMonth($query) { /* ... */ }
}
```

### 4. Activity Model
```php
class Activity extends Model
{
    // Polymorphic relationships
    public function user() { /* Returns appropriate user type */ }
    public function admin() { return $this->belongsTo(Admin::class, 'user_id'); }
    public function doctor() { return $this->belongsTo(Doctor::class, 'user_id'); }
    public function patient() { return $this->belongsTo(Patient::class, 'user_id'); }
    
    // Helper method
    public static function log($userType, $userId, $action, $description, $metadata = null, $ipAddress = null)
}
```

## Updated Models

### Doctor Model
- Added relationships: `appointments()`, `attendance()`, `todayAttendance()`
- Added scope: `active()`

### Patient Model
- Added relationship: `appointments()`
- Added scope: `todayAppointments()`

## Seeders

### 1. AppointmentSeeder
- Membuat janji temu untuk hari ini, kemarin, dan minggu ini
- Status bervariasi: completed, in_progress, scheduled
- Biaya bervariasi sesuai jenis layanan

### 2. DoctorAttendanceSeeder
- Data kehadiran dokter hari ini dan hari-hari sebelumnya
- Status: present, absent, late, half_day
- Waktu check-in dan check-out yang realistis

### 3. RevenueSeeder
- Data pendapatan harian untuk 30+ hari
- Breakdown: consultation_fees, medication_fees, procedure_fees
- Total revenue otomatis dihitung

### 4. ActivitySeeder
- Log aktivitas untuk admin, dokter, dan pasien
- Aktivitas: login, check-in, view_dashboard, book_appointment, dll
- Timestamp realistis dalam 7 hari terakhir

## Controller Updates

### AdminsDashboardController
```php
public function index() {
    // Statistics
    $statistics = [
        'patients_today' => Appointment::today()->distinct('patient_id')->count(),
        'active_doctors' => DoctorAttendance::today()->present()->count(),
        'appointments_today' => Appointment::today()->count(),
        'revenue_today' => Revenue::today()->first()?->total_revenue ?? 0,
    ];

    // Present doctors
    $presentDoctors = Doctor::with(['todayAttendance'])
        ->whereHas('attendance', function($query) use ($today) {
            $query->whereDate('date', $today->toDateString())
                  ->whereIn('status', ['present', 'late']);
        })->get();

    // Recent activities
    $recentActivities = Activity::orderBy('created_at', 'desc')->limit(10)->get();

    // Chart data
    $revenueData = Revenue::whereBetween('date', [/* 7 days */])->get();
    $patientStats = [/* 7 days patient count */];

    return view('admins.dashboard.beranda', compact(/* ... */));
}
```

## View Updates

### Blade Template (`resources/views/admins/dashboard/beranda.blade.php`)
- Menggunakan data real dari database
- Statistik cards menampilkan data aktual
- List dokter yang hadir dari database
- Aktivitas terbaru dari tabel activities
- Charts menggunakan data revenue dan patient stats

### JavaScript Integration
- Data PHP dikirim ke JavaScript via `window.dashboardData`
- Charts menggunakan data real dari database
- Auto-refresh setiap 5 menit (logical, bukan data refresh)

## Features Implemented

### 1. Real-time Statistics
- **Pasien Hari Ini**: Jumlah pasien unik yang memiliki janji temu hari ini
- **Dokter Aktif**: Jumlah dokter yang hadir hari ini
- **Janji Temu Hari Ini**: Total janji temu hari ini
- **Pendapatan Hari Ini**: Total pendapatan hari ini

### 2. Doctor Attendance
- Menampilkan dokter yang hadir hari ini
- Status online dengan indikator visual
- Waktu check-in ditampilkan
- Foto profil dokter (jika ada)

### 3. Activity Logs
- Aktivitas terbaru dari semua user types
- Icon berbeda untuk setiap jenis user
- Timestamp relatif (e.g., "5 menit yang lalu")
- Deskripsi aktivitas yang informatif

### 4. Charts
- **Statistik Pasien**: Line chart 7 hari terakhir
- **Pendapatan**: Bar chart 7 hari terakhir
- Data real dari database
- Responsive dan interaktif

### 5. Quick Actions
- Tambah Dokter (placeholder)
- Laporan Harian (placeholder)
- Pengaturan (placeholder)
- Notifikasi toast untuk feedback

## Database Commands

### Setup Database
```bash
# Reset dan seed database
php artisan migrate:fresh --seed

# Hanya jalankan seeder tertentu
php artisan db:seed --class=AppointmentSeeder
php artisan db:seed --class=DoctorAttendanceSeeder
php artisan db:seed --class=RevenueSeeder
php artisan db:seed --class=ActivitySeeder
```

### Build Assets
```bash
# Build untuk production
npm run build

# Development mode
npm run dev
```

## Sample Data

### Default Credentials
- **Admin**: admin@smarthospital.com / password
- **Doctor**: sarah.johnson@smarthospital.com / password
- **Patient**: john.doe@email.com / password

### Sample Statistics (Today)
- Pasien: 3-5 pasien
- Dokter Aktif: 2-3 dokter
- Janji Temu: 5 janji temu
- Pendapatan: ~Rp 820.000

## Future Enhancements

### 1. Real-time Updates
- Implementasi WebSockets untuk update real-time
- Live notifications untuk aktivitas baru
- Auto-refresh data tanpa reload halaman

### 2. Advanced Analytics
- Trend analysis untuk pendapatan
- Predictive analytics untuk jumlah pasien
- Performance metrics untuk dokter

### 3. Export Features
- Export laporan ke PDF/Excel
- Scheduled reports via email
- Custom date range reports

### 4. Notifications
- Email notifications untuk admin
- In-app notifications
- SMS alerts untuk urgent matters

## Troubleshooting

### Common Issues

1. **Relationship Error**: Pastikan semua model memiliki relationship yang benar
2. **Seeder Error**: Jalankan `php artisan migrate:fresh --seed` untuk reset database
3. **Chart Not Loading**: Pastikan `npm run build` sudah dijalankan
4. **Data Not Showing**: Cek apakah seeder berhasil dijalankan

### Debug Commands
```bash
# Cek data di database
php artisan tinker
>>> App\Models\Appointment::today()->count()
>>> App\Models\DoctorAttendance::today()->present()->count()
>>> App\Models\Revenue::today()->first()

# Cek migration status
php artisan migrate:status

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## Conclusion

Dashboard admin telah berhasil diintegrasikan dengan database Laravel dan menampilkan data real-time. Semua fitur utama berfungsi dengan baik termasuk statistik, kehadiran dokter, aktivitas log, dan charts. Data seeder menyediakan sample data yang realistis untuk testing dan development. 