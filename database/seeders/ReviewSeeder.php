<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use Carbon\Carbon;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reviews = [
            [
                'reviewer_name' => 'Zahra Fitri Andiani',
                'rating' => 5,
                'review_content' => 'Pelayanannya sangat bagus, dokter dan perawat ramah. saat datang ke klinik langsung dapat penanganan karena sudah buat janji',
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'reviewer_name' => 'Tio Saputra',
                'rating' => 5,
                'review_content' => 'Konsultasi online sangat berguna bagi saya yang malas dan cukup sibuk. dokternya juga sangat responsif dan fast respon. saya juga senang karena bebas memilih dengan dokter mana saja saya konsultasi',
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'reviewer_name' => 'Ahmad Syarif',
                'rating' => 4,
                'review_content' => 'Informasi obat yang tersedia sangat membantu. Harga terjangkau dan pelayanan memuaskan. Sistem online memudahkan untuk booking jadwal.',
                'created_at' => Carbon::now()->subDays(5),
            ],
            [
                'reviewer_name' => 'Siti Nurhaliza',
                'rating' => 5,
                'review_content' => 'Dokternya sangat profesional dan ramah. Penjelasan yang diberikan mudah dipahami. Fasilitas klinik juga bersih dan nyaman.',
                'created_at' => Carbon::now()->subDays(7),
            ],
            [
                'reviewer_name' => 'Budi Santoso',
                'rating' => 4,
                'review_content' => 'Pelayanan cepat dan efisien. Tidak perlu menunggu lama. Harga konsultasi juga terjangkau untuk semua kalangan.',
                'created_at' => Carbon::now()->subDays(10),
            ],
            [
                'reviewer_name' => 'Maya Sari',
                'rating' => 5,
                'review_content' => 'Sistem appointment online sangat membantu. Dokter memberikan penjelasan yang detail dan mudah dipahami. Sangat puas dengan layanannya.',
                'created_at' => Carbon::now()->subDays(12),
            ],
            [
                'reviewer_name' => 'Rizki Pratama',
                'rating' => 4,
                'review_content' => 'Klinik yang recommended! Staff ramah, dokter berpengalaman, dan fasilitas lengkap. Akan kembali lagi jika butuh konsultasi.',
                'created_at' => Carbon::now()->subDays(15),
            ],
            [
                'reviewer_name' => 'Dewi Lestari',
                'rating' => 5,
                'review_content' => 'Telemedicine nya sangat membantu di masa pandemi. Bisa konsultasi dari rumah dengan aman. Dokternya juga sangat sabar menjelaskan.',
                'created_at' => Carbon::now()->subDays(18),
            ]
        ];

        foreach ($reviews as $review) {
            Review::create($review);
        }
    }
}