# 🔍 Fitur Search Halaman Konsultasi

## Overview

Fitur search yang telah ditingkatkan untuk halaman konsultasi dokter dengan berbagai improvement:

## ✨ Fitur Utama

### 1. Search Multi-Field
Pencarian dapat dilakukan berdasarkan:
- ✅ Nama dokter
- ✅ Nomor STR (Surat Tanda Registrasi)
- ✅ Nomor SIP (Surat Izin Praktik)
- ✅ Email dokter

### 2. Search Info Banner
- Menampilkan jumlah hasil pencarian
- Menampilkan kata kunci yang dicari
- Alert jika tidak ada hasil

### 3. Clear Search Button
- Tombol X merah untuk clear search
- Kembali ke tampilan semua dokter

### 4. No Results State
- Tampilan khusus jika tidak ada hasil
- Icon dan pesan yang jelas
- Tombol untuk kembali ke semua dokter

### 5. Highlight Search Term
- Kata kunci yang dicari di-highlight dengan warna kuning
- Memudahkan user melihat hasil yang relevan

### 6. Loading State
- Animasi loading saat search
- Mencegah double submit

### 7. Keyboard Shortcut
- **Ctrl+K** atau **Cmd+K** untuk focus ke search box
- Auto-select text untuk quick replace

### 8. Pagination with Query
- Pagination tetap maintain search query
- Tidak perlu search ulang saat pindah halaman

## 🎯 Cara Menggunakan

### Basic Search
1. Ketik nama dokter di search box
2. Klik tombol "Cari" atau tekan Enter
3. Hasil akan ditampilkan

### Clear Search
1. Klik tombol X merah
2. Atau hapus text dan submit

### Keyboard Shortcut
1. Tekan **Ctrl+K** (Windows/Linux) atau **Cmd+K** (Mac)
2. Search box akan focus dan text ter-select
3. Langsung ketik untuk search

## 📊 Contoh Pencarian

### Cari berdasarkan Nama
```
Input: "Ahmad"
Result: Dr. Ahmad Fauzi
```

### Cari berdasarkan STR
```
Input: "123456"
Result: Dokter dengan STR yang mengandung "123456"
```

### Cari berdasarkan SIP
```
Input: "503/SIP"
Result: Dokter dengan SIP yang mengandung "503/SIP"
```

### Cari berdasarkan Email
```
Input: "ahmad.fauzi"
Result: ahmad.fauzi@klinik.com
```

## 🎨 UI Components

### Search Container
```html
<form method="GET" action="{{ route('konsultasi.index') }}" class="konsul-search-container">
    <input type="text" name="search" value="{{ $search }}" placeholder="...">
    <button type="submit"><i class="fas fa-search"></i> Cari</button>
    <a href="..." class="konsul-btn-clear"><i class="fas fa-times"></i></a>
</form>
```

### Search Info Banner
```html
<div class="konsul-search-info">
    <p>
        <i class="fas fa-info-circle"></i> 
        Menampilkan <strong>5</strong> hasil untuk "<strong>Ahmad</strong>"
    </p>
</div>
```

### No Results State
```html
<div class="konsul-no-results">
    <div class="konsul-no-results-icon">
        <i class="fas fa-user-md-slash fa-3x"></i>
    </div>
    <h3>Tidak Ada Dokter Ditemukan</h3>
    <p>Maaf, tidak ada dokter yang sesuai dengan pencarian...</p>
    <a href="..." class="konsul-btnD konsul-btn-primary">
        <i class="fas fa-arrow-left"></i> Lihat Semua Dokter
    </a>
</div>
```

## 🔧 Technical Details

### Controller Logic
```php
public function index(Request $request)
{
    $search = $request->input('search', '');

    $nakes = TenagaKesehatan::where('role', 'dokter_umum')
        ->when($search, function ($query) use ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('str', 'like', "%{$search}%")
                  ->orWhere('sip', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        })
        ->orderBy('nama', 'asc')
        ->paginate(6)
        ->withQueryString();

    $totalResults = $nakes->total();
    
    return view('konsultasi.konsultasiNakes', compact('nakes', 'search', 'totalResults'));
}
```

### JavaScript Enhancement
```javascript
// Auto-focus and select
if (searchInput.value) {
    searchInput.focus();
    searchInput.select();
}

// Highlight search term
highlightSearchTerm(searchTerm);

// Keyboard shortcut
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        searchInput.focus();
    }
});
```

