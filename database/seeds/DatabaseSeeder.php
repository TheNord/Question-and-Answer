<?php

use App\Models\QuestionTag;
use App\Models\Tag;
use App\Models\Like;
use App\Models\Question;
use App\Models\Reply;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 10)->create();
        factory(Tag::class, 15)->create();
        factory(Question::class, 10)->create();
        factory(QuestionTag::class, 30)->create();
        factory(Reply::class, 20)->create()->each(function ($reply) {
            return $reply->like()->save(factory(Like::class)->make());
        });
    }
}
