<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $items = Wishlist::with('product.images')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['product_id' => 'required|exists:products,id']);

        Wishlist::firstOrCreate([
            'user_id' => $request->user()->id,
            'product_id' => $data['product_id'],
        ]);

        return response()->json(['message' => 'Added to wishlist']);
    }

    public function destroy(Request $request, Product $product)
    {
        Wishlist::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->delete();

        return response()->json(['message' => 'Removed from wishlist']);
    }
}
