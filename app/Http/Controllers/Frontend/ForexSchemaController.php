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
        try {
            $user = auth()->user();
            $isPartOfMasterIb = user_meta('is_part_of_master_ib', null, $user);

            $tagNames = $user->riskProfileTags()->pluck('name')->toArray();

            $globalSchemas = ForexSchema::active()
                ->traderType()
                ->where('is_global', 1)
                ->get();

            $userSchemas = ForexSchema::active()
                ->traderType()
                ->relevantForUser($user->country, $tagNames)
                ->get();

            $schemas = collect();

            if ($isPartOfMasterIb) {
                $ibGroup = IbGroup::with('rebateRules.forexSchemas')->find($isPartOfMasterIb);

                if ($ibGroup) {
                    foreach ($ibGroup->rebateRules as $rule) {
                        $schemas = $schemas->merge($rule->forexSchemas->where('status', true));
                    }
                }
            }
            $schemas = $schemas->merge($userSchemas)->merge($globalSchemas)
                ->unique('id')
                ->sortBy('priority')
                ->values();

            $activePlatform = setting('active_trader_type', 'features');
            $platformLinks = PlatformLink::where('platform', $activePlatform)->where('status', 1)->get();

            return view('frontend::forex_schema.index', compact('schemas', 'platformLinks'));

        } catch (\Exception $e) {
            // Log the error with detailed context
            \Log::error('Error in ForexSchemaController@index', [
                'user_id' => auth()->id(),
                'message' => $e->getMessage(),
            ]);

            return redirect()->back()->withErrors(['An unexpected error occurred. Please try again later.']);
        }
    }


    public function schemaPreview($id)
    {
        $id = get_hash($id);
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
