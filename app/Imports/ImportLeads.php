<?php

namespace App\Imports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Throwable;

class ImportLeads extends CustomStringBinder implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsOnError
{
    use SkipsFailures, SkipsErrors;

    protected int $successCount = 0;

    public function model(array $row)
    {
        $row = array_filter($row, fn($key) => is_string($key), ARRAY_FILTER_USE_KEY);

        $this->successCount++;

        return new Lead([
            'salutation' => $row['salutation'],
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'client_email' => $row['client_email'],
            'phone' => $row['phone'],
            'source_id' => $row['source_id'],
            'lead_owner' => $row['lead_owner'],
            'company_name' => $row['company_name'],
            'website' => $row['website'],
            'office_phone_number' => $row['office_phone_number'],
            'country' => $row['country'],
            'state' => $row['state'],
            'city' => $row['city'],
            'postal_code' => $row['postal_code'],
            'address' => $row['address'],
            'created' => $row['created']
        ]);
    }

    public function rules(): array
    {
        return [
            'salutation' => 'required|string|max:10',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'client_email' => 'required|email|unique:leads,client_email',
            'phone' => 'required|string|max:20',
            'source_id' => 'required|numeric',
            'lead_owner' => 'required|numeric',
            'company_name' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
            'office_phone_number' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:50',
            'state' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:50',
            'postal_code' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'created' => 'nullable|date',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'salutation.required' => 'Salutation is required.',
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'client_email.required' => 'Email is required.',
            'client_email.email' => 'Invalid email format.',
            'client_email.unique' => 'This email already exists in the database.',
            'phone.required' => 'Phone number is required.',
            'website.url' => 'Website must be a valid URL.',
            'source_id.required' => 'Source ID is required.',
            'lead_owner.required' => 'Lead Owner is required.',
        ];
    }

    public function prepareForValidation($data, $index)
    {
        // Sanitize numeric fields to strings
        if (isset($data['phone'])) {
            $data['phone'] = (string) $data['phone'];
        }

        if (isset($data['office_phone_number'])) {
            $data['office_phone_number'] = (string) $data['office_phone_number'];
        }

        if (isset($data['postal_code'])) {
            $data['postal_code'] = (string) $data['postal_code'];
        }

        return $data;
    }

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

}
