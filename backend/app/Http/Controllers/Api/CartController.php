<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cartService) {}

    public function index(Request $request)
    {
        $cart = $this->cartService->getOrCreateCart($request->user()->id);

        return response()->json($this->cartService->getSummary($cart));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'integer|min:1',
        ]);

        $cart = $this->cartService->getOrCreateCart($request->user()->id);
        $this->cartService->addItem(
            $cart,
            $data['product_id'],
            $data['quantity'] ?? 1,
            $data['product_variant_id'] ?? null
        );

        return response()->json($this->cartService->getSummary($cart->fresh()));
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $cartItem->update(['quantity' => $request->quantity]);

        $cart = $this->cartService->getOrCreateCart($request->user()->id);

        return response()->json($this->cartService->getSummary($cart->fresh()));
    }

    public function destroy(Request $request, CartItem $cartItem)
    {
        $cartItem->delete();
        $cart = $this->cartService->getOrCreateCart($request->user()->id);

        return response()->json($this->cartService->getSummary($cart->fresh()));
    }

    public function applyCoupon(Request $request)
    {
        $data = $request->validate(['code' => 'required|string']);
        $coupon = Coupon::where('code', $data['code'])->first();

        if (! $coupon || ! $coupon->isValid()) {
            return response()->json(['message' => 'Invalid or expired coupon'], 422);
        }

        $cart = $this->cartService->getOrCreateCart($request->user()->id);
        $cart->update(['coupon_code' => $coupon->code]);

        return response()->json($this->cartService->getSummary($cart->fresh()));
    }
}
