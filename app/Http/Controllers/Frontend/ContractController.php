<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\UserContract;
use Illuminate\Support\Facades\Validator;

class ContractController extends Controller
{
    public function index()
    {
        return view('frontend::contracts.index');
    }

    public function show()
    {
        $user = auth()->user();
        $signature = '';
        $shouldHideElement = true;

        return view('frontend::contracts.show', compact('user', 'signature', 'shouldHideElement'));
    }

    public function storeContract(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'signature' => 'required|string',
            'contract_id' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $user = auth()->user();
        $signature = $request->input('signature');

        $contractData = [
            'user' => $user,
            'signature' => $signature,
            'shouldHideElement' => false,
        ];

        $pdf = Pdf::loadView('frontend::contracts.include.__contract_template', $contractData);
        $fileName = 'contract_' . $user->id . '_' . time() . '.pdf';
        $directory = storage_path('app/public/user_contracts');

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0775, true);
        }

        try {
            $pdfPath = $pdf->save($directory . '/' . $fileName);
        } catch (\Exception $e) {
            notify()->error('There was an error generating the contract: ' . $e->getMessage(), 'Error');
            return redirect()->back();
        }

        UserContract::create([
            'user_id' => $user->id,
            'contract_id' => $request->input('contract_id'),
            'file_name' => $fileName,
        ]);

        notify()->success('Contract Submit Successfully');
        return redirect()->back();
    }
}
