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

            // Settings: control visibility of global accounts in different contexts
            $showGlobalByCountryAndTags = setting('show_global_accounts_with_country_tags', 'account_type_settings');
            $showGlobalWithIbRebateRules = setting('show_global_accounts_with_ib_rebate_rules', 'account_type_settings');
            $allowGlobalAny = $showGlobalByCountryAndTags || $showGlobalWithIbRebateRules;
    
            // Get user's master IB status
            $isPartOfMasterIb = UserMeta::where('user_id', $user->id)
                ->where('meta_key', 'is_part_of_master_ib')
                ->value('meta_value');
    
            // Base query for all schemas
            $baseQuery = ForexSchema::active()->traderType();

            // Prepare result collection
            $schemas = collect();

            // Check if user matches any country/tag based non-global schema
            $matchesCountryOrTags = $baseQuery->clone()
                ->where('account_category_id', '!=', 1)
                ->where(function($query) use ($user, $tagNames) {
                    $query->relevantForUser($user->country, $tagNames);
                })
                ->exists();

            // If user is NOT part of master IB and also does NOT match any country/tag rule,
            // show ALL global-category accounts regardless of global visibility settings
            if (!$isPartOfMasterIb) {
                if (!$matchesCountryOrTags) {
                    $schemas = $baseQuery->clone()
                        ->where('account_category_id', 1)
                        ->get()
                        ->unique('id')
                        ->sortBy('priority')
                        ->values();
                } else {
                    // Apply existing non-IB visibility logic controlled by settings
                    $schemas = $baseQuery
                        ->where(function($query) use ($user, $tagNames, $allowGlobalAny) {
                            if ($allowGlobalAny) {
                                $query->where(function($q) use ($user, $tagNames) {
                                    $q->where('account_category_id', 1)
                                      ->where(function($globalMatch) use ($user, $tagNames) {
                                          $globalMatch
                                              ->where(function($matchQuery) use ($user, $tagNames) {
                                                  $matchQuery->relevantForUser($user->country, $tagNames);
                                              })
                                              ->orWhereNull('country')
                                              ->orWhereJsonLength('country', 0)
                                              ->orWhereNull('tags')
                                              ->orWhereJsonLength('tags', 0);
                                      });
                                })
                                ->orWhere(function($subQuery) use ($user, $tagNames) {
                                    $subQuery->where('account_category_id', '!=', 1)
                                            ->where(function($matchQuery) use ($user, $tagNames) {
                                                $matchQuery->relevantForUser($user->country, $tagNames);
                                            });
                                });
                            } else {
                                $query->where(function($subQuery) use ($user, $tagNames) {
                                    $subQuery->where('account_category_id', '!=', 1)
                                            ->where(function($matchQuery) use ($user, $tagNames) {
                                                $matchQuery->relevantForUser($user->country, $tagNames);
                                            });
                                });
                            }
                            // Always include schemas explicitly marked as global
                            $query->orWhere('is_global', 1);
                        })
                        ->get()
                        ->unique('id')
                        ->sortBy('priority')
                        ->values();
                }
            }
    
            // Initialize collections for IB users
            $globalSchemasFromRules = collect(); // For global accounts from rebate rules
            $globalSchemasFromSetting = collect(); // For global accounts from IB group setting
    
            $hasIbRuleSchemas = false;

            if ($isPartOfMasterIb) {
                // CHANGED: Added traderType filter to the eager load
                $ibGroup = IbGroup::with(['rebateRules.forexSchemas' => function($query) {
                    $query->active()->traderType();
                }])->find($isPartOfMasterIb);
    
                if ($ibGroup) {
                    $allowGlobalFromIbRules = (bool) $showGlobalWithIbRebateRules && (bool) $ibGroup->is_global_account;
                    // Rule 2: Get schemas from rebate rules; global visibility controlled by setting
                    foreach ($ibGroup->rebateRules as $rule) {
                        $ruleSchemas = $rule->forexSchemas()
                            ->where('status', true)
                            ->traderType()
                            ->active()
                            ->get();
                        if ($ruleSchemas->count() > 0) {
                            $hasIbRuleSchemas = true;
                        }

                        // IB rule global handling:
                        // - If setting OFF or IB group not marked global: exclude global-category from rules, allow only is_global==1
                        // - If setting ON and group is global: include global-category from rules as-is
                        if (!$allowGlobalFromIbRules) {
                            $ruleSchemas = $ruleSchemas->filter(function ($schema) {
                                return ($schema->is_global == 1) || ($schema->account_category_id != 1);
                            });
                        }

                        // Add rule schemas
                        $schemas = $schemas->merge($ruleSchemas);

                        // Track global accounts from rebate rules only if allowed
                        if ($allowGlobalFromIbRules) {
                            $globalSchemasFromRules = $globalSchemasFromRules->merge(
                                $ruleSchemas->where('account_category_id', 1)
                            );
                        }
                    }

                    // Additionally include global accounts (by country/tag) only if allowed by IB rule setting and group flag
                    if ($allowGlobalFromIbRules) {
                        $globalSchemasFromSetting = $baseQuery->clone()
                            ->where('account_category_id', 1)
                            ->where(function($globalMatch) use ($user, $tagNames) {
                                $globalMatch
                                    ->where(function($matchQuery) use ($user, $tagNames) {
                                        $matchQuery->relevantForUser($user->country, $tagNames);
                                    })
                                    ->orWhereNull('country')
                                    ->orWhereJsonLength('country', 0)
                                    ->orWhereNull('tags')
                                    ->orWhereJsonLength('tags', 0);
                            })
                            ->whereNotIn('id', $globalSchemasFromRules->pluck('id')) // Avoid duplicates
                            ->get();
                    }
                }
                // Always include schemas explicitly marked as global
                $alwaysGlobalSchemas = $baseQuery->clone()
                    ->where('is_global', 1)
                    ->get();
                $schemas = $schemas->merge($alwaysGlobalSchemas);
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

            // If IB user has no rule schemas and also does not match any country/tag,
            // show ONLY universal-global accounts when IB-global visibility is NOT allowed.
            // If IB-global visibility IS allowed (setting ON and group marked global),
            // do not override; previously prepared global schemas will be merged below.
            if ($isPartOfMasterIb && !$hasIbRuleSchemas && !$matchesCountryOrTags) {
                if (!$allowGlobalFromIbRules) {
                    $schemas = $baseQuery->clone()
                        ->where('is_global', 1)
                        ->get();
                    // Reset setting-derived globals to avoid duplicates; we'll re-merge below
                    $globalSchemasFromSetting = collect();
                }
            }

            // Merge all schemas (safe for both IB and non-IB flows):
            // - For IB: combines rule/global/extra matches
            // - For non-IB: $globalSchemasFromSetting is empty and this is a no-op
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
