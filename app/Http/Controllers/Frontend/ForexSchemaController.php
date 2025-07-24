<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ForexSchema;
use App\Models\Schema;
use App\Models\User;
use App\Models\PlatformLink;
use App\Models\IbGroup;
use App\Traits\ForexApiTrait;
use App\Models\UserMeta;
class ForexSchemaController extends Controller
{
    use ForexApiTrait;
    public function index()
    {
        // try {
            $user = auth()->user();
            $tagNames = $user->riskProfileTags()->pluck('name')->toArray();
    
            // Get user's master IB status
            $isPartOfMasterIb = UserMeta::where('user_id', $user->id)
                ->where('meta_key', 'is_part_of_master_ib')
                ->value('meta_value');
                // dd($isPartOfMasterIb);  
    
            // Base query for all schemas
            $baseQuery = ForexSchema::active()->traderType();
    
            // If user is NOT part of master IB, show ALL accounts immediately
            if (!$isPartOfMasterIb) {
                $schemas = $baseQuery
                ->relevantForUser($user->country, $tagNames)
                ->orWhere('account_category_id', 1)
                ->get()
                    ->unique('id')
                    ->sortBy('priority')
                    ->values();
    
                $activePlatform = setting('active_trader_type', 'features');
                $platformLinks = PlatformLink::where('platform', $activePlatform)
                    ->where('status', 1)
                    ->get();
    
                return view('frontend::forex_schema.index', compact('schemas', 'platformLinks'));
            }
    
            // Initialize collections
            // $userSchemas = $baseQuery->clone()
            //     ->relevantForUser($user->country, $tagNames)
            //     ->get();
    
            $schemas = collect();
            $globalSchemasFromRules = collect(); // For global accounts from rebate rules
            $globalSchemasFromSetting = collect(); // For global accounts from IB group setting
    
            if ($isPartOfMasterIb) {
                $ibGroup = IbGroup::with(['rebateRules.forexSchemas'])->find($isPartOfMasterIb);
    
                if ($ibGroup) {
                    // Get ALL schemas from rebate rules (including global accounts)
                    foreach ($ibGroup->rebateRules as $rule) {
                        $ruleSchemas = $rule->forexSchemas()
                            ->where('status', true)
                            ->get();
                        
                        $schemas = $schemas->merge($ruleSchemas);
                        
                        // Collect global accounts from rebate rules
                        $globalSchemasFromRules = $globalSchemasFromRules->merge(
                            $ruleSchemas->where('account_category_id', 1)
                        );
                    }
    
                    // Include additional global schemas if IB group has global access enabled
                    if ($ibGroup->is_global_account) {
                        $globalSchemasFromSetting = $baseQuery->clone()
                            ->where('account_category_id', 1)
                            ->whereNotIn('id', $globalSchemasFromRules->pluck('id')) // Avoid duplicates
                            ->get();
                    }
                }
            }
    
            // Merge all schemas:
            // 1. Schemas from rebate rules (including global accounts)
            // 2. User-specific schemas
            // 3. Additional global schemas if enabled
            $schemas = $schemas->merge($globalSchemasFromSetting)
                ->unique('id')
                ->sortBy('priority')
                ->values();
    
            $activePlatform = setting('active_trader_type', 'features');
            $platformLinks = PlatformLink::where('platform', $activePlatform)
                ->where('status', 1)
                ->get();
    
            return view('frontend::forex_schema.index', compact('schemas', 'platformLinks'));
    
        // } catch (\Exception $e) {
        //     \Log::error('Error in ForexSchemaController@index', [
        //         'user_id' => auth()->id(),
        //         'message' => $e->getMessage(),
        //     ]);
    
        //     return redirect()->back()->withErrors(['An unexpected error occurred. Please try again later.']);
        // }
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
