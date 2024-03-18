<?php

namespace App\Http\Controllers\Backend;

use App\Enums\IBStatus;
use App\Http\Controllers\Controller;
use App\Models\ForexAccount;
use App\Models\IbQuestion;
use App\Models\IbSchema;
use App\Models\User;
use App\Traits\ForexApiTrait;
use App\Traits\NotifyTrait;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class IBController extends Controller
{
    use ForexApiTrait, NotifyTrait;

    public function index(Request $request)
    {

        $questions = IbQuestion::all();
        return view('backend.ib.index', compact('questions'));
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
        $input = $request->all();
//        dd($input);
        $validator = Validator::make($input, [
            'name' => 'required|unique:ib_questions,name,' . $id,
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
        notify()->success($kyc->name . ' ' . __(' IB Updated'));

        return redirect()->route('admin.ib-form.index');
    }

    public function destroy($id)
    {
        IbQuestion::find($id)->delete();
        notify()->success(__('IB Deleted Successfully'));

        return redirect()->route('admin.ib-form.index');
    }

    public function IbPendingList(Request $request)
    {
        if ($request->ajax()) {

            $data = User::where('ib_status', IBStatus::PENDING)->latest();
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
                ->rawColumns(['avatar', 'kyc', 'ib_status', 'action'])
                ->make(true);
        }
        return view('backend.ib.pending');
    }

    public function IbApprovedList(Request $request)
    {
        if ($request->ajax()) {

            $data = User::where('ib_status', IBStatus::APPROVED)->latest();
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
                ->rawColumns(['avatar', 'kyc', 'ib_status', 'action'])
                ->make(true);
        }
        return view('backend.ib.approved');
    }

    public function IbRejectedList(Request $request)
    {
        if ($request->ajax()) {

            $data = User::where('ib_status', IBStatus::REJECTED)->latest();
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
                ->rawColumns(['avatar', 'kyc', 'ib_status', 'action'])
                ->make(true);
        }
        return view('backend.ib.rejected');
    }

    public function IbAllList(Request $request)
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
                ->rawColumns(['avatar', 'kyc', 'ib_status', 'action'])
                ->make(true);
        }
        return view('backend.ib.all');
    }

    public function answerView(User $user)
    {
        $ibData = $user->ibQuestionAnswers; // Adjust this based on your actual relationship
        return View::make('backend.ib.include.__ib_detail_render', ['user' => $user, 'ibData' => $ibData]);

//        return response()->json($ibData);
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
                $message = __('User has already a member of IB Program');
                if ($request->ajax()) {
                    return response()->json(['error' => $message, 'reload' => false]);
                } else {
                    notify()->error($message, 'Error Log');
                    return redirect()->back();
                }
            }

            $responseLogin = $this->saveForexAccount($user);
//            $responseLogin = 1223

            if ($responseLogin) {
                $user->ib_login = $responseLogin;
                $user->ib_status = IBStatus::APPROVED;
                $user->save();

                $this->updateChildAgents($user);
//                event(new NewIBEvent($user));
                $shortcodes = [
                    '[[full_name]]' => $user->full_name,
                    '[[email]]' => $user->email,
                    '[[site_title]]' => setting('site_title', 'global'),
                    '[[site_url]]' => route('home'),
                    '[[status]]' => IBStatus::APPROVED,
                ];
                $this->mailNotify($user->email, 'ib_action', $shortcodes);
                $this->smsNotify('ib_action', $shortcodes, $user->phone);
                $this->pushNotify('ib_action', $shortcodes, route('user.referral'), $user->id);
                $message = __('User has been successfully approved as IB Member');
                if ($request->ajax()) {
                    return response()->json(['title' => 'Account Approved for IB', 'success' => $message, 'reload' => $isReload]);
                } else {
                    notify()->success($message, 'IB added');
                    return redirect()->back();
                }
            } else {
                $message = __('some error occurred.please try again');
                if ($request->ajax()) {
                    return response()->json(['error' => $message, 'reload' => false]);
                } else {
                    notify()->error($message, 'Error Log');

                    return redirect()->back();
                }
            }
        }
        return response()->json(['error' => __('User not found or invalid user account id.'), 'reload' => false]);

    }
    public function updateIbMember(Request $request)
    {
        $input = $request->all();
        $ibLogin = $request->ib_login;
        $userID = ($request->get('user_id')) ? (int)$request->get('user_id') : (int)$request->get('user_id');

        $validator = Validator::make($input, [
            'ib_login' => 'required|unique:users,ib_login,' . $userID,
        ]);
        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }
