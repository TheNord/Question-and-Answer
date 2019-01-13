<?php

namespace App\Http\Controllers;

use App\Events\DeleteReplyEvent;
use App\Http\Resources\ReplyResource;
use App\Models\Question;
use App\Models\Reply;
use App\Notifications\NewReplyNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt', ['except' => ['index', 'show']]);
    }

    public function index(Question $question)
    {
        return ReplyResource::collection($question->replies);
    }

    public function store(Question $question, Request $request)
    {
        $request->validate([
            'body' => 'required|string|min:30|max:1000',
        ]);

        $reply = $question->replies()->create($request->all());

        if ($reply->user_id !== $question->user_id) {
            $user = $question->user;
            $user->notify(new NewReplyNotification($reply));
        }

        return response(['reply' => new ReplyResource($reply)], 201);
    }

    public function show(Question $question, Reply $reply)
    {
        return new ReplyResource($reply);
    }

    public function update(Question $question, Reply $reply, Request $request)
    {
        try {
            $this->checkManage($reply);

            $request->validate([
                'body' => 'required|string|min:30|max:1000',
            ]);

            $reply->update([
                'body' => $request->body
            ]);

            Cache::delete('reply_' .$reply->id);

            return response($reply, 200);
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()], 400);
        }

    }

    public function destroy(Question $question, Reply $reply)
    {
        try {
            $this->checkManage($reply);
            $reply->delete();
            Cache::delete('reply_' .$reply->id);
            broadcast(new DeleteReplyEvent($reply->id))->toOthers();
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
