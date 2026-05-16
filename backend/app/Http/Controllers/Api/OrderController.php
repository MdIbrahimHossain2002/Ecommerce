<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    public function index(Request $request)
    {
        $orders = Order::with('items')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'payment_method' => 'required|in:cod,stripe,paypal,sslcommerz,bkash,nagad,rocket,bank_transfer',
            'billing_address' => 'required|array',
            'shipping_address' => 'required|array',
            'notes' => 'nullable|string',
        ]);

        $order = $this->orderService->createFromCart($request->user(), $data);

        return response()->json($order, 201);
    }

    public function show(Request $request, string $orderNumber)
    {
        $order = Order::with('items')
            ->where('user_id', $request->user()->id)
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return response()->json($order);
    }
}
