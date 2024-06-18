<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Post;
use Illuminate\Support\Facades\Log;

class PostCreated
{
    use Dispatchable, SerializesModels;

    public $post;

    public function __construct(Post $post)
    {
        $this->post = $post;

        Log::info('PostCreated event created', [
            'post_id' => $this->post->id,
            'user_id' => $this->post->user_id,
            'title' => $this->post->title,
        ]);
    }
}