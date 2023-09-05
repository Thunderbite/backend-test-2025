<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prize extends Model
{
    protected $fillable = [
        'campaign_id',
        'name',
        'description',
        'level',
        'weight',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('name', 'like', '%'.$query.'%');
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function prizes()
    {
        return $this->hasMany(PrizeTable::class, 'prize_id');
    }
}
