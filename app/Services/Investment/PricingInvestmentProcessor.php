<?php

namespace App\Services\Investment;

use App\Enums\AccountBalanceType;
use App\Enums\ForexTradingAccountTypesStatus;
use App\Enums\ForexTradingStatus;
use App\Enums\RefundType;
use App\Enums\ActionType;
use App\Enums\TraderType;
use App\Enums\TxnType;
use App\Enums\TxnStatus;
use App\Enums\LedgerTnxType;
use App\Enums\InvestmentStatus;
use App\Enums\TransactionStatus;
use App\Enums\TransactionCalcType;
use App\Enums\TransactionType;

use App\Events\NewForexAccountEvent;

use App\Models\AccountGroup;
use App\Models\AccountType;
use App\Models\Country;
use App\Models\ForexSchemaInvestment;
use App\Models\ForexSchemaPhaseRule;
use App\Models\ForexTrading;
use App\Models\FundedBonus;

use App\Models\Ledger;



use App\Models\Referral;

use App\Models\Transaction;
use App\Models\User;
use App\Services\ForexApiService;

use App\Services\x9ApiService;
use Brick\Math\RoundingMode;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Brick\Math\BigDecimal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Constraint\Count;
use Txn;

class PricingInvestmentProcessor
{


    private $details;
//    private $transactionService;

    public function __construct()
    {
//        $this->transactionService = new TransactionService();
    }

    public function setDetails(array $details)
    {
        $this->details = $details;
        return $this;
    }

    private function toTnxMeta($invest)
    {
        $data = [
            'desc' => data_get($invest, 'desc'),
            'amount' => data_get($invest, 'amount'),
            'profit' => data_get($invest, 'profit'),
            'total' => data_get($invest, 'total'),
            'term' => data_get($invest, 'term'),
            'term_calc' => data_get($invest, 'term_calc'),
        ];

        return $data;
    }

    private function toFundedTnxMeta($invest)
    {
//        dd(data_get($invest, 'amount'));
        $data = [
            'desc' => data_get($invest, 'desc'),
//            'amount' => data_get($invest, 'amount'),
            'total' => data_get($invest, 'total')
        ];
//dd($data);
        return $data;
    }

    private function saveInvestment()
    {
//        dd($this->details);
        $invest = new ForexSchemaInvestment();
        $invest->fill($this->details);
        $invest->pvx = generate_unique_ivx(ForexSchemaInvestment::class, 'pvx');
//        dd($invest);
        $invest->save();
        return $invest;
    }

    private function schemePartnerBonus($invest)
    {
        $userId = $invest->user_id;
        Log::info(['user_id first in partner bonus' => $userId]);
        $schemeId = $invest->scheme_id;
        $investAmount = $invest->amount;
//        dd($schemeId);
        $schemePartnerBonuses = SchemePartnerBonus::where('iv_scheme_id', $schemeId)->where('status', 1);
//        dd($schemePartnerBonuses->get());
//        dd($userId,$invest);
        if ($schemePartnerBonuses->exists()) {
            foreach ($schemePartnerBonuses->get() as $schemePartnerBonus) {
                switch ((int)$schemePartnerBonus->level) {
                    case 1:
                        $this->firstLevel($userId, $schemePartnerBonus->bonus, $invest, $investAmount);
                        break;
                    case 2:
                        $this->secondLevel($userId, $schemePartnerBonus->bonus, $invest, $investAmount);
                        break;
                    case 3:
                        $this->thirdLevel($userId, $schemePartnerBonus->bonus, $invest, $investAmount);
                        break;
                    case 4:
                        $this->fourthLevel($userId, $schemePartnerBonus->bonus, $invest, $investAmount);
                        break;
                    case 5:
                        $this->fifthLevel($userId, $schemePartnerBonus->bonus, $invest, $investAmount);
                        break;
                    case 6:
                        $this->sixthLevel($userId, $schemePartnerBonus->bonus, $invest, $investAmount);
                        break;
                    case 7:
                        $this->seventhLevel($userId, $schemePartnerBonus->bonus, $invest, $investAmount);
                        break;
                    default:
                        return true;
                }
            }
        }

    }

    public function fundedBonus($invest)
    {
        $userId = $invest->user_id;
//        Log::info(['user_id first in partner bonus' => $userId]);
        $schemeId = $invest->forex_schema_phase_rule_id;
        $investAmount = to_minus($invest->amount, $invest->discount);
//        dd($schemeId);
        $schemePartnerBonuses = FundedBonus::where('forex_schema_phase_rule_id', $schemeId)->where('status', 1);
//        dd($schemePartnerBonuses->get());
//        dd($userId,$invest);
        if ($schemePartnerBonuses->exists()) {
            foreach ($schemePartnerBonuses->get() as $schemePartnerBonus) {
                switch ((int)$schemePartnerBonus->level) {
                    case 1:
                        $this->firstLevelFunded($userId, $schemePartnerBonus->bonus, $invest, $investAmount);
                        break;
                    case 2:
                        $this->secondLevelFunded($userId, $schemePartnerBonus->bonus, $invest, $investAmount);
                        break;
//                    case 3:
//                        $this->thirdLevel($userId, $schemePartnerBonus->bonus, $invest, $investAmount);
//                        break;
//                    case 4:
//                        $this->fourthLevel($userId, $schemePartnerBonus->bonus, $invest, $investAmount);
//                        break;
//                    case 5:
//                        $this->fifthLevel($userId, $schemePartnerBonus->bonus, $invest, $investAmount);
//                        break;
//                    case 6:
//                        $this->sixthLevel($userId, $schemePartnerBonus->bonus, $invest, $investAmount);
//                        break;
//                    case 7:
//                        $this->seventhLevel($userId, $schemePartnerBonus->bonus, $invest, $investAmount);
//                        break;
                    default:
                        return true;
                }
            }
        }

    }

    private function schemePartnerBonusForFavorite($invest)
    {
        $userId = $invest->user_id;
        $schemeId = $invest->scheme_id;
        $investAmount = $invest->amount;
//        dd($invest);
        $favoriteBonuses = config('favoritebonus');
//        Log::info(['favoriteLevel session value'=> Session('favoriteLevel'.$userId)]);
        if (count($favoriteBonuses) > Session('favoriteLevel' . $userId)) {
            $favoriteBonuses = array_slice($favoriteBonuses, 0, Session('favoriteLevel' . $userId));
        }
        if (count($favoriteBonuses) > 0) {
            foreach ($favoriteBonuses as $index => $favoriteBonus) {
                ++$index;
                switch ((int)$index) {
                    case 1:
                        $this->firstLevel($userId, $favoriteBonus, $invest, $investAmount);
                        break;
                    case 2:
                        $this->secondLevel($userId, $favoriteBonus, $invest, $investAmount);
                        break;
                    case 3:
                        $this->thirdLevel($userId, $favoriteBonus, $invest, $investAmount);
                        break;
                    case 4:
                        $this->fourthLevel($userId, $favoriteBonus, $invest, $investAmount);
                        break;
                    case 5:
                        $this->fifthLevel($userId, $favoriteBonus, $invest, $investAmount);
                        break;
                    case 6:
                        $this->sixthLevel($userId, $favoriteBonus, $invest, $investAmount);
                        break;
                    case 7:
                        $this->seventhLevel($userId, $favoriteBonus, $invest, $investAmount);
                        break;
                    case 8:
                        $this->eightLevel($userId, $favoriteBonus, $invest, $investAmount);
                        break;
                    case 9:
                        $this->ninthLevel($userId, $favoriteBonus, $invest, $investAmount);
                        break;
                    case 10:
                        $this->tenthLevel($userId, $favoriteBonus, $invest, $investAmount);
                        break;
                    default:
                        return true;
                }
            }
        }

    }

