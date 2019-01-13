<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
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
            'reply_count' => $this->replies->count(),
            'vote_count' => $this->voteCount($votes),
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
            'tags' => TagResource::collection($this->tags),
            'views' => $this->views
        ];
    }

    private function voteCount($votes) {
        $voteUp = $votes->where('type', 1)->count();
        $voteDwn = $votes->where('type', 0)->count();

        return $voteUp - $voteDwn;
    }
}