//       dd($userID);
        $isReload = ($request->get('reload')) ? $request->get('reload') : false;

        $user = User::find($userID);
        if (!blank($user)) {
//            if ($user->status == UserStatus::INACTIVE) {
//                throw ValidationException::withMessages(['invalid' => __('User account may not verified or inactive.')]);
//            }
            if ($user->ib_login == $request->ib_login) {
                $message = __('Already assigns same IB number :ib',['ib'=>$request->ib_login]);
                if ($request->ajax()) {
                    return response()->json(['error' => $message, 'reload' => false]);
                } else {
                    notify()->error($message, 'Error Log');
                    return redirect()->back();
                }
            }
            $response = $this->getUserInfoApi($request->ib_login);
            if(!$response){
                $message = __(':ib not exist in MT5. Kindly enter the correct IB account ',['ib'=>$request->ib_login]);
                if ($request->ajax()) {
                    return response()->json(['error' => $message, 'reload' => false]);
                } else {
                    notify()->error($message, 'Error Log');
                    return redirect()->back();
                }
            }

//            $responseLogin = 1223
                $user->ib_login = $request->ib_login;
                $user->ib_status = IBStatus::APPROVED;
                $user->save();

                $this->updateChildAgents($user);
//                event(new NewIBEvent($user));
                $shortcodes = [
                    '[[full_name]]' => $user->full_name,
                    '[[email]]' => $user->email,
                    '[[site_title]]' => setting('site_title', 'global'),
                    '[[site_url]]' => route('home'),
                    '[[status]]' => IBStatus::APPROVED,
                ];
                $this->mailNotify($user->email, 'ib_action', $shortcodes);
                $this->smsNotify('ib_action', $shortcodes, $user->phone);
                $this->pushNotify('ib_action', $shortcodes, route('user.referral'), $user->id);
                $message = __('User has been successfully updated IB account');
                if ($request->ajax()) {
                    return response()->json(['title' => 'Account Updated for IB', 'success' => $message, 'reload' => $isReload]);
                } else {
                    notify()->success($message, 'IB Updated successfully');
                    return redirect()->back();
                }
            } else {
                $message = __('some error occurred.please try again');
                if ($request->ajax()) {
                    return response()->json(['error' => $message, 'reload' => false]);
                } else {
                    notify()->error($message, 'Error Log');

                    return redirect()->back();
                }
            }
        }
   public function approveMIbMember(Request $request)
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
            if (isset($user->multi_ib_login)) {
                $message = __('User has already a member of MIB Program');
                if ($request->ajax()) {
                    return response()->json(['error' => $message, 'reload' => false]);
                } else {
                    notify()->error($message, 'Error Log');
                    return redirect()->back();
                }
            }

            $responseLogin = $this->saveMIBAccount($user);
//            $responseLogin = 1223

            if ($responseLogin) {
                $user->multi_ib_login = $responseLogin;
//                $user->ib_status = IBStatus::APPROVED;
                $user->save();

//                event(new NewIBEvent($user));
                $shortcodes = [
                    '[[full_name]]' => $user->full_name,
                    '[[email]]' => $user->email,
                    '[[site_title]]' => setting('site_title', 'global'),
                    '[[site_url]]' => route('home'),
                    '[[status]]' => IBStatus::APPROVED,
                ];
                $this->mailNotify($user->email, 'mib_action', $shortcodes);
//                $this->smsNotify('mib_action', $shortcodes, $user->phone);
                $this->pushNotify('mib_action', $shortcodes, route('user.referral'), $user->id);
                $message = __('User has been successfully approved as MIB Member');
                if ($request->ajax()) {
                    return response()->json(['title' => 'Account Approved for MIB', 'success' => $message, 'reload' => $isReload]);
                } else {
                    notify()->success($message, 'MIB added');
                    return redirect()->back();
                }
            } else {
                $message = __('some error occurred.please try again');
                if ($request->ajax()) {
                    return response()->json(['error' => $message, 'reload' => false]);
                } else {
                    notify()->error($message, 'Error Log');

                    return redirect()->back();
                }
            }
        }
        return response()->json(['error' => __('User not found or invalid user account id.'), 'reload' => false]);

    }
    public function updateMIbMember(Request $request)
    {
        $input = $request->all();
        $ibLogin = $request->ib_login;
        $userID = ($request->get('user_id')) ? (int)$request->get('user_id') : (int)$request->get('user_id');

        $validator = Validator::make($input, [
            'multi_ib_login' => 'required|unique:users,multi_ib_login,' . $userID,
        ]);
        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }
