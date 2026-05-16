<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        return response()->json([
            'banners' => Banner::where('is_active', true)->orderBy('sort_order')->get(),
            'categories' => Category::where('is_active', true)->whereNull('parent_id')->orderBy('sort_order')->limit(8)->get(),
            'featured' => Product::with('images')->where('status', 'active')->where('is_featured', true)->latest()->limit(8)->get(),
            'bestsellers' => Product::with('images')->where('status', 'active')->where('is_bestseller', true)->orderByDesc('sold_count')->limit(8)->get(),
            'new_arrivals' => Product::with('images')->where('status', 'active')->where('is_new_arrival', true)->latest()->limit(8)->get(),
            'on_sale' => Product::with('images')->where('status', 'active')->whereNotNull('discount_price')->latest()->limit(8)->get(),
            'brands' => Brand::where('is_active', true)->limit(12)->get(),
            'settings' => [
                'site_name' => Setting::get('site_name', 'ShopHub'),
                'currency' => Setting::get('currency', 'BDT'),
                'currency_symbol' => Setting::get('currency_symbol', '৳'),
            ],
        ]);
    }
}
