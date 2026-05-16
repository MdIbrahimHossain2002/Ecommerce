<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\InventoryLog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(private CartService $cartService) {}

    public function createFromCart(User $user, array $data): Order
    {
        return DB::transaction(function () use ($user, $data) {
            $cart = $this->cartService->getOrCreateCart($user->id);
            $summary = $this->cartService->getSummary($cart);

            if (empty($summary['items'])) {
                abort(422, 'Cart is empty.');
            }

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-'.strtoupper(Str::random(10)),
                'status' => 'pending',
                'subtotal' => $summary['subtotal'],
                'tax' => $summary['tax'],
                'shipping' => $summary['shipping'],
                'discount' => $summary['discount'],
                'total' => $summary['total'],
                'payment_method' => $data['payment_method'] ?? 'cod',
                'payment_status' => ($data['payment_method'] ?? 'cod') === 'cod' ? 'pending' : 'pending',
                'billing_address' => $data['billing_address'] ?? null,
                'shipping_address' => $data['shipping_address'] ?? null,
                'notes' => $data['notes'] ?? null,
                'coupon_code' => $cart->coupon_code,
            ]);

            foreach ($summary['items'] as $item) {
                $product = Product::find($item['product_id']);
                $variantInfo = null;

                if ($item['product_variant_id']) {
                    $variant = ProductVariant::find($item['product_variant_id']);
                    $variantInfo = $variant ? "{$variant->name}: {$variant->value}" : null;
                    if ($variant) {
                        $variant->decrement('stock', $item['quantity']);
                    }
                }

                if ($product) {
                    $product->decrement('stock', $item['quantity']);
                    $product->increment('sold_count', $item['quantity']);

                    InventoryLog::create([
                        'product_id' => $product->id,
                        'type' => 'out',
                        'quantity' => $item['quantity'],
                        'stock_after' => $product->fresh()->stock,
                        'notes' => "Order {$order->order_number}",
                        'user_id' => $user->id,
                    ]);
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['product_variant_id'],
                    'product_name' => $product?->name ?? 'Unknown',
                    'variant_info' => $variantInfo,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['line_total'],
                ]);
            }

            if ($cart->coupon_code) {
                Coupon::where('code', $cart->coupon_code)->increment('used_count');
            }

            $cart->items()->delete();
            $cart->update(['coupon_code' => null]);

            return $order->load('items');
        });
    }
}
