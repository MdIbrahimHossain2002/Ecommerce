<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images', 'variants'])
            ->where('status', 'active');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('sku', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        if ($request->category) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        if ($request->brand) {
            $query->whereHas('brand', fn ($q) => $q->where('slug', $request->brand));
        }

        if ($request->featured) {
            $query->where('is_featured', true);
        }

        if ($request->bestseller) {
            $query->where('is_bestseller', true);
        }

        if ($request->new_arrivals) {
            $query->where('is_new_arrival', true);
        }

        if ($request->on_sale) {
            $query->whereNotNull('discount_price');
        }

        $sort = $request->sort ?? 'latest';
        match ($sort) {
            'price_low' => $query->orderByRaw('COALESCE(discount_price, regular_price) ASC'),
            'price_high' => $query->orderByRaw('COALESCE(discount_price, regular_price) DESC'),
            'popular' => $query->orderByDesc('sold_count'),
            default => $query->latest(),
        };

        return response()->json($query->paginate($request->per_page ?? 12));
    }

    public function show(string $slug)
    {
        $product = Product::with([
            'category', 'brand', 'images', 'variants',
            'reviews' => fn ($q) => $q->where('status', 'approved')->with('user:id,name'),
        ])->where('slug', $slug)->where('status', 'active')->firstOrFail();

        $related = Product::with('images')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->limit(8)
            ->get();

        return response()->json(['product' => $product, 'related' => $related]);
    }
}
