<?php

namespace App\Models;

use DateTimeZone;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Campaign extends Model
{
    use HasSlug;

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function prizes(): HasMany
    {
        return $this->hasMany(Prize::class);
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
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

    protected function nowInTimeZone(): Attribute
    {
        return Attribute::make(
            get: fn () => now()->setTimezone($this->timezone),
        );
    }

    public function hasNotStarted()
    {
        return $this->starts_at->greaterThan($this->now_in_time_zone) ?? false;
    }

    public function hasEnded()
    {
        return $this->ends_at->lessThan($this->now_in_time_zone) ?? false;
    }
}
