<?php
namespace App\Http\Controllers;

use App\Factories\UrlFactory;
use App\Repositories\UrlRepository;
use Illuminate\Http\Request;
use App\Models\Url;
use Carbon\Carbon;

class UrlController extends Controller
{
    public function shorten(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url',
        ]);

        $url = UrlFactory::create($validated['url']);

        return response()->json([
            'original' => $url->original_url,
            'short' => url($url->short_url),
            'expires_at' => $url->expires_at
        ]);
    }

    public function redirect($shortUrl)
    {
        $url = Url::where('short_url', $shortUrl)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$url) {
            return response()->json(['error' => 'Not found or expired'], 404);
        }

        return redirect($url->original_url, 302);
    }
}
