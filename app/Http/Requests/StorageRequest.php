<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StorageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {

        $aws_regions = [
            'us-east-2', 'us-east-1', 'us-west-1', 'us-west-2',
            'af-south-1', 'ap-east-1', 'ap-south-1', 'ap-northeast-3',
            'ap-northeast-2', 'ap-southeast-1', 'ap-southeast-2',
            'ap-northeast-1', 'ca-central-1', 'eu-central-1', 'eu-west-1',
            'eu-west-2', 'eu-south-1', 'eu-west-3', 'eu-north-1',
            'me-south-1', 'me-central-1', 'sa-east-1',
        ];

         return [
            'aws_key'    => ['required', 'string', 'max:255'],
            'aws_secret' => ['required', 'string', 'max:255'],
            'aws_bucket' => ['required', 'string', 'max:255'],
            'aws_region' => ['required', 'string', Rule::in($aws_regions)], // Add valid AWS regions
        ];
    }

    /**
     * Customize the error messages.
     */
    public function messages(): array
    {
        return [
            'aws_key.required'    => 'The AWS key is required.',
            'aws_secret.required' => 'The AWS secret is required.',
            'aws_bucket.required' => 'The AWS bucket name is required.',
            'aws_region.required' => 'The AWS region is required.',
            'aws_region.in'       => 'The selected AWS region is invalid.',
        ];
    }

    // Handle the validation
    protected function failedValidation(Validator $validator)
    {
        notify()->error($validator->errors()->first(), 'Error');
        
        throw new HttpResponseException(
            redirect()->back()->withErrors($validator)->withInput()
        );
    }
}
