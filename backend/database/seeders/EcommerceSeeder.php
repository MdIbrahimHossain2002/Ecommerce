<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\CmsPage;
use App\Models\Coupon;
use App\Models\Faq;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Models\ShippingMethod;
use App\Models\ShippingZone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EcommerceSeeder extends Seeder
{
    public function run(): void
    {
        Setting::set('site_name', 'ShopHub');
        Setting::set('currency', 'BDT');
        Setting::set('currency_symbol', '৳');
        Setting::set('tax_rate', 5);
        Setting::set('default_shipping', 60);
        Setting::set('free_shipping_min', 2000);

        $electronics = Category::create(['name' => 'Electronics', 'slug' => 'electronics', 'sort_order' => 1]);
        $fashion = Category::create(['name' => 'Fashion', 'slug' => 'fashion', 'sort_order' => 2]);
        $home = Category::create(['name' => 'Home & Living', 'slug' => 'home-living', 'sort_order' => 3]);

        Category::create(['parent_id' => $electronics->id, 'name' => 'Phones', 'slug' => 'phones', 'sort_order' => 1]);
        Category::create(['parent_id' => $electronics->id, 'name' => 'Laptops', 'slug' => 'laptops', 'sort_order' => 2]);
        Category::create(['parent_id' => $fashion->id, 'name' => 'Men', 'slug' => 'men', 'sort_order' => 1]);
        Category::create(['parent_id' => $fashion->id, 'name' => 'Women', 'slug' => 'women', 'sort_order' => 2]);

        $samsung = Brand::create(['name' => 'Samsung', 'slug' => 'samsung']);
        $apple = Brand::create(['name' => 'Apple', 'slug' => 'apple']);
        $nike = Brand::create(['name' => 'Nike', 'slug' => 'nike']);

        $products = [
            ['name' => 'Samsung Galaxy S24', 'category' => $electronics, 'brand' => $samsung, 'price' => 89999, 'sale' => 84999, 'featured' => true, 'bestseller' => true],
            ['name' => 'iPhone 15 Pro', 'category' => $electronics, 'brand' => $apple, 'price' => 129999, 'sale' => null, 'featured' => true, 'new' => true],
            ['name' => 'MacBook Air M3', 'category' => $electronics, 'brand' => $apple, 'price' => 149999, 'sale' => 139999, 'bestseller' => true],
            ['name' => 'Nike Air Max 90', 'category' => $fashion, 'brand' => $nike, 'price' => 12999, 'sale' => 9999, 'featured' => true],
            ['name' => 'Wireless Earbuds Pro', 'category' => $electronics, 'brand' => $samsung, 'price' => 4999, 'sale' => 3999, 'new' => true],
            ['name' => 'Smart Watch Ultra', 'category' => $electronics, 'brand' => $apple, 'price' => 45999, 'sale' => null, 'bestseller' => true],
            ['name' => 'Cotton T-Shirt Premium', 'category' => $fashion, 'brand' => $nike, 'price' => 1999, 'sale' => 1499, 'new' => true],
            ['name' => 'LED Desk Lamp', 'category' => $home, 'brand' => null, 'price' => 2499, 'sale' => 1999, 'featured' => true],
        ];

        foreach ($products as $i => $p) {
            $product = Product::create([
                'category_id' => $p['category']->id,
                'brand_id' => $p['brand']?->id,
                'name' => $p['name'],
                'slug' => Str::slug($p['name']),
                'sku' => 'SKU-'.str_pad($i + 1, 5, '0', STR_PAD_LEFT),
                'description' => "High quality {$p['name']} with excellent features and warranty.",
                'regular_price' => $p['price'],
                'discount_price' => $p['sale'],
                'stock' => rand(20, 100),
                'status' => 'active',
                'is_featured' => $p['featured'] ?? false,
                'is_bestseller' => $p['bestseller'] ?? false,
                'is_new_arrival' => $p['new'] ?? false,
                'sold_count' => rand(10, 500),
            ]);

            ProductImage::create([
                'product_id' => $product->id,
                'path' => 'https://picsum.photos/seed/'.($i + 1).'/600/600',
                'is_primary' => true,
            ]);

            if (str_contains($p['name'], 'T-Shirt') || str_contains($p['name'], 'Nike')) {
                foreach (['S', 'M', 'L', 'XL'] as $size) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'name' => 'Size',
                        'value' => $size,
                        'stock' => rand(5, 20),
                    ]);
                }
            }
        }

        Banner::create([
            'title' => 'Summer Sale',
            'subtitle' => 'Up to 50% off on selected items',
            'image' => 'https://picsum.photos/seed/banner1/1400/500',
            'link' => '/products?on_sale=1',
            'button_text' => 'Shop Now',
            'sort_order' => 1,
        ]);

        Banner::create([
            'title' => 'New Arrivals',
            'subtitle' => 'Discover the latest products',
            'image' => 'https://picsum.photos/seed/banner2/1400/500',
            'link' => '/products?new_arrivals=1',
            'button_text' => 'Explore',
            'sort_order' => 2,
        ]);

        Coupon::create([
            'code' => 'WELCOME10',
            'type' => 'percentage',
            'value' => 10,
            'min_order' => 500,
            'usage_limit' => 100,
            'expires_at' => now()->addMonths(3),
        ]);

        Coupon::create([
            'code' => 'FLAT200',
            'type' => 'fixed',
            'value' => 200,
            'min_order' => 1000,
            'usage_limit' => 50,
            'expires_at' => now()->addMonth(),
        ]);

        $zone = ShippingZone::create(['name' => 'Bangladesh', 'regions' => ['BD']]);
        ShippingMethod::create(['shipping_zone_id' => $zone->id, 'name' => 'Standard Delivery', 'rate' => 60, 'estimated_days' => 3]);
        ShippingMethod::create(['shipping_zone_id' => $zone->id, 'name' => 'Express Delivery', 'rate' => 120, 'estimated_days' => 1]);

        foreach (['about-us', 'privacy-policy', 'terms-conditions', 'contact'] as $slug) {
            CmsPage::create([
                'slug' => $slug,
                'title' => ucwords(str_replace('-', ' ', $slug)),
                'content' => "<p>Content for {$slug} page. Update from admin panel.</p>",
            ]);
        }

        Faq::create(['question' => 'How do I place an order?', 'answer' => 'Browse products, add to cart, and proceed to checkout.', 'sort_order' => 1]);
        Faq::create(['question' => 'What payment methods do you accept?', 'answer' => 'We accept COD, bKash, Nagad, SSLCommerz, Stripe, and PayPal.', 'sort_order' => 2]);
        Faq::create(['question' => 'How long does delivery take?', 'answer' => 'Standard delivery takes 2-5 business days.', 'sort_order' => 3]);
    }
}
