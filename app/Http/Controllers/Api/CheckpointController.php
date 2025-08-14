<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckpointRequest;
use App\Http\Resources\CheckpointResource;
use App\Models\Checkpoint;
use App\Models\VideoLesson;
use App\Services\Api\CheckpointService;
use Illuminate\Http\Request;

class CheckpointController extends Controller
{
    public function __construct(private CheckpointService $checkpointService)
    {
    }

    public function store(CheckpointRequest $request, VideoLesson $video)
    {
        $checkpoint = $this->checkpointService->create($video, $request->validated());
        return success(new CheckpointResource($checkpoint), 'Checkpoint created successfully', 201);
    }

    public function destroy($id)
    {
        $checkpoint=Checkpoint::find($id);
        if(!$checkpoint){
            return error('This Checkpoint not found',[],404);
        }
        $checkpoint->delete();
        return success([], 'Checkpoint deleted successfully', 200);
    }

    public function nextEvent(VideoLesson $video, Request $request)
    {
        $after = (int) $request->query('after', 0);
        $event = $this->checkpointService->nextEvent($video, $after);

        if (!$event) {
            return error('No next event found', [], 404);
        }

        return success($event, 'Next event retrieved successfully', 200);
    }
}
