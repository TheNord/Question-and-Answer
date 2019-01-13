<?php

namespace App\UseCases\Votes;

use App\Events\VoteEvent;
use App\Models\Question;

class VoteService
{
    public function deleteEarlyVote(Question $question)
    {
        $vote = $question->vote()->where('user_id', auth()->id())->first();
        if($vote) {
            $vote->delete();
        }
    }

    public function voteUp(Question $question)
    {
        $vote = $question->vote()->create([
            'user_id' => auth()->id(),
            'type' => true
        ]);

        broadcast(new VoteEvent($question->slug, 1))->toOthers();

        return $vote;
    }

    public function voteDwn(Question $question)
    {
        $vote = $question->vote()->create([
            'user_id' => auth()->id(),
            'type' => false
        ]);

        broadcast(new VoteEvent($question->slug, 0))->toOthers();

        return $vote;
    }
}