### CSS Styling
```css
.konsul-search-info {
    background: #e3f2fd;
    border-left: 4px solid var(--primary);
    padding: 12px 20px;
    animation: slideDown 0.3s ease;
}

.konsul-no-results {
    text-align: center;
    padding: 60px 20px;
    background: var(--gray-light);
    border-radius: var(--radius);
}
```

## 🧪 Testing

### Test Case 1: Search dengan Hasil
1. Buka `/konsultasi`
2. Ketik "Ahmad" di search box
3. Klik "Cari"
4. ✅ Harus muncul Dr. Ahmad Fauzi
5. ✅ Kata "Ahmad" di-highlight
6. ✅ Banner info muncul

### Test Case 2: Search tanpa Hasil
1. Ketik "XYZ123" di search box
2. Klik "Cari"
3. ✅ Muncul "Tidak Ada Dokter Ditemukan"
4. ✅ Tombol "Lihat Semua Dokter" muncul

### Test Case 3: Clear Search
1. Search "Ahmad"
2. Klik tombol X merah
3. ✅ Kembali ke tampilan semua dokter

### Test Case 4: Pagination
1. Search "Dr"
2. Jika ada banyak hasil, klik pagination
3. ✅ Search query tetap ada di URL
4. ✅ Hasil tetap filtered

### Test Case 5: Keyboard Shortcut
1. Tekan Ctrl+K (atau Cmd+K)
2. ✅ Search box focus
3. ✅ Text ter-select

### Test Case 6: Empty Search
1. Hapus semua text di search box
2. Klik "Cari"
3. ✅ Redirect ke tampilan semua dokter

## 📱 Responsive Design

### Mobile
- Search container stack vertical
- Button full width
- Font size adjusted

### Tablet
- Search container horizontal
- Optimal spacing

### Desktop
- Full features
- Hover effects

## 🎯 Performance

### Optimizations
- ✅ Query optimization dengan `when()`
- ✅ Pagination untuk limit results
- ✅ Index database pada kolom yang di-search
- ✅ Debounce pada auto-submit (jika ditambahkan)

### Recommendations
```sql
-- Add index untuk improve search performance
CREATE INDEX idx_nakes_nama ON tenaga_kesehatan(nama);
CREATE INDEX idx_nakes_str ON tenaga_kesehatan(str);
CREATE INDEX idx_nakes_sip ON tenaga_kesehatan(sip);
CREATE INDEX idx_nakes_email ON tenaga_kesehatan(email);
```

## 🔮 Future Enhancements

### Possible Improvements
1. **Auto-complete**: Suggest dokter saat mengetik
2. **Filter**: Tambah filter berdasarkan jadwal, pengalaman
3. **Sort**: Sort by nama, pengalaman, rating
4. **Advanced Search**: Search dengan multiple criteria
5. **Search History**: Simpan recent searches
6. **Voice Search**: Search dengan suara
7. **Fuzzy Search**: Toleransi typo

### Example Auto-complete
```javascript
// Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func(...args), wait);
    };
}

// Auto-complete
searchInput.addEventListener('input', debounce(function(e) {
    const query = e.target.value;
    if (query.length >= 2) {
        fetchSuggestions(query);
    }
}, 300));
```

## 📝 Files Modified

### Backend
- `app/Http/Controllers/TelemedicineController.php` - Search logic

### Frontend
- `resources/views/konsultasi/konsultasiNakes.blade.php` - UI
- `resources/views/layouts/app3.blade.php` - Script include
- `public/css/telemedicine.css` - Styling
- `public/js/konsultasi-search.js` - JavaScript enhancement

## 🐛 Known Issues

None at the moment.

## 📞 Support

Jika ada masalah dengan search:
1. Clear browser cache
2. Check console untuk JavaScript errors
3. Verify database connection
4. Check Laravel logs

## ✅ Checklist

- [x] Multi-field search
- [x] Search info banner
- [x] Clear button
- [x] No results state
- [x] Highlight search term
- [x] Loading state
- [x] Keyboard shortcut
- [x] Pagination with query
- [x] Responsive design
- [x] CSS styling
- [x] JavaScript enhancement
- [x] Documentation

---

**Version**: 1.0.0
**Last Updated**: [Current Date]
**Author**: [Your Name]
