<?php

namespace App\Http\Controllers\Backend;

use App\Imports\UserImportClass;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;


class ImportController extends Controller
{
    public function index()
    {
   return view('backend.imports.user-imports');
    }
    public function import(Request $request)
    {
//        dd($request->all());
        $file = $request->file('file');
//        dd($file);

        Excel::import(new UserImportClass(), $file);

        return redirect()->back()->with('success', 'Data imported successfully.');
    }
}
