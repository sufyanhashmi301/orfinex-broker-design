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
        $levels = Level::paginate(10);
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

        // Get the last level order and increment it
        $newOrder = Level::max('level_order') + 1;

        $level = Level::create([
            'title' => $request->title,
            'level_order' => $newOrder,
        ]);

        notify()->success($level->title . ' ' . __('Level Created'));
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
            return response()->json(['error' => __('Level not found')], 404);
        }

        // Delete the level
        $level->delete();

        // Reorder remaining levels by ID ascending
        $levels = Level::orderBy('id')->get();
        $order = 1;

        foreach ($levels as $l) {
            $l->update(['level_order' => $order++]);
        }

        notify()->success(__('Level deleted successfully and levels reordered.'));

        // For AJAX, return JSON
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        // For form submit fallback
        return redirect()->route('admin.multi-ib-level.index');
    }




}
