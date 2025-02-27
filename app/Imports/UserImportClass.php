<?php

namespace App\Imports;

use App\Models\UserImport;
use App\Models\WalletBalance;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;

class UserImportClass implements ToModel
{
    public function model(array $row)
    {
//        if(!UserImport::where('email',$row[6])->exists()) {
//            return new UserImport([
////                'login'                => $row[0] ?? null, // Assuming 'id' is in the first column
//                'f_name'              => $row[0] ?? null, // Name is in the second column
//                'email'              => $row[1] ?? null, // Name is in the second column
//                'phone'             => $row[2] ?? null
////                'balance'           => $row[6] ?? null,
////                'equity'              => $row[7] ?? null,
////                'profit'=> $row[8] ?? null,
////                'floating'=> $row[9] ?? null,
////                'currency'=> $row[10] ?? null,
////                'lead_score'=> $row[11] ?? null, // Map other columns as needed
//            ]);
            return new WalletBalance([

                'email'              => $row[0] ?? null, // Name is in the second column
                'balance'             => $row[1] ?? null
            ]);

    }
}
