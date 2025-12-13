<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'review_content' => 'required|string|max:300'
        ], [
            'rating.required' => 'Rating harus diisi',
            'rating.min' => 'Rating minimal 1 bintang',
            'rating.max' => 'Rating maksimal 5 bintang',
            'review_content.required' => 'Review tidak boleh kosong',
            'review_content.max' => 'Review maksimal 300 karakter'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $review = Review::create([
                'user_id' => Auth::id(),
                'reviewer_name' => Auth::user()->name,
                'rating' => $request->rating,
                'review_content' => $request->review_content
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review berhasil dikirim! Terima kasih atas feedback Anda.',
                'data' => $review
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan review. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Get reviews for homepage
     */
    public function getHomepageReviews()
    {
        try {
            $reviews = Review::latest()
                ->limit(12)
                ->get()
                ->map(function ($review) {
                    return [
                        'id' => $review->id,
                        'reviewer_name' => $review->reviewer_name,
                        'rating' => $review->rating,
                        'review_content' => $review->review_content,
                        'created_at' => $review->created_at->format('Y-m-d H:i:s'),
                        'formatted_date' => $review->created_at->format('d M Y')
                    ];
                });

            return response()->json([
                'success' => true,
                'reviews' => $reviews
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get featured reviews
     */
    public function getFeaturedReviews()
    {
        try {
            $reviews = Review::latest()->limit(3)->get();

            return response()->json([
                'success' => true,
                'reviews' => $reviews
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data featured review'
            ], 500);
        }
    }

    /**
     * Get all reviews with pagination
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $rating = $request->get('rating');

            $query = Review::latest();

            if ($rating) {
                $query->where('rating', $rating);
            }

            $reviews = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'reviews' => $reviews
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data review'
            ], 500);
        }
    }
}