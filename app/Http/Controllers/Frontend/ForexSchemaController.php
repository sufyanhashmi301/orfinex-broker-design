<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ForexSchema;
use App\Models\Schema;
use App\Models\User;
use App\Models\PlatformLink;
use App\Models\IbGroup;
use App\Traits\ForexApiTrait;

class ForexSchemaController extends Controller
{
    use ForexApiTrait;
    public function index()
    {

        $user = auth()->user();
        $referrer = $user->referrer;
        $isPartOfMasterIb = user_meta('is_part_of_master_ib', null, $referrer);

        $globalSchemas = ForexSchema::where('is_global', 1)->where('status', 1)->get();

        if ($referrer && $isPartOfMasterIb) {
            $ibGroup = IbGroup::with('rebateRules.forexSchemas')->find($isPartOfMasterIb);

            $forexSchemas = collect();

            foreach ($ibGroup->rebateRules as $rule) {
                $forexSchemas = $forexSchemas->merge($rule->forexSchemas);
            }

            // Remove duplicates and sort if needed
            $schemas = $forexSchemas->merge($globalSchemas)->unique('id')->sortBy('priority')->values();
        }else{
            // $this->sendApiPostRequest('url','data');
            // $this->getUserApi(554944);

            $tagNames = $user->riskProfileTags()->pluck('name')->toArray();

            $userSchemas = ForexSchema::active()
                ->traderType()
                ->relevantForUser($user->country, $tagNames)
                ->get();

            $schemas = $userSchemas->merge($globalSchemas)
                ->unique('id')
                ->sortBy('priority')
                ->values();
        }


        $activePlatform = setting('active_trader_type', 'features');
        $platformLinks = PlatformLink::where('platform', $activePlatform)->where('status', 1)->get();

        return view('frontend::forex_schema.index', compact('schemas', 'platformLinks'));
    }

    public function schemaPreview($id)
    {
        $tagNames = auth()->user()->riskProfileTags()->pluck('name')->toArray();
        $schemas = ForexSchema::where('status', true)
            ->where(function($query) use ($tagNames) {
                $query->whereJsonContains('country', auth()->user()->country)
                    ->orWhereJsonContains('country', 'All')
                    ->orWhere(function($subQuery) use ($tagNames) {
                        foreach ($tagNames as $tagName) {
                            $subQuery->orWhereJsonContains('tags', $tagName);
                        }
                    });
            })
            ->orderBy('priority', 'asc')
            ->get();
//        $schemas = ForexSchema::where('status', true)->orderBy('priority','asc')->get();
        $schema = ForexSchema::find($id);

        return view('frontend::forex_schema.preview', compact('schema', 'schemas'));
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
            'commission' => $schema->commission == 0 ? __('No Commission') : $schema->commission,
            'is_real_islamic' => $schema->is_real_islamic,
            'is_demo_islamic' => $schema->is_demo_islamic,
        ];
    }


}
