<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Enums\TxnStatus;
use App\Models\Transaction;
use Illuminate\Http\Request;

use LaravelDaily\Invoices\Invoice;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountTypeInvestment;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class BillingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $billing_transactions = Transaction::where('user_id', Auth::id())->orderBy('id', 'DESC')->get();
        $accounts = AccountTypeInvestment::where('user_id', Auth::id())->get();

        return view('frontend::billing.index', compact('billing_transactions', 'accounts'));
    }


    public function generateInvoice($transaction_id){

        $user = User::find(Auth::id());
        $transaction = Transaction::find($transaction_id);
        

        if($transaction->user_id != $user->id) {
            abort(403);
        }

        $account = AccountTypeInvestment::find($transaction->target_id);
        $account_type = $account->getAccountTypeSnapshotData();

        // Transaction status
        $transaction_status = '';
        if($transaction->status == TxnStatus::Success) {
            $transaction_status = 'PAID';
        } elseif ($transaction->status == TxnStatus::Pending) {
            $transaction_status = 'DUE';
        } elseif ($transaction->status == TxnStatus::Failed) {
            $transaction_status = 'REJECTED';
        }

        $customer = new Buyer([
            'name'          => $user->first_name . ' ' . $user->last_name,
            'custom_fields' => [
                'email' => $user->email,
            ],
        ]);

        $item = new InvoiceItem();
        $item->title($account_type['title'])
             ->pricePerUnit($transaction->final_amount);
             
        $invoice = Invoice::make()
            ->buyer($customer)
            ->addItem($item)
            ->currencyCode('USD')
            ->currencySymbol('$')
            ->status($transaction_status)
            ->series('')
            ->delimiter('')
            ->logo(asset(setting('site_logo','global')))
            ->sequence($transaction->tnx)
            ->date(Carbon::parse($transaction->created_at));

        return $invoice->stream();  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit($id)
    {
        //
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
        //
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
