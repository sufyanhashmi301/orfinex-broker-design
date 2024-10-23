<?php

namespace App\Imports;

use App\Models\BanexOld;
use App\Models\PrimexUser;
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Carbon;

class BanexUsersImport implements ToModel
{
    public function model(array $row)
    {
        return new BanexOld([
            'login'                => $row[0] ?? null, // Assuming 'id' is in the first column
            'first_name'              => $row[1] ?? null, // Name is in the second column
            'last_name'              => $row[2] ?? null, // Name is in the second column
            'group'            => $row[3] ?? null,
            'country'             => $row[4],
            'email'             => $row[5] ?? null,
            'balance'           => $row[6] ?? null,
            'equity'              => $row[7] ?? null,
            'profit'=> $row[8] ?? null,
            'floating'=> $row[9] ?? null,
            'currency'=> $row[10] ?? null,
            'lead_score'=> $row[11] ?? null,
        ]);
    }


}


