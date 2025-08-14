<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoLesson extends Model
{
   use HasFactory;

    protected $fillable = ['title', 'video_url', 'duration_seconds'];

     protected $casts = [
        'duration_seconds' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function checkpoints()
    {
        return $this->hasMany(Checkpoint::class)->orderBy('timestamp_seconds');
    }

      public function getFormattedDurationAttribute()
    {
        return gmdate('H:i:s', $this->duration_seconds);
    }
     public function getYouTubeEmbedUrl()
    {
        $url = $this->video_url;

        // Extract video ID from different YouTube URL formats
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches)) {
            $videoId = $matches[1];
            return "https://www.youtube.com/embed/{$videoId}?enablejsapi=1&rel=0&modestbranding=1";
        }

        return $url; // Return original URL if not YouTube
    }

    // Check if the video is from YouTube
    public function isYouTubeVideo()
    {
        return str_contains($this->video_url, 'youtube.com') || str_contains($this->video_url, 'youtu.be');
    }
}
