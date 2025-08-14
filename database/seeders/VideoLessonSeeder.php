<?php

namespace Database\Seeders;

use App\Models\VideoLesson;
use Illuminate\Database\Seeder;

class VideoLessonSeeder extends Seeder
{
    public function run(): void
    {
        $videos = [
            ['title' => 'Laravel Installation', 'video_url' => 'https://youtu.be/HHj6YU43eV4?si=X8I_a0qd2r2RbHVr', 'duration_seconds' => 4000],
            ['title' => 'PHP OOP', 'video_url' => 'https://youtu.be/9ZNx-mFyB5g?si=DT3ukPZTzXrQh3oX', 'duration_seconds' => 3000],
        ];

        foreach ($videos as $videoData) {
            $video = VideoLesson::create($videoData);
            $video->checkpoints()->createMany([
                ['timestamp_seconds' => 50, 'event_type' => 'note', 'event_data' => ['text' => 'Intro']],
                ['timestamp_seconds' => 150, 'event_type' => 'quiz', 'event_data' => ['question' => 'Install Xampp?']],
                ['timestamp_seconds' => 250, 'event_type' => 'popup', 'event_data' => ['text' => 'Install Composer']]
            ]);
        }
    }
}
