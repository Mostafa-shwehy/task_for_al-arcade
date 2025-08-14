<?php

namespace App\Services\Api;

use App\Models\Checkpoint;
use App\Models\VideoLesson;
use Illuminate\Support\Facades\DB;

class CheckpointService
{
    public function create(VideoLesson $video, array $data): Checkpoint
    {
        DB::beginTransaction();
        try {
            $checkpoint = $video->checkpoints()->create($data);

            DB::commit();

            return $checkpoint;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function nextEvent(VideoLesson $video, int $after): ?Checkpoint
    {
        return $video->checkpoints()
            ->where('timestamp_seconds', '>', $after)
            ->orderBy('timestamp_seconds')
            ->first();
    }
}
