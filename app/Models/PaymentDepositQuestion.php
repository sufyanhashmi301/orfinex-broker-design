<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PaymentDepositQuestion
 * 
 * @property int $id
 * @property string $name
 * @property string $fields
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class PaymentDepositQuestion extends Model
{
    protected $table = 'payment_deposit_questions';

    protected $casts = [
        'fields' => 'array',
        'status' => 'int'
    ];

    protected $fillable = [
        'name',
        'fields',
        'status'
    ];

    /**
     * Get decoded fields
     */
    public function getDecodedFieldsAttribute()
    {
        return json_decode($this->fields, true);
    }

    /**
     * Scope for active questions
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
