<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BranchCountry;
use App\Models\BranchForm;
use App\Models\BranchFormSubmission;
use Illuminate\Http\Request;
use App\Traits\ImageUpload;

class UserBranchFormController extends Controller
{
    use ImageUpload;
    public function submit(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            abort(403);
        }

        // Resolve branch based on user's country
        $countryName = $user->country;
        $code = $countryName ? strtoupper((string) getCountryCode($countryName)) : null;
        if (!$code) {
            return back()->withErrors(['branch_form' => __('Unable to resolve country for mapping.')]);
        }

        $branchCountry = BranchCountry::where('country_code', $code)->first();
        if (!$branchCountry) {
            return back()->withErrors(['branch_form' => __('No branch form available for your selected country.')]);
        }

        $branchId = $branchCountry->branch_id;
        $form = BranchForm::where('branch_id', $branchId)->where('status', 1)->first();
        if (!$form) {
            return back()->withErrors(['branch_form' => __('No branch form available for your selected country.')]);
        }

        // Validate required fields
        $fields = is_array($form->fields) ? $form->fields : (is_string($form->fields) ? json_decode($form->fields, true) : []);
        $fields = $fields ?: [];
        $rules = [];
        foreach ($fields as $f) {
            if (($f['validation'] ?? '') === 'required') {
                $rules['branch_form.' . $f['name']] = 'required';
            }
        }
        if (!empty($rules)) {
            $request->validate($rules);
        }

        // Build payload, handle file uploads
        $submitted = $request->input('branch_form', []);
        $payload = [];
        foreach ($fields as $f) {
            $name = $f['name'] ?? null;
            if (!$name) continue;
            $type = $f['type'] ?? 'text';
            if ($type === 'file') {
                if ($request->hasFile('branch_form.' . $name)) {
                    $file = $request->file('branch_form.' . $name);
                    $ext = strtolower($file->getClientOriginalExtension());
                    $imageExt = ['jpeg','jpg','png','gif','svg','webp'];
                    if (in_array($ext, $imageExt, true)) {
                        // Save via ImageUpload trait (assets/global/images/...)
                        $saved = $this->imageUploadTrait($file);
                        // Trait returns path without 'assets/' prefix, e.g. 'global/images/xyz.jpg'
                        $payload[$name] = $saved; 
                    } else {
                        // Use generic file uploader from the trait (accepts pdf/doc/txt, etc.)
                        if (method_exists($this, 'paymentDepositFileUploadTrait')) {
                            $saved = $this->paymentDepositFileUploadTrait($file);
                            $payload[$name] = $saved; // also relative to 'assets/'
                        } else {
                            // Fallback to storage
                            $payload[$name] = $file->store('uploads/branch_forms', 'public');
                        }
                    }
                }
            } else {
                $payload[$name] = $submitted[$name] ?? null;
            }
        }

        BranchFormSubmission::updateOrCreate(
            ['user_id' => $user->id, 'branch_id' => $branchId],
            ['fields' => $payload, 'status' => 'pending']
        );

        notify()->success(__('Form submitted successfully. We will review and update your branch.'));
        return redirect()->route('user.dashboard');
    }
}






