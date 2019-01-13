<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['title', 'slug', 'body', 'user_id', 'category_id'];

    protected $with = ['replies'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function vote()
    {
        return $this->hasMany(Votes::class);
    }

    public function voteUp()
    {
        return $this->hasMany(Votes::class)->where('type', true);
    }

    public function voteDwn()
    {
        return $this->hasMany(Votes::class)->where('type', false);
    }

    public function addView()
    {
        return $this->increment('views');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function($question) {
            $question->slug = str_slug($question->title);
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getPathAttribute()
    {
        return 'question/' . $this->slug;
    }
}
