<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = ['body', 'question_id', 'user_id'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function like()
    {
        return $this->hasMany(Like::class);
    }

    public function comment()
    {
        return $this->hasMany(ReplyComment::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($reply) {
            auth()->check() ? $reply->user_id = auth()->id() : null;
        });
    }
}
