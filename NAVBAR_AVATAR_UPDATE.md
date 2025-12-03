# 🖼️ Update Avatar di Navbar

## Perubahan yang Dilakukan

Avatar user sekarang muncul di navbar untuk semua role (Admin, Dokter, Pasien).

### File yang Diubah

1. **`resources/views/partials/navbar.blade.php`** - Navbar Admin
2. **`resources/views/partials/navdokter.blade.php`** - Navbar Dokter
3. **`resources/views/partials/nav.blade.php`** - Navbar Pasien

## Implementasi

### 1. Navbar Admin & Dokter
```blade
<img alt="image" src="{{ Auth::user()->avatar }}" 
     class="rounded-circle mr-1" 
     style="width: 30px; height: 30px; object-fit: cover;">
```

**Fitur:**
- Foto bulat (rounded-circle)
- Ukuran 30x30 pixel
- Object-fit: cover (foto tidak terdistorsi)
- Otomatis ambil dari `Auth::user()->avatar`

### 2. Navbar Pasien
```blade
@if(Auth::user()->hasCustomAvatar())
    <img src="{{ Auth::user()->avatar }}" 
         alt="{{ Auth::user()->name }}" 
         style="width: 40px; height: 40px; border-radius: 50%; 
                object-fit: cover; border: 2px solid #4a83d3;">
@else
    <i class="fa-solid fa-circle-user fa-2xl"></i>
@endif
```

**Fitur:**
- Cek apakah user punya foto custom
- Jika ada: tampilkan foto dengan border biru
- Jika tidak: tampilkan icon user default
- Ukuran 40x40 pixel

## Cara Kerja

### Method `getAvatarAttribute()` di Model User
```php
public function getAvatarAttribute()
{
    // Dokter/Nakes: ambil dari tenaga_kesehatan
    if (in_array($this->role, ['dokter', 'bidan', 'perawat', 'admin'])) {
        $nakes = $this->tenagaKesehatan;
        if ($nakes && $nakes->foto_url) {
            return $nakes->foto_url;
        }
    }
    
    // Pasien: ambil dari profil_pasien
    if ($this->role === 'pasien') {
        $profil = $this->profil_pasien;
        if ($profil && $profil->foto) {
            return Storage::disk('public')->url($profil->foto);
        }
    }
    
    // Default avatar
    return asset('assets/img/avatar/avatar-1.png');
}
```

### Method `hasCustomAvatar()` di Model User
```php
public function hasCustomAvatar()
{
    if (in_array($this->role, ['dokter', 'bidan', 'perawat', 'admin'])) {
        return $this->tenagaKesehatan && $this->tenagaKesehatan->foto_url;
    }
    
    if ($this->role === 'pasien') {
        return $this->profil_pasien && $this->profil_pasien->foto;
    }
    
    return false;
}
```

## Testing

### Test 1: Avatar Admin
1. Login sebagai admin
2. Cek navbar pojok kanan atas
3. ✅ Avatar default harus muncul (karena admin biasanya tidak punya foto di tenaga_kesehatan)

### Test 2: Avatar Dokter
1. Login sebagai dokter yang sudah punya foto
2. Cek navbar pojok kanan atas
3. ✅ Foto dokter harus muncul

### Test 3: Avatar Pasien dengan Foto
1. Login sebagai pasien
2. Upload foto di halaman Profil
3. Refresh halaman
4. ✅ Foto pasien harus muncul di navbar dengan border biru

### Test 4: Avatar Pasien tanpa Foto
1. Login sebagai pasien baru (belum upload foto)
2. Cek navbar pojok kanan atas
3. ✅ Icon user default harus muncul

### Test 5: Update Foto Real-time
1. Login sebagai pasien
2. Upload foto baru di halaman Profil
3. Kembali ke halaman lain (misal: Dashboard)
4. ✅ Foto di navbar harus ter-update otomatis

## Styling

### Admin & Dokter Navbar
```css
.rounded-circle {
    border-radius: 50%;
}

img {
    width: 30px;
    height: 30px;
    object-fit: cover;
}
```

### Pasien Navbar
```css
img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #4a83d3;
}
```

## Troubleshooting

### Avatar tidak muncul / error 404
**Solusi:**
```bash
php artisan storage:link
```

### Avatar masih default padahal sudah upload
**Cek:**
1. Clear browser cache (Ctrl + Shift + R)
2. Clear Laravel cache:
   ```bash
   php artisan cache:clear
   php artisan view:clear
   ```
3. Cek database apakah foto tersimpan
4. Cek permission folder storage

### Avatar terdistorsi
**Solusi:**
Pastikan CSS `object-fit: cover` sudah diterapkan

### Border tidak muncul (Pasien)
**Cek:**
Pastikan style inline sudah benar:
```blade
style="border: 2px solid #4a83d3;"
```

## Customization

### Ubah Ukuran Avatar
```blade
<!-- Admin/Dokter -->
style="width: 40px; height: 40px; object-fit: cover;"

<!-- Pasien -->
style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;"
```

### Ubah Warna Border (Pasien)
```blade
style="border: 2px solid #ff5733;" <!-- Merah -->
style="border: 2px solid #28a745;" <!-- Hijau -->
style="border: 2px solid #ffc107;" <!-- Kuning -->
```

### Tambah Hover Effect
```css
.user-profile img:hover {
    transform: scale(1.1);
    transition: transform 0.3s ease;
}
```

### Tambah Badge Online
```blade
<div style="position: relative;">
    <img src="{{ Auth::user()->avatar }}" ...>
    <span style="position: absolute; bottom: 0; right: 0; 
                 width: 10px; height: 10px; 
                 background: #28a745; border-radius: 50%; 
                 border: 2px solid white;"></span>
</div>
```

## Integrasi dengan Fitur Lain

### 1. Chatify
Avatar yang sama juga muncul di:
- Daftar kontak Chatify
- Header percakapan
- Info user

### 2. Halaman Konsultasi
Avatar dokter muncul di card dokter dengan badge online

### 3. Profil Pasien
Foto yang di-upload di halaman profil otomatis muncul di navbar

## Best Practices

1. **Ukuran Foto**: Maksimal 2MB
2. **Format**: JPG, PNG, JPEG
3. **Resolusi**: Minimal 200x200 pixel
4. **Aspect Ratio**: 1:1 (persegi) untuk hasil terbaik
5. **Kompresi**: Gunakan kompresi untuk mempercepat loading

## Notes

- Avatar di-cache oleh browser, jadi perlu refresh untuk melihat perubahan
- Method `getAvatarAttribute()` otomatis dipanggil saat akses `Auth::user()->avatar`
- Eager loading sudah diterapkan untuk menghindari N+1 query problem
- Default avatar bisa diganti dengan mengedit path di method `getAvatarAttribute()`
