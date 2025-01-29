<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'sku_id',
        'event_id',
        'ticket_code',
        'ticket_date',
        'status',
    ];

    public function sku()
    {
        return $this->belongsTo(Sku::class);
    }
}
