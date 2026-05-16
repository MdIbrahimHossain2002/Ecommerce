<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user:id,name', 'product:id,name']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        return response()->json($query->latest()->paginate(15));
    }

    public function updateStatus(Request $request, Review $review)
    {
        $data = $request->validate(['status' => 'required|in:pending,approved,rejected']);

        $review->update($data);

        return response()->json($review);
    }

    public function reply(Request $request, Review $review)
    {
        $data = $request->validate(['admin_reply' => 'required|string']);

        $review->update($data);

        return response()->json($review);
    }
}
