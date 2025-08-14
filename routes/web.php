<?php

use App\Http\Controllers\Website\CheckpointController;
use App\Http\Controllers\Website\LessonController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [LessonController::class, 'index'])->name('lessons.index');
Route::get('/lessons/{id}', [LessonController::class, 'show'])->name('lessons.show');

Route::get('/lessons/{video}/next-checkpoint', [CheckpointController::class, 'nextEvent'])->name('api.checkpoints.next');
