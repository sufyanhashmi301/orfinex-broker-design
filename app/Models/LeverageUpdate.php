<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Import the User model

/**
 * Class LeverageUpdate
 * 
 * @property int $id
 * @property int $user_id
 * @property int $forex_account_id
 * @property int $last_leverage
 * @property int $updated_leverage
 * @property int $status
 * @property int|null $approved_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class LeverageUpdate extends Model
{
    protected $table = 'leverage_updates';

    protected $casts = [
        'user_id' => 'int',
        'forex_account_id' => 'int',
        'last_leverage' => 'int',
        'updated_leverage' => 'int',
        'status' => 'int',
        'approved_by' => 'int'
    ];

    protected $fillable = [
        'user_id',
        'forex_account_id',
        'last_leverage',
        'updated_leverage',
        'status',
        'approved_by'
    ];

    // Define the relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Assumes user_id is the foreign key
    }

    // If you have a ForexAccount model, you can also define that relationship
    public function forexAccount()
    {
        return $this->belongsTo(ForexAccount::class, 'forex_account_id');
    }
}
