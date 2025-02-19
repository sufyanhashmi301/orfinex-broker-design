<?php

namespace App\Http\Controllers;

use App\Models\Gateway;
use Illuminate\Http\Request;
use App\Models\WithdrawMethod;
use App\Traits\ImageUpload;
use Illuminate\Support\Facades\Validator;

class WithdrawMethodController extends Controller
{
    use ImageUpload;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {
        $button = [
            'name' => __('ADD NEW'),
            'icon' => 'plus',
            'route' => route('admin.withdraw-method.create', $type),
        ];
        $withdrawMethods = WithdrawMethod::whereType($type)->get();

        return view('backend.withdraw.method', compact('withdrawMethods', 'button', 'type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {
        $button = [
            'name' => __('Back'),
            'icon' => 'corner-down-left',
            'route' => route('admin.withdraw-method.index', $type),
        ];
        $gateways = Gateway::where('status', true)->whereNot('is_withdraw', '=', '0')->get();

        return view('backend.withdraw.method_create', compact('button', 'type', 'gateways'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'icon' => 'required_if:type,==,manual',
            'gateway_id' => 'required_if:type,==,auto',
            'name' => 'required',
            'currency' => 'required',
            'required_time' => 'required_if:type,==,manual',
            'required_time_format' => 'required_if:type,==,manual',
            'charge' => 'required',
            'charge_type' => 'required',
            'rate' => 'required',
            'min_withdraw' => 'required',
            'max_withdraw' => 'required',
            'status' => 'required',
            'fields' => 'required_if:type,==,manual',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $fields = null;
        if ($input['type'] == 'auto') {

            $withdrawGateways = Gateway::find($input['gateway_id']);
            $withdrawFields = explode(',', $withdrawGateways->is_withdraw);

            $fields = array_map(function ($field) {
                return [
                    'name' => $field,
                    'type' => 'text',
                    'validation' => 'required',
                ];
            }, $withdrawFields);

        }


        $data = [
            'icon' => isset($input['icon']) ? self::imageUploadTrait($input['icon'], null, 'admin/withdraw_methods') : '',
            'gateway_id' => $input['gateway_id'] ?? null,
            'type' => $input['type'],
            'name' => $input['name'],
            'required_time' => $input['required_time'] ?? 0,
            'required_time_format' => $input['required_time_format'] ?? 'hour',
            'currency' => $input['currency'],
            'charge' => $input['charge'],
            'charge_type' => $input['charge_type'],
            'rate' => $input['rate'],
            'min_withdraw' => $input['min_withdraw'],
            'max_withdraw' => $input['max_withdraw'],
            'status' => $input['status'],
            'country' => isset($input['country']) ? $input['country'] : ['All'],
            'fields' => json_encode($fields ?? $input['fields']),
        ];

        $withdrawMethod = WithdrawMethod::create($data);
        notify()->success($withdrawMethod->name . ' ' . __('Withdraw Method Created'));

        return redirect()->route('admin.withdraw-method.index', $input['type']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($type)
    {
        $button = [
            'name' => __('Back'),
            'icon' => 'corner-down-left',
            'route' => route('admin.withdraw-method.index', $type),
        ];

        $withdrawMethod = WithdrawMethod::find(\request('id'));
        $supported_currencies = Gateway::find($withdrawMethod->gateway_id)->supported_currencies ?? [];

        return view('backend.withdraw.method_edit', compact('button', 'withdrawMethod', 'type', 'supported_currencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'currency' => 'required',
            'required_time' => 'required_if:type,==,manual',
            'required_time_format' => 'required_if:type,==,manual',
            'charge' => 'required',
            'charge_type' => 'required',
            'rate' => 'required',
            'min_withdraw' => 'required',
            'max_withdraw' => 'required',
            'status' => 'required',
            'fields' => 'required_if:type,==,manual',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $withdrawMethod = WithdrawMethod::find($id);

        $data = [
            'name' => $input['name'],
            'required_time' => $input['required_time'] ?? $withdrawMethod->required_time,
            'required_time_format' => $input['required_time_format'] ?? $withdrawMethod->required_time_format,
            'currency' => $input['currency'] ?? $withdrawMethod->currency,
            'charge' => $input['charge'],
            'charge_type' => $input['charge_type'],
            'rate' => $input['rate'],
            'min_withdraw' => $input['min_withdraw'],
            'max_withdraw' => $input['max_withdraw'],
            'status' => $input['status'],
            'country' => isset($input['country']) ? $input['country'] : ['All'],
            'fields' => isset($input['fields']) ? json_encode($input['fields']) : $withdrawMethod->fields,
        ];

        if ($request->hasFile('icon')) {
            $icon = self::imageUploadTrait($input['icon'], $withdrawMethod->icon, 'admin/withdraw_methods');
            $data = array_merge($data, ['icon' => $icon]);
        }

        $withdrawMethod->update($data);
        notify()->success($withdrawMethod->name . ' ' . __('Withdraw Method Updated'));

        return redirect()->route('admin.withdraw-method.index', $withdrawMethod->type);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
