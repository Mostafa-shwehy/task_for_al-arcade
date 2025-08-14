<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VideoLessonRequest;
use App\Http\Resources\LessonResource;
use App\Models\VideoLesson;
use App\Services\Api\VideoLessonService;

class LessonController extends Controller
{
    public function __construct(private VideoLessonService $videoLessonService)
    {
    }
    public function index()
    {
        $videos = $this->videoLessonService->getAll();
        return success(LessonResource::collection($videos)->response()->getData(true), 'Lessons Videos retrieved successfully', 200);
    }

    public function store(VideoLessonRequest $request)
    {
        $video = $this->videoLessonService->create($request->validated());
        return success(new LessonResource($video), 'Video created successfully', 201);
    }

    public function show($id)
    {
        $video = VideoLesson::with('checkpoints')->find($id);
        if(!$video){
            return error('This lLesson not found',[],404);
        }
        return success($video, 'Video retrieved successfully');
    }
}
