<?php

namespace App\Http\Controllers;

use App\Http\Requests\Replies\CreateRequest;
use App\Http\Resources\ReplyResource;
use App\Models\Question;
use App\Models\Reply;
use App\UseCases\Reply\ReplyService;

class ReplyController extends Controller
{
    private $service;

    public function __construct(ReplyService $service)
    {
        $this->middleware('jwt', ['except' => ['index', 'show']]);
        $this->service = $service;
    }

    public function index(Question $question)
    {
        return ReplyResource::collection($question->replies);
    }

    public function store(Question $question, CreateRequest $request)
    {
        $reply = $this->service->create($question, $request);
        return response(['reply' => new ReplyResource($reply)], 201);
    }

    public function show(Question $question, Reply $reply)
    {
        return new ReplyResource($reply);
    }

    public function update(Question $question, Reply $reply, CreateRequest $request)
    {
        try {
            $reply = $this->service->edit($reply, $request);
            return response($reply, 200);
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()], 400);
        }

    }

    public function destroy(Question $question, Reply $reply)
    {
        try {
            $this->checkManage($reply);
            $this->service->delete($reply);
            return response(null, 204);
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()], 400);
        }
    }

    private function checkManage(Reply $reply)
    {
        if ($reply->user_id != auth()->id()) {
            throw new \Exception('You can not manage this answer');
        }
    }
}
