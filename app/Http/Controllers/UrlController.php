<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Url;
use Carbon\Carbon;

class UrlController extends Controller
{
    public function shorten(Request $request)
    {

        $request->validate([
            'url' => 'required|url'
        ]);

        $shortUrl = substr(bin2hex(random_bytes(5)), 0, 10);

        $url = Url::create([
            'original_url' => $request->url,
            'short_url' => $shortUrl,
            'expires_at' => Carbon::now()->addMinute()
        ]);

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
