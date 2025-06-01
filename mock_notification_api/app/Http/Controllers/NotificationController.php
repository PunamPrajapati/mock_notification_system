<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{

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

        // dispatch(new \App\Jobs\PublishNotificationJob($notification));

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
        $notifications = Notification::orderBy('created_at', 'desc')->limit(10)->get();
        return response()->json([
            'status' => true,
            'message' => 'Recent Notifications',
            'data' => $notifications
        ]);
    }

    public function summary()
    {
        $summary = [
            'total' => Notification::count(),
            'processed' => Notification::where('status', 'processed')->count(),
            'failed' => Notification::where('status', 'failed')->count(),
            'pending' => Notification::where('status', 'pending')->count(),
        ];
        return response()->json([
            'status' => true,
            'message' => 'Notification Summary',
            'data' => $summary
        ]);
    }
}
