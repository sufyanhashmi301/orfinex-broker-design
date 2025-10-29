<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SuccessPage extends Model
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
        'subtitle',
        'message',
        'quote',
        'quote_author',
        'image_path',
        'button_text',
        'button_link',
        'button_type',
        'trustpilot_button_show',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'trustpilot_button_show' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache when success page is created or updated
        static::saved(function ($successPage) {
            Cache::forget("success_page_{$successPage->type}");
        });

        // Clear cache when success page is deleted
        static::deleted(function ($successPage) {
            Cache::forget("success_page_{$successPage->type}");
        });
    }

    /**
     * Scope a query to only include active success pages.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query;
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
     * Get success page by type with caching.
     *
     * @param  string  $type
     * @return self|null
     */
    public static function getByType($type)
    {
        return Cache::remember("success_page_{$type}", 3600, function () use ($type) {
            return self::byType($type)->first();
        });
    }

    /**
     * Check if a custom success page exists for a type.
     *
     * @param  string  $type
     * @return bool
     */
    public static function hasCustomPage($type)
    {
        return self::getByType($type) !== null;
    }

    /**
     * Clear cache for specific type.
     *
     * @param  string  $type
     * @return void
     */
    public static function clearCache($type)
    {
        Cache::forget("success_page_{$type}");
    }

    /**
     * Clear all success page caches.
     *
     * @return void
     */
    public static function clearAllCache()
    {
        $types = ['deposit', 'withdrawal', 'transfer', 'payment'];
        
        foreach ($types as $type) {
            Cache::forget("success_page_{$type}");
        }
    }
}
