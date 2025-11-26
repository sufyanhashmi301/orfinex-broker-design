<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ErrorPage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'title',
        'description',
        'message',
        'button_text',
        'button_link',
        'button_type',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache when error page is created or updated
        static::saved(function ($errorPage) {
            Cache::forget("error_page_{$errorPage->type}");
        });

        // Clear cache when error page is deleted
        static::deleted(function ($errorPage) {
            Cache::forget("error_page_{$errorPage->type}");
        });
    }

    /**
     * Scope a query to filter by type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get error page by type with caching.
     *
     * @param  string  $type
     * @return self|null
     */
    public static function getByType($type)
    {
        return Cache::remember("error_page_{$type}", 3600, function () use ($type) {
            return self::byType($type)->first();
        });
    }

    /**
     * Clear cache for specific type.
     *
     * @param  string  $type
     * @return void
     */
    public static function clearCache($type)
    {
        Cache::forget("error_page_{$type}");
    }
}

