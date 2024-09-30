<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SystemTag;
use App\Traits\NotifyTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SystemTagController extends Controller
{
    use NotifyTrait;

    public function index()
    {
        $systemTags = SystemTag::paginate(10);
        return view('backend.system_tag.index', compact('systemTags'));
    }

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|unique:system_tags,name',
        'status' => 'required|boolean', // Ensure status is a boolean
    ]);

    if ($validator->fails()) {
        notify()->error($validator->errors()->first(), 'Error');
        return redirect()->back();
    }

    // If status is not provided, set it to active (1)
    $status = $request->input('status', 1); // Default to 1 (active) if not provided

    $systemTag = SystemTag::create($request->only(['name', 'desc']) + ['status' => $status]);
    notify()->success($systemTag->name . ' ' . __('System Tag Created'));
    return redirect()->route('admin.system-tag.index');
}


    public function create()
    {
        return view('backend.system_tag.create');
    }

    public function show(SystemTag $systemTag)
    {
        return view('backend.system_tag.edit', compact('systemTag'));
    }

    public function edit($id)
{
    $systemTag = SystemTag::find($id);
    if (!$systemTag) {
        notify()->error(__('System Tag not found'), 'Error');
        return redirect()->route('admin.system-tag.index');
    }
    return view('backend.system_tag.modal.__edit_form', compact('systemTag'));
}


    public function destroy($id)
    {
        $systemTag = SystemTag::find($id);
        if ($systemTag) {
            $systemTag->delete();
            notify()->success(__('System Tag Deleted Successfully'));
        } else {
            notify()->error(__('System Tag not found'), 'Error');
        }
        return redirect()->route('admin.system-tag.index');
    }

    public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|unique:system_tags,name,' . $id,
        'status' => 'required|boolean',
    ]);

    if ($validator->fails()) {
        notify()->error($validator->errors()->first(), 'Error');
        return redirect()->back();
    }

    $systemTag = SystemTag::find($id);
    if (!$systemTag) {
        notify()->error(__('System Tag not found'), 'Error');
        return redirect()->route('admin.system-tag.index');
    }

    // Update the system tag with provided fields, keeping the existing status if not provided
    $systemTag->update($request->only(['name', 'desc', 'status']));
    notify()->success($systemTag->name . ' ' . __('System Tag Updated'));
    return redirect()->route('admin.system-tag.index');
}

}
