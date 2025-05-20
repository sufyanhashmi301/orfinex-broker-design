<?php

namespace App\Services;

use App\Models\AccountType;
use App\Traits\ImageUpload;

class AccountTypeService
{
    use imageUpload;

    /**
     * Resolve Custom Data
     */
    private function resolveCustomData(array $input, $mode = "store", $account_type = null) 
    {
        // Customize only fields that need transformation
        $custom_data = [
            'countries' => isset($input['countries']) ? json_encode($input['countries']) : json_encode(['All']),
            'icon' => isset($input['icon']) ? $this->imageUploadTrait($input['icon'], null, 'admin/plans') : null
        ];

        if($mode == 'update') {
            $custom_data['icon'] = isset($input['icon']) ? $this->imageUploadTrait($input['icon'], null, 'admin/plans') :  $account_type->icon;
        }

        // Merge custom data with the rest of the input, and create the record
        $final_data = array_merge($input, $custom_data);

        return $final_data;
    }

    /**
     * Create Account Type
     */
    public function createAccountType(array $input)
    {
        $final_data = $this->resolveCustomData($input);
        
        return AccountType::create($final_data);
    }

    /**
     * Update Account Type
     */
    public function updateAccountType(array $input, $account_type)
    {
        $final_data = $this->resolveCustomData($input, 'update', $account_type);
        // dd($final_data);
        return $account_type->update($final_data);
    }

}
