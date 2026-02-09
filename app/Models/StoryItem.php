<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoryItem extends Model
{
    protected $fillable = [
        'story_id',
        'file_path',
        'type',
        'order',
    ];

    public function story()
    {
        return $this->belongsTo(Story::class);
    }
}
