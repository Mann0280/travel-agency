<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageAgency extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'agency_id',
        'price',
        'commission',
        'duration',
        'date',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'commission' => 'decimal:2',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }
}
