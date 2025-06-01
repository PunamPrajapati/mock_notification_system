<?php

namespace App\Http\Middleware;

use App\Models\Notification;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RateLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->input('user_id');

        if (!$userId) {
            return response()->json([
                'message' => 'Missing user_id in request.'
            ], 400);
        }

        $limit = 10;
        $timeWindow = Carbon::now()->subHour();

        $count = Notification::where('user_id', $userId)
            ->where('created_at', '>=', $timeWindow)
            ->count();

        if ($count >= $limit) {
            return response()->json([
                'message' => 'Rate limit exceeded. Max 10 notifications per hour.'
            ], 429);
        }

        return $next($request);
    }
}
