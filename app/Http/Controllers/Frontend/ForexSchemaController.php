<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use App\Models\Schema;
use App\Models\AccountType;
use App\Models\ForexSchema;
use App\Traits\ForexApiTrait;
use App\Http\Controllers\Controller;
use App\Models\Addon;

class ForexSchemaController extends Controller
{
    use ForexApiTrait;
    public function index()
    {
        $user = auth()->user();


//        $this->sendApiPostRequest('url','data');
//        $this->getUserApi(554944);
        
        $tagNames = $user->riskProfileTags()->pluck('name')->toArray();

        $schemas = AccountType::active()->traderType()  // Use the defined scope for active schemas
        ->relevantForUser($user->country, $tagNames)  // Use the integrated scope for filtering by country and tags
        ->orderBy('priority', 'asc')
            ->get();
//        dd($schemas);

        return view('frontend::forex_schema.index', compact('schemas'));
    }

    public function schemaPreview($id)
    {
        // $tagNames = auth()->user()->riskProfileTags()->pluck('name')->toArray();
        // $schemas = AccountType::where('status', true)
        //     ->where(function($query) use ($tagNames) {
        //         $query->whereJsonContains('countries', auth()->user()->country)
        //             ->orWhereJsonContains('countries', 'All')
        //             ->orWhere(function($subQuery) use ($tagNames) {
        //                 foreach ($tagNames as $tagName) {
        //                     $subQuery->orWhereJsonContains('tags', $tagName);
        //                 }
        //             });
        //     })
        //     ->orderBy('priority', 'asc')
        //     ->get();
            
        
        $account_type = AccountType::find($id);
        $addons = Addon::where('status', 1)->get();

        return view('frontend::forex_schema.preview', compact('account_type', 'addons'));
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
