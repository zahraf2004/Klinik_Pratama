-- Script untuk membuat user untuk dokter yang belum punya user_id
-- Jalankan ini jika ada dokter lama yang belum punya akun user

-- Contoh: Membuat user untuk dokter dengan ID tertentu
-- Ganti nilai sesuai dengan data dokter yang ada

-- Untuk Dr. Ahmad Fauzi (contoh)
INSERT INTO users (name, email, password, role, created_at, updated_at)
SELECT 
    nama,
    email,
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    CASE 
        WHEN role = 'dokter_umum' THEN 'dokter'
        ELSE role
    END,
    NOW(),
    NOW()
FROM tenaga_kesehatan
WHERE email = 'ahmad.fauzi@klinik.com'
AND NOT EXISTS (SELECT 1 FROM users WHERE users.email = tenaga_kesehatan.email);

-- Update tenaga_kesehatan dengan user_id yang baru dibuat
UPDATE tenaga_kesehatan tk
SET user_id = (SELECT id FROM users WHERE email = tk.email)
WHERE tk.user_id IS NULL
AND EXISTS (SELECT 1 FROM users WHERE users.email = tk.email);

-- Atau lebih mudah, jalankan seeder ulang:
-- php artisan db:seed --class=TenagaKesehatanSeeder
