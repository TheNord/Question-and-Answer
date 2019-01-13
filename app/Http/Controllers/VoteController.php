<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\UseCases\Votes\VoteService;

class VoteController extends Controller
{
    private $service;

    public function __construct(VoteService $service)
    {
        $this->middleware('jwt');
        $this->service = $service;
    }

    public function voteUp(Question $question)
    {
        try {
            $this->checkOwner($question->user_id);
            $this->checkEarlyVoted($question, true);
            $this->service->deleteEarlyVote($question);
            $this->service->voteUp($question);
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()], 400);
        }

        return response(null, 204);
    }

    public function voteDwn(Question $question)
    {
        try {
            $this->checkOwner($question->user_id);
            $this->checkEarlyVoted($question, false);
            $this->service->deleteEarlyVote($question);
            $this->service->voteDwn($question);
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()], 400);
        }

        return response(null, 204);
    }

    private function checkOwner($id)
    {
        if ($id == auth()->id()) {
            throw new \Exception('Cannot vote your own questions');
        }
    }

    private function checkEarlyVoted(Question $question, $currentType)
    {
        $userVoteType = $question->vote()->where('user_id', auth()->id())->pluck('type')->first();

        if (!isset($userVoteType)) {
            return;
        }

        if ($userVoteType == 1 && $currentType == true) {
            throw new \Exception('You have already voted, you can only change your vote.');
        }

        if ($userVoteType == 0 && $currentType == false) {
            throw new \Exception('You have already voted, you can only change your vote.');
        }
    }
}
