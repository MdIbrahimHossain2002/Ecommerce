<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        return response()->json($request->user()->addresses);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'label' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line' => 'required|string',
            'city' => 'required|string',
            'state' => 'nullable|string',
            'postal_code' => 'required|string',
            'country' => 'nullable|string',
            'is_default' => 'boolean',
        ]);

        if ($data['is_default'] ?? false) {
            $request->user()->addresses()->update(['is_default' => false]);
        }

        $address = $request->user()->addresses()->create($data);

        return response()->json($address, 201);
    }

    public function update(Request $request, Address $address)
    {
        if ($address->user_id !== $request->user()->id) {
            abort(403);
        }

        $data = $request->validate([
            'label' => 'nullable|string|max:50',
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'address_line' => 'sometimes|string',
            'city' => 'sometimes|string',
            'state' => 'nullable|string',
            'postal_code' => 'sometimes|string',
            'country' => 'nullable|string',
            'is_default' => 'boolean',
        ]);

        if ($data['is_default'] ?? false) {
            $request->user()->addresses()->update(['is_default' => false]);
        }

        $address->update($data);

        return response()->json($address);
    }

    public function destroy(Request $request, Address $address)
    {
        if ($address->user_id !== $request->user()->id) {
            abort(403);
        }

        $address->delete();

        return response()->json(['message' => 'Address deleted']);
    }
}
