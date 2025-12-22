<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\ReferralRelationship;

class FixCircularReferences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'referral:fix-circular 
                            {--dry-run : Show what would be fixed without making changes}
                            {--force : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Detect and automatically fix circular references by setting ref_id=NULL for oldest user in each loop';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔍 Detecting circular references in referral system...');
        $this->newLine();

        // Detect all circular reference groups
        $circularGroups = $this->detectCircularReferenceGroups();

        if (empty($circularGroups)) {
            $this->info('✅ No circular references found! Your referral system is healthy.');
            return Command::SUCCESS;
        }

        $this->error('⚠️  CIRCULAR REFERENCES DETECTED!');
        $this->warn('Found ' . count($circularGroups) . ' circular reference group(s).');
        $this->newLine();

        // Show what will be fixed
        $usersToFix = [];
        foreach ($circularGroups as $index => $group) {
            $this->info("Group " . ($index + 1) . ": " . $group['type']);
            $this->line("  Users involved: " . implode(', ', array_map(function($u) {
                return "{$u['email']} (ID: {$u['id']})";
            }, $group['users'])));
            
            // Get oldest user (lowest ID) in this group
            $oldestUser = collect($group['users'])->sortBy('id')->first();
            $usersToFix[] = $oldestUser;
            
            $this->warn("  → Will remove ref_id for OLDEST user: {$oldestUser['email']} (ID: {$oldestUser['id']})");
            $this->newLine();
        }

        if ($this->option('dry-run')) {
            $this->info('🔍 DRY RUN MODE - No changes will be made');
            $this->newLine();
            $this->info('The following users would be fixed:');
            foreach ($usersToFix as $user) {
                $this->line("  - {$user['email']} (ID: {$user['id']})");
                $this->line("    Actions: SET ref_id = NULL, DELETE from referral_relationships");
            }
            return Command::SUCCESS;
        }

        // Confirm before proceeding
        if (!$this->option('force')) {
            if (!$this->confirm('Do you want to proceed with fixing these circular references?', false)) {
                $this->info('Operation cancelled.');
                return Command::FAILURE;
            }
        }

        // Fix the circular references
        return $this->fixCircularReferences($usersToFix);
    }

    /**
     * Detect circular reference groups
     *
     * @return array
     */
    protected function detectCircularReferenceGroups(): array
    {
        $groups = [];

        // Group 1: Self-references (User → User)
        $selfRefs = DB::table('users')
            ->whereColumn('id', '=', 'ref_id')
            ->select('id', 'email', DB::raw("CONCAT(first_name, ' ', last_name) as full_name"), 'ref_id')
            ->orderBy('id')
            ->get();

        foreach ($selfRefs as $user) {
            $groups[] = [
                'type' => 'Self-Reference',
                'users' => [[
                    'id' => $user->id,
                    'email' => $user->email,
                    'full_name' => $user->full_name,
                    'ref_id' => $user->ref_id,
                ]],
            ];
        }

        // Group 2: 2-level loops (A → B → A)
        $twoLevelLoops = DB::table('users as u1')
            ->join('users as u2', 'u1.ref_id', '=', 'u2.id')
            ->whereColumn('u2.ref_id', '=', 'u1.id')
            ->whereColumn('u1.id', '!=', 'u2.id')
            ->select(
                'u1.id as u1_id',
                'u1.email as u1_email',
                DB::raw("CONCAT(u1.first_name, ' ', u1.last_name) as u1_name"),
                'u1.ref_id as u1_ref_id',
                'u2.id as u2_id',
                'u2.email as u2_email',
                DB::raw("CONCAT(u2.first_name, ' ', u2.last_name) as u2_name"),
                'u2.ref_id as u2_ref_id'
            )
            ->orderBy('u1.id')
            ->get();

        // Group pairs together to avoid duplicates
        $processed = [];
        foreach ($twoLevelLoops as $loop) {
            $key = min($loop->u1_id, $loop->u2_id) . '-' . max($loop->u1_id, $loop->u2_id);
            if (!in_array($key, $processed)) {
                $groups[] = [
                    'type' => '2-Level Loop',
                    'users' => [
                        [
                            'id' => $loop->u1_id,
                            'email' => $loop->u1_email,
                            'full_name' => $loop->u1_name,
                            'ref_id' => $loop->u1_ref_id,
                        ],
                        [
                            'id' => $loop->u2_id,
                            'email' => $loop->u2_email,
                            'full_name' => $loop->u2_name,
                            'ref_id' => $loop->u2_ref_id,
                        ],
                    ],
                ];
                $processed[] = $key;
            }
        }

        // Group 3: 3-level loops (A → B → C → A)
        $threeLevelLoops = DB::table('users as u1')
            ->join('users as u2', 'u1.ref_id', '=', 'u2.id')
            ->join('users as u3', 'u2.ref_id', '=', 'u3.id')
            ->whereColumn('u3.ref_id', '=', 'u1.id')
            ->whereColumn('u1.id', '!=', 'u2.id')
            ->whereColumn('u2.id', '!=', 'u3.id')
            ->whereColumn('u1.id', '!=', 'u3.id')
            ->select(
                'u1.id as u1_id', 'u1.email as u1_email', DB::raw("CONCAT(u1.first_name, ' ', u1.last_name) as u1_name"), 'u1.ref_id as u1_ref_id',
                'u2.id as u2_id', 'u2.email as u2_email', DB::raw("CONCAT(u2.first_name, ' ', u2.last_name) as u2_name"), 'u2.ref_id as u2_ref_id',
                'u3.id as u3_id', 'u3.email as u3_email', DB::raw("CONCAT(u3.first_name, ' ', u3.last_name) as u3_name"), 'u3.ref_id as u3_ref_id'
            )
            ->orderBy('u1.id')
            ->get();

        $processed = [];
        foreach ($threeLevelLoops as $loop) {
            $ids = [$loop->u1_id, $loop->u2_id, $loop->u3_id];
            sort($ids);
            $key = implode('-', $ids);
            if (!in_array($key, $processed)) {
                $groups[] = [
                    'type' => '3-Level Loop',
                    'users' => [
                        ['id' => $loop->u1_id, 'email' => $loop->u1_email, 'full_name' => $loop->u1_name, 'ref_id' => $loop->u1_ref_id],
                        ['id' => $loop->u2_id, 'email' => $loop->u2_email, 'full_name' => $loop->u2_name, 'ref_id' => $loop->u2_ref_id],
                        ['id' => $loop->u3_id, 'email' => $loop->u3_email, 'full_name' => $loop->u3_name, 'ref_id' => $loop->u3_ref_id],
                    ],
                ];
                $processed[] = $key;
            }
        }

        // Group 4: 4-level loops (A → B → C → D → A)
        $fourLevelLoops = DB::table('users as u1')
            ->join('users as u2', 'u1.ref_id', '=', 'u2.id')
            ->join('users as u3', 'u2.ref_id', '=', 'u3.id')
            ->join('users as u4', 'u3.ref_id', '=', 'u4.id')
            ->whereColumn('u4.ref_id', '=', 'u1.id')
            ->where(function ($query) {
                $query->whereColumn('u1.id', '!=', 'u2.id')
                    ->whereColumn('u1.id', '!=', 'u3.id')
                    ->whereColumn('u1.id', '!=', 'u4.id')
                    ->whereColumn('u2.id', '!=', 'u3.id')
                    ->whereColumn('u2.id', '!=', 'u4.id')
                    ->whereColumn('u3.id', '!=', 'u4.id');
            })
            ->select(
                'u1.id as u1_id', 'u1.email as u1_email', DB::raw("CONCAT(u1.first_name, ' ', u1.last_name) as u1_name"), 'u1.ref_id as u1_ref_id',
                'u2.id as u2_id', 'u2.email as u2_email', DB::raw("CONCAT(u2.first_name, ' ', u2.last_name) as u2_name"), 'u2.ref_id as u2_ref_id',
                'u3.id as u3_id', 'u3.email as u3_email', DB::raw("CONCAT(u3.first_name, ' ', u3.last_name) as u3_name"), 'u3.ref_id as u3_ref_id',
                'u4.id as u4_id', 'u4.email as u4_email', DB::raw("CONCAT(u4.first_name, ' ', u4.last_name) as u4_name"), 'u4.ref_id as u4_ref_id'
            )
            ->orderBy('u1.id')
            ->get();

        $processed = [];
        foreach ($fourLevelLoops as $loop) {
            $ids = [$loop->u1_id, $loop->u2_id, $loop->u3_id, $loop->u4_id];
            sort($ids);
            $key = implode('-', $ids);
            if (!in_array($key, $processed)) {
                $groups[] = [
                    'type' => '4-Level Loop',
                    'users' => [
                        ['id' => $loop->u1_id, 'email' => $loop->u1_email, 'full_name' => $loop->u1_name, 'ref_id' => $loop->u1_ref_id],
                        ['id' => $loop->u2_id, 'email' => $loop->u2_email, 'full_name' => $loop->u2_name, 'ref_id' => $loop->u2_ref_id],
                        ['id' => $loop->u3_id, 'email' => $loop->u3_email, 'full_name' => $loop->u3_name, 'ref_id' => $loop->u3_ref_id],
                        ['id' => $loop->u4_id, 'email' => $loop->u4_email, 'full_name' => $loop->u4_name, 'ref_id' => $loop->u4_ref_id],
                    ],
                ];
                $processed[] = $key;
            }
        }

        return $groups;
    }

    /**
     * Fix circular references by removing ref_id from oldest user
     *
     * @param array $usersToFix
     * @return int
     */
    protected function fixCircularReferences(array $usersToFix): int
    {
        $this->newLine();
        $this->info('🔧 Starting to fix circular references...');
        $this->newLine();

        $fixed = 0;
        $errors = 0;

        foreach ($usersToFix as $user) {
            try {
                DB::beginTransaction();

                // 1. Set ref_id to NULL
                $updated = DB::table('users')
                    ->where('id', $user['id'])
                    ->update(['ref_id' => null, 'updated_at' => now()]);

                // 2. Delete from referral_relationships table
                $deleted = DB::table('referral_relationships')
                    ->where('user_id', $user['id'])
                    ->delete();

                DB::commit();

                $this->info("✅ Fixed: {$user['email']} (ID: {$user['id']})");
                $this->line("   - Set ref_id = NULL");
                $this->line("   - Deleted {$deleted} record(s) from referral_relationships");
                
                $fixed++;

            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("❌ Error fixing user {$user['id']}: " . $e->getMessage());
                $errors++;
            }
        }

        $this->newLine();
        
        if ($errors === 0) {
            $this->info("🎉 Successfully fixed {$fixed} circular reference(s)!");
            $this->newLine();
            $this->info('💡 Next steps:');
            $this->line('   1. Review the affected users in the admin panel');
            $this->line('   2. Manually reassign them to the correct parents');
            $this->line('   3. Run: php artisan referral:check-circular to verify all issues are resolved');
            
            return Command::SUCCESS;
        } else {
            $this->warn("⚠️  Fixed {$fixed} user(s), but encountered {$errors} error(s)");
            $this->line('   Check the error messages above for details');
            
            return Command::FAILURE;
        }
    }
}

