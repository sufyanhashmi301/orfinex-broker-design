<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class IbQuestionAnswer
 *
 * @property int $id
 * @property string $user_id
 * @property string $fields
 * @property Carbon|null $submitted_at
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class IbQuestionAnswer extends Model
{
	protected $table = 'ib_question_answers';

	protected $fillable = [
		'user_id',
		'fields',
		'submitted_at',
		'ip_address',
		'user_agent'
	];

	protected $casts = [
		'fields' => 'array',
		'submitted_at' => 'datetime',
		'created_at' => 'datetime',
		'updated_at' => 'datetime'
	];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get sanitized fields data
     */
    public function getSanitizedFieldsAttribute(): array
    {
        if (!is_array($this->fields)) {
            return [];
        }

        $sanitized = [];
        foreach ($this->fields as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = array_map('strip_tags', $value);
            } else {
                $sanitized[$key] = strip_tags($value);
            }
        }

        return $sanitized;
    }

    /**
     * Scope for recent submissions
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('submitted_at', '>=', now()->subDays($days));
    }

    /**
     * Scope for specific user
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
