<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\AccountBalanceType;
use App\Enums\ForexTradingAccountTypesStatus;
use App\Enums\ForexTradingStatus;
use App\Enums\TokenSchemeStatus;
use App\Enums\TransactionCalcType;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Enums\PricingInvestmentStatus;
use App\Enums\LedgerTnxType;
use App\Enums\SchemeStatus;

use App\Events\IBTransferEvent;
use App\Events\NewExternalTransferEvent;
use App\Events\NewInternalTransferEvent;
use App\Helpers\PricingMsgState;
use App\Jobs\ProcessEmail;
use App\Models\Account;
use App\Models\AllowReferralTransfer;
use App\Models\ForexTrading;
use App\Models\FundCertificate;
use App\Models\FundedDiscountCode;

use App\Models\PricingInvestment;


use App\Models\PricingScheme;
use App\Models\Referral;
use App\Models\ReferralCode;

use App\Models\Transaction;
use App\Models\User;
use App\Services\ForexTradingAPIService;
use App\Services\PricingInvestormService;
use App\Traits\ForexApi;
use Brick\Math\RoundingMode;
use Carbon\Carbon;
use Brick\Math\BigDecimal;
use App\Helpers\MsgState;
use App\Filters\PricingPlansFilter;
use App\Filters\LedgerFilter;
use App\Services\GraphData;
use App\Services\Transaction\TransactionService;
use App\Http\Controllers\Controller;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

use Intervention\Image\Facades\Image;

class PricingInvestmentController extends Controller
{
    use ForexApi;
    private $investment;

    public function __construct(PricingInvestormService $investment)
    {
        $this->investment = $investment;

    }

    public function index(Request $request, GraphData $graph)
    {

        $amounts = [];
        $user_id = auth()->id();
//        dd($request->all());
        $actived = PricingInvestment::where('user_id', $user_id)->where('status', PricingInvestmentStatus::ACTIVE)->get();
        // foreach ($actived as $activeAccount) {
        //     $this->syncPricingAccount($activeAccount->login);
        // }
        $actived = PricingInvestment::where('user_id', $user_id)->where('status', PricingInvestmentStatus::ACTIVE)->get();

        $investments = PricingInvestment::whereIn('status', [
            PricingInvestmentStatus::PENDING,
            PricingInvestmentStatus::ACTIVE,
            PricingInvestmentStatus::COMPLETED,
            PricingInvestmentStatus::VIOLATED
        ])->where('user_id', $user_id)
            ->orderBy('id', 'desc')->get();

//        if(auth()->user()->favorite == 1){
//            $favorited = PricingInvestment::where('user_id', 1)->where('status', PricingInvestmentStatus::ACTIVE)->get();
//
//        }


//        dd($investments);

        $recents = PricingInvestment::where('user_id', $user_id)->where('status', PricingInvestmentStatus::COMPLETED)->latest()->limit(3)->get();

//        if (auth()->user()->favorite == 1) {
//            $favorited = PricingInvestment::where('user_id', 1)->where('status', PricingInvestmentStatus::ACTIVE)->get();
//            $actived = $actived->merge($favorited);
//            $investments = $investments->merge($favorited);
////            $actived = PricingInvestment::where('user_id', 1)->where('status', PricingInvestmentStatus::ACTIVE)->get();
////            $actived = $merged->all();
//        }
        $investments = $investments->groupBy('status');
//dd($investments);

        // Profit calculate
        if ($actived->count() > 0) {
//            dd($actived);
//            $this->bulkCalculate($actived);
        }

        $invested = PricingInvestment::where('user_id', $user_id)->where('status', PricingInvestmentStatus::ACTIVE);
        $pending = PricingInvestment::where('user_id', $user_id)
            ->where(function ($query) {
                $query->where('status', PricingInvestmentStatus::PENDING)
                    ->orWhere('status', PricingInvestmentStatus::ACTIVE);
            });

//        $profits = IvProfit::where('user_id', $user_id)->whereNull('payout');

        $amounts['invested'] = $invested->sum('total');
//        dd($amounts);
        $amounts['amount_allotted'] = $invested->sum('amount_allotted');
        $amounts['profit'] = $invested->sum('profit');
//        $amounts['locked'] = to_sum($profits->sum('amount'), $pending->sum('amount'));
//        $amounts['profitLocked'] = $profits->sum('amount');
        $amounts['capitalLocked'] = $pending->sum('amount');
//        if (auth()->user()->favorite == 1) {
//            $favoritedAmount = PricingInvestment::where('user_id', 1)->where('status', PricingInvestmentStatus::ACTIVE);
//            $amounts['invested'] = $amounts['invested'] + $favoritedAmount->sum('amount');
//            $amounts['profit'] = $amounts['profit'] + $favoritedAmount->sum('profit');
//        }
        // Graph chart
//        $graph->set('profit', 'term_start');
//dd($graph);
        $profit = PricingInvestment::select(DB::raw('SUM(profit) as profit,term_start'))
            ->where('status', PricingInvestmentStatus::ACTIVE)
            ->groupBy(DB::RAW('CAST(term_start as DATE)'))
            ->get();
//        dd($profit);

//        $profitChart = $graph->getDays($profit, 31)->flatten();
//        dd($profitChart);

        $graph->set('amount', 'term_start');

        $investment = PricingInvestment::select(DB::raw('SUM(amount) as amount,term_start'))
            ->where('status', PricingInvestmentStatus::ACTIVE)
            ->groupBy(DB::RAW('CAST(term_start as DATE)'))
            ->get();

//        $investChart = $graph->getDays($investment, 31)->flatten();
//        dd($investChart);


        return view("frontend::investment.dashboard", compact('investments', 'recents', 'amounts'));
    }

