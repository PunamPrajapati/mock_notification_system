<?php

namespace App\Jobs;

use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Redis;

class PublishNotificationJob implements ShouldQueue
{
    use Queueable;
    protected $notification;

    /**
     * Create a new job instance.
     */
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $payload = [
        'id' => $this->notification->id,
        'user_id' => $this->notification->user_id,
        'message' => $this->notification->message,
        'type' => 'push',
        'status' => 'pending',
        'created_at' => $this->notification->created_at->toISOString()
    ];

    // Send to Redis or RabbitMQ
    Redis::publish('notifications', json_encode($payload));
    }
}
