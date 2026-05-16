<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        return response()->json(Coupon::latest()->paginate(15));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:coupons',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'is_active' => 'boolean',
        ]);

        return response()->json(Coupon::create($data), 201);
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $request->validate([
            'code' => 'sometimes|string|unique:coupons,code,'.$coupon->id,
            'type' => 'in:percentage,fixed',
            'value' => 'numeric|min:0',
            'min_order' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $coupon->update($data);

        return response()->json($coupon);
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return response()->json(['message' => 'Coupon deleted']);
    }
}
