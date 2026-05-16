<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use App\Models\Faq;
use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    public function page(string $slug)
    {
        $page = CmsPage::where('slug', $slug)->where('is_active', true)->firstOrFail();

        return response()->json($page);
    }

    public function faqs()
    {
        return response()->json(
            Faq::where('is_active', true)->orderBy('sort_order')->get()
        );
    }

    public function subscribe(Request $request)
    {
        $data = $request->validate(['email' => 'required|email|unique:newsletter_subscriptions,email']);

        NewsletterSubscription::create($data);

        return response()->json(['message' => 'Subscribed successfully']);
    }
}
