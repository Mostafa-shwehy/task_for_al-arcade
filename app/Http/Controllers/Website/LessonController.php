<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\VideoLesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
      public function index()
    {
        $lessons = VideoLesson::withCount('checkpoints')->latest()->paginate(12);
        return view('lessons.index', compact('lessons'));
    }

    public function show($id)
    {
        $lesson = VideoLesson::with('checkpoints')->find($id);

        if (!$lesson) {
            abort(404, 'Lesson not found');
        }

        return view('lessons.show', compact('lesson'));
    }
}