    public function planList(Request $request)
    {
        $planQuery = PricingScheme::query();
//        $planOrder = sys_settings('iv_plan_order');

//        switch ($planOrder) {
//            case "reverse":
//                $planQuery->orderBy('id', 'desc');
//                break;
//            case "random":
//                $planQuery->inRandomOrder();
//                break;
//            case "featured":
//                $planQuery->orderBy('featured', 'desc');
//                break;
//        }

        $plans = $planQuery->where('status', SchemeStatus::ACTIVE)->get();
        $plans = $plans->groupBy(['type','sub_type']);
    //    dd($plans);
        if (blank($plans)) {
            $errors = PricingMsgState::of('no-plan', 'invest');
//            dd($errors);
            return view("frontend::investment.invest.errors", $errors);
        }
//        dd('s');

        return view("frontend::investment.invest-plans", compact('plans'));
    }

    public function investmentHistory(Request $request, $status = null)
    {
//        dd($request->all());
        $input = array_filter($request->only(['status', 'query']));
        $eligibleStatus = [
            PricingInvestmentStatus::PENDING,
            PricingInvestmentStatus::ACTIVE,
            PricingInvestmentStatus::COMPLETED,
            PricingInvestmentStatus::VIOLATED
        ];

        $investCount = PricingInvestment::select('id', 'status')->loggedUser()->get()
            ->groupBy('status')->map(function ($item) {
                return count($item);
            });

        $filter = new PricingPlansFilter(new Request(array_merge($input, ['status' => $status ?? $request->get('status')])));
        $investQuery = PricingInvestment::loggedUser()->orderBy('id', 'desc')->filter($filter);

        if ($status && in_array($status, $eligibleStatus)) {
            $investQuery->where('status', $status);
            $listing = $status;
        } else {
            $listing = 'all';
        }

        $investments = $investQuery->paginate(user_meta('iv_history_perpage', 20))->onEachSide(0);

        return view("frontend::investment.history", compact('investments', 'investCount', 'listing'));
    }

    public function getDiscountByCode(Request $request)
    {
//        dd($request->all());
        $code = FundedDiscountCode::where('code',$request->code)->where('status','active')->first();
        if (blank($code)) {
            throw ValidationException::withMessages(['invest' => 'Invalid Discount code or Expire code validity!']);
        }
        return response()->json(['reload' => false, 'percentage' => $code->discount,'success'=>__(' Congratulations! You have successfully obtained a :percentage discount code for your funded!',['percentage'=>$code->discount.'%'])]);
    }

