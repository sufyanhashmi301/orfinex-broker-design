<?php

namespace App\Http\Controllers\Backend;

use App\Enums\IBStatus;
use App\Http\Controllers\Controller;
use App\Models\IbQuestion;
use App\Models\User;
use App\Traits\ForexApiTrait;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IBController extends Controller
{
    Use ForexApiTrait;
    public function index(Request $request)
    {

        $questions = IbQuestion::all();
        return view('backend.ib.index',compact('questions'));
    }

    public function create()
    {
        return view('backend.ib.create');
    }
    public function edit($id)
    {
        $kyc = IbQuestion::find($id);

        return view('backend.ib.edit', compact('kyc'));
    }
    public function update(Request $request, $id)
    {
        return redirect()->back();
        $input = $request->all();
//        dd($input);
        $validator = Validator::make($input, [
            'name' => 'required|unique:ib_questions,name,'.$id,
            'status' => 'required',
            'fields' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $data = [
            'name' => $input['name'],
            'status' => $input['status'],
            'fields' => json_encode($input['fields']),
        ];

        $kyc = IbQuestion::find($id);
        $kyc->update($data);
        notify()->success($kyc->name.' '.__(' IB Updated'));

        return redirect()->route('admin.ib-form.index');
    }
    public function destroy($id)
    {
        IbQuestion::find($id)->delete();
        notify()->success(__('IB Deleted Successfully'));

        return redirect()->route('admin.ib-form.index');
    }

    public function IbPending(Request $request)
    {
        if ($request->ajax()) {

            $data = User::where('ib_status',IBStatus::PENDING)->latest();
//            dd($data);

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('avatar', 'backend.user.include.__avatar')
                ->editColumn('kyc', 'backend.user.include.__kyc')
                ->editColumn('ib_status', 'backend.ib.include.__ib_status')
                ->editColumn('email', function ($request) {
                    return safe($request->email);
                })
                ->editColumn('username', function ($request) {
                    return safe($request->username);
                })
                ->addColumn('action', function ($user) {
                    return view('backend.ib.include.__action', ['user' => $user]);
                })
                ->rawColumns(['avatar', 'kyc', 'ib_status',  'action'])
                ->make(true);
        }
        return view('backend.ib.pending');
    }
    public function IbApproved(Request $request)
    {
        if ($request->ajax()) {

            $data = User::where('ib_status',IBStatus::APPROVED)->latest();
//            dd($data);

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('avatar', 'backend.user.include.__avatar')
                ->editColumn('kyc', 'backend.user.include.__kyc')
                ->editColumn('ib_status', 'backend.ib.include.__ib_status')
                ->editColumn('email', function ($request) {
                    return safe($request->email);
                })
                ->editColumn('username', function ($request) {
                    return safe($request->username);
                })
                ->addColumn('action', function ($user) {
                    return view('backend.ib.include.__action', ['user' => $user]);
                })
                ->rawColumns(['avatar', 'kyc', 'ib_status',  'action'])
                ->make(true);
        }
        return view('backend.ib.approved');
    }

    public function IbRejected(Request $request)
    {
        if ($request->ajax()) {

            $data = User::where('ib_status',IBStatus::REJECTED)->latest();
//            dd($data);

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('avatar', 'backend.user.include.__avatar')
                ->editColumn('kyc', 'backend.user.include.__kyc')
                ->editColumn('ib_status', 'backend.ib.include.__ib_status')
                ->editColumn('email', function ($request) {
                    return safe($request->email);
                })
                ->editColumn('username', function ($request) {
                    return safe($request->username);
                })
                ->addColumn('action', function ($user) {
                    return view('backend.ib.include.__action', ['user' => $user]);
                })
                ->rawColumns(['avatar', 'kyc', 'ib_status',  'action'])
                ->make(true);
        }
        return view('backend.ib.rejected');
    }

    public function IbAll(Request $request)
    {
        if ($request->ajax()) {

            $data = User::latest();
//            dd($data);

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('avatar', 'backend.user.include.__avatar')
                ->editColumn('kyc', 'backend.user.include.__kyc')
                ->editColumn('ib_status', 'backend.ib.include.__ib_status')
                ->editColumn('email', function ($request) {
                    return safe($request->email);
                })
                ->editColumn('username', function ($request) {
                    return safe($request->username);
                })
                ->addColumn('action', function ($user) {
                    return view('backend.ib.include.__action', ['user' => $user]);
                })
                ->rawColumns(['avatar', 'kyc', 'ib_status',  'action'])
                ->make(true);
        }
        return view('backend.ib.all');
    }
    public function approveIbMember(Request $request)
    {
//        dd($request->all());
        $userID = ($request->get('user_id')) ? (int)$request->get('user_id') : (int)$request->get('user_id');
//       dd($userID);
        $isReload = ($request->get('reload')) ? $request->get('reload') : false;

        $user = User::find($userID);
        if (!blank($user)) {
//            if ($user->status == UserStatus::INACTIVE) {
//                throw ValidationException::withMessages(['invalid' => __('User account may not verified or inactive.')]);
//            }
            if ($user->ib_status == IBStatus::APPROVED) {
                return response()->json(['error' => __('User has already a member of IB Program'), 'reload' => false]);
            }

            $responseLogin = $this->saveForexAccount($user);

            if ($responseLogin) {
                $user->ib_login = $responseLogin;
                $user->ib_status = IBStatus::APPROVED;
                $user->save();
//                event(new NewIBEvent($user));
                return response()->json(['title' => 'Account Approved for IB', 'success' => __('User has been successfully approved as IB Member.'), 'reload' => $isReload]);
            }else{
                return response()->json(['error' => __('some error occurred.please try again!'), 'reload' => false]);
            }
        }
        return response()->json(['error' => __('User not found or invalid user account id.'), 'reload' => false]);

    }
    public function saveForexAccount($user)
    {
        $group = config('forextrading.group');
//        $group = 'IB\1';
        $auth = config('forextrading.auth');
        $server = config('forextrading.server');
        $password = 'SNNH@2024@bol';
        $investPassword = 'SNNH@2024@bol';
        $name = $user->full_name;
        if(!$name){
            $name = 'abc';
        }
        $phone = $user->phone;
        if(!$phone){
            $phone = 12345678;
        }
        $country = $user->country;
        if(!$country){
            $country = 'UAE';
        }
        $dataArray = array(
            'Name' => $name,
            'Leverage' => 1,
            'Group' => $group,
            'MainPassword' => $password,
            'InvestPassword' => $investPassword,
            'PhonePassword' => $password,
            'auth' => $auth,
            'Email' => $user->email,
            'Phone' =>$phone,
            'Country' => $country,
        );
        $URL = config('forextrading.createUserUrl');
//        dd($dataArray,$URL);
        $response = $this->sendApiRequest($URL, $dataArray);
//        dd($response->object());
//        if ($response->serverError() || $response->failed()) {
//            return redirect()->back()->withErrors(['msg' => 'Some error occurred! please try again']);
//        }

        if ($response->status() == 200 && $response->successful() && $response->object()->ResponseCode == 0) {
            $resData = $response->object();
//            dd($response,$response->data[0]->Login);
//            if ($resData->Login) {
////                $data = $request->all();
//                $data['account_type_id'] = $accountType->id;
//                $data['login'] = $resData->Login;
//                $data['account_name'] = 'IB Account';
//                $data['type'] = 'IB';
//                $data['user_id'] = $user->id;
//                $data['currency'] = base_currency();
//                $data['main_password'] = $password;
//                $data['invest_password'] = $investPassword;
//                $data['phone_password'] = $resData->PhonePassword;
//                $data['group'] = $resData->Group;
//                $data['leverage'] = $resData->Leverage;
//                $data['auth'] = $auth;
//                $data['status'] = ForexTradingStatus::ACTIVE;
//                $data['server'] = $server;
//                $data['account_type'] = $accountType->account_type;
//                $data['trading_platform'] = $accountType->one_platform_type;
//                ForexTrading::create($data);

            return $resData->Login;
//            }
            return false;
        }
        return false;
        //        return redirect()->back()->withErrors(['msg' => 'Update your phone and country in profile']);
    }
    public function rejectIbMember(Request $request)
    {
        $userID = ($request->get('user_id')) ? (int)$request->get('user_id') : (int)$request->get('user_id');
//       dd($userID);
        $isReload = ($request->get('reload')) ? $request->get('reload') : false;

        $user = User::find($userID);
        if (!blank($user)) {
//            if ($user->status == UserStatus::INACTIVE) {
//                throw ValidationException::withMessages(['invalid' => __('User account may not verified or inactive.')]);
//            }
////            if ($user->ib_status == IBStatus::APPROVED) {
//                throw ValidationException::withMessages(['invalid' => __('User has already a member of IB Program.')]);
//            }
//            $response = $this->saveForexAccount($user);

//            if ($response) {
                $user->ib_status = IBStatus::REJECTED;
            $user->save();
            return response()->json(['title' => 'Account rejected for IB', 'success' => __('User has been successfully rejected as IB Member.'), 'reload' => $isReload]);
//            }else{
//                throw ValidationException::withMessages(['invalid' => __('some error occurred.please try again!')]);
//
//            }
        }
    }
    public function saveForm(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|unique:ib_questions,name',
            'status' => 'required',
            'fields' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $data = [
            'name' => $input['name'],
            'status' => $input['status'],
            'fields' => json_encode($input['fields']),
        ];

        $kyc = IbQuestion::create($data);
        notify()->success($kyc->name.' '.__(' IB Created'));

        return redirect()->route('admin.ib-form.index');
    }
}
