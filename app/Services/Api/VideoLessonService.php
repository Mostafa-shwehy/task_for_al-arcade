<?php

namespace App\Services\Api;

use App\Models\VideoLesson;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class VideoLessonService
{
    public function getAll()
    {
        return VideoLesson::with('checkpoints')->latest()->paginate(1);
    }

    public function create(array $data): VideoLesson
    {
        DB::beginTransaction();
        try {
            $lessons = VideoLesson::create($data);

            DB::commit();

            return $lessons->load('checkpoints');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


}