    public function addReferral(Request $request)
    {
//        dd($request->all());
            $referralCode = ReferralCode::where('code', $request->code)->exists();
            if (!$referralCode) {
                throw ValidationException::withMessages(['referral_id' => __("Kindly provide Valid Affiliate Code.")]);
            }

        $referralCode = ReferralCode::where('code', $request->code)->first();

        $meta = [
            'at'=> Carbon::now()->timestamp,
            'code'=> $referralCode->code
        ];

        $userId = auth()->user()->id;
        Referral::UpdateOrCreate(['user_id'=>$userId],['refer_by'=>$referralCode->user_id,'join_at'=>Carbon::now(),'meta'=>$meta]);
        $parentUser =  User::find($referralCode->user_id);
        $user = User::find($userId);
        $user->refer = $parentUser->id;
        $user->save();

//      dd($parentUser);
        if($parentUser->ib_login) {
            $forexAccounts = ForexTrading::where('user_id',$userId)->realActiveAccount($userId)->get();
//          dd($request->user_id,$forexAccounts);
            foreach ($forexAccounts as $forexAccount) {
                $updateUserUrl = config('forextrading.updateAgentAccount');
                $dataArray = [];
                $dataArray['Login'] = $forexAccount->login;
                $dataArray['Agent'] = $parentUser->ib_login;

//              dd($dataArray);
                $updateUserApiResponse = $this->sendApiPostRequest($updateUserUrl, $dataArray);
//        dd($updateUserApiResponse->object());
                if ($updateUserApiResponse->status() == 200 && $updateUserApiResponse->successful() && $updateUserApiResponse->object()->ResponseCode == 0) {
                    $forexAccount->agent = $parentUser->ib_login;
                    $forexAccount->save();
                }

            }
        }

        return response()->json(['reload' => false, 'success'=>__(' Congratulations! You have successfully affiliated under provided referral code!')]);
    }

    public function investmentDetails($id)
    {
        $invest = PricingInvestment::find(get_hash($id));
//        dd($invest);
        if (blank($invest)) {
            throw ValidationException::withMessages(['invest' => 'Invalid Funded!']);
        }
        if ($invest->status == PricingInvestmentStatus::ACTIVE) {
            // $response = $this->syncPricingAccount($invest->login);
            $invest = $invest->fresh();

            $growthPercentage = percentage_of_total_calc($invest->profit, $invest->amount_allotted);

            $todayDrawddown = 0;
            if (BigDecimal::of(to_minus($invest->snap_equity, $invest->current_equity))->isGreaterThan(BigDecimal::of(0))) {
                $todayDrawddown = to_minus($invest->snap_equity, $invest->current_equity);
            }
            $remainingLoss = to_minus($invest->daily_drawdown_limit, $todayDrawddown);
            return view("frontend::investment.active-plan", compact("invest", "todayDrawddown", "remainingLoss", "growthPercentage"));
        }
        $plans = PricingScheme::where('status', 'active')->get();

        return view("frontend::investment.plan", compact("invest", "plans"));
    }

    public function planCertificate($id)
    {
        $invest = PricingInvestment::find(get_hash($id));
//        dd($invest);
        if (blank($invest)) {
            throw ValidationException::withMessages(['invest' => 'Invalid Funded!']);
        }

        if ($invest->status == PricingInvestmentStatus::ACTIVE) {
            // $this->syncPricingAccount($invest->login);
            $invest = $invest->fresh();

            $image = Image::make(public_path('frontend/images/orfinexcertificate.png'));
            $profit = $invest->profit . ' ' . base_currency();

            // Add text to the image
            $image->text($profit, 1320, 2192, function ($font) {
                $font->file(public_path('/assets/fonts/DMSans-Bold.ttf'));
                $font->size(90);
                $font->color('#ff0000');
                $font->align('left');
                $font->valign('bottom');
            });
            $date = Carbon::now();
            $image->text(show_date($date, true), 1320, 2350, function ($font) {
                $font->file(public_path('/assets/fonts/DMSans-Bold.ttf'));
                $font->size(50);
                $font->color('#ff0000');
                $font->align('center');
                $font->valign('bottom');
            });
            $code = str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $codeMsg = 'Verification Code: ' .$code ;
            $image->text($codeMsg, 2785, 2445, function ($font) {
                $font->file(public_path('/assets/fonts/DMSans-Bold.ttf'));
                $font->size(50);
                $font->color('#ff0000');
                $font->align('left');
                $font->valign('bottom');
            });

            if (!Storage::has('FundCertificate')) {
                $this->createCertificateFolder();
            }
            $imageName =  'Orfinex-certificate'.$invest->id.'.jpg';
            $destinationPath = 'app/FundCertificate/';
            $path = $destinationPath . '/' . $imageName;
//            dd(public_path(''),asset($destinationPath.'/'.$imageName));
            $image->save(storage_path($destinationPath . '/' . $imageName));
            $path = 'FundCertificate/' . $imageName;
//            dd($path);
            $data['profit'] = $invest->profit;
            $data['date'] = $date;
            $data['code'] = $code;
            $data['path'] = $path;

            $key = 'profit_certificate'.$invest->id;
            Session::put($key, $data);

            $previewImage = ' <img id="certificateImage" src="'.preview_media($path).'" alt="Image Preview">';
            return response()->json(['reload' => false, 'append' => $previewImage]);

        }
    }

