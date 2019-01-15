<?php

namespace App\UseCases\Reply;


use App\Events\DeleteReplyEvent;
use App\Events\NewReplyEvent;
use App\Http\Requests\Replies\CommentRequest;
use App\Http\Requests\Replies\CreateRequest;
use App\Http\Resources\ReplyCommentResource;
use App\Models\Question;
use App\Models\Reply;
use App\Notifications\NewReplyNotification;
use Illuminate\Support\Facades\Cache;

class ReplyService
{
    public function create(Question $question, CreateRequest $request)
    {
        $reply = $question->replies()->create($request->only('body'));

        if ($reply->user_id !== $question->user_id) {
            $user = $question->user;
            $user->notify(new NewReplyNotification($reply));
        }

        return $reply;
    }

    public function addComment(Reply $reply, CommentRequest $request)
    {
        $comment = $reply->comment()->create([
            'user_id' => auth()->id(),
            'body' => $request->body
        ]);

        $comment = new ReplyCommentResource($comment);

        broadcast(new NewReplyEvent($reply->id, $comment))->toOthers();

        return $comment;
    }

    public function edit(Reply $reply, CreateRequest $request)
    {
        $reply->update([
            'body' => $request->body
        ]);

        Cache::delete('reply_' .$reply->id);

        return $reply;
    }

    public function delete(Reply $reply)
    {
        $reply->delete();
        Cache::delete('reply_' .$reply->id);
        broadcast(new DeleteReplyEvent($reply->id))->toOthers();
    }
}