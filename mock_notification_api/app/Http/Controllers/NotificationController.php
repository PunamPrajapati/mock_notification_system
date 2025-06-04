<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{

    protected $cache;

    public function __construct(CacheService $cache)
    {
        $this->cache = $cache;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'message' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Errors',
                'data' => $validator->errors()->all()
            ]);
        }

        $notification = Notification::create([
            'user_id' => $request->user_id,
            'message' => $request->message,
        ]);

        $this->cache->forget(['recent_notifications', 'summary_notifications']);
        dispatch(new \App\Jobs\PublishNotificationJob($notification));

        return response()->json([
            'status' => true,
            'message' => 'Notification saved successfully',
            'data' => $notification
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:processed,failed',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Errors',
                'data' => $validator->errors()->all()
            ]);
        }

        $notification = Notification::findOrFail($id);
        $notification->status = $request->status;
        $notification->save();

        return response()->json([
            'status' => true,
            'message' => 'Status Updated',
            'data' => $notification
        ]);
    }

    public function recent()
    {
        $notifications = $this->cache->remember('recent_notifications', 10, function() {
            return Notification::orderBy('created_at', 'desc')->limit(10)->get();
        });
         
        return response()->json([
            'status' => true,
            'message' => 'Recent Notifications',
            'data' => $notifications
        ]);
    }

    public function summary()
    {
        $summary = $this->cache->remember('summary_notifications', 10, function() {
            return [
                    'total' => Notification::count(),
                    'processed' => Notification::where('status', 'processed')->count(),
                    'failed' => Notification::where('status', 'failed')->count(),
                    'pending' => Notification::where('status', 'pending')->count(),
                ];
        });
        return response()->json([
            'status' => true,
            'message' => 'Notification Summary',
            'data' => $summary
        ]);
    }
}
