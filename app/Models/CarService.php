<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CarService extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'price', 'about', 'photo', 'duration_in_hour', 'slug', 'icon'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function storeServices(): HasMany
    {
        return $this->hasMany(StoreService::class);
    }

    //test
    // public function stores(): BelongsToMany
    // {
    //     return $this->belongsToMany(CarStore::class);
    // }
}