    public function firstLevel($userId, $schemePartnerBonusPercentage, $invest, $investAmount)
    {
        $query = Referral::query();
        $query->where('user_id', $userId);
        $referrals = $query->get();
//        dd($userId,$referrals);
        $this->saveSchemePartnerBonus($userId, $referrals, $schemePartnerBonusPercentage, $invest, $investAmount);
    }

    public function secondLevel($userId, $schemePartnerBonusPercentage, $invest, $investAmount)
    {
//        $firstLevelUsers = Referral::where('user_id',$userId)->pluck('refer_by');
        $firstLevelUsers = Session('firstLevelUsers' . $userId);
        $query = Referral::with('referBy');
        $query->whereIn('user_id', $firstLevelUsers);
        $referrals = $query->get();
//        dd($schemePartnerBonusPercentage,$invest,$investAmount);
        $this->saveSchemePartnerBonus($userId, $referrals, $schemePartnerBonusPercentage, $invest, $investAmount);
    }

    public function thirdLevel($userId, $schemePartnerBonusPercentage, $invest, $investAmount)
    {
//        $firstLevelUsers = Referral::where('user_id',$userId)->pluck('refer_by');
//        $secondLevelUsers = Referral::whereIn('user_id',$firstLevelUsers)->pluck('refer_by');
        $secondLevelUsers = Session('secondLevelUsers' . $userId);

        $query = Referral::with('referBy');
        $query->whereIn('user_id', $secondLevelUsers);
        $referrals = $query->get();
        $this->saveSchemePartnerBonus($userId, $referrals, $schemePartnerBonusPercentage, $invest, $investAmount);


    }

    public function fourthLevel($userId, $schemePartnerBonusPercentage, $invest, $investAmount)
    {
//        $firstLevelUsers = Referral::where('user_id',$userId)->pluck('refer_by');
//        $secondLevelUsers = Referral::whereIn('user_id',$firstLevelUsers)->pluck('refer_by');
//        $thirdLevelUsers = Referral::whereIn('user_id',$secondLevelUsers)->pluck('refer_by');
        $thirdLevelUsers = Session('thirdLevelUsers' . $userId);

        $query = Referral::with('referBy');
        $query->whereIn('user_id', $thirdLevelUsers);
        $referrals = $query->get();
        $this->saveSchemePartnerBonus($userId, $referrals, $schemePartnerBonusPercentage, $invest, $investAmount);

    }

    public function fifthLevel($userId, $schemePartnerBonusPercentage, $invest, $investAmount)
    {
//        $firstLevelUsers = Referral::where('user_id',$userId)->pluck('refer_by');
//        $secondLevelUsers = Referral::whereIn('user_id',$firstLevelUsers)->pluck('refer_by');
//        $thirdLevelUsers = Referral::whereIn('user_id',$secondLevelUsers)->pluck('refer_by');
//        $fourthLevelUsers = Referral::whereIn('user_id',$thirdLevelUsers)->pluck('refer_by');
        $fourthLevelUsers = Session('fourthLevelUsers' . $userId);

        $query = Referral::with('referBy');
        $query->whereIn('user_id', $fourthLevelUsers);
        $referrals = $query->get();
        $this->saveSchemePartnerBonus($userId, $referrals, $schemePartnerBonusPercentage, $invest, $investAmount);

    }

    public function sixthLevel($userId, $schemePartnerBonusPercentage, $invest, $investAmount)
    {
//        $firstLevelUsers = Referral::where('user_id',$userId)->pluck('refer_by');
//        $secondLevelUsers = Referral::whereIn('user_id',$firstLevelUsers)->pluck('refer_by');
//        $thirdLevelUsers = Referral::whereIn('user_id',$secondLevelUsers)->pluck('refer_by');
//        $fourthLevelUsers = Referral::whereIn('user_id',$thirdLevelUsers)->pluck('refer_by');
//        $fifthLevelUsers = Referral::whereIn('user_id',$fourthLevelUsers)->pluck('refer_by');
        $fifthLevelUsers = Session('fifthLevelUsers' . $userId);

        $query = Referral::with('referBy');
        $query->whereIn('user_id', $fifthLevelUsers);
        $referrals = $query->get();
        $this->saveSchemePartnerBonus($userId, $referrals, $schemePartnerBonusPercentage, $invest, $investAmount);

    }

    public function seventhLevel($userId, $schemePartnerBonusPercentage, $invest, $investAmount)
    {
//        $firstLevelUsers = Referral::where('user_id',$userId)->pluck('refer_by');
//        $secondLevelUsers = Referral::whereIn('user_id',$firstLevelUsers)->pluck('refer_by');
//        $thirdLevelUsers = Referral::whereIn('user_id',$secondLevelUsers)->pluck('refer_by');
//        $fourthLevelUsers = Referral::whereIn('user_id',$thirdLevelUsers)->pluck('refer_by');
//        $fifthLevelUsers = Referral::whereIn('user_id',$fourthLevelUsers)->pluck('refer_by');
//        $sixthLevelUsers = Referral::whereIn('user_id',$fifthLevelUsers)->pluck('refer_by');
        $sixthLevelUsers = Session('sixthLevelUsers' . $userId);

        $query = Referral::with('referBy');
        $query->whereIn('user_id', $sixthLevelUsers);
        $referrals = $query->get();
        $this->saveSchemePartnerBonus($userId, $referrals, $schemePartnerBonusPercentage, $invest, $investAmount);

    }

    public function eightLevel($userId, $schemePartnerBonusPercentage, $invest, $investAmount)
    {
//        $firstLevelUsers = Referral::where('user_id',$userId)->pluck('refer_by');
//        $secondLevelUsers = Referral::whereIn('user_id',$firstLevelUsers)->pluck('refer_by');
//        $thirdLevelUsers = Referral::whereIn('user_id',$secondLevelUsers)->pluck('refer_by');
//        $fourthLevelUsers = Referral::whereIn('user_id',$thirdLevelUsers)->pluck('refer_by');
//        $fifthLevelUsers = Referral::whereIn('user_id',$fourthLevelUsers)->pluck('refer_by');
//        $sixthLevelUsers = Referral::whereIn('user_id',$fifthLevelUsers)->pluck('refer_by');
//        $seventhLevelUsers = Referral::whereIn('user_id',$sixthLevelUsers)->pluck('refer_by');
        $seventhLevelUsers = Session('seventhLevelUsers' . $userId);
//        dd($seventhLevelUsers);

        $query = Referral::with('referBy');
        $query->whereIn('user_id', $seventhLevelUsers);
        $referrals = $query->get();
        $this->saveSchemePartnerBonus($userId, $referrals, $schemePartnerBonusPercentage, $invest, $investAmount);

    }

