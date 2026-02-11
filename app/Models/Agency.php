<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $name
 * @property string|null $contact_person
 * @property string|null $email
 * @property string|null $password
 * @property string|null $phone
 * @property string|null $alternate_phone
 * @property string|null $address
 * @property string|null $address_line1
 * @property string|null $address_line2
 * @property string|null $city
 * @property string|null $state
 * @property string|null $country
 * @property string|null $pincode
 * @property string|null $logo
 * @property int $experience_years
 * @property float $commission_percentage
 * @property float $rating
 * @property string $status
 * @property bool $is_verified
 * @property string|null $bank_name
 * @property string|null $account_holder
 * @property string|null $account_number
 * @property string|null $ifsc_code
 * @property string|null $gst_number
 * @property string|null $pan_number
 * @property string|null $license_number
 * @property string|null $payment_terms
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Agency extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'description',
        'contact_person',
        'email',
        'password',
        'phone',
        'alternate_phone',
        'address',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'country',
        'pincode',
        'logo',
        'experience_years',
        'commission_percentage',
        'rating',
        'status',
        'is_verified',
        'bank_name',
        'account_holder',
        'account_number',
        'ifsc_code',
        'gst_number',
        'pan_number',
        'license_number',
        'payment_terms',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'commission_percentage' => 'float',
        'rating' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class);
    }
}