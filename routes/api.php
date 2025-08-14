<?php

use App\Http\Controllers\Api\CheckpointController;
use App\Http\Controllers\Api\LessonController;
use Illuminate\Support\Facades\Route;



Route::apiResource( '/videos', LessonController::class)->only(['index','store', 'show']);

Route::post('/videos/{video}/checkpoints', [CheckpointController::class, 'store']);
Route::delete('/checkpoints/{id}', [CheckpointController::class, 'destroy']);
Route::get('/videos/{video}/next-event', [CheckpointController::class, 'nextEvent']);


Route::any('{url}', function () {
    return error('page not found', ['error'=>'page not found'], 404);
})->where('url', '.*')->middleware('api');