    public function planCertificateDownload($id)
    {
        $invest = PricingInvestment::find(get_hash($id));
//        dd($invest);
        if (blank($invest)) {
            throw ValidationException::withMessages(['invest' => 'Invalid Funded!']);
        }

        if ($invest->status == PricingInvestmentStatus::ACTIVE) {
//            dd($imageName);
            $data = Session::get('profit_certificate'.$invest->id);
            $data['user_id'] = auth()->user()->id;
            $data['pricing_invest_id'] = $invest->id;
//            dd($data);
           $certificate =  FundCertificate::where([
                ['user_id',$data['user_id']],
                ['pricing_invest_id',$data['pricing_invest_id']],
                ['date',$data['date']]])->first();
           if(!$certificate){
               FundCertificate::create($data);
           }

            $imageName = 'Orfinex-certificate'.$invest->id.'.jpg';

            $path = 'FundCertificate/'.$imageName;

            // Check if the file exists
            if (Storage::exists($path)) {
                // Get the file's MIME type
                $mime = Storage::mimeType($path);

                // Set the response headers for the file download
                $headers = [
                    'Content-Type' => $mime,
                    'Content-Disposition' => 'attachment; filename="' . $imageName . '"',
                ];

                // Return the file as a response for download
                return response()->download(storage_path('app/' . $path), $imageName, $headers);
            }
//            return response()->download(['reload' => false, 'append' => $previewImage]);

        }
//        $plans = PricingScheme::where('status', 'active')->get();
//
//        return view("frontend::investment.plan", compact("invest", "plans"));
    }

    public function createCertificateFolder()
    {
        $validPermissionCodes = ['0755', '0775', '0777'];

        $storagePermission = substr(sprintf('%o', fileperms(Storage::path(''))), -4);
        if (!in_array($storagePermission, $validPermissionCodes)) {
            throw ValidationException::withMessages(['error' => __("Please check permission for 'app' folder in storage directory.")]);
        }

        try {
            Storage::makeDirectory('FundCertificate');
        } catch (\Exception $e) {
            throw ValidationException::withMessages(['error' => __("Unable to create 'Fund Certificate' folder. Please check permission for 'app' folder in storage directory.")]);
        }

    }

    public function showInvestmentDetails($id)
    {
//        dd($id);
        $invest = PricingInvestment::find(get_hash($id));
//        dd($invest);

        if (blank($invest)) {
            throw ValidationException::withMessages(['invest' => 'Invalid Funded!']);
        }
//        dd($invest,'investmentDetails');
//
//        $this->profitCalculate($invest);
//
//        $invest = $invest->fresh()->load(['profits' => function ($q) {
//            $q->orderBy('term_no', 'desc');
//        }]);
        $plans = PricingScheme::where('status', 'active')->get();
//        dd($plans);

        return view("frontend::investment.show-plan", compact("invest", "plans"));
    }



    public function payoutInvest(Request $request)
    {
        $min = min_to_compare();
        $balance = user_balance(AccType('invest'));

        if ($request->ajax()) {
            $balance = (BigDecimal::of($balance)->compareTo($min) != 1) ? false : $balance;

            return view("frontend::investment.misc.modal-payout", compact("balance"))->render();
        } else {
            return redirect()->route('user.pricing.dashboard');
        }
    }

