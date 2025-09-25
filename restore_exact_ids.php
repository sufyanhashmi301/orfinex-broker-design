<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Helper function to determine user_ib_rule_id and level_id from shares pattern
function analyzeSharePattern($shares, $levels) {
    // Pattern: For N levels, each user_ib_rule gets N entries in user_ib_rule_levels
    // Share pattern: Level 1 = 1 entry, Level 2 = 2 entries, Level 3 = 3 entries, etc.
    // Total shares per user_ib_rule = 1+2+3+...+N = N*(N+1)/2
    
    $levelCount = $levels->count();
    $sharesPerRule = $levelCount * ($levelCount + 1) / 2; // 1+2+3+...+N
    
    echo "Pattern analysis: {$levelCount} levels = {$sharesPerRule} shares per user_ib_rule" . PHP_EOL;
    
    // Group shares by user_ib_rule (every sharesPerRule entries belong to one user_ib_rule)
    $sharesByRule = [];
    $currentRule = 0;
    $currentRuleShares = [];
    
    foreach ($shares as $index => $share) {
        $currentRuleShares[] = $share;
        
        // If we've collected enough shares for current rule, start new rule
        if (count($currentRuleShares) >= $sharesPerRule) {
            $sharesByRule[$currentRule] = collect($currentRuleShares);
            $currentRuleShares = [];
            $currentRule++;
        }
    }
    
    // Add remaining shares if any
    if (!empty($currentRuleShares)) {
        $sharesByRule[$currentRule] = collect($currentRuleShares);
    }
    
    return [$sharesByRule, $sharesPerRule];
}

echo "=== Restoring user_ib_rule_levels with EXACT IDs ===" . PHP_EOL;

