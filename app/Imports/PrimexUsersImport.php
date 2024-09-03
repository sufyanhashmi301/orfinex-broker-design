<?php

namespace App\Imports;

use App\Models\PrimexUser;
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Carbon;

class PrimexUsersImport implements ToModel
{
    public function model(array $row)
    {
        return new PrimexUser([
            'old_id'                => $row[0] ?? null, // Assuming 'id' is in the first column
            'name'              => $row[1] ?? null, // Name is in the second column
            'created'           => null,
            'status'            => $row[3] ?? null,
            'email'             => $row[4],
            'phone'             => $row[5] ?? null,
            'country'           => $row[6] ?? null,
            'city'              => $row[7] ?? null,
            'verification_level'=> $row[8] ?? null,
            'last_login'        =>  null,
        ]);
    }

    private function checkAndFormatDate($date)
    {
        try {
            // Convert from 'MM/dd/yyyy hh:mm:ss a' to 'Y-m-d H:i:s'
            return Carbon::createFromFormat('m/d/Y h:i:s A', $date)
                ->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            // If there's an error with the date conversion, log it or handle accordingly
            \Log::error("Date format error: " . $e->getMessage());
            return null;  // Return null or a default date as needed
        }
    }

}


