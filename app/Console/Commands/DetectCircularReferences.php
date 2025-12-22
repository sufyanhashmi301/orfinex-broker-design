<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class DetectCircularReferences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'referral:check-circular 
                            {--fix : Automatically fix circular references by removing ref_id}
                            {--detailed : Show detailed information about each circular reference}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Detect circular references in the user referral system';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔍 Checking for circular references in referral system...');
        $this->newLine();

        $circularUsers = $this->detectCircularReferences();

        if (empty($circularUsers)) {
            $this->info('✅ No circular references found! Your referral system is healthy.');
            return Command::SUCCESS;
        }

        $this->error('⚠️  CIRCULAR REFERENCES DETECTED!');
        $this->warn('Found ' . count($circularUsers) . ' user(s) involved in circular reference chains.');
        $this->newLine();

        // Group by issue type
        $grouped = collect($circularUsers)->groupBy('issue_type');

        foreach ($grouped as $type => $users) {
            $this->warn("📌 {$type}: " . count($users) . " user(s)");
        }

        $this->newLine();

        // Show table of affected users
        $this->table(
            ['ID', 'Email', 'Name', 'Parent ID', 'Parent Email', 'Issue Type'],
            collect($circularUsers)->map(function ($user) {
                return [
                    $user['id'],
                    $user['email'],
                    $user['full_name'],
                    $user['ref_id'] ?? 'NULL',
                    $user['parent_email'] ?? 'N/A',
                    $user['issue_type'],
                ];
            })->toArray()
        );

        if ($this->option('detailed')) {
            $this->showDetailedInformation($circularUsers);
        }

        // Offer to fix
        if ($this->option('fix')) {
            return $this->fixCircularReferences($circularUsers);
        } else {
            $this->newLine();
            $this->info('💡 To automatically fix these issues, run:');
            $this->line('   php artisan referral:check-circular --fix');
        }

        return Command::FAILURE;
    }

    /**
     * Detect circular references in the referral system
     *
     * @return array
     */
    protected function detectCircularReferences(): array
    {
        $circularUsers = [];

        // Check 1: Self-references
        $selfRefs = DB::table('users')
            ->whereColumn('id', '=', 'ref_id')
            ->select('id', 'email', DB::raw("CONCAT(first_name, ' ', last_name) as full_name"), 'ref_id')
            ->get();

        foreach ($selfRefs as $user) {
            $circularUsers[] = [
                'id' => $user->id,
                'email' => $user->email,
                'full_name' => $user->full_name,
                'ref_id' => $user->ref_id,
                'parent_email' => $user->email, // Same as self
                'issue_type' => 'Self-Reference',
            ];
        }

        // Check 2: 2-level loops (A → B → A)
        $twoLevelLoops = DB::table('users as u1')
            ->join('users as u2', 'u1.ref_id', '=', 'u2.id')
            ->whereColumn('u2.ref_id', '=', 'u1.id')
            ->whereColumn('u1.id', '!=', 'u2.id')
            ->select(
                'u1.id',
                'u1.email',
                DB::raw("CONCAT(u1.first_name, ' ', u1.last_name) as full_name"),
                'u1.ref_id',
                'u2.email as parent_email'
            )
            ->get();

        foreach ($twoLevelLoops as $user) {
            $circularUsers[] = [
                'id' => $user->id,
                'email' => $user->email,
                'full_name' => $user->full_name,
                'ref_id' => $user->ref_id,
                'parent_email' => $user->parent_email,
                'issue_type' => '2-Level Loop',
            ];
        }

        // Check 3: 3-level loops (A → B → C → A)
        $threeLevelLoops = DB::table('users as u1')
            ->join('users as u2', 'u1.ref_id', '=', 'u2.id')
            ->join('users as u3', 'u2.ref_id', '=', 'u3.id')
            ->whereColumn('u3.ref_id', '=', 'u1.id')
            ->whereColumn('u1.id', '!=', 'u2.id')
            ->whereColumn('u2.id', '!=', 'u3.id')
            ->whereColumn('u1.id', '!=', 'u3.id')
            ->select(
                'u1.id',
                'u1.email',
                DB::raw("CONCAT(u1.first_name, ' ', u1.last_name) as full_name"),
                'u1.ref_id',
                'u2.email as parent_email'
            )
            ->get();

        foreach ($threeLevelLoops as $user) {
            $circularUsers[] = [
                'id' => $user->id,
                'email' => $user->email,
                'full_name' => $user->full_name,
                'ref_id' => $user->ref_id,
                'parent_email' => $user->parent_email,
                'issue_type' => '3-Level Loop',
            ];
        }

        // Check 4: 4-level loops (A → B → C → D → A)
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
                'u1.id',
                'u1.email',
                DB::raw("CONCAT(u1.first_name, ' ', u1.last_name) as full_name"),
                'u1.ref_id',
                'u2.email as parent_email'
            )
            ->get();

        foreach ($fourLevelLoops as $user) {
            $circularUsers[] = [
                'id' => $user->id,
                'email' => $user->email,
                'full_name' => $user->full_name,
                'ref_id' => $user->ref_id,
                'parent_email' => $user->parent_email,
                'issue_type' => '4-Level Loop',
            ];
        }

        // Remove duplicates (same user might appear in multiple checks)
        return collect($circularUsers)->unique('id')->values()->toArray();
    }

    /**
     * Show detailed information about circular references
     *
     * @param array $circularUsers
     * @return void
     */
    protected function showDetailedInformation(array $circularUsers): void
    {
        $this->newLine();
        $this->info('📋 Detailed Information:');
        $this->newLine();

        foreach ($circularUsers as $user) {
            $this->line("User: {$user['email']} (ID: {$user['id']})");
            $this->line("Issue: {$user['issue_type']}");
            
            if ($user['issue_type'] === 'Self-Reference') {
                $this->line("Path: {$user['email']} → {$user['email']} (points to self)");
            } else {
                $path = $this->buildCircularPath($user['id']);
                $this->line("Circular Path: {$path}");
            }
            
            $this->newLine();
        }
    }

    /**
     * Build a visual representation of the circular path
     *
     * @param int $userId
     * @return string
     */
    protected function buildCircularPath(int $userId): string
    {
        $path = [];
        $visited = [];
        $currentId = $userId;
        $maxDepth = 20;
        $depth = 0;

        while ($currentId && $depth < $maxDepth) {
            if (in_array($currentId, $visited)) {
                // Found the loop
                $user = User::find($currentId);
                $path[] = "{$user->email} (ID: {$currentId}) [LOOP DETECTED]";
                break;
            }

            $user = User::find($currentId);
            if (!$user) {
                break;
            }

            $path[] = "{$user->email} (ID: {$currentId})";
            $visited[] = $currentId;
            $currentId = $user->ref_id;
            $depth++;
        }

        return implode(' → ', $path);
    }

    /**
     * Fix circular references by removing ref_id
     *
     * @param array $circularUsers
     * @return int
     */
    protected function fixCircularReferences(array $circularUsers): int
    {
        $this->newLine();
        $this->warn('⚠️  About to fix circular references by setting ref_id = NULL');
        
        if (!$this->confirm('Are you sure you want to proceed?', false)) {
            $this->info('Operation cancelled.');
            return Command::FAILURE;
        }

        try {
            DB::beginTransaction();

            $userIds = collect($circularUsers)->pluck('id')->toArray();
            
            // Update users to remove circular references
            $updated = DB::table('users')
                ->whereIn('id', $userIds)
                ->update(['ref_id' => null]);

            DB::commit();

            $this->info("✅ Successfully fixed {$updated} circular reference(s).");
            $this->newLine();
            $this->info('💡 You should now manually reassign these users to the correct parents.');
            
            return Command::SUCCESS;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Error fixing circular references: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

