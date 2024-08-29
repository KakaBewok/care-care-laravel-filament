<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreService extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'StoreServices';

    protected $fillable = [
        'car_service_id',
        'car_store_id'
    ];
}
