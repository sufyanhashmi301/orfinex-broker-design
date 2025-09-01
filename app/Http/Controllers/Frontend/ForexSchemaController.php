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
    
            // Base query for all schemas
            $baseQuery = ForexSchema::active()->traderType();
    
            // If user is NOT part of master IB, apply filtering based on priority rules
            if (!$isPartOfMasterIb) {
                $schemas = $baseQuery
                ->where(function($query) use ($user, $tagNames) {
                    // Rule 1: Global accounts (account_category_id = 1) show to ALL users regardless of country/tag
                    $query->where('account_category_id', 1)
                    // Rule 3: Non-global accounts show based on tag/country matching
                    ->orWhere(function($subQuery) use ($user, $tagNames) {
                        $subQuery->where('account_category_id', '!=', 1)
                                ->where(function($matchQuery) use ($user, $tagNames) {
                                    $matchQuery->relevantForUser($user->country, $tagNames);
                                });
                    });
                })
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
            $schemas = collect();
            $globalSchemasFromRules = collect(); // For global accounts from rebate rules
            $globalSchemasFromSetting = collect(); // For global accounts from IB group setting
    
            if ($isPartOfMasterIb) {
                // CHANGED: Added traderType filter to the eager load
                $ibGroup = IbGroup::with(['rebateRules.forexSchemas' => function($query) {
                    $query->active()->traderType();
                }])->find($isPartOfMasterIb);
    
                if ($ibGroup) {
                    // Rule 2: Get ALL schemas from rebate rules regardless of country/tag
                    foreach ($ibGroup->rebateRules as $rule) {
                        $ruleSchemas = $rule->forexSchemas()
                            ->where('status', true)
                            ->traderType()
                            ->active()
                            ->get();
                        
                        // Add ALL rule schemas without any filtering (IB users see all linked accounts)
                        $schemas = $schemas->merge($ruleSchemas);
                        
                        // Collect global accounts from rebate rules for the setting check
                        $globalSchemasFromRules = $globalSchemasFromRules->merge(
                            $ruleSchemas->where('account_category_id', 1)
                        );
                    }
    
                    // Rule 1: IB users ALWAYS see global accounts regardless of is_global_account setting
                    $globalSchemasFromSetting = $baseQuery->clone()
                        ->where('account_category_id', 1)
                        ->whereNotIn('id', $globalSchemasFromRules->pluck('id')) // Avoid duplicates
                        ->get();
                }
            }
    
            // For IB users, also include accounts that match their country/tag but are not global or part of IB group
            if ($isPartOfMasterIb) {
                $existingSchemaIds = $schemas->merge($globalSchemasFromSetting)->pluck('id')->toArray();
                
                $additionalSchemas = $baseQuery->clone()
                    ->where('account_category_id', '!=', 1) // Not global
                    ->whereNotIn('id', $existingSchemaIds) // Not already included from IB group
                    ->where(function($query) use ($user, $tagNames) {
                        $query->relevantForUser($user->country, $tagNames);
                    })
                    ->get();
                
                $schemas = $schemas->merge($additionalSchemas);
            }

            // Merge all schemas:
            // 1. Schemas from rebate rules (including global accounts)
            // 2. Additional global schemas if enabled
            // 3. Additional country/tag matching schemas for IB users
            $schemas = $schemas->merge($globalSchemasFromSetting)
                ->unique('id')
                ->sortBy('priority')
                ->values();
    
            $activePlatform = setting('active_trader_type', 'features');
            $platformLinks = PlatformLink::where('platform', $activePlatform)
                ->where('status', 1)
                ->get();
    
            return view('frontend::forex_schema.index', compact('schemas', 'platformLinks'));
    }
    
    public function schemaPreview($id)
    {
        $id = get_hash($id);
        $user = auth()->user();
        $tagNames = $user->riskProfileTags()->pluck('name')->toArray();
        
        // Get user's master IB status
        $isPartOfMasterIb = UserMeta::where('user_id', $user->id)
            ->where('meta_key', 'is_part_of_master_ib')
            ->value('meta_value');
        
        $baseQuery = ForexSchema::active()->traderType();
        
        if (!$isPartOfMasterIb) {
            // Non-IB users: Global accounts + tag/country matching accounts
            $schemas = $baseQuery
                ->where(function($query) use ($user, $tagNames) {
                    $query->where('account_category_id', 1) // Global accounts
                    ->orWhere(function($subQuery) use ($user, $tagNames) {
                        $subQuery->where('account_category_id', '!=', 1)
                                ->relevantForUser($user->country, $tagNames);
                    });
                })
                ->orderBy('priority', 'asc')
                ->get();
        } else {
            // IB users: All schemas from their group + global accounts
            $schemas = collect();
            $ibGroup = IbGroup::with(['rebateRules.forexSchemas' => function($query) {
                $query->active()->traderType();
            }])->find($isPartOfMasterIb);
            
            if ($ibGroup) {
                foreach ($ibGroup->rebateRules as $rule) {
                    $ruleSchemas = $rule->forexSchemas()
                        ->where('status', true)
                        ->traderType()
                        ->active()
                        ->get();
                    $schemas = $schemas->merge($ruleSchemas);
                }
                
                // IB users ALWAYS see global accounts regardless of is_global_account setting
                $globalSchemas = $baseQuery->clone()
                    ->where('account_category_id', 1)
                    ->get();
                $schemas = $schemas->merge($globalSchemas);
                
                // Also include accounts that match country/tag but are not global or part of IB group
                $existingSchemaIds = $schemas->pluck('id')->toArray();
                $additionalSchemas = $baseQuery->clone()
                    ->where('account_category_id', '!=', 1) // Not global
                    ->whereNotIn('id', $existingSchemaIds) // Not already included from IB group
                    ->where(function($query) use ($user, $tagNames) {
                        $query->relevantForUser($user->country, $tagNames);
                    })
                    ->get();
                
                $schemas = $schemas->merge($additionalSchemas);
            }
            
            $schemas = $schemas->unique('id')->sortBy('priority')->values();
        }
            
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