    public function payoutProceed(Request $request)
    {
        $this->validate($request, [
            'amount' => ['required', 'numeric', 'gt:0']
        ], [
            'amount.required' => __('Enter a valid amount to transfer funds.'),
            'amount.numeric' => __('Enter a valid amount to transfer funds.'),
        ]);

        $user_id = auth()->id();
        $min = min_to_compare();
        $amount = $request->get('amount');
        $balance = user_balance(AccType('invest'));

        if (BigDecimal::of($balance)->compareTo($min) != 1) {
            throw ValidationException::withMessages(['invalid' => __("You do not have enough funds in your account to transfer.")]);
        }
        if (BigDecimal::of($amount)->compareTo($balance) > 0) {
            throw ValidationException::withMessages(['amount' => ['title' => __('Insufficient balance!'), 'message' => __('The amount exceeds your available funds.')]]);
        }

        if (BigDecimal::of($amount)->compareTo($min) != 1) {
            throw ValidationException::withMessages(['invalid' => __("The amount is required to transfer.")]);
        }
        if ($amount) {
            $tnxData = [
                'user_id' => $user_id,
                'base_amount' => $amount,
                'amount' => $amount,
                'calc' => TransactionCalcType::CREDIT,
                'type' => TransactionType::INVESTMENT,
                'base_currency' => base_currency(),
                'currency' => base_currency(),
                'base_fees' => 0,
                'amount_fees' => 0,
                'exchange_rate' => 1,
                'method' => 'system',
                'desc' => 'Received from ' . w2n(AccType('invest')),
                'pay_to' => AccType('main'),
                'pay_from' => AccType('invest'),
            ];

            $this->wrapInTransaction(function ($tnxData, $user_id) {
                $transactionService = new TransactionService();
                $transaction = $transactionService->createManualTransaction($tnxData);
                $ledger = $this->makeTransferIvLedger($transaction);

                $transaction->reference = $ledger->ivx;
                $transaction->save();

                $completedBy = (!empty(system_admin())) ? ['id' => system_admin()->id, 'name' => system_admin()->name] : [];
                $transactionService->confirmTransaction($transaction, $completedBy);

                $account = get_user_account($user_id, AccType('invest'));
                $account->amount = to_minus($account->amount, $ledger->amount);
                $account->save();
            }, $tnxData, $user_id);

            return response()->json(['title' => __('Fund Transferred'), 'msg' => __('Your funds successfully transferred into your main account balance.'), 'reload' => true]);
        } else {
            throw ValidationException::withMessages(['amount' => __('Opps! We unable to process your request. Please reload the page and try again.')]);
        }

    }