try {
    // First, let's see what we have
    $shares = DB::table('user_ib_rule_level_shares')
        ->select('user_ib_rule_level_id', 'level_id', 'share')
        ->orderBy('user_ib_rule_level_id')
        ->orderBy('level_id')
        ->get();
    
    echo "Found " . $shares->count() . " shares in database:" . PHP_EOL;
    
    // Group shares by user_ib_rule_level_id to understand what we need to create
    $sharesByRuleLevel = $shares->groupBy('user_ib_rule_level_id');
    
    // Get available user_ib_rules
    $userIbRules = DB::table('user_ib_rules')->orderBy('id')->get();
    echo "Available user_ib_rules: " . $userIbRules->pluck('id')->implode(', ') . PHP_EOL;
    
    // Check what already exists
    $existingRuleLevels = DB::table('user_ib_rule_levels')->pluck('id')->toArray();
    echo "Existing user_ib_rule_levels: " . implode(', ', $existingRuleLevels) . PHP_EOL;
    
    // Get all available levels
    $levels = DB::table('levels')->orderBy('level_order')->get();
    echo "Available levels: " . $levels->pluck('id')->implode(', ') . PHP_EOL;
    
    echo PHP_EOL . "=== Analyzing share pattern to create user_ib_rule_levels ===" . PHP_EOL;
    echo "Pattern: For 2 levels -> Level 1: 1 share, Level 2: 2 shares = 3 total shares per user_ib_rule" . PHP_EOL;
    echo "Reverse: 3 shares -> 2 user_ib_rule_levels entries (one for each level)" . PHP_EOL;
    
    // Analyze the share pattern
    list($sharesByRule, $sharesPerRule) = analyzeSharePattern($shares, $levels);
    
    echo "Found " . count($sharesByRule) . " user_ib_rule groups in shares" . PHP_EOL;
    
    DB::beginTransaction();
    
    // Disable foreign key checks to allow specific ID insertion
    DB::statement('SET FOREIGN_KEY_CHECKS=0');
    
    $created = 0;
    $ruleLevelIdCounter = 1; // Start from ID 1
    
    foreach ($sharesByRule as $ruleIndex => $ruleShares) {
        // Get the corresponding user_ib_rule
        $userIbRule = $userIbRules->get($ruleIndex);
        if (!$userIbRule) {
            echo "WARNING: No user_ib_rule found for index {$ruleIndex}, skipping" . PHP_EOL;
            continue;
        }
        
        echo PHP_EOL . "Processing user_ib_rule_id {$userIbRule->id} (index {$ruleIndex}):" . PHP_EOL;
        echo "  Shares: " . $ruleShares->count() . " entries" . PHP_EOL;
        
        // Create one user_ib_rule_level entry for each level
        foreach ($levels as $level) {
            // Check if this rule_level_id already exists
            if (in_array($ruleLevelIdCounter, $existingRuleLevels)) {
                echo "  ID {$ruleLevelIdCounter} already exists, skipping" . PHP_EOL;
                $ruleLevelIdCounter++;
                continue;
            }
            
            // Check if there are shares that reference this rule_level_id
            $sharesForThisId = $shares->where('user_ib_rule_level_id', $ruleLevelIdCounter);
            if ($sharesForThisId->isEmpty()) {
                echo "  No shares found for ID {$ruleLevelIdCounter}, skipping" . PHP_EOL;
                $ruleLevelIdCounter++;
                continue;
            }
            
            // Insert with the EXACT ID that shares are expecting
            DB::table('user_ib_rule_levels')->insert([
                'id' => $ruleLevelIdCounter,
                'user_ib_rule_id' => $userIbRule->id,
                'level_id' => $level->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            echo "  ✅ Created ID {$ruleLevelIdCounter}: user_ib_rule_id={$userIbRule->id}, level_id={$level->id}" . PHP_EOL;
            $created++;
            $ruleLevelIdCounter++;
        }
    }
    
    // Re-enable foreign key checks
    DB::statement('SET FOREIGN_KEY_CHECKS=1');
    
    DB::commit();
    
    echo PHP_EOL . "=== RESTORATION COMPLETE ===" . PHP_EOL;
    echo "Created {$created} user_ib_rule_levels entries with exact IDs" . PHP_EOL;
    
    // Verify the restoration
    echo PHP_EOL . "=== VERIFICATION ===" . PHP_EOL;
    
    $orphanedShares = DB::table('user_ib_rule_level_shares as s')
        ->leftJoin('user_ib_rule_levels as rl', 's.user_ib_rule_level_id', '=', 'rl.id')
        ->whereNull('rl.id')
        ->count();
    
    $totalShares = DB::table('user_ib_rule_level_shares')->count();
    $totalRuleLevels = DB::table('user_ib_rule_levels')->count();
    
    echo "Total user_ib_rule_levels: {$totalRuleLevels}" . PHP_EOL;
    echo "Total user_ib_rule_level_shares: {$totalShares}" . PHP_EOL;
    echo "Orphaned shares: {$orphanedShares}" . PHP_EOL;
    
    if ($orphanedShares == 0) {
        echo "🎉 SUCCESS! All shares now have corresponding rule levels with correct IDs!" . PHP_EOL;
    } else {
        echo "⚠️ WARNING: Still have {$orphanedShares} orphaned shares" . PHP_EOL;
    }
    
    // Show the final mapping
    echo PHP_EOL . "=== FINAL MAPPING ===" . PHP_EOL;
    $finalMapping = DB::table('user_ib_rule_levels')
        ->orderBy('id')
        ->get(['id', 'user_ib_rule_id', 'level_id']);
        
    foreach ($finalMapping as $mapping) {
        echo "ID {$mapping->id}: user_ib_rule_id={$mapping->user_ib_rule_id}, level_id={$mapping->level_id}" . PHP_EOL;
    }
    
} catch (Exception $e) {
    DB::rollBack();
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
    echo "Stack trace: " . $e->getTraceAsString() . PHP_EOL;
}

echo PHP_EOL . "=== Script completed ===" . PHP_EOL;
