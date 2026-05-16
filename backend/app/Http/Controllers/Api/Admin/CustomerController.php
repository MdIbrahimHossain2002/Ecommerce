<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user')->withCount('orders');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        return response()->json($query->latest()->paginate(15));
    }

    public function show(User $user)
    {
        return response()->json($user->load(['orders.items', 'addresses']));
    }

    public function updateStatus(Request $request, User $user)
    {
        $data = $request->validate(['status' => 'required|in:active,banned']);
        $user->update($data);

        return response()->json($user);
    }
}
