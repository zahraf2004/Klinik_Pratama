# 🎨 Visual Summary - Avatar Integration

## 📍 Lokasi Avatar Muncul

```
┌─────────────────────────────────────────────────────────────┐
│                    SISTEM KLINIK                             │
└─────────────────────────────────────────────────────────────┘

1. NAVBAR (Pojok Kanan Atas)
   ┌──────────────────────────────────────┐
   │  [Logo]  Menu  Menu  [👤 Avatar ▼]  │
   └──────────────────────────────────────┘
   
   ✅ Admin    → Avatar dari tenaga_kesehatan (atau default)
   ✅ Dokter   → Avatar dari tenaga_kesehatan
   ✅ Pasien   → Avatar dari profil_pasien (atau icon default)

2. HALAMAN KONSULTASI
   ┌─────────────────────────────────────┐
   │  Dokter Tersedia                    │
   │  ┌──────────────────────────────┐   │
   │  │ [👤 Avatar] Dr. Ahmad Fauzi │   │
   │  │ 🟢 Tersedia untuk chat       │   │
   │  │ [💬 Chat Sekarang]           │   │
   │  └──────────────────────────────┘   │
   └─────────────────────────────────────┘
   
   ✅ Avatar dokter dari tenaga_kesehatan
   ✅ Badge hijau jika punya user_id

3. CHATIFY - Daftar Kontak
   ┌─────────────────────────────────────┐
   │  MESSAGES                           │
   │  ┌──────────────────────────────┐   │
   │  │ [👤] Dr. Ahmad Fauzi         │   │
   │  │ [👤] Pasien A                │   │
   │  │ [👤] Pasien B                │   │
   │  └──────────────────────────────┘   │
   └─────────────────────────────────────┘
   
   ✅ Avatar sesuai role user

4. CHATIFY - Header Percakapan
   ┌─────────────────────────────────────┐
   │  [←] [👤] Dr. Ahmad Fauzi    [⭐][🏠][ℹ️] │
   │  ─────────────────────────────────  │
   │  Halo dok...                        │
   └─────────────────────────────────────┘
   
   ✅ Avatar di header chat

5. CHATIFY - Info User
   ┌─────────────────────────────────────┐
   │  User Details              [✕]      │
   │  ┌──────────────────────────────┐   │
   │  │      [👤 Avatar Besar]       │   │
   │  │      Dr. Ahmad Fauzi         │   │
   │  │                              │   │
   │  │  Shared Photos               │   │
   │  └──────────────────────────────┘   │
   └─────────────────────────────────────┘
   
   ✅ Avatar besar di info panel

## 🔄 Flow Data Avatar

```
┌──────────────────────────────────────────────────────────────┐
│                    DATABASE                                   │
├──────────────────────────────────────────────────────────────┤
│                                                               │
│  users                    tenaga_kesehatan                    │
│  ┌─────────────┐         ┌──────────────────┐               │
│  │ id          │◄────────│ user_id          │               │
│  │ name        │         │ nama             │               │
│  │ email       │         │ foto_path ───────┼──┐            │
│  │ role        │         └──────────────────┘  │            │
│  └─────────────┘                               │            │
│         │                                       │            │
│         │                                       ▼            │
│         │                              storage/tenaga_...    │
│         │                                                    │
│         │              profil_pasien                         │
│         │              ┌──────────────────┐                 │
│         └──────────────► user_id          │                 │
│                        │ foto ─────────────┼──┐             │
│                        └──────────────────┘  │             │
│                                              │             │
│                                              ▼             │
│                                     storage/patient-...    │
└──────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌──────────────────────────────────────────────────────────────┐
│                    MODEL USER                                 │
├──────────────────────────────────────────────────────────────┤
│                                                               │
│  getAvatarAttribute()                                         │
│  ├─ if role = dokter/bidan/perawat/admin                     │
│  │  └─ return tenagaKesehatan->foto_url                      │
│  │                                                            │
│  ├─ if role = pasien                                          │
│  │  └─ return profil_pasien->foto_url                        │
│  │                                                            │
│  └─ else                                                      │
│     └─ return default avatar                                 │
│                                                               │
└──────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌──────────────────────────────────────────────────────────────┐
│                    VIEW (BLADE)                               │
├──────────────────────────────────────────────────────────────┤
│                                                               │
│  {{ Auth::user()->avatar }}                                   │
│  │                                                            │
│  └─► Otomatis panggil getAvatarAttribute()                   │
│      │                                                        │
│      └─► Return URL foto atau default                        │
│                                                               │
└──────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌──────────────────────────────────────────────────────────────┐
│                    BROWSER                                    │
├──────────────────────────────────────────────────────────────┤
│                                                               │
│  <img src="http://localhost/storage/tenaga_kesehatan/...">   │
│  <img src="http://localhost/storage/patient-photos/...">     │
│  <img src="http://localhost/assets/img/avatar/avatar-1.png"> │
│                                                               │
└──────────────────────────────────────────────────────────────┘
```

## 🎯 Perbedaan Avatar per Role

### 👨‍⚕️ ADMIN
```
Navbar:  [👤 30x30] Hi, Admin
         └─ Foto dari tenaga_kesehatan (jika ada)
         └─ Default avatar (jika tidak ada)