    public function forexTradingTransfer(Request $request)
    {
        $min = min_to_compare();
        $AccType = $request->type;
        $forexTradingBalance = user_balance(AccType($AccType));
//        dd($forexTradingBalance);
        $forexTradingBalance = (BigDecimal::of($forexTradingBalance)->compareTo($min) != 1) ? false : $forexTradingBalance;

        $this->validate($request, [
            'amount' => ['required', 'numeric', 'gt:0', 'lte:' . $forexTradingBalance . '']
        ], [
            'amount.required' => __('Enter a valid amount to transfer funds.'),
            'amount.numeric' => __('Enter a valid amount to transfer funds.'),
        ]);

        $user_id = auth()->id();
        $min = min_to_compare();
        $amount = $request->get('amount');
        $balance = user_balance(AccType($AccType));

        if (BigDecimal::of($balance)->compareTo($min) != 1) {
            throw ValidationException::withMessages(['invalid' => __("You do not have enough funds in your account to transfer.")]);
        }
        if (BigDecimal::of($amount)->compareTo($balance) > 0) {
            throw ValidationException::withMessages(['amount' => ['title' => __('Insufficient balance!'), 'message' => __('The amount exceeds your available funds.')]]);
        }

        if (BigDecimal::of($amount)->compareTo($min) != 1) {
            throw ValidationException::withMessages(['invalid' => __("The amount is required to transfer.")]);
        }

        if ($amount) {
            $tnxData = [
                'user_id' => $user_id,
                'base_amount' => $amount,
                'amount' => $amount,
                'calc' => TransactionCalcType::DEBIT,
                'type' => TransactionType::FOREX_TRADING_WALLET_TRANSFER,
                'base_currency' => base_currency(),
                'currency' => base_currency(),
                'base_fees' => 0,
                'amount_fees' => 0,
                'exchange_rate' => 1,
                'method' => 'system',
                'desc' => 'Forex Trading Transferred to ' . w2n(AccType('main')),
                'pay_to' => AccType('main'),
                'pay_from' => AccType($AccType),
            ];
//dd('s');
            //Debit transaction
            $this->wrapInTransaction(function ($tnxData, $user_id, $AccType) {
                $source = $AccType;
//                dd($source);
                $transactionService = new TransactionService();
                $transaction = $transactionService->createManualTransactionWithSource($tnxData, $source);
//                dd($transaction);
                $ledger = $this->makeTransferIvLedger($transaction);

                $transaction->reference = $ledger->ivx;
                $transaction->save();
//                dd('end');
//                $completedBy = (!empty(system_admin())) ? ['id' => system_admin()->id, 'name' => system_admin()->name] : [];
                $transactionService->confirmTransactionWithSource($transaction, ["id" => auth()->user()->id, "name" => auth()->user()->name], $source);
//                dd('end');
                $account = get_user_account($user_id, AccType($AccType));
                $account->amount = to_minus($account->amount, $ledger->amount);
                $account->save();
//                dd('end');
            }, $tnxData, $user_id, $AccType);
//            dd($AccType,AccType($AccType),w2n(AccType($AccType)));

            //Credit entry of transferred
            $tnxData['type'] = TransactionType::FOREX_TRADING_WALLET_TRANSFER;
            $tnxData['calc'] = TransactionCalcType::CREDIT;
            $tnxData['desc'] = 'Received from ' . w2n(AccType($AccType));

            $this->wrapInTransaction(function ($tnxData, $user_id) {
                $transactionService = new TransactionService();
                $transaction = $transactionService->createManualTransaction($tnxData);
                $ledger = $this->makeTransferIvLedger($transaction);

                $transaction->reference = $ledger->ivx;
                $transaction->save();

//                $completedBy = (!empty(system_admin())) ? ['id' => system_admin()->id, 'name' => system_admin()->name] : [];
                $transactionService->confirmTransaction($transaction, ["id" => auth()->user()->id, "name" => auth()->user()->name]);

            }, $tnxData, $user_id);

            return redirect()->back()->with(['success' => __('Successfully transferred')]);

        } else {
            throw ValidationException::withMessages(['amount' => __('Opps! We unable to process your request. Please reload the page and try again.')]);
        }

    }

    private function makeTransferIvLedger($transaction)
    {
        $data = [
            'ivx' => generate_unique_ivx(IvLedger::class, 'ivx'),
            'user_id' => $transaction->user_id,
            'calc' => $transaction->calc,
            'type' => $transaction->type,
            'amount' => $transaction->amount,
            'fees' => 0.0,
            'total' => to_sum($transaction->amount, 0.0),
            'currency' => $transaction->currency,
            'invest_id' => 0,
            'tnx_id' => $transaction->id,
            'reference' => $transaction->tnx,
            'source' => $transaction->pay_from,
            'dest' => $transaction->pay_to,
            'desc' => $transaction->description,
        ];

        $ledger = new IvLedger();
        $ledger->fill($data);
        $ledger->save();

        return $ledger;
    }

    private function profitCalculate(PricingInvestment $invest)
    {
//        dd($invest,'profitCalculate');
        if (empty($invest)) return false;

        try {
            if (in_array($invest->status, [PricingInvestmentStatus::ACTIVE, PricingInvestmentStatus::COMPLETED])) {
                $this->wrapInTransaction(function ($invest) {
                    $this->investment->processInvestmentProfit($invest);
                }, $invest);
            }
        } catch (\Exception $e) {
            save_error_log($e, 'profit-calc');
        }

        return true;
    }

    private function bulkCalculate($investments)
    {
        if (empty($investments)) return false;
//        dd($investments,'bulkCalculate');
        foreach ($investments as $invest) {

            $this->profitCalculate($invest);
        }

        return true;
    }
}
