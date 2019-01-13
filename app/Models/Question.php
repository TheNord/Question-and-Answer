<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['title', 'slug', 'body', 'user_id', 'tags_id'];

    protected $with = ['replies'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,
            'question_tags',
            'question_id',
            'tag_id'
        );
    }

    public function setTags($ids)
    {
        if($ids == null) {return;}

        // синхронизируем тэги
        $this->tags()->sync($ids);
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
