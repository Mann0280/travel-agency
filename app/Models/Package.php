<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;

    protected $appends = ['image_url'];

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration',
        'from_city',
        'departure_cities',
        'is_featured',
        'destination_id',
        'start_date',
        'end_date',
        'agency_id',
        'agency_id',
        'travel_date',
        'location',
        'category',
        'rating',
        'reviews_count',
        'status',
        'itinerary',
        'inclusions',
        'exclusions',
        'things_to_carry',
        'terms_conditions',
        'contact_info',
        'available_months',
        'duration_days',
        'image',
        'is_approved',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'departure_cities' => 'array',
        'is_featured' => 'boolean',
        'travel_date' => 'date',
        'is_approved' => 'boolean',
        'rating' => 'decimal:2',
        'itinerary' => 'array',
        'inclusions' => 'array',
        'exclusions' => 'array',
        'things_to_carry' => 'array',
        'terms_conditions' => 'array',
        'contact_info' => 'array',
        'available_months' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Scope to get only approved packages
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return $this->destination->image ?? asset('images/placeholder.jpg');
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        return asset('storage/' . $this->image);
    }

    public function packageAgencies(): HasMany
    {
        return $this->hasMany(PackageAgency::class);
    }
}