<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ReplyComment extends Model
{
    protected $table = 'reply_comments';

    protected $fillable = ['body', 'user_id', 'question_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
