<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkpoint extends Model
{
    use HasFactory;

    protected $fillable = ['video_lesson_id', 'timestamp_seconds', 'event_type', 'event_data'];

      protected $casts = [
        'timestamp_seconds' => 'integer',
        'event_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function videoLesson()
    {
        return $this->belongsTo(VideoLesson::class);
    }
     public function getFormattedTimestampAttribute()
    {
        return gmdate('i:s', $this->timestamp_seconds);
    }
}
