<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryLog;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images']);

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                ->orWhere('sku', 'like', "%{$request->search}%");
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        return response()->json($query->latest()->paginate(15));
    }

    public function store(Request $request)
    {
        $data = $this->validateProduct($request);
        $data['slug'] = Str::slug($data['name']).'-'.Str::random(4);

        $product = Product::create($data);
        $this->syncImages($product, $request->images ?? []);
        $this->syncVariants($product, $request->variants ?? []);

        return response()->json($product->load(['images', 'variants', 'category', 'brand']), 201);
    }

    public function show(Product $product)
    {
        return response()->json($product->load(['images', 'variants', 'category', 'brand']));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateProduct($request, $product->id);
        $product->update($data);

        if ($request->has('images')) {
            $product->images()->delete();
            $this->syncImages($product, $request->images);
        }

        if ($request->has('variants')) {
            $product->variants()->delete();
            $this->syncVariants($product, $request->variants);
        }

        return response()->json($product->fresh()->load(['images', 'variants', 'category', 'brand']));
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }

    private function validateProduct(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku'.($id ? ",{$id}" : ''),
            'barcode' => 'nullable|string',
            'description' => 'nullable|string',
            'specifications' => 'nullable|array',
            'tags' => 'nullable|array',
            'regular_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'in:active,inactive,draft',
            'seo_title' => 'nullable|string',
            'seo_description' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_bestseller' => 'boolean',
            'is_new_arrival' => 'boolean',
        ]);
    }

    private function syncImages(Product $product, array $images): void
    {
        foreach ($images as $i => $path) {
            ProductImage::create([
                'product_id' => $product->id,
                'path' => $path,
                'is_primary' => $i === 0,
                'sort_order' => $i,
            ]);
        }
    }

    private function syncVariants(Product $product, array $variants): void
    {
        foreach ($variants as $variant) {
            ProductVariant::create([
                'product_id' => $product->id,
                'name' => $variant['name'],
                'value' => $variant['value'],
                'sku' => $variant['sku'] ?? null,
                'price_adjustment' => $variant['price_adjustment'] ?? 0,
                'stock' => $variant['stock'] ?? 0,
            ]);
        }
    }
}