//       dd($userID);
        $isReload = ($request->get('reload')) ? $request->get('reload') : false;

        $user = User::find($userID);
        if (!blank($user)) {
            if ($user->multi_ib_login == $request->multi_ib_login) {
                $message = __('Already assigns same MIB number :ib',['ib'=>$request->multi_ib_login]);
                if ($request->ajax()) {
                    return response()->json(['error' => $message, 'reload' => false]);
                } else {
                    notify()->error($message, 'Error Log');
                    return redirect()->back();
                }
            }
            $response = $this->getUserInfoApi($request->multi_ib_login);
            if(!$response){
                $message = __(':ib not exist in MT5. Kindly enter the correct MIB account ',['ib'=>$request->multi_ib_login]);
                if ($request->ajax()) {
                    return response()->json(['error' => $message, 'reload' => false]);
                } else {
                    notify()->error($message, 'Error Log');
                    return redirect()->back();
                }
            }

//            $responseLogin = 1223
                $user->ib_login = $request->multi_ib_login;
                $user->save();

                $shortcodes = [
                    '[[full_name]]' => $user->full_name,
                    '[[email]]' => $user->email,
                    '[[site_title]]' => setting('site_title', 'global'),
                    '[[site_url]]' => route('home'),
                    '[[status]]' => IBStatus::APPROVED,
                ];
                $this->mailNotify($user->email, 'mib_action', $shortcodes);
                $this->pushNotify('mib_action', $shortcodes, route('user.referral'), $user->id);
                $message = __('User has been successfully updated MIB account');
                if ($request->ajax()) {
                    return response()->json(['title' => 'Account Updated for MIB', 'success' => $message, 'reload' => $isReload]);
                } else {
                    notify()->success($message, 'MIB Updated successfully');
                    return redirect()->back();
                }
            } else {
                $message = __('some error occurred.please try again');
                if ($request->ajax()) {
                    return response()->json(['error' => $message, 'reload' => false]);
                } else {
                    notify()->error($message, 'Error Log');

                    return redirect()->back();
                }
            }
        }


    public function updateChildAgents($pUser)
    {
        $users = User::where('ref_id', $pUser->id)->get();
        foreach ($users as $user) {
            $forexAccounts = ForexAccount::where('user_id', $user->id)
                ->where('account_type', 'real')
                ->get();
//        dd($forexAccounts,$this->user);
            foreach ($forexAccounts as $forexAccount) {
                $this->updateAgent($forexAccount->login, $pUser->ib_login);
            }
        }
    }

    public function saveForexAccount($user)
    {
        $ibSchema = IbSchema::where('type', 'ib')->where('status', true)->first();
//        dd($ibSchema);
        if (!$ibSchema) {
            return false;
        }
        $group = $ibSchema->group;
//        dd($group);
//        $group = 'IB\1';

        $server = config('forextrading.server');
        $password = 'SNNH@2024@bol';
        $investPassword = 'SNNH@2024@bol';
        $name = $user->full_name;
        if (!$name) {
            $name = 'abc';
        }
        $phone = $user->phone;
        if (!$phone) {
            $phone = 12345678;
        }
        $country = $user->country;
        if (!$country) {
            $country = 'UAE';
        }
        $dataArray = array(
            'Name' => $name,
            'Leverage' => 1,
            'Group' => $group,
            'MasterPassword' => $password,
            'InvestorPassword' => $investPassword,
//            'PhonePassword' => $password,
            'Email' => $user->email,
            'Phone' => $phone,
            'Country' => $country,
        );
        $dataArray['Login'] = 0;
        $dataArray['Language'] = 0;
        $dataArray['Rights'] = 'USER_RIGHT_ALL';
        $dataArray['Status'] = 'YES';
        $URL = config('forextrading.createUserUrl');
//        dd($dataArray,$URL);
        $response = $this->sendApiPostRequest($URL, $dataArray);
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
    public function saveMIBAccount($user)
    {
        $ibSchema = IbSchema::where('type', 'multi_ib')->where('status', true)->first();
//        dd($ibSchema);
        if (!$ibSchema) {
            return false;
        }
        $group = $ibSchema->group;
//        dd($group);
//        $group = 'IB\1';

        $server = config('forextrading.server');
        $password = 'SNNH@2024@bol';
        $investPassword = 'SNNH@2024@bol';
        $name = $user->full_name;
        if (!$name) {
            $name = 'abc';
        }
        $phone = $user->phone;
        if (!$phone) {
            $phone = 12345678;
        }
        $country = $user->country;
        if (!$country) {
            $country = 'UAE';
        }
        $dataArray = array(
            'Name' => $name,
            'Leverage' => 1,
            'Group' => $group,
            'MasterPassword' => $password,
            'InvestorPassword' => $investPassword,
//            'PhonePassword' => $password,
            'Email' => $user->email,
            'Phone' => $phone,
            'Country' => $country,
        );
        $dataArray['Login'] = 0;
        $dataArray['Language'] = 0;
        $dataArray['Rights'] = 'USER_RIGHT_ALL';
        $dataArray['Status'] = 'YES';
        $URL = config('forextrading.createUserUrl');
//        dd($dataArray,$URL);
        $response = $this->sendApiPostRequest($URL, $dataArray);
        if ($response->status() == 200 && $response->successful() && $response->object()->ResponseCode == 0) {
            $resData = $response->object();

            return $resData->Login;
//            }
            return false;
        }
        return false;
    }

    public function rejectIbMember(Request $request)
    {
        $userID = ($request->get('user_id')) ? (int)$request->get('user_id') : (int)$request->get('user_id');
//       dd($userID);
        $isReload = ($request->get('reload')) ? $request->get('reload') : false;

        $user = User::find($userID);
        if (!blank($user)) {
            $user->ib_status = IBStatus::REJECTED;
            $user->save();
            return response()->json(['title' => 'Account rejected for IB', 'success' => __('User has been successfully rejected as IB Member.'), 'reload' => $isReload]);
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
        notify()->success($kyc->name . ' ' . __(' IB Created'));

        return redirect()->route('admin.ib-form.index');
    }
}
