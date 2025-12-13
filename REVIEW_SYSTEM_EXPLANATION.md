# Review System - Field Explanation

## 📋 Field Tabel Reviews dan Fungsinya

### **User Input Fields (Wajib di Form)**
1. **`rating`** - Rating 1-5 bintang
   - User pilih rating dari 1-5
   - Wajib diisi
   
2. **`review_content`** - Isi review/komentar
   - User tulis pengalaman mereka
   - Max 300 karakter
   - Wajib diisi

### **Auto-Generated Fields (Tidak perlu di Form)**
3. **`user_id`** - ID user yang login
   - Otomatis dari `Auth::id()`
   - Untuk tracking siapa yang review
   
4. **`reviewer_name`** - Nama reviewer
   - Otomatis dari `Auth::user()->name`
   - Untuk display di carousel
   
5. **`created_at/updated_at`** - Timestamp
   - Otomatis dari Laravel
   - Untuk sorting dan display tanggal

### **Admin Management Fields (Tidak perlu di Form User)**
6. **`recommend`** - Apakah user merekomendasikan
   - **Default: `true`** (semua review dianggap rekomendasi)
   - Bisa diubah admin jika perlu
   - **Tidak perlu checkbox di form user**
   
7. **`is_approved`** - Status persetujuan
   - **Default: `true`** (auto-approve)
   - Admin bisa mengubah jadi `false` untuk hide review
   - **Tidak perlu di form user**
   
8. **`is_featured`** - Status featured
   - **Default: `false`**
   - Admin bisa set `true` untuk review unggulan
   - Featured review bisa ditampilkan di tempat khusus
   - **Tidak perlu di form user**

## 🎯 Kesimpulan

**Form User hanya perlu 2 field:**
- Rating (1-5 bintang) ✅
- Review Content (textarea) ✅

**Field lainnya otomatis/admin:**
- `user_id` → Auto dari login
- `reviewer_name` → Auto dari user name  
- `recommend` → Default true
- `is_approved` → Default true (auto-approve)
- `is_featured` → Default false (admin bisa ubah)
- `timestamps` → Auto Laravel

## 🔧 Keuntungan Sistem Ini

1. **User Experience Simple** - Hanya 2 field yang perlu diisi
2. **Admin Control** - Admin tetap bisa manage review
3. **Flexible** - Bisa tambah fitur featured review nanti
4. **Auto-Approve** - Review langsung muncul tanpa delay
5. **Tracking** - Tetap tau siapa yang review via user_id

## 🚀 Future Features (Opsional)

- Admin panel untuk manage review
- Featured review section
- Review moderation jika diperlukan
- Review statistics dashboard