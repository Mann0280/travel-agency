<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'location',
        'highlights',
    ];

    protected $casts = [
        'highlights' => 'array',
    ];

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class);
    }
}