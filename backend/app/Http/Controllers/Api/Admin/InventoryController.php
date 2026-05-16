<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryLog;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryLog::with(['product:id,name,sku', 'user:id,name']);

        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        return response()->json($query->latest()->paginate(20));
    }

    public function adjust(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:in,out,adjust',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $product = Product::findOrFail($data['product_id']);

        match ($data['type']) {
            'in' => $product->increment('stock', $data['quantity']),
            'out' => $product->decrement('stock', min($data['quantity'], $product->stock)),
            'adjust' => $product->update(['stock' => $data['quantity']]),
        };

        $log = InventoryLog::create([
            'product_id' => $product->id,
            'type' => $data['type'],
            'quantity' => $data['quantity'],
            'stock_after' => $product->fresh()->stock,
            'notes' => $data['notes'] ?? null,
            'user_id' => $request->user()->id,
        ]);

        return response()->json($log, 201);
    }

    public function lowStock()
    {
        return response()->json(
            Product::where('stock', '<=', 10)->orderBy('stock')->get(['id', 'name', 'sku', 'stock'])
        );
    }
}
