<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $votes = $this->vote;

        return [
            'title' => $this->title,
            'path' => $this->path,
            'body' => $this->body,
            'replies' => ReplyResource::collection($this->replies),
            'reply_count' => $this->replies->count(),
            'voted' => $this->voteType($votes),
            'vote_count' => $this->voteCount($votes),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
            'user' => $this->user->name,
            'user_id' => $this->user->id,
            'category' => $this->category->name,
            'slug' => $this->slug,
            'views' => $this->views
        ];
    }

    private function voteType($votes)
    {
        if ($votes->count() > 0 && auth()->check()) {
            return $votes->where('user_id', auth()->id())->pluck('type')->first();
        }

        return null;
    }

    private function voteCount($votes)
    {
        $voteUp = $votes->where('type', 1)->count();
        $voteDwn = $votes->where('type', 0)->count();

        return $voteUp - $voteDwn;
    }
}