```

### 👨‍⚕️ DOKTER
```
Navbar:  [👤 30x30] Hi, Dr. Ahmad
         └─ Foto dari tenaga_kesehatan

Chatify: [👤] Dr. Ahmad Fauzi
         └─ Foto dari tenaga_kesehatan
```

### 👤 PASIEN
```
Navbar:  [👤 40x40 + border biru] Nama Pasien
         └─ Foto dari profil_pasien (jika ada)
         └─ Icon user (jika tidak ada)

Chatify: [👤] Nama Pasien
         └─ Foto dari profil_pasien
         └─ Default avatar (jika tidak ada)
```

## 📊 Ukuran Avatar

| Lokasi                  | Ukuran    | Border | Shape  |
|------------------------|-----------|--------|--------|
| Navbar Admin           | 30x30 px  | -      | Circle |
| Navbar Dokter          | 30x30 px  | -      | Circle |
| Navbar Pasien          | 40x40 px  | 2px    | Circle |
| Chatify List           | Auto      | -      | Circle |
| Chatify Header         | Auto      | -      | Circle |
| Chatify Info           | Large     | -      | Circle |
| Card Dokter (Konsul)   | Auto      | -      | Circle |

## 🎨 Styling Details

### Navbar Admin/Dokter
```css
.rounded-circle {
    border-radius: 50%;
}
width: 30px;
height: 30px;
object-fit: cover;
```

### Navbar Pasien (Dengan Foto)
```css
width: 40px;
height: 40px;
border-radius: 50%;
object-fit: cover;
border: 2px solid #4a83d3;
```

### Navbar Pasien (Tanpa Foto)
```html
<i class="fa-solid fa-circle-user fa-2xl"></i>
```

### Badge Online (Card Dokter)
```css
position: absolute;
bottom: 5px;
right: 5px;
width: 12px;
height: 12px;
background: #4CAF50;
border: 2px solid white;
border-radius: 50%;
```

## 🔄 Update Flow

```
User Upload Foto
      │
      ▼
┌─────────────────┐
│ Form Submit     │
└─────────────────┘
      │
      ▼
┌─────────────────┐
│ Controller      │
│ - Save to DB    │
│ - Store file    │
└─────────────────┘
      │
      ▼
┌─────────────────┐
│ Database        │
│ - foto_path     │
│ - foto          │
└─────────────────┘
      │
      ▼
┌─────────────────┐
│ Model Accessor  │
│ getAvatarAttr() │
└─────────────────┘
      │
      ▼
┌─────────────────┐
│ View Render     │
│ - Navbar        │
│ - Chatify       │
│ - Card Dokter   │
└─────────────────┘
      │
      ▼
┌─────────────────┐
│ Browser Display │
│ ✅ Avatar Updated│
└─────────────────┘
```

## 🎯 Key Features

✅ **Single Source of Truth**: Method `getAvatarAttribute()` di model User
✅ **Automatic**: Tidak perlu manual set avatar di setiap view
✅ **Consistent**: Avatar sama di semua tempat
✅ **Efficient**: Eager loading untuk avoid N+1 query
✅ **Fallback**: Default avatar jika tidak ada foto
✅ **Responsive**: Object-fit cover untuk prevent distortion

## 🚀 Quick Reference

### Akses Avatar di Blade
```blade
{{ Auth::user()->avatar }}
```

### Cek Punya Foto Custom
```blade
@if(Auth::user()->hasCustomAvatar())
    <!-- Ada foto -->
@else
    <!-- Tidak ada foto -->
@endif
```

### Update Foto
```php
// Dokter (via Admin)
$nakes->foto_path = $request->file('foto')->store('tenaga_kesehatan', 'public');

// Pasien
$profil->foto = $request->file('foto')->store('patient-photos', 'public');
```

### Get Avatar URL
```php
// Di Controller
$avatar = Auth::user()->avatar;

// Di Model
$user->avatar;
```
