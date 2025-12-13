<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reviewer_name',
        'rating',
        'review_content'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationship dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope untuk review terbaru
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Scope untuk rating tinggi
    public function scopeHighRating($query)
    {
        return $query->where('rating', '>=', 4);
    }

    // Accessor untuk format tanggal yang user-friendly
    public function getFormattedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    // Accessor untuk rating dalam bentuk bintang
    public function getStarsAttribute()
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    // Method untuk mendapatkan review untuk homepage
    public static function getHomepageReviews($limit = 12)
    {
        return self::latest()
            ->limit($limit)
            ->get();
    }

    // Method untuk statistik rating
    public static function getRatingStats()
    {
        $reviews = self::all();
        
        if ($reviews->count() === 0) {
            return [
                'average' => 0,
                'total' => 0,
                'distribution' => [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0]
            ];
        }

        $average = $reviews->avg('rating');
        $total = $reviews->count();
        
        $distribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $distribution[$i] = $reviews->where('rating', $i)->count();
        }

        return [
            'average' => round($average, 1),
            'total' => $total,
            'distribution' => $distribution
        ];
    }
}