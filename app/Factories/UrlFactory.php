<?php

namespace App\Factories;

use App\Models\Url;
use Carbon\Carbon;

class UrlFactory
{
    public static function create($originalUrl)
    {
        $expiresAt = Carbon::now()->addMinutes();
        return Url::create([
            'original_url' => $originalUrl,
            'short_url' => substr(bin2hex(random_bytes(5)), 0, 10),
            'expires_at' => $expiresAt,
        ]);
    }
}
