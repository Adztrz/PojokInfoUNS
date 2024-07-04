<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'created_at' => Carbon::parse($this->created_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
            'user' => new UserResource($this->user),
            'group' => new GroupResource($this->group),
            'attachments' => PostAttachmentResource::collection($this->attachments),
            'num_of_reactions' => $this->reactions_count,
            'current_user_has_reaction' => $this->reactions->count() > 0
        ];
    }
}
