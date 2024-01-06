<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class IbQuestionAnswer
 *
 * @property int $id
 * @property string $user_id
 * @property string $fields
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
		'fields'
	];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
