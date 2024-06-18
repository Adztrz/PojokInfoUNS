<?php

namespace App\Listeners;

use App\Events\PostCreated;
use Illuminate\Support\Facades\Log;


class LogPostCreated
{
    public function handle(PostCreated $event)
    {
        Log::info('LogPostCreated listener triggered');

        Log::channel('user_actions')->info('Post Created', [
            'post_id' => $event->post->id,
            'user_id' => $event->post->user_id,
            'title' => $event->post->title,
        ]);
    }
}
