<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenue = Order::whereNotIn('status', ['cancelled'])->sum('total');
        $totalOrders = Order::count();
        $totalCustomers = User::where('role', 'user')->count();
        $totalProducts = Product::count();
        $lowStock = Product::where('stock', '<=', 10)->count();

        $recentOrders = Order::with('user:id,name,email')
            ->latest()
            ->limit(10)
            ->get();

        $monthlySales = Order::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw('SUM(total) as revenue'),
            DB::raw('COUNT(*) as orders')
        )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $ordersByStatus = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        return response()->json([
            'stats' => compact('totalRevenue', 'totalOrders', 'totalCustomers', 'totalProducts', 'lowStock'),
            'recent_orders' => $recentOrders,
            'monthly_sales' => $monthlySales,
            'orders_by_status' => $ordersByStatus,
        ]);
    }
}
