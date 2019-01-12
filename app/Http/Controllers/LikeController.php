<?php

namespace App\Http\Controllers;

use App\Events\LikeEvent;
use App\Models\Reply;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt');
    }

    public function likeIt(Reply $reply)
    {
        try {
            $this->checkOwner($reply->user_id);
            $reply->like()->create(['user_id' => auth()->id()]);
            broadcast(new LikeEvent($reply->id, 1))->toOthers();
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()], 400);
        }

    }

    public function unLikeIt(Reply $reply)
    {
        $this->checkOwner($reply->user_id);
        $reply->like()->where('user_id', auth()->id())->first()->delete();
        broadcast(new LikeEvent($reply->id, 0))->toOthers();
    }

    private function checkOwner($id) {
        if($id == auth()->id()) {
            throw new \Exception('Cannot rate your own answers');
        }
    }
}
