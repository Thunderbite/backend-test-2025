<?php

namespace App\Models;

use DateTimeZone;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Campaign extends Model
{
    use HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'timezone', 'name', 'slug', 'starts_at', 'ends_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public static function search($query)
    {
        return empty($query) ? static::query()
            : static::where('name', 'like', '%'.$query.'%')
                ->orWhere('timezone', 'like', '%'.$query.'%')
                ->orWhere('starts_at', 'like', '%'.$query.'%')
                ->orWhere('ends_at', 'like', '%'.$query.'%');
    }

    public function getAvailableTimezones()
    {
        // Set empty value
        $return[null] = '';

        // Build array
        foreach (DateTimeZone::listIdentifiers(DateTimeZone::ALL) as $timezone) {
            $return[$timezone] = $timezone;
        }

        // Return
        return $return;
    }
}
