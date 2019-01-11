<?php

use Illuminate\Http\Request;

Route::group([
    'prefix' => 'auth'
], function () {

    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('check', 'AuthController@check');

});

Route::post('/notifications', 'NotificationController@notificationCounts');
Route::post('/notifications/markAsRead', 'NotificationController@markAsRead');

Route::apiResource('/question', 'QuestionController');
Route::apiResource('/category', 'CategoryController');
Route::apiResource('/question/{question}/reply', 'ReplyController');

Route::post('/like/{reply}', 'LikeController@likeIt');
Route::delete('/like/{reply}', 'LikeController@unLikeIt');

Route::post('/voteUp/{question}', 'VoteController@voteUp');
Route::post('/voteDwn/{question}', 'VoteController@voteDwn');