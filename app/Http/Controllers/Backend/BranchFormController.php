<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\BranchCountry;
use App\Models\BranchForm;
use App\Traits\NotifyTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\BranchFormSubmission;
use App\Models\User;

class BranchFormController extends Controller
{
    use NotifyTrait;

    public function __construct()
    {
        $this->middleware('permission:manage-branch-form', ['only' => ['edit', 'update']]);
    }

    /**
     * Show create/update form for a specific branch.
     */
    public function edit(int $branchId)
    {
        $branch = Branch::findOrFail($branchId);
        $form = BranchForm::firstOrNew(['branch_id' => $branch->id]);
        $selectedCountryCodes = BranchCountry::where('branch_id', $branch->id)->pluck('country_code')->toArray();

        return view('backend.branch.form', compact('branch', 'form', 'selectedCountryCodes'));
    }

    /**
     * Store/update branch form and attach countries with uniqueness validation.
     */
    public function update(Request $request, int $branchId)
    {
        $branch = Branch::findOrFail($branchId);

        $validator = Validator::make($request->all(), [
            'countries' => 'required|array|min:1',
            'countries.*' => 'string',
            'status' => 'required|in:0,1',
            'fields' => 'required|array|min:1',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back()->withInput();
        }

        // Normalize incoming countries to codes and names using helpers
        $countriesInput = (array) $request->input('countries', []);
        $normalized = collect($countriesInput)->map(function ($value) {
            // Accept either code or "Name:+Dial" or Name
            $code = null; $name = null; $dial = null;
            if (strpos($value, ':') !== false) {
                $parts = explode(':', $value);
                $name = $parts[0];
                $dial = $parts[1] ?? null;
                $code = getCountryCode($name);
            } else {
                // If ISO code length 2 supplied, use as code; else treat as name
                if (is_string($value) && strlen($value) === 2) {
                    $code = strtoupper($value);
                    $name = $name ?: $value;
                } else {
                    $name = $value;
                    $code = getCountryCode($name);
                }
            }
            if (!$dial && $name) {
                $dial = getCountryDialCode($name);
            }
            return [
                'country_code' => strtoupper((string) $code),
                'country_name' => (string) $name,
                'dial_code' => (string) ($dial ?? ''),
            ];
        })->filter(fn ($c) => !empty($c['country_code']) && !empty($c['country_name']))->values();

        if ($normalized->isEmpty()) {
            notify()->error(__('Please select at least one valid country.'), 'Error');
            return redirect()->back()->withInput();
        }

        // Enforce uniqueness across branches
        $codes = $normalized->pluck('country_code')->toArray();
        $conflicts = BranchCountry::whereIn('country_code', $codes)
            ->where('branch_id', '!=', $branch->id)
            ->get();

        if ($conflicts->isNotEmpty()) {
            $conflictList = $conflicts->map(function ($c) {
                return $c->country_name;
            })->unique()->implode(', ');

            notify()->error(__('These countries are already assigned to another branch: ') . $conflictList, 'Error');
            return redirect()->back()->withInput();
        }

        DB::beginTransaction();
        try {
            // Upsert branch form
            $form = BranchForm::updateOrCreate(
                ['branch_id' => $branch->id],
                [
                    'fields' => json_encode($request->input('fields', [])),
                    'status' => (bool) $request->input('status', 0),
                ]
            );

            // Replace countries for this branch
            BranchCountry::where('branch_id', $branch->id)->delete();
            foreach ($normalized as $c) {
                BranchCountry::create([
                    'branch_id' => $branch->id,
                    'country_code' => $c['country_code'],
                    'country_name' => $c['country_name'],
                    'dial_code' => $c['dial_code'],
                ]);
            }

            DB::commit();
            notify()->success($branch->name . ' ' . __('Form saved successfully'));
            return redirect()->route('admin.branches.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Branch form save failed', [
                'branch_id' => $branch->id,
                'error' => $e->getMessage(),
            ]);
            notify()->error(__('Failed to save form. Please try again.'), 'Error');
            return redirect()->back()->withInput();
        }
    }

}


