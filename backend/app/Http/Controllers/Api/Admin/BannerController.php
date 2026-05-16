<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        return response()->json(Banner::orderBy('sort_order')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'subtitle' => 'nullable|string',
            'image' => 'required|string',
            'link' => 'nullable|string',
            'button_text' => 'nullable|string',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ]);

        return response()->json(Banner::create($data), 201);
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $request->validate([
            'title' => 'sometimes|string',
            'subtitle' => 'nullable|string',
            'image' => 'sometimes|string',
            'link' => 'nullable|string',
            'button_text' => 'nullable|string',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ]);

        $banner->update($data);

        return response()->json($banner);
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();

        return response()->json(['message' => 'Banner deleted']);
    }
}
