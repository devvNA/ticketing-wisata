<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'vendor_id',
        'event_category_id',
        'name',
        'description',
        'image',
        'start_date',
        'end_date',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function eventCategory()
    {
        return $this->belongsTo(EventCategory::class);
    }

    public function skus()
    {
        return $this->hasMany(Sku::class);
    }
}
