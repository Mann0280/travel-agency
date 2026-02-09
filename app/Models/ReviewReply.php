<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewReply extends Model
{
    use HasFactory;

    protected $fillable = ['review_id', 'reply_text', 'reply_by'];

    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}