    public function ninthLevel($userId, $schemePartnerBonusPercentage, $invest, $investAmount)
    {
//        $firstLevelUsers = Referral::where('user_id',$userId)->pluck('refer_by');
//        $secondLevelUsers = Referral::whereIn('user_id',$firstLevelUsers)->pluck('refer_by');
//        $thirdLevelUsers = Referral::whereIn('user_id',$secondLevelUsers)->pluck('refer_by');
//        $fourthLevelUsers = Referral::whereIn('user_id',$thirdLevelUsers)->pluck('refer_by');
//        $fifthLevelUsers = Referral::whereIn('user_id',$fourthLevelUsers)->pluck('refer_by');
//        $sixthLevelUsers = Referral::whereIn('user_id',$fifthLevelUsers)->pluck('refer_by');
//        $seventhLevelUsers = Referral::whereIn('user_id',$sixthLevelUsers)->pluck('refer_by');
//        $eightLevelUsers = Referral::whereIn('user_id',$seventhLevelUsers)->pluck('refer_by');
        $eightLevelUsers = Session('eightLevelUsers' . $userId);

        $query = Referral::with('referBy');
        $query->whereIn('user_id', $eightLevelUsers);
        $referrals = $query->get();
        $this->saveSchemePartnerBonus($userId, $referrals, $schemePartnerBonusPercentage, $invest, $investAmount);

    }

    public function tenthLevel($userId, $schemePartnerBonusPercentage, $invest, $investAmount)
    {
//        $firstLevelUsers = Referral::where('user_id',$userId)->pluck('refer_by');
//        $secondLevelUsers = Referral::whereIn('user_id',$firstLevelUsers)->pluck('refer_by');
//        $thirdLevelUsers = Referral::whereIn('user_id',$secondLevelUsers)->pluck('refer_by');
//        $fourthLevelUsers = Referral::whereIn('user_id',$thirdLevelUsers)->pluck('refer_by');
//        $fifthLevelUsers = Referral::whereIn('user_id',$fourthLevelUsers)->pluck('refer_by');
//        $sixthLevelUsers = Referral::whereIn('user_id',$fifthLevelUsers)->pluck('refer_by');
//        $seventhLevelUsers = Referral::whereIn('user_id',$sixthLevelUsers)->pluck('refer_by');
//        $eightLevelUsers = Referral::whereIn('user_id',$seventhLevelUsers)->pluck('refer_by');
//        $ninthLevelUsers = Referral::whereIn('user_id',$eightLevelUsers)->pluck('refer_by');
        $ninthLevelUsers = Session('ninthLevelUsers' . $userId);

        $query = Referral::with('referBy');
        $query->whereIn('user_id', $ninthLevelUsers);
        $referrals = $query->get();
        $this->saveSchemePartnerBonus($userId, $referrals, $schemePartnerBonusPercentage, $invest, $investAmount);

    }

    public function saveSchemePartnerBonus($InvestorUserId, $referrals, $schemePartnerBonusPercentage, $invest, $investAmount)
    {
        $source = AccountBalanceType::SCHEME_PARTNER_BONUS;
//        dd($referrals);
        foreach ($referrals as $referral) {
            $userId = $referral->refer_by;
//            if(Session('hasFavorite') == true){
//                $userId = $referral->user_id;
//            }
            $userAccount = get_user_account($userId, $source);
            $bonus = 0.0;

            if ((float)$schemePartnerBonusPercentage > 0) {

                $scale = (is_crypto($invest->currency)) ? dp_calc('crypto') : dp_calc('fiat');
                $bonus = BigDecimal::of($schemePartnerBonusPercentage)->multipliedBy($investAmount)->dividedBy(100, $scale, RoundingMode::HALF_DOWN);

                $userAccount->amount = BigDecimal::of($userAccount->amount)->plus($bonus);
                $userAccount->save();

                $invest_bonus = $invest;
                $invest_bonus->user_id = $userId;
                $invest_bonus->amount = $bonus;
                $invest_bonus->desc = __('Partner Bonus WRT :InvestorId', ['InvestorId' => get_ref_code($InvestorUserId)]);
                $invest_bonus->meta = ['source' => AccountBalanceType::SCHEME_PARTNER_BONUS, 'fees' => 0, 'exchange' => 0];

                $transaction = $this->saveSchemePartnerBonusTransaction($invest_bonus);

//                $this->transactionService->confirmSchemePartnerTransaction($transaction, [
//                    'id' => auth()->user()->id,
//                    'name' => auth()->user()->name
//                ]);
            }
        }

    }

    //Funded Levels
    public function firstLevelFunded($userId, $schemePartnerBonusPercentage, $invest, $investAmount)
    {
        $query = Referral::query();
        $query->where('user_id', $userId);
        $referrals = $query->get();
//        dd($userId,$referrals);
        $desc = __('Direct Commission - Funded WRT :InvestorId', ['InvestorId' => get_ref_code($userId)]);
        $this->saveFundedBonus($userId, $referrals, $schemePartnerBonusPercentage, $invest, $investAmount, $desc);
    }

    public function secondLevelFunded($userId, $schemePartnerBonusPercentage, $invest, $investAmount)
    {
        $firstLevelUsers = Referral::where('user_id', $userId)->pluck('refer_by');
//        $firstLevelUsers = Session('firstLevelUsers' . $userId);
        $query = Referral::with('referBy')
            ->whereHas('referBy.user_metas', function ($q) {
                $q->where('meta_key', 'master_affiliate')->where('meta_value', 'active'); // Apply your condition on the comments relation
            });
        $query->whereIn('user_id', $firstLevelUsers);
        $referrals = $query->get();
//        dd($firstLevelUsers,$referrals);
        $desc = __('Master Commission - Funded WRT :InvestorId', ['InvestorId' => get_ref_code($userId)]);
        $this->saveFundedBonus($userId, $referrals, $schemePartnerBonusPercentage, $invest, $investAmount, $desc);
    }

    public function saveFundedBonus($InvestorUserId, $referrals, $fundedBonusPercentage, $invest, $investAmount, $desc)
    {
        $source = AccountBalanceType::AFFILIATE_WALLET;
//        dd($referrals);
        foreach ($referrals as $referral) {
            $userId = $referral->refer_by;
//            if(Session('hasFavorite') == true){
//                $userId = $referral->user_id;
//            }
            $userAccount = get_user_account($userId, $source);
            $bonus = 0.0;

            if ((float)$fundedBonusPercentage > 0) {
//                dd($fundedBonusPercentage);

                $scale = (is_crypto($invest->currency)) ? dp_calc('crypto') : dp_calc('fiat');
                $bonus = BigDecimal::of($fundedBonusPercentage)->multipliedBy($investAmount)->dividedBy(100, $scale, RoundingMode::HALF_DOWN);

                $userAccount->amount = BigDecimal::of($userAccount->amount)->plus($bonus);
                $userAccount->save();

                $invest_bonus = $invest;
                $invest_bonus->user_id = $userId;
                $invest_bonus->amount = $bonus;
                $invest_bonus->desc = $desc;
                $invest_bonus->meta = ['source' => $source, 'fees' => 0, 'exchange' => 0];

                $transaction = $this->saveFundedBonusTransaction($invest_bonus, $source);
//                $this->transactionService->confirmTransactionWithSource($transaction, [
//                    'id' => 1,
//                    'name' => 'system'
//                ], $source);
            }
        }

    }


