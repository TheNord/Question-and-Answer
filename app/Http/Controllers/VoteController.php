<?php

namespace App\Http\Controllers;

use App\Events\VoteEvent;
use App\Models\Question;
use App\UseCases\Votes\VoteRepository;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    private $repository;

    public function __construct(VoteRepository $repository)
    {
        $this->middleware('jwt');
        $this->repository = $repository;
    }

    public function voteUp(Question $question)
    {
        try {
            $this->checkOwner($question->user_id);
            $this->checkEarlyVoted($question, true);
            $this->repository->deleteEarlyVote($question);
            $this->repository->voteUp($question);

            broadcast(new VoteEvent($question->slug, 1))->toOthers();
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()], 400);
        }

    }

    public function voteDwn(Question $question)
    {
        try {
            $this->checkOwner($question->user_id);
            $this->checkEarlyVoted($question, false);
            $this->repository->deleteEarlyVote($question);
            $this->repository->voteDwn($question);

            broadcast(new VoteEvent($question->slug, 0))->toOthers();
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()], 400);
        }
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

        if(!isset($userVoteType)) {
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
