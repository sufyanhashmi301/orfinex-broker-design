<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Enums\FundedSchemeTypes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ForexSchemaPhase
 *
 * @property int $id
 * @property int $forex_schema_id
 * @property string $group
 * @property string $type
 * @property int $validity_count
 * @property string|null $term_type
 * @property string|null $server
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property ForexSchema $forex_schema
 * @property Collection|ForexSchemaPhaseRule[] $forex_schema_phase_rules
 *
 * @package App\Models
 */
class ForexSchemaPhase extends Model
{
	use SoftDeletes;
	protected $table = 'forex_schema_phases';

	protected $casts = [
		'forex_schema_id' => 'int',
		'validity_count' => 'int',
		'phase' => 'int'
	];

	protected $fillable = [
		'forex_schema_id',
		'phase',
		'group',
		'type',
        'validity_count',
		'term_type',
		'server'
	];

	public function forexSchema()
	{
		return $this->belongsTo(ForexSchema::class);
	}

	public function forexSchemaPhaseRules()
	{
		return $this->hasMany(ForexSchemaPhaseRule::class);
	}
    public function getFundedTypeAttribute()
    {
        // Get the type from the related scheme model
        $type = data_get($this, 'type');

        // Check against defined constants in the FundedSchemeTypes interface
        switch ($type) {
            case FundedSchemeTypes::CHALLENGE_PHASE:
                return 'Challenge Phase';  // Customize the string as needed
            case FundedSchemeTypes::FUNDED_PHASE:
                return 'Funded Phase';     // Customize the string as needed
            case FundedSchemeTypes::DIRECT_FUNDING:
                return 'Direct Funding';   // Customize the string as needed
            default:
                // Default to capitalizing the first letter of the type if not matched
                return ucfirst($type);
        }
    }

}
