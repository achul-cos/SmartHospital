# Smart Hospital - Database Structure

## Overview
Aplikasi Smart Hospital memiliki tiga aktor pengguna utama dengan struktur database yang terpisah untuk masing-masing aktor.

## User Roles

### 1. Admin Rumah Sakit (admins)
**Tabel:** `admins`

**Fields:**
- `id` - Primary Key (Auto Increment)
- `name` - Nama Admin (String)
- `employee_number` - Nomor Pegawai (String, Unique)
- `photo` - Foto Admin (String, Nullable)
- `email` - Email (String, Unique)
- `phone_number` - Nomor Telepon (String)
- `password` - Password (String, Hashed)
- `position` - Jabatan (String)
- `created_at` - Timestamp
- `updated_at` - Timestamp

**Sample Data:**
- Administrator (ADM001) - Super Admin
- Manager Rumah Sakit (ADM002) - Hospital Manager

### 2. Dokter (doctors)
**Tabel:** `doctors`

**Fields:**
- `id` - Primary Key (Auto Increment)
- `name` - Nama Dokter (String)
- `doctor_number` - Nomor Dokter (String, Unique)
- `photo` - Foto Dokter (String, Nullable)
- `email` - Email (String, Unique)
- `phone_number` - Nomor Telepon (String)
- `specialization` - Bidang Spesialisasi (String)
- `password` - Password (String, Hashed)
- `gender` - Jenis Kelamin (Enum: 'male', 'female')
- `created_at` - Timestamp
- `updated_at` - Timestamp

**Sample Data:**
- Dr. Sarah Johnson (DOC001) - Cardiologist
- Dr. Michael Chen (DOC002) - Neurologist
- Dr. Emily Rodriguez (DOC003) - Pediatrician

### 3. Pasien (patients)
**Tabel:** `patients`

**Fields:**
- `id` - Primary Key (Auto Increment)
- `name` - Nama Pasien (String)
- `card_number` - Nomor Kartu (String, Unique)
- `gender` - Jenis Kelamin (Enum: 'male', 'female')
- `birth_date` - Tanggal Lahir (Date)
- `address` - Alamat (Text)
- `phone_number` - Nomor Telepon (String)
- `email` - Email (String, Unique)
- `photo` - Foto Pasien (String, Nullable)
- `notes` - Catatan (Text, Nullable)
- `created_at` - Timestamp
- `updated_at` - Timestamp

**Sample Data:**
- John Doe (PAT001) - Male, 1990-05-15
- Jane Smith (PAT002) - Female, 1985-08-22
- Ahmad Rahman (PAT003) - Male, 1995-12-10

## Authentication Configuration

### Guards
- `admin` - Untuk autentikasi Admin
- `doctor` - Untuk autentikasi Dokter
- `patient` - Untuk autentikasi Pasien

### Middleware
- `admin` - AdminMiddleware untuk melindungi rute admin
- `doctor` - DoctorMiddleware untuk melindungi rute dokter
- `patient` - PatientMiddleware untuk melindungi rute pasien

## Models

### Admin Model
- Extends `Authenticatable`
- Uses `Notifiable` trait
- Fillable fields: name, employee_number, photo, email, phone_number, password, position
- Hidden fields: password, remember_token

### Doctor Model
- Extends `Authenticatable`
- Uses `Notifiable` trait
- Fillable fields: name, doctor_number, photo, email, phone_number, specialization, password, gender
- Hidden fields: password, remember_token

### Patient Model
- Extends `Authenticatable`
- Uses `Notifiable` trait
- Fillable fields: name, card_number, gender, birth_date, address, phone_number, email, photo, notes
- Hidden fields: password, remember_token

## Usage Examples

### Authentication
```php
// Login sebagai admin
Auth::guard('admin')->attempt($credentials);

// Check jika user adalah admin
Auth::guard('admin')->check();

// Get current admin
$admin = Auth::guard('admin')->user();
```

### Route Protection
```php
// Rute yang dilindungi middleware admin
Route::middleware('admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
});

// Rute yang dilindungi middleware doctor
Route::middleware('doctor')->group(function () {
    Route::get('/doctor/dashboard', [DoctorController::class, 'dashboard']);
});

// Rute yang dilindungi middleware patient
Route::middleware('patient')->group(function () {
    Route::get('/patient/dashboard', [PatientController::class, 'dashboard']);
});
```

## Database Commands

### Migration
```bash
php artisan migrate
```

### Seeding
```bash
php artisan db:seed
```

### Reset Database
```bash
php artisan migrate:fresh --seed
```

## Default Credentials

### Admin
- Email: admin@smarthospital.com
- Password: password

### Doctor
- Email: sarah.johnson@smarthospital.com
- Password: password

### Patient
- Email: john.doe@email.com
- No password (patients don't have password field in current structure) 