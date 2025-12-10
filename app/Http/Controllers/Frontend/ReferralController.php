<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\TxnType;
use App\Enums\AccountBalanceType;
use App\Http\Controllers\Controller;
use App\Models\AdvertisementMaterial;
use App\Models\IbQuestion;
use App\Models\Language;
use App\Models\LevelReferral;
use App\Models\MultiLevel;
use App\Models\ReferralRelationship;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\ForexApiTrait;
use Brick\Math\BigDecimal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReferralController extends Controller
{
    use ForexApiTrait;
    public function ibRequest()
    {
        if(auth()->user()->ib_status == \App\Enums\IBStatus::APPROVED || isset(auth()->user()->ref_id)){
            return redirect()->route('user.multi-level.ib.dashboard');
        }
//        dd('ss');
        if (! setting('sign_up_referral', 'permission')) {
            abort('404');
        }
         if (!setting('master_ib_request', 'kyc_permissions') && auth()->user()->kyc < kyc_required_completed_level())  {
                notify()->error('KYC Pending: Please complete your KYC verification to proceed with your master ib request', __('Error'));
               return redirect()->route('user.kyc');
            }
        $user = auth()->user();

        $getReferral = $user->getReferrals()->first();
//        $totalReferralProfit = $user->totalReferralProfit();

        $level = LevelReferral::max('the_order');
        $balance = isset(auth()->user()->ib_balance) ? BigDecimal::of(auth()->user()->ib_balance) : BigDecimal::of(0);

        $ibQuestions = IbQuestion::where('status', true)->get();
        $qrCode = QrCode::size(300)->generate($getReferral->link);

        return view('frontend::partner.ib_request', compact( 'getReferral',  'level', 'balance', 'ibQuestions', 'qrCode'));
    }
    public function referral()
    {
        if(auth()->user()->ib_status != \App\Enums\IBStatus::APPROVED && !isset(auth()->user()->ref_id)){

        }

        if(auth()->user()->ib_status == \App\Enums\IBStatus::APPROVED){
            return redirect()->route('user.multi-level.ib.dashboard');
        }
        $user = auth()->user();

        $getReferral = $user->getReferrals()->first();
//        $totalReferralProfit = $user->totalReferralProfit();

        $level = LevelReferral::max('the_order');


        return view('frontend::partner.referrals', compact( 'getReferral',  'level'));
    }
    public function referralMembers(Request $request)
    {
        $user = auth()->user();

        // Capture the selected level order from the request, defaulting to '0' for all levels
        $selectedLevel = $request->input('level_order', 0);

        $referrals = User::where('ref_id', $user->id)->get();

        $maxLevelOrder = 0;

        $maxLevelOrderCount = $maxLevelOrder;

        return view('frontend::referral.index', compact('maxLevelOrderCount', 'referrals', 'selectedLevel'));
    }

    public function advertisementMaterial(Request $request)
    {
//        dd($request->all());
        $language = $request->input('language');
        $advertisements = AdvertisementMaterial::where('status', true);

        if ($language) {
            $advertisements->where('language', $language);
        }
        $advertisements = $advertisements->get()->groupBy('type');
//        dd($advertisements);

        if ($request->ajax()) {
            return view('frontend::referral.include.__advertisement_material_partial', compact('advertisements'));
        }
        $languages = Language::where('status', true)->get();
        return view('frontend::referral.index', compact('advertisements','languages'));
    }

    public function reports()
    {

        $user = auth()->user();
        if (setting('site_referral', 'global') == 'level') {
            $referrals = Transaction::where('user_id', $user->id)
                ->where('target_type', '!=', null)
                ->where('is_level', '=', 1)
                ->where('status', '!=', \App\Enums\TxnStatus::None) // Exclude none status transactions
                ->get()
                ->groupBy('level');
        } else {
            $referrals = Transaction::where('user_id', $user->id)
                ->where('target_type', '!=', null)
                ->where('status', '!=', \App\Enums\TxnStatus::None) // Exclude none status transactions
                ->get()
                ->groupBy('target');
        }
        $generalReferrals = Transaction::where('user_id', $user->id)
            ->where('target_type', null)
            ->where('type', TxnType::Referral)
            ->where('status', '!=', \App\Enums\TxnStatus::None) // Exclude none status transactions
            ->latest()
            ->paginate(8);

        $getReferral = $user->getReferrals()->first();
        $totalReferralProfit = $user->totalReferralProfit();

        return view('frontend::referral.index', compact('referrals', 'getReferral', 'totalReferralProfit', 'generalReferrals'));
    }

    public function history()
    {
        // Prepare filters from request (same logic as admin side)
        $filters = request()->only([
            'login', 'deal', 'symbol', 'date_filter', 'created_at',
            'transaction_date', 'transaction_status' // Legacy support
        ]);
        
        // Map legacy frontend filter names to backend filter names for backward compatibility
        if (isset($filters['transaction_date'])) {
            $filters['date_filter'] = $filters['transaction_date'];
            unset($filters['transaction_date']);
        }
        if (isset($filters['transaction_status'])) {
            $filters['status'] = $filters['transaction_status'];
            unset($filters['transaction_status']);
        }
        
        // Use the same service as admin side for consistency
        $query = \App\Services\IBTransactionQueryService::getUserIBTransactions(auth()->user()->id, $filters);
        
        if (!$query) {
            $transactions = new LengthAwarePaginator([], 0, 10);
        } else {
            // Get current page from request
            $currentPage = Paginator::resolveCurrentPage();
            $perPage = 10;
            
            // For union queries, we need to wrap in a subquery and then paginate
            // This is more efficient than loading all results
            $subQuery = $query->orderBy('created_at', 'desc');
            
            // Get total count by wrapping the union query
            $total = DB::query()->fromSub($subQuery, 'sub')->count();
            
            // Get paginated results by applying offset and limit to the subquery
            $offset = ($currentPage - 1) * $perPage;
            $results = DB::query()
                ->fromSub($subQuery, 'sub')
                ->offset($offset)
                ->limit($perPage)
                ->get();
            
            // Create paginator
            $transactions = new LengthAwarePaginator(
                $results,
                $total,
                $perPage,
                $currentPage,
                [
                    'path' => request()->url(),
                    'pageName' => 'page',
                ]
            );
            
            // Append query parameters to pagination links
            $transactions->appends(request()->query());
        }
        
        // Get summary data for display (same as admin side)
        $summary = \App\Services\IBTransactionQueryService::getUserIBTransactionsSummary(auth()->user()->id, $filters);
        
        // Get user's lifetime IB balance and current IB wallet balance
        $user = auth()->user();
        $lifetimeIBBalance = $user ? $user->ib_balance : 0;
        
        // Get current IB wallet balance
        $currentIBWalletBalance = 0;
        if ($user) {
            $ibWalletAccount = get_user_account($user->id, \App\Enums\AccountBalanceType::IB_WALLET);
            $currentIBWalletBalance = $ibWalletAccount ? $ibWalletAccount->amount : 0;
        }
        
        if (request()->ajax()) {
            return response()->json([
                'html' => view('frontend::referral.include.__ib_transaction_row', compact('transactions'))->render(),  
                'html_mobile' => view('frontend::referral.include.__mobile_ib_transaction_row', compact('transactions'))->render(),
                'pagination' => (string) $transactions->links(),
                'total' => $transactions->total(),
                'summary' => [
                    'ib_balance' => number_format($lifetimeIBBalance, 2),
                    'current_ib_wallet_balance' => number_format($currentIBWalletBalance, 2),
                    'total_amount' => number_format($summary['total_amount'], 2),
                    'total_count' => number_format($summary['total_count']),
                    'filter_start_date' => $summary['filter_start_date'] ? $summary['filter_start_date']->format('M d, Y') : null,
                    'filter_end_date' => $summary['filter_end_date'] ? $summary['filter_end_date']->format('M d, Y') : null,
                ]
            ]);
        }

        return view('frontend::referral.index', compact('transactions', 'summary', 'lifetimeIBBalance', 'currentIBWalletBalance'));
    }

    public function exportHistory()
    {
        // Get filters from request (same as display logic)
        $filters = request()->only([
            'login', 'deal', 'symbol', 'date_filter', 'created_at',
            'transaction_date', 'transaction_status' // Legacy support
        ]);
        
        // Map legacy frontend filter names to backend filter names for backward compatibility
        if (isset($filters['transaction_date'])) {
            $filters['date_filter'] = $filters['transaction_date'];
            unset($filters['transaction_date']);
        }
        if (isset($filters['transaction_status'])) {
            $filters['status'] = $filters['transaction_status'];
            unset($filters['transaction_status']);
        }
        
        $user = auth()->user();
        $fileName = strtolower(str_replace(' ', '-', $user->username)) . '-ib-transactions.xlsx';
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\ibTransactionsUsersExport($user->id, $filters), 
            $fileName
        );
    }

    public function network() {
        $level = LevelReferral::max('the_order');
//        dd($level);
        return view('frontend::referral.index', compact( 'level'));
    }

    public function download($filename)
    {
        if (!File::exists('assets/'.$filename)) {
            notify()->error(__('file not exists'), __('Error'));
            return redirect()->back();
        }
        return response()->download('assets/'.$filename);
    }

}
