<?php

namespace App\UseCases\Question;

use App\Models\Question;
use Illuminate\Support\Facades\Redis;

class QuestionService
{
    public function addView(Question $question)
    {
        if (!auth()->check()) {
            return true;
        }

        // проверяем есть ли ключ по типу UserId_QuestionId
        // если ключа нет, создаем и добавляем просмотр к вопросу, ttl ключа сутки
        $key = auth()->id() . '_' . $question->id;

        if(Redis::EXISTS($key)) {
            return true;
        }

        Redis::SETEX($key, 86400, true);
        return $question->addView();
    }
}