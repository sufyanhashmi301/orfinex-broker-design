<?php

namespace App\Imports;

use App\Models\UserImport;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;

class UserImportClass implements ToModel
{
    public function model(array $row)
    {
//        if(!UserImport::where('email',$row[6])->exists()) {
            return new UserImport([
                'login' => $row[0],
                'f_name' => $row[1],
                'l_name' => $row[2],
                'm_name' => $row[3],
                'group' => $row[4],
                'country' => $row[5],
                'email' => $row[6],
                'leverage' => $row[7]
//                'register_time' => isset($row[14]) ? Carbon::createFromFormat('Y.m.d H:i', $row[14])->format('Y-m-d H:i:s') : Carbon::now()
                // Map other columns as needed
            ]);
//        }
    }
}