    public function favoriteUser($userId)
    {
//        dd($userId);

        $hasFavorite = false;
        Session(['favoriteLevel' . $userId => 0]);
        $firstLevelUsers = Referral::where('user_id', $userId)->pluck('refer_by');
        Session(['firstLevelUsers' . $userId => $firstLevelUsers]);

        if (count($firstLevelUsers) > 0) {
            $favoriteUser = User::whereIn('id', $firstLevelUsers);
            $favoriteUser->where('favorite', 1);
            $favoriteUser = $favoriteUser->exists();
            if ($favoriteUser) {
                Session(['favoriteLevel' . $userId => 1]);
                $hasFavorite = true;
            }
        }

        $secondLevelUsers = Referral::whereIn('user_id', $firstLevelUsers)->pluck('refer_by');
        Session(['secondLevelUsers' . $userId => $secondLevelUsers]);
        if (count($secondLevelUsers) > 0) {
            $favoriteUser = User::whereIn('id', $secondLevelUsers);
            $favoriteUser->where('favorite', 1);
            $favoriteUser = $favoriteUser->exists();
            if ($favoriteUser) {
                Session(['favoriteLevel' . $userId => 2]);
                $hasFavorite = true;
            }
        }
        $thirdLevelUsers = Referral::whereIn('user_id', $secondLevelUsers)->pluck('refer_by');
        Session(['thirdLevelUsers' . $userId => $thirdLevelUsers]);
        if (count($thirdLevelUsers) > 0) {
            $favoriteUser = User::whereIn('id', $thirdLevelUsers);
            $favoriteUser->where('favorite', 1);
            $favoriteUser = $favoriteUser->exists();
            if ($favoriteUser) {
                Session(['favoriteLevel' . $userId => 3]);
                $hasFavorite = true;
            }
        }

        $fourthLevelUsers = Referral::whereIn('user_id', $thirdLevelUsers)->pluck('refer_by');
        Session(['fourthLevelUsers' . $userId => $fourthLevelUsers]);
        if (count($fourthLevelUsers) > 0) {
            $favoriteUser = User::whereIn('id', $fourthLevelUsers);
            $favoriteUser->where('favorite', 1);
            $favoriteUser = $favoriteUser->exists();
            if ($favoriteUser) {
                Session(['favoriteLevel' . $userId => 4]);
                $hasFavorite = true;
            }
        }

        $fifthLevelUsers = Referral::whereIn('user_id', $fourthLevelUsers)->pluck('refer_by');
        Session(['fifthLevelUsers' . $userId => $fifthLevelUsers]);
        if (count($fifthLevelUsers) > 0) {
            $favoriteUser = User::whereIn('id', $fifthLevelUsers);
            $favoriteUser->where('favorite', 1);
            $favoriteUser = $favoriteUser->exists();
            if ($favoriteUser) {
                Session(['favoriteLevel' . $userId => 5]);
                $hasFavorite = true;
            }
        }

        $sixthLevelUsers = Referral::whereIn('user_id', $fifthLevelUsers)->pluck('refer_by');
        Session(['sixthLevelUsers' . $userId => $sixthLevelUsers]);
        if (count($sixthLevelUsers) > 0) {
            $favoriteUser = User::whereIn('id', $sixthLevelUsers);
            $favoriteUser->where('favorite', 1);
            $favoriteUser = $favoriteUser->exists();
            if ($favoriteUser) {
                Session(['favoriteLevel' . $userId => 6]);
                $hasFavorite = true;
            }
        }


        $seventhLevelUsers = Referral::whereIn('user_id', $sixthLevelUsers)->pluck('refer_by');
        Session(['seventhLevelUsers' . $userId => $seventhLevelUsers]);
        if (count($seventhLevelUsers) > 0) {
            $favoriteUser = User::whereIn('id', $seventhLevelUsers);
            $favoriteUser->where('favorite', 1);
            $favoriteUser = $favoriteUser->exists();
            if ($favoriteUser) {
                Session(['favoriteLevel' . $userId => 7]);
                $hasFavorite = true;
            }
        }

        $eightLevelUsers = Referral::whereIn('user_id', $seventhLevelUsers)->pluck('refer_by');
        Session(['eightLevelUsers' . $userId => $eightLevelUsers]);
        if (count($eightLevelUsers) > 0) {
            $favoriteUser = User::whereIn('id', $eightLevelUsers);
            $favoriteUser->where('favorite', 1);
            $favoriteUser = $favoriteUser->exists();
            if ($favoriteUser) {
                Session(['favoriteLevel' . $userId => 8]);
                $hasFavorite = true;
            }
        }

        $ninthLevelUsers = Referral::whereIn('user_id', $eightLevelUsers)->pluck('refer_by');
        Session(['ninthLevelUsers' . $userId => $ninthLevelUsers]);
        if (count($ninthLevelUsers) > 0) {
            $favoriteUser = User::whereIn('id', $ninthLevelUsers);
            $favoriteUser->where('favorite', 1);
            $favoriteUser = $favoriteUser->exists();
            if ($favoriteUser) {
                Session(['favoriteLevel' . $userId => 9]);
                $hasFavorite = true;
            }
        }

        $tenthLevelUsers = Referral::whereIn('user_id', $ninthLevelUsers)->pluck('refer_by');
        Session(['tenthLevelUsers' . $userId => $tenthLevelUsers]);
        if (count($tenthLevelUsers) > 0) {
            $favoriteUser = User::whereIn('id', $tenthLevelUsers);
            $favoriteUser->where('favorite', 1);
            $favoriteUser = $favoriteUser->exists();
            if ($favoriteUser) {
                Session(['favoriteLevel' . $userId => 10]);
                $hasFavorite = true;
            }
        }
        return $hasFavorite;
    }

    public function saveInvestTransaction($invest)
    {
        $userId = $invest->user_id;
        $source = Arr::get($invest, 'meta.source', AccType('main'));
        if ($source == AccountBalanceType::MAIN || $source == \App\Enums\PaymentMethod::STRIPE || $source == \App\Enums\PaymentMethod::BANK_TRANSFER || $source == \App\Enums\PaymentMethod::COINBASE) {
            $account = get_user_account($userId, $source);
        } else {
            $account = ForexTrading::where('login', $source)->first();
        }
//        dd($account);
        $amount = Arr::get($invest, 'total');
        $fees = Arr::get($invest, 'meta.fees', 0);
        $depositType = TxnType::Funded;
        $txnInfo = Txn::new($amount, $fees, to_sum($amount, $fees), AccountBalanceType::FUNDED, 'funded account', $depositType, TxnStatus::Pending, 'USD', $amount, auth()->id(), null, 'User', $manualData ?? [], 'none', $account->id, AccountBalanceType::FUNDED);

//        $transaction = new Transaction();
//        $transaction->tnx = generate_unique_tnx();
//        $transaction->type = TransactionType::PRICING_INVESTMENT;
//        $transaction->user_id = $userId;
//        $transaction->account_from = $account->id;
//        $transaction->calc = TransactionCalcType::DEBIT;
//        $transaction->amount = Arr::get($invest, 'total');
//        $transaction->fees = Arr::get($invest, 'meta.fees', 0);
//        $transaction->total = to_sum($transaction->amount, $transaction->fees);
//        $transaction->currency = Arr::get($invest, 'currency');
//        $transaction->tnx_amount = Arr::get($invest, 'total');
//        $transaction->tnx_fees = Arr::get($invest, 'meta.fees', 0);
//        $transaction->tnx_total = to_sum($transaction->tnx_amount, $transaction->tnx_fees);
//        $transaction->tnx_currency = Arr::get($invest, 'currency');
//        $transaction->tnx_method = AccountBalanceType::PRICING_INVEST;
//        $transaction->exchange = Arr::get($invest, 'meta.exchange', 1);
//        $transaction->status = TransactionStatus::NONE;
//        $transaction->description = __('Invest on :Name', ['name' => Arr::get($invest, 'scheme.name')]);
//        $transaction->meta = $this->toTnxMeta($invest);
//        $transaction->pay_from = $source;
//        $transaction->pay_to = AccountBalanceType::PRICING_INVEST;
//        $transaction->created_by = $userId;
//        $transaction->save();
////        dd($transaction);
        return $txnInfo;
    }

