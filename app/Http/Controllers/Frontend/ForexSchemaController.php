<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\TxnStatus;
use App\Models\User;
use App\Models\Schema;
use App\Models\AccountType;
use App\Models\ForexSchema;
use App\Traits\ForexApiTrait;
use App\Http\Controllers\Controller;
use App\Models\AccountTypeInvestment;
use App\Models\Addon;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class ForexSchemaController extends Controller
{
    use ForexApiTrait;
    public function index()
    {
        
    }

    public function schemaPreview($id)
    {
        
        
    }

    public function schemaSelect($id)
    {
        $schema = ForexSchema::find($id);
        $currency = setting('site_currency', 'global');

        $leveragedropdown = '';
        foreach (explode(',', $schema->leverage) as $leverage) {
            $leveragedropdown .= '<option value="' . $leverage . '">' . $leverage . '</option>';
        }

        return [
            'leverage' => $leveragedropdown,
            'display_leverage' => explode(',', $schema->leverage)[0],
            'first_min_deposit' => !empty($schema->first_min_deposit) ? $schema->first_min_deposit : 0,
            'spread' => !empty($schema->spread) ? $schema->spread : 0,
            'commission' => $schema->commission == 0 ? 'No Commission' : $schema->commission,
            'is_real_islamic' => $schema->is_real_islamic,
            'is_demo_islamic' => $schema->is_demo_islamic,
        ];
    }


}
