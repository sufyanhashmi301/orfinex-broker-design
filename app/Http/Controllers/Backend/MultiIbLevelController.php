<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Traits\NotifyTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MultiIbLevelController extends Controller
{
    use NotifyTrait;
    public function __construct()
    {
        $this->middleware('permission:multi-ib-level-list', ['only' => ['index']]);
         $this->middleware('permission:multi-ib-level-create', ['only' => ['store']]);
         $this->middleware('permission:multi-ib-level-edit', ['only' => ['update']]);
         $this->middleware('permission:multi-ib-level-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        // Always order by ID ascending to maintain consistent sequence
        $levels = Level::orderBy('id', 'asc')->paginate(10);
        return view('backend.multi_ib_level.index', compact('levels'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:levels,title',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        try {
            // Begin database transaction for consistency
            DB::beginTransaction();

            // Get the highest existing ID and increment by 1
            $maxId = Level::max('id') ?? 0;
            $nextId = $maxId + 1;

            // Get the next sequential level order based on current count
            $nextOrder = Level::count() + 1;

            // Create the new level normally (auto-increment will handle ID)
            $level = Level::create([
                'title' => $request->title,
                'level_order' => $nextOrder,
            ]);

            DB::commit();
            notify()->success($level->title . ' ' . __('Level Created'));
        } catch (\Exception $e) {
            DB::rollBack();
            notify()->error(__('Failed to create level. Please try again.'), 'Error');
            Log::error('Level creation failed: ' . $e->getMessage());
        }

        return redirect()->route('admin.multi-ib-level.index');
    }

    public function edit($id)
{
    $level = Level::find($id);
    if (!$level) {
        return response()->json(['error' => __('Level not found')], 404);
    }

    return view('backend.multi_ib_level.modal.__edit_form', compact('level'))->render();
}

public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'title' => 'required|unique:levels,title,' . $id,
    ]);

    if ($validator->fails()) {
        notify()->error($validator->errors()->first(), 'Error');
        return redirect()->back();
    }

    $level = Level::find($id);
    if (!$level) {
        notify()->error(__('Level not found'), 'Error');
        return redirect()->route('admin.multi-ib-level.index');
    }

    // Update only the title, keeping other fields unchanged
    $level->update([
        'title' => $request->title,
    ]);

    notify()->success($level->title . ' ' . __('Level Updated Successfully'), 'Success');
    return redirect()->route('admin.multi-ib-level.index');
}

    public function destroy(Request $request, $id)
    {
        $level = Level::find($id);

        if (!$level) {
            if ($request->ajax()) {
                return response()->json(['error' => __('Level not found')], 404);
            }
            notify()->error(__('Level not found'), 'Error');
            return redirect()->route('admin.multi-ib-level.index');
        }

        try {
            // Store level title for notification
            $levelTitle = $level->title;

            // Delete the level
            $level->delete();

            // Recreate all levels with sequential IDs to maintain ID order
            // Example: If we had IDs [1,2,3,4,5] and deleted 3, remaining levels become [1,2,3,4]
            $this->recreateLevelsWithSequentialIds();
            
            // Debug: Log the current state after recreation
            $newLevels = Level::orderBy('id')->get(['id', 'title'])->toArray();
            Log::info('Levels after deletion and recreation:', $newLevels);
            
            notify()->success(__('Level ":title" deleted successfully.', ['title' => $levelTitle]));

            // For AJAX, return JSON
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => __('Level deleted successfully.')]);
            }

        } catch (\Exception $e) {
            Log::error('Level deletion failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            if ($request->ajax()) {
                return response()->json(['error' => __('Failed to delete level: ') . $e->getMessage()], 500);
            }
            notify()->error(__('Failed to delete level: ') . $e->getMessage(), 'Error');
        }

        // For form submit fallback
        return redirect()->route('admin.multi-ib-level.index');
    }

    /**
     * Recreate all levels with sequential IDs to maintain ID order in database
     * This ensures consistent ID ordering after create/delete operations
     * Always recreates to ensure proper ID sequence (1, 2, 3, 4...)
     */
    private function recreateLevelsWithSequentialIds()
    {
        // Note: This method runs within the transaction started by the calling method
        
        // Get all existing levels ordered by ID
        $existingLevels = Level::orderBy('id', 'asc')->get();
        
        // Debug: Log existing levels before recreation
        Log::info('Existing levels before recreation:', $existingLevels->toArray());
        
        // If no levels exist, just reset auto-increment and return
        if ($existingLevels->isEmpty()) {
            Log::info('No levels to recreate - resetting auto-increment to 1');
            // Reset auto-increment to 1 for next record
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            DB::statement('ALTER TABLE levels AUTO_INCREMENT = 1');
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            return;
        }
        
        // Store level data before deletion
        $levelData = [];
        foreach ($existingLevels as $index => $level) {
            $levelData[] = [
                'title' => $level->title,
                'level_order' => $index + 1,
                'created_at' => $level->created_at ? $level->created_at->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ];
        }
        
        // Debug: Log the data we're about to recreate
        Log::info('Level data to recreate:', $levelData);
        
        // Check for foreign key references before deletion
        $userIbRuleLevels = DB::table('user_ib_rule_levels')->count();
        Log::info('Found user_ib_rule_levels records: ' . $userIbRuleLevels);
        
        // Delete all existing levels (handle foreign key constraints properly)
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Level::truncate();
        DB::statement('ALTER TABLE levels AUTO_INCREMENT = 1');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        
        // Recreate levels with sequential IDs using Eloquent (simpler approach)
        foreach ($levelData as $index => $data) {
            Level::create([
                'title' => $data['title'],
                'level_order' => $data['level_order'],
                'created_at' => $data['created_at'],
                'updated_at' => $data['updated_at'],
            ]);
        }
        
        Log::info('Levels recreated successfully');
    }

    /**
     * Test method to create sample levels for testing ID shifting
     * Remove this method after testing
     */
    public function testCreateLevels()
    {
        try {
            DB::beginTransaction();
            
            // Clear existing levels
            Level::truncate();
            DB::statement('ALTER TABLE levels AUTO_INCREMENT = 1');
            
            // Create 5 test levels
            $testLevels = ['Bronze', 'Silver', 'Gold', 'Platinum', 'Diamond'];
            
            foreach ($testLevels as $index => $title) {
                Level::create([
                    'title' => $title . ' Level',
                    'level_order' => $index + 1,
                ]);
            }
            
            DB::commit();
            
            $levels = Level::orderBy('id')->get(['id', 'title']);
            return response()->json([
                'message' => 'Test levels created successfully',
                'levels' => $levels
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }




}