    public function createForexAccountForPricing($invest)
    {
        $password = generate_dummy_password();


        $group = $invest->forexSchemaPhaseRule->forexSchemaPhase->group;
//        dd($group);
        $schema = $invest->forexSchemaPhaseRule->forexSchemaPhase->forexSchema;
//        dd($schema);
        $traderType = $invest->trader_type;

        $phaseIds = $schema->forexSchemaPhases->pluck('id');


        $latestInvestments = ForexSchemaInvestment::whereHas('forexSchemaPhaseRule', function($query) use ($phaseIds) {
            $query->whereIn('forex_schema_phase_id', $phaseIds);
        })->whereIn('status', [InvestmentStatus::ACTIVE,InvestmentStatus::VIOLATED])
            ->latest('login')->first();
//        dd($schema,$phaseIds,$latestInvestments);
        if($traderType == TraderType::MT5) {
            $login = 0;
        }elseif($traderType == TraderType::X9) {
            $login = 'default';
        }
//        if (setting('is_forex_group_range', 'global')){
//            $forexAccount = ForexSchemaInvestment::where('forex_schema_phase_rule_id',$invest->forexSchemaPhaseRule->id)->orderBY('login','desc')->first();
            if($latestInvestments) {
                if(isset($latestInvestments->login) && $latestInvestments->login >= $invest->forexSchemaPhaseRule->forexSchemaPhase->forexSchema->end_range){
                    $message = __('Sorry, The account creation range is completed of :title type. Please choose different type or contact support to increase the account range.',['title'=> $schema->title]);
                    notify()->error($message, 'Error');
                    return redirect()->back();
                }elseif(isset($latestInvestments->login)) {
                    $login = ++$latestInvestments->login;
                    $investment = ForexSchemaInvestment::where('login',$login)->exists();
                    if($investment){
                        ++$login;
                    }
//                    dd($login,$latestInvestments->login);
                    }
            }else{
                $login = $invest->forexSchemaPhaseRule->forexSchemaPhase->forexSchema->start_range;
            }
//        }
//dd($traderType);
        if($traderType == TraderType::MT5) {
          return  $this->mt5CreateNewUser($invest,$login,$group,$password);
        }elseif($traderType == TraderType::X9) {
            return  $this->x9CreateNewUser($invest,$login,$group,$password);
        }



    }

    public function mt5CreateNewUser($invest,$login,$group,$password)
    {
        $data = [
            "login" => $login,
            "group" => $group,
            "firstName" => 'Phase 1 $'.$invest->amount_allotted.'-'.$invest->user->first_name,
            "middleName" => "",
            "lastName" => $invest->user->last_name,
            "leverage" => $invest->leverage,
            "rights" => "USER_RIGHT_ALL",
            "country" => $invest->user->country,
            "city" => $invest->user->city,
            "state" => "",
            "zipCode" => $invest->user->zip_code,
            "address" => $invest->user->address,
            "phone" => $invest->user->phone,
            "email" => $invest->user->email,
            "agent" => 0,
            "account" => "",
            "company" => setting('site_title', 'global'),
            "language" => 0,
            "phonePassword" => 'SNNH@2024@bol',
            "status" => "RE",
            "masterPassword" => $password,
            "investorPassword" => 'SNNH@2024@bol'
        ];
//        dd($data);
        $forexApiService = new ForexApiService();
        $response = $forexApiService->createUser($data);
//        dd($response);
        if ($response['success']) {
            $resResult = $response['result'];
            $mt5Login = $resResult['login'];
//            dd($response,$response->data[0]->Login);
            if ($mt5Login && $resResult['responseCode'] == 0) {
                $rightData =  [
                    "login" => $mt5Login,
                    "rights" => 'USER_RIGHT_ENABLED',

                ];
                $forexApiService->setUserRights($rightData);

                $invest->main_password = $password;
                $invest->save();
                return $mt5Login;

            }
            throw ValidationException::withMessages(['invest' => 'Some error occurred! please try again']);

//            return redirect()->back()->withErrors(['msg' => 'Some error occurred! please try again']);

        }
    }
    public function x9CreateNewUser($invest,$login,$group,$password)
    {
//        Country::wheere()
        $data = [
            "preferred_login" => 'default',
            "client_id" => null,
            "client_group_type_id" => 2,
            "client_group_id" => (int)$group,
            "first_name" => $invest->user->first_name,
            "middle_name" => null,
            "last_name" => $invest->user->last_name,
//            "leverage" => $invest->leverage,

            "country_id" => 5,
//            "city" => $invest->user->city,
//            "state" => "",
//            "zipCode" => $invest->user->zip_code,
//            "address" => $invest->user->address,
            "phone" => $invest->user->phone,
            "email" => $invest->user->email,
//            "agent" => 0,
            "company" => setting('site_title', 'global'),
            "master_password" => $password,
            "investor_password" => 'SNNH@2024@bol'
        ];
//        dd($data);
        $forexApiService = new x9ApiService();
        $response = $forexApiService->createUser($data);
//        dd($response);
        if ($response['success']) {
            $resResult = $response['result']['trading_account'];

            $invest->main_password = $password;
            $invest->save();
            return $resResult['account_number'];
        }
        throw ValidationException::withMessages(['invest' => 'Some error occurred! please try again']);

    }
    public function checkGroup($invest)
    {
        $rule = ForexSchemaPhaseRule::find($invest->forex_schema_phase_rule_id);
        $schemeGroup = $rule->forexSchemaPhase->group;
        return $schemeGroup;
    }

    public function mt5DepositForexAccountForPricing($mt5Login, $invest)
    {
        $actualDeposit = $invest->amount_allotted;
        if ($invest->currency == 'USC') {
            $actualDeposit = $actualDeposit * 100;
        }
        $data = [
            'login' => $mt5Login,
            'Amount' => $actualDeposit,
            'type' => 1,//deposit
            'TransactionComments' => 'Direct Funded Balance'
        ];
//        dd($data);
        $forexApiService = new ForexApiService();
        $response = $forexApiService->balanceOperation($data);

//dd($response,$response['result']['responseCode']);
        if ($response['success'] && $response['result']['responseCode'] == 10009) {
            return true;
        } else {
            return false;
        }

    }
    public function x9DepositForexAccountForPricing($mt5Login, $invest)
    {
        $actualDeposit = $invest->amount_allotted;
        if ($invest->currency == 'USC') {
            $actualDeposit = $actualDeposit * 100;
        }
        $data = [
            'login_id' => $mt5Login,
            'operation_type' => 'balance',
            'amount' => $actualDeposit,
            'transaction_type' => 'deposit',//deposit
            'comment' => 'Direct Funded Balance',
            'operate_without_checking' => true
        ];
//        dd($data);
        $forexApiService = new x9ApiService();
        $response = $forexApiService->balanceOperation($data);
//        dd($response);

//dd($response,$response['result']['responseCode']);
        if ($response['success'] && $response['result']['status'] == true) {
            return true;
        } else {
            return false;
        }

    }


