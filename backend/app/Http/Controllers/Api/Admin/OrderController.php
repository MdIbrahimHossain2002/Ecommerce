<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user:id,name,email', 'items']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where('order_number', 'like', "%{$request->search}%");
        }

        return response()->json($query->latest()->paginate(15));
    }

    public function show(Order $order)
    {
        return response()->json($order->load(['user', 'items']));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,returned,refunded',
            'tracking_number' => 'nullable|string',
            'payment_status' => 'nullable|in:pending,paid,failed,refunded',
        ]);

        $order->update($data);

        return response()->json($order);
    }
}
