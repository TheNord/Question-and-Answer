<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

class ReplyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return Cache::rememberForever('reply_' .$this->id, function () {
            return [
                'id' => $this->id,
                'reply' => $this->body,
                'user' => $this->user->name,
                'user_id' => $this->user_id,
                'question_slug' => $this->question->slug,
                'like_count' => $this->like->count(),
                'liked' => !!$this->like->where('user_id', auth()->id())->count(),
                'comments' => ReplyCommentResource::collection($this->comment),
                'created_at' => $this->created_at
            ];
        });
    }
}