    public function saveFundedBonusTransaction($invest, $source)
    {
//        dd($invest,Arr::get($invest, 'amount'),Arr::get($invest, 'fees'));
        $userId = $invest->user_id;
        $account = get_user_account($userId, $source);
        $invest = $invest->toArray();
//        dd($account);
//dd(Arr::get($invest, 'amount'));
        $transaction = new Transaction();
        $transaction->tnx = generate_unique_tnx();
        $transaction->type = TransactionType::AFFILIATE_BONUS;
        $transaction->user_id = $userId;
        $transaction->account_to = $account->id;
        $transaction->calc = TransactionCalcType::CREDIT;
        $transaction->amount = Arr::get($invest, 'amount');
        $transaction->fees = Arr::get($invest, 'meta.fees', 0);
        $transaction->total = to_sum($transaction->amount, $transaction->fees);
        $transaction->currency = Arr::get($invest, 'currency');
        $transaction->tnx_amount = Arr::get($invest, 'amount');
        $transaction->tnx_fees = Arr::get($invest, 'meta.fees', 0);
        $transaction->tnx_total = to_sum($transaction->tnx_amount, $transaction->tnx_fees);
//        dd(to_sum($transaction->tnx_amount, $transaction->tnx_fees));
//        dd($invest,Arr::get($invest, 'amount'),Arr::get($invest, 'amount'),to_sum($transaction->tnx_amount, $transaction->tnx_fees,to_sum($transaction->tnx_amount, $transaction->tnx_fees)));
//dd(Arr::get($invest, 'exchange_rate', 1));
        $transaction->tnx_currency = Arr::get($invest, 'currency');
        $transaction->tnx_method = 'system';
        $transaction->exchange = Arr::get($invest, 'exchange_rate', 1);
        $transaction->status = TransactionStatus::NONE;
        $transaction->description = Arr::get($invest, 'desc');
        $transaction->meta = $this->toFundedTnxMeta($invest);
//        dd($this->toFundedTnxMeta($invest));
//        $transaction->pay_from = $source;
//        $transaction->pay_to = AccType('scheme_partner_bonus');
        $transaction->created_by = $userId;
        $transaction->completed_at = Carbon::now();
//        dd($transaction);
        $transaction->save();

        return $transaction;
    }

    private function saveIvAction($action, $type, $typeId, $actionBy = null)
    {
        $ivAction = new IvAction();
        $ivAction->action = $action;
        $ivAction->action_at = Carbon::now();
        $ivAction->type = $type;
        $ivAction->type_id = $typeId;
        $ivAction->action_by = $actionBy ?? auth()->user()->id;
        $ivAction->save();

        return $ivAction;
    }

    public function rocessInvestment(): ForexSchemaInvestment
    {
//        $source_type = Arr::get($this->details, 'type');
//        dd($source_type);
        $source_type = Arr::get($this->details, 'meta.payment_type');
        $source = Arr::get($this->details, 'source', AccType('main'));
        $userId = auth()->user()->id;
        $amount = Arr::get($this->details, 'total');
        $actualWithdraw = $amount;
//        dd($source_type,$source);
        if ($source_type == 'wallets') {
            $userAccount = get_user_account($userId, $source);
            $balance = $userAccount->amount;
//            dd($source_type,$source,$actualWithdraw,$balance);
            if (BigDecimal::of($actualWithdraw)->compareTo($balance) > 0) {
                throw ValidationException::withMessages([
                    'amount' => __('Sorry, you do not have sufficient balance in your account for investment. Please make a deposit and try again once you have sufficient balance.')
                ]);
            }
        }
        if ($source_type == 'forex') {
            $userAccount = ForexTrading::where('login', $source)->first();
            $fx = $userAccount;

            if ($fx->currency == 'USC') {
                $scale = (is_crypto($fx->currency)) ? dp_calc('crypto') : dp_calc('fiat');
                $actualWithdraw = round($actualWithdraw * 100, $scale);
            }
            $balance = $this->getForexAccountBalance($userAccount->login);
            if (BigDecimal::of($actualWithdraw)->compareTo($balance) > 0) {
                throw ValidationException::withMessages([
                    'amount' => __('Sorry, you do not have sufficient balance in your account for investment. Please make a deposit and try again once you have sufficient balance.')
                ]);
            }
        }
        $invest = $this->saveInvestment();
        if (!blank($invest)) {
            if ($source_type == 'wallets') {
                $fees = BigDecimal::of(data_get($invest, 'meta.fees', 0));
                $userAccount->amount = BigDecimal::of($userAccount->amount)->minus(BigDecimal::of($invest->total))->minus($fees);
                $userAccount->save();
            }
            if ($source_type == 'forex') {
                $this->forexInvestmentWithdraw($invest, $source, $actualWithdraw);
            }

//            $this->saveIvAction(ActionType::ORDER, "invest", $invest->id);

            return $invest;
        } else {
            throw ValidationException::withMessages(['invest' => __("Unable to invest on selected plan. Please try again or contact us if the problem persists.")]);
        }

    }

    public function forexInvestmentWithdraw($invest, $login, $actualWithdraw)
    {
        $withdrawUrl = config('forextrading.withdrawUrl');


        $dataArray = [
            'Login' => $login,
            'Amount' => -$actualWithdraw,
            'Comment' => "Pricing-Invest/" . $invest->currency . "/" . $invest->id,

        ];
//        dd($dataArray);
        $withdrawResponse = $this->sendApiPostRequest($withdrawUrl, $dataArray);
//        dd($userAccount,$withdrawResponse);
        if ($withdrawResponse->status() == 200 && $withdrawResponse->object() == 10009) {

        } else {
            $invest->delete();
            throw new \Exception(__("You do not have enough money! Kindly select valid amount. Try again!"));
        }
    }

