<?php

namespace App\UseCases\Votes;


use App\Models\Question;

class VoteRepository
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
        return $question->vote()->create([
            'user_id' => auth()->id(),
            'type' => true
        ]);
    }

    public function voteDwn(Question $question)
    {
        return $question->vote()->create([
            'user_id' => auth()->id(),
            'type' => false
        ]);
    }
}