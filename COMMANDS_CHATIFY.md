# Command Reference untuk Chatify Integration

## 🔧 Setup Commands

### Link Storage (Wajib!)
```bash
php artisan storage:link
```
**Fungsi**: Membuat symbolic link dari `storage/app/public` ke `public/storage` agar foto bisa diakses via URL

### Jalankan Seeder
```bash
# Jalankan semua seeder
php artisan db:seed

# Jalankan seeder tertentu
php artisan db:seed --class=TenagaKesehatanSeeder
```

### Clear Cache
```bash
# Clear semua cache
php artisan optimize:clear

# Atau satu per satu
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

## 🗄️ Database Commands

### Refresh Database (HATI-HATI: Hapus semua data!)
```bash
php artisan migrate:fresh --seed
```

### Rollback dan Migrate Ulang
```bash
php artisan migrate:rollback
php artisan migrate
php artisan db:seed
```

### Cek Status Migration
```bash
php artisan migrate:status
```

## 🔍 Debug Commands

### Cek Routes
```bash
php artisan route:list | findstr chatify
```

### Cek Config Chatify
```bash
php artisan config:show chatify
```

### Test Database Connection
```bash
php artisan tinker
```
Lalu di tinker:
```php
// Cek user
User::all();

// Cek dokter dengan user_id
TenagaKesehatan::whereNotNull('user_id')->get();

// Cek avatar user tertentu
$user = User::find(1);
echo $user->avatar;

// Cek foto dokter
$nakes = TenagaKesehatan::with('user')->first();
echo $nakes->foto_url;

// Cek foto pasien
$profil = ProfilPasien::first();
echo $profil->foto_url;
```

## 📦 Publish Chatify Assets (Jika Perlu)

### Publish Views
```bash
php artisan vendor:publish --tag=chatify-views
```

### Publish Config
```bash
php artisan vendor:publish --tag=chatify-config
```

### Publish Assets (CSS, JS, Images)
```bash
php artisan vendor:publish --tag=chatify-assets
```

### Publish Semua
```bash
php artisan vendor:publish --provider="Chatify\ChatifyServiceProvider"
```

## 🧪 Testing Commands

### Run Tests
```bash
php artisan test
```

### Test Specific Feature
```bash
php artisan test --filter=ChatTest
```

## 🔐 User Management Commands

### Buat User Manual via Tinker
```bash
php artisan tinker
```
```php
// Buat user dokter
$user = User::create([
    'name' => 'Dr. Test',
    'email' => 'test@klinik.com',
    'password' => bcrypt('password123'),
    'role' => 'dokter',
]);

// Buat tenaga kesehatan
$nakes = TenagaKesehatan::create([
    'user_id' => $user->id,
    'nama' => 'Dr. Test',
    'email' => 'test@klinik.com',
    'hp' => '08123456789',
    'role' => 'dokter_umum',
]);
```

### Update User Password
```bash
php artisan tinker
```
```php
$user = User::where('email', 'ahmad.fauzi@klinik.com')->first();
$user->password = bcrypt('newpassword123');
$user->save();
```

### Update user_id untuk Dokter Lama
```bash
php artisan tinker
```
```php
// Ambil semua dokter tanpa user_id
$nakes = TenagaKesehatan::whereNull('user_id')->get();

foreach ($nakes as $n) {
    // Cek apakah user sudah ada
    $user = User::where('email', $n->email)->first();
    
    if (!$user) {
        // Buat user baru
        $user = User::create([
            'name' => $n->nama,
            'email' => $n->email,
            'password' => bcrypt($n->hp),
            'role' => $n->role === 'dokter_umum' ? 'dokter' : $n->role,
        ]);
    }
    
    // Update user_id
    $n->user_id = $user->id;
    $n->save();
    
    echo "Updated: {$n->nama}\n";
}
```

## 📊 Query Useful

### Cek Dokter dengan/tanpa User
```sql
-- Dokter dengan user_id
SELECT * FROM tenaga_kesehatan WHERE user_id IS NOT NULL;

-- Dokter tanpa user_id
SELECT * FROM tenaga_kesehatan WHERE user_id IS NULL;

-- Join dengan users
SELECT tk.nama, tk.email, u.id as user_id, u.role
FROM tenaga_kesehatan tk
LEFT JOIN users u ON tk.user_id = u.id;
```

### Cek Foto
```sql
-- Dokter dengan foto
SELECT nama, foto_path FROM tenaga_kesehatan WHERE foto_path IS NOT NULL;

-- Pasien dengan foto
SELECT pp.*, u.name 
FROM profil_pasien pp
JOIN users u ON pp.user_id = u.id
WHERE pp.foto IS NOT NULL;
```

## 🚀 Production Commands

### Optimize untuk Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Clear Optimization (Development)
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 📝 Logs

### Lihat Log Laravel
```bash
# Windows
type storage\logs\laravel.log

# Atau buka file di editor
code storage\logs\laravel.log
```

### Clear Log
```bash
# Windows
del storage\logs\laravel.log
```

## 🔄 Restart Services

### Restart Queue (Jika Pakai Queue)
```bash
php artisan queue:restart
```

### Restart Development Server
```bash
# Stop (Ctrl+C) lalu start lagi
php artisan serve
```

## 💾 Backup Commands

### Backup Database
```bash
# Menggunakan mysqldump (jika pakai MySQL)
mysqldump -u root -p nama_database > backup.sql

# Restore
mysql -u root -p nama_database < backup.sql
```

### Backup Storage
```bash
# Windows
xcopy storage\app\public backup\storage /E /I /Y
```

## 🎨 Asset Commands

### Compile Assets (Jika Pakai Vite/Mix)
```bash
npm run dev
npm run build
```

### Install Dependencies
```bash
composer install
npm install
```
