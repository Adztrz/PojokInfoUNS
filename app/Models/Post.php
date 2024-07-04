<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'body',
        'group_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(PostAttachment::class)->latest();
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(PostReaction::class);
    }

    public static function postsForTimeline($userId, $getLatest = true): Builder
    {
        $query = Post::query()
            ->withCount('reactions')
            ->with([
                'user',
                'group',
                'group.currentUserGroup',
                'attachments',
                'reactions' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                }]);

        if ($getLatest) {
            $query->latest();
        }

        return $query;
    }

    public function isOwner($userId)
    {
        return $this->user_id == $userId;
    }
}
