<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PopularSearch extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_city',
        'to_city',
        'display_text',
        'status',
        'order',
        'clicks',
    ];

    protected $casts = [
        'order' => 'integer',
        'clicks' => 'integer',
    ];

    /**
     * Scope to get only active searches
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get searches ordered by order field
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    /**
     * Increment click count
     */
    public function incrementClicks()
    {
        $this->increment('clicks');
    }

    /**
     * Get URL parameters for this search
     */
    public function getUrlParametersAttribute()
    {
        $params = [];
        
        if ($this->from_city) {
            $params['from'] = $this->from_city;
        }
        
        if ($this->to_city) {
            $params['to'] = $this->to_city;
        }
        
        if ($this->from_city || $this->to_city) {
            $params['search'] = '1';
        }
        
        return $params;
    }

    /**
     * Auto-generate display text if not provided
     */
    public static function generateDisplayText($fromCity, $toCity, $displayText = null)
    {
        if ($displayText) {
            return $displayText;
        }

        if ($fromCity && $toCity) {
            return $fromCity . ' → ' . $toCity;
        } elseif ($fromCity) {
            return 'From ' . $fromCity;
        } elseif ($toCity) {
            return 'To ' . $toCity;
        }

        return 'Custom Search';
    }
}