    public function processInvestment(): ForexSchemaInvestment
    {
//        $source_type = Arr::get($this->details, 'type');
//        dd($source_type);
//        $source_type = Arr::get($this->details, 'meta.payment_type');
//        $source = Arr::get($this->details, 'source', AccType('main'));
//        $userId = auth()->user()->id;
//        $amount = Arr::get($this->details, 'total');
//        $actualWithdraw = $amount;
//        dd($source_type,$source);
//        if ($source_type == 'wallets') {
//            $userAccount = get_user_account($userId, $source);
//            $balance = $userAccount->amount;
////            dd($source_type,$source,$actualWithdraw,$balance);
//            if (BigDecimal::of($actualWithdraw)->compareTo($balance) > 0) {
//                throw ValidationException::withMessages([
//                    'amount' => __('Sorry, you do not have sufficient balance in your account for investment. Please make a deposit and try again once you have sufficient balance.')
//                ]);
//            }
//        }
//        if ($source_type == 'forex') {
//            $userAccount = ForexTrading::where('login', $source)->first();
//            $fx = $userAccount;
//
////            if ($fx->currency == 'USC') {
////                $scale = (is_crypto($fx->currency)) ? dp_calc('crypto') : dp_calc('fiat');
////                $actualWithdraw = round($actualWithdraw * 100, $scale);
////            }
////            $balance = $this->getForexAccountBalance($userAccount->login);
////            if (BigDecimal::of($actualWithdraw)->compareTo($balance) > 0) {
////                throw ValidationException::withMessages([
////                    'amount' => __('Sorry, you do not have sufficient balance in your account for investment. Please make a deposit and try again once you have sufficient balance.')
////                ]);
////            }
//        }

        $invest = $this->saveInvestment();
        if (!blank($invest)) {
//            $transaction = $this->saveInvestTransaction($invest);

//            if (blank($transaction)) {
//                throw ValidationException::withMessages(['transaction' => __("Failed to approved the investment. Please try again or contact us if the problem persists.")]);
//            }

//            $transaction->status = TransactionStatus::PENDING;
//            $transaction->reference = $invest->pvx;
//            $transaction->save();

//            $this->transactionService->confirmTransactionForInvestment($transaction, [
//                'id' => auth()->user()->id,
//                'name' => auth()->user()->name
//            ]);

//            if ($source_type == 'wallets') {
////                $ledgerBalance = $this->getLedgerBalance($userAccount->id);
////                $this->createLedgerEntry($transaction, $ledgerBalance);
//                $fees = BigDecimal::of(data_get($invest, 'meta.fees', 0));
//                $userAccount->amount = BigDecimal::of($userAccount->amount)->minus(BigDecimal::of($invest->total))->minus($fees);
//                $userAccount->save();
//            }
//            if ($source_type == 'forex') {
//                $this->forexInvestmentWithdraw($invest, $source, $actualWithdraw);
//            }

//            $this->saveIvAction(ActionType::ORDER, "invest", $invest->id);

            return $invest;
        } else {
            throw ValidationException::withMessages(['invest' => __("Unable to invest on selected plan. Please try again or contact us if the problem persists.")]);
        }

    }

    private function createLedgerEntry($transaction, $ledgerBalance)
    {
        $ledger = new Ledger();
        $ledger->transaction_id = $transaction->id;
//        dd($ledger);

        if ($transaction->calc == TransactionCalcType::DEBIT) {
            $ledger->debit = $transaction->amount;
            $ledger->account_id = $transaction->account_from;
//            dd($ledgerBalance,BigDecimal::of($transaction->total));
            $balance = BigDecimal::of($ledgerBalance)->minus(BigDecimal::of($transaction->total));
//            dd($ledgerBalance,$balance);
        }

        if ($transaction->calc == TransactionCalcType::CREDIT) {
            $ledger->credit = $transaction->total;
            $ledger->account_id = $transaction->account_to;
            $balance = BigDecimal::of($ledgerBalance)->plus(BigDecimal::of($transaction->amount));
        }
//        dd($balance,BigDecimal::of(0.00));

        if ($balance < BigDecimal::of(0.00)) {
//            dd($balance);
            throw new \Exception(__("Unprocessable transaction."));
        }

        $ledger->balance = $balance;
//        dd($ledger);
        $ledger->save();

        return $ledger;
    }

    private function getLedgerBalance($accountId)
    {
        $latestLedgerEntry = Ledger::where('account_id', $accountId)->orderBy('id', 'desc')->first();
        return data_get($latestLedgerEntry, 'balance', 0.00);
    }

    public function processInvestmentForMigration(): ForexSchemaInvestment
    {
//        $source = Arr::get($this->details, 'source', AccType('main'));
//        $amount = Arr::get($this->details, 'amount');

//        $userId = auth()->user()->id;
//        dd($userId, $source);
//        $userAccount = get_user_account($userId, $source);
//        $userBalance = $userAccount->amount;

//        if (BigDecimal::of($amount)->compareTo($userBalance) > 0) {
//            throw ValidationException::withMessages([
//                'amount' => __('Sorry, you do not have sufficient balance in your account for investment. Please make a deposit and try again once you have sufficient balance.')
//            ]);
//        }

        $invest = $this->saveInvestment();

        if (!blank($invest)) {
//            $fees = BigDecimal::of(data_get($invest, 'meta.fees', 0));
//            $userAccount->amount = BigDecimal::of($userAccount->amount)->minus(BigDecimal::of($invest->amount))->minus($fees);
//            $userAccount->save();

            $this->saveIvAction(ActionType::ORDER, "invest", $invest->id);

            return $invest;
        } else {
            throw ValidationException::withMessages(['invest' => __("Unable to invest on selected plan. Please try again or contact us if the problem persists.")]);
        }
    }

    public function approveInvestment(ForexSchemaInvestment $invest, $remarks = null, $note = null)
    {
//        dd($invest);
        $mt5Login = $this->createForexAccountForPricing($invest);
//        $mt5Login = 11111127;
        $traderType = $invest->trader_type;

        if($traderType == TraderType::MT5) {
            $deposit = $this->mt5DepositForexAccountForPricing($mt5Login, $invest);
        }elseif($traderType == TraderType::X9) {
            $deposit = $this->x9DepositForexAccountForPricing($mt5Login, $invest);
        }
//        dd($deposit);
        if ($deposit) {
            $termStart = CarbonImmutable::now();
//            $termTenure = sprintf("%s %s", data_get($invest, 'scheme.term'), ucfirst(data_get($invest, 'scheme.term_type')));
            $group = $invest->forexSchemaPhaseRule->forexSchemaPhase->group;

            $invest->account_name = $invest->forexSchemaPhaseRule->forexSchemaPhase->forexSchema->title .'_'. $invest->id;
            $invest->login = $mt5Login;
            $invest->group = $group;
            $invest->status = InvestmentStatus::ACTIVE;
            $invest->term_start = $termStart;

//        $invest->term_end = $termEnd;
            $invest->save();
            //referral Bonus
//            $this->fundedBonus($invest);

        }
        return $invest;
    }

    public function approveInvestmentForMigration(ForexSchemaInvestment $invest, $remarks = null, $note = null)
    {
        $transaction = $this->saveInvestTransaction($invest);

        if (blank($transaction)) {
            throw ValidationException::withMessages(['transaction' => __("Failed to approved the investment. Please try again or contact us if the problem persists.")]);
        }

        $transaction->status = TransactionStatus::PENDING;
        $transaction->reference = $invest->ivx;
        $transaction->save();

//        $this->transactionService->confirmTransactionForMigration($transaction, [
//            'id' => auth()->user()->id,
//            'name' => auth()->user()->name
//        ]);

        $ledger = new IvLedger();
        $ledger->ivx = generate_unique_ivx(IvLedger::class, 'ivx');
        $ledger->user_id = $invest->user_id;
        $ledger->type = LedgerTnxType::INVEST;
        $ledger->calc = TransactionCalcType::NONE;
        $ledger->amount = $invest->amount;
        $ledger->fees = 0;
        $ledger->total = to_sum($ledger->amount, $ledger->fees);
        $ledger->currency = $invest->currency;
        $ledger->desc = $transaction->description;
        $ledger->invest_id = $invest->id;
        $ledger->tnx_id = $transaction->id;
        $ledger->reference = $invest->ivx;
        $ledger->source = $transaction->pay_from;
        $ledger->dest = $transaction->pay_to;
        $ledger->save();

        $termStart = CarbonImmutable::now();
        $termTenure = sprintf("%s %s", data_get($invest, 'scheme.term'), ucfirst(data_get($invest, 'scheme.term_type')));
        $termEnd = $termStart->add($termTenure)->addMinutes(1)->addSeconds(5);

        $invest->remarks = $remarks;
        $invest->note = $note;
        $invest->reference = $transaction->tnx;
        $invest->status = InvestmentStatus::ACTIVE;
        $invest->term_start = $termStart;
        $invest->term_end = $termEnd;
        $invest->save();

        if (iv_start_automatic()) {
            $actionBy = isset(system_admin()->id) ? system_admin()->id : 1;
            $this->saveIvAction(ActionType::STATUS_ACTIVE, "invest", $invest->id, $actionBy);
        } else {
            $this->saveIvAction(ActionType::STATUS_ACTIVE, "invest", $invest->id);
        }
        return $invest;
    }

    private function cancelPendingInvest(ForexSchemaInvestment $invest)
    {
        $source = data_get($invest, 'meta.source', AccType('main'));
        $paymentType = data_get($invest, 'meta.payment_type', 'wallets');
        $fees = BigDecimal::of(data_get($invest, 'meta.fees', 0));
//        dd($source,$paymentType);
        if ($paymentType == 'wallets') {
            $userAccount = get_user_account($invest->user_id, $source);
            $userAccount->amount = BigDecimal::of($userAccount->amount)->plus(BigDecimal::of($invest->total))->plus($fees);
            $userAccount->save();

            $invest->status = InvestmentStatus::CANCELLED;
            $invest->save();

        } elseif ($paymentType == 'banks') {
            $invest->status = InvestmentStatus::CANCELLED;
            $invest->save();
        } else {
            $this->cancelForexInvestmentTransaction($source, $invest->total, $invest);
        }

        return $invest->fresh();
    }

    private function cancelForexInvestmentTransaction($source, $amount, $invest)
    {
        DB::beginTransaction();
        try {
            $depositUrl = config('forextrading.depositUrl');

            $forex = ForexTrading::where('login', $source)->first();
            $actualDeposit = $amount;
            if ($forex->currency == 'USC') {
                $actualDeposit = $actualDeposit * 100;
            }
//            dd($invest->ivScheme);
            $dataArray = array(
                'Login' => $source,
                'Amount' => $actualDeposit,
                'Comment' => "Pricing/Invst/cancel/" . $invest->currency . "/" . $invest->id,

            );
            $cancelWithdrawResponse = $this->sendApiPostRequest($depositUrl, $dataArray);
//        dd($cancelWithdrawResponse->object());

            if ($cancelWithdrawResponse->status() == 200 && $cancelWithdrawResponse->object() == 10009) {
                $invest->status = InvestmentStatus::CANCELLED;
                $invest->save();
                DB::commit();
            }
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            throw ValidationException::withMessages(['invest' => 'Something wrong! please try again']);
            // something went wrong
        }
    }


    public function cancelRunningInvest(ForexSchemaInvestment $invest)
    {
        $refundAmount = 0;
        $refundType = request()->get('cancel-method', RefundType::PARTIAL);

        $remarks = request()->get('remarks');
        $note = request()->get('note');

        if (empty($note)) {
            throw ValidationException::withMessages(['note' => __("Cancelation note is required to cancelled.")]);
        }

        if ($refundType == RefundType::TOTAL) {
            $refundAmount = $invest->amount;
        } elseif ($refundType == RefundType::PARTIAL) {
            $earnedProfit = IvProfit::where('user_id', $invest->user_id)
                ->where('invest_id', $invest->id)
                ->sum('amount');
            $remainingAmount = BigDecimal::of($invest->amount)->minus(BigDecimal::of($earnedProfit));
            $refundAmount = $remainingAmount->toFloat() > 0 ? $remainingAmount->toFloat() : 0;
        }

        $data = [
            'ivx' => generate_unique_ivx(IvLedger::class, 'ivx'),
            'user_id' => $invest->user_id,
            'type' => LedgerTnxType::CAPITAL,
            'calc' => TransactionCalcType::NONE,
            'amount' => $refundAmount,
            'fees' => 0.00,
            'total' => to_sum($refundAmount, 0.00),
            'currency' => $invest->currency,
            'desc' => "Returned funded after cancelled",
            'note' => $note,
            'remarks' => $remarks,
            'invest_id' => $invest->id,
            'reference' => $invest->ivx,
            'source' => AccType('invest'),
            'created_at' => Carbon::now(),
        ];

        $ledger = new IvLedger();
        $ledger->fill($data);
        $ledger->save();

//        $investWallet = get_user_account($invest->user_id, AccType('invest'));
//        $investWallet->amount = BigDecimal::of($investWallet->amount)->plus($refundAmount);
//        $investWallet->save();
        $source = data_get($invest, 'meta.source');
        $this->cancelForexInvestmentTransaction($source, $refundAmount, $invest);

        $invest->status = InvestmentStatus::CANCELLED;
        $invest->remarks = ($remarks) ? "New: " . $remarks . "\n\n" . "Old:" . $invest->remarks : $invest->remarks;
        $invest->note = ($note) ? "New: " . $note . "\n\n" . "Old:" . $invest->note : $invest->note;
        $invest->save();

        $this->saveIvAction(ActionType::REFUND, "invest", $invest->id);
        $this->saveIvAction(ActionType::STATUS_CANCEL, "invest", $invest->id);
        return $invest->fresh();
    }

    private function cancelByAdmin(ForexSchemaInvestment $invest)
    {
        if ($invest->status == InvestmentStatus::PENDING) {
            $invest = $this->cancelPendingInvest($invest);

            try {
                ProcessEmail::dispatch('investment-cancel-customer', data_get($invest, 'user'), null, $invest);
                ProcessEmail::dispatch('investment-cancel-admin', data_get($invest, 'user'), null, $invest);
            } catch (\Exception $e) {
                save_mailer_log($e, 'investment-cancel-admin');
            }

            return $invest;
        } else {
            $invest = $this->cancelRunningInvest($invest);

            try {
                ProcessEmail::dispatch('investment-cancellation-customer', data_get($invest, 'user'), null, $invest);
                ProcessEmail::dispatch('investment-cancellation-admin', data_get($invest, 'user'), null, $invest);
            } catch (\Exception $e) {
                save_mailer_log($e, 'investment-cancellation-admin');
            }

            return $invest;
        }
    }

    public function cancelInvestment(ForexSchemaInvestment $invest)
    {
        if (is_admin()) {
            return $this->cancelByAdmin($invest);
        } elseif ($invest->user_can_cancel && $invest->status == InvestmentStatus::PENDING) {
            $this->cancelPendingInvest($invest);
        } else {
            throw ValidationException::withMessages(['invest' => __("Cancellation failed! Please try again or contact us if the problem persists.")]);
        }
    }

}
