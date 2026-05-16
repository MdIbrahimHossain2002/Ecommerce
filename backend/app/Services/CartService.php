<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function getOrCreateCart(?int $userId = null, ?string $sessionId = null): Cart
    {
        $userId = $userId ?? Auth::id();

        if ($userId) {
            return Cart::firstOrCreate(['user_id' => $userId]);
        }

        return Cart::firstOrCreate(['session_id' => $sessionId ?? session()->getId()]);
    }

    public function addItem(Cart $cart, int $productId, int $quantity = 1, ?int $variantId = null): CartItem
    {
        $product = Product::findOrFail($productId);

        if ($variantId) {
            $variant = ProductVariant::where('product_id', $productId)->findOrFail($variantId);
            if ($variant->stock < $quantity) {
                abort(422, 'Insufficient stock for selected variant.');
            }
        } elseif ($product->stock < $quantity) {
            abort(422, 'Insufficient stock.');
        }

        $item = $cart->items()
            ->where('product_id', $productId)
            ->where('product_variant_id', $variantId)
            ->first();

        if ($item) {
            $item->update(['quantity' => $item->quantity + $quantity]);
        } else {
            $item = $cart->items()->create([
                'product_id' => $productId,
                'product_variant_id' => $variantId,
                'quantity' => $quantity,
            ]);
        }

        return $item->load(['product.images', 'variant']);
    }

    public function getSummary(Cart $cart): array
    {
        $cart->load(['items.product.images', 'items.variant']);

        $subtotal = 0;
        $items = [];

        foreach ($cart->items as $item) {
            $price = $item->product->effective_price;
            if ($item->variant) {
                $price += (float) $item->variant->price_adjustment;
            }
            $lineTotal = $price * $item->quantity;
            $subtotal += $lineTotal;

            $items[] = [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_variant_id' => $item->product_variant_id,
                'quantity' => $item->quantity,
                'price' => $price,
                'line_total' => $lineTotal,
                'product' => $item->product,
                'variant' => $item->variant,
            ];
        }

        $taxRate = (float) (Setting::get('tax_rate', 0) ?? 0);
        $tax = round($subtotal * ($taxRate / 100), 2);

        $shipping = (float) (Setting::get('default_shipping', 60) ?? 60);
        if ($subtotal >= (float) (Setting::get('free_shipping_min', 2000) ?? 2000)) {
            $shipping = 0;
        }

        $discount = 0;
        if ($cart->coupon_code) {
            $coupon = Coupon::where('code', $cart->coupon_code)->first();
            if ($coupon && $coupon->isValid()) {
                $discount = $coupon->calculateDiscount($subtotal);
            }
        }

        $total = max(0, $subtotal + $tax + $shipping - $discount);

        return compact('items', 'subtotal', 'tax', 'shipping', 'discount', 'total', 'coupon_code');
    }
}
