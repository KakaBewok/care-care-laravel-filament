<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarStore extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'is_open',
        'is_full',
        'city_id',
        'address',
        'phone_number',
        'cs_name',
    ];

    public function carServices(): HasMany {
        return $this->hasMany(CarService::class);
    }

    public function city(): BelongsTo {
        return $this->belongsTo(City::class);
    }

    public function photos(): HasMany {
        return $this->hasMany(StorePhoto::class);
    }
}
