<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Services\InvoiceService;

class InvoiceController extends Controller
{

    protected $invoice;

    public function __construct(InvoiceService $invoice) {
        $this->invoice = $invoice;
    }

    // Ajax function to Verify Coupon Code
    public function verifyCoupon(Request $request) {
        $account_type_id = get_hash($request->input('account_type_id')); // Retrieve the scheme type from the request
        $userId = auth()->user()->id; // Get the current user's ID

        $discountQuery = Discount::where('code', $request->input('code'))
            ->where('status', true) // Check if the discount is active
            ->where(function ($query) {
                $query->where('expire_at', '>=', now()) // Check if the discount is not expired
                    ->orWhereNull('expire_at');      // Or no expiration date
            });

        $discount = $discountQuery->first();

        if ($discount) {

            // Check if the discount has reached its usage limit
            if ($discount->used_count >= $discount->usage_limit) {
                return response()->json(['valid' => false, 'message' => __('This discount code has reached its usage limit.')]);
            }

            // Check if you can apply discount to this account type
            if(!in_array($account_type_id, $discount->applied_to) && !in_array('all', $discount->applied_to)) {
                return response()->json(['valid' => false, 'message' => __('Invalid or expired discount code.')]);
            }

            // Check if the total price falls in between some levels
            $totalPrice = $request->total_price;
            $levelExists = null;

            foreach ($discount->discount_levels ?? [] as $level) {
                $from = (float) $level['amount_from'];
                $to = (float) $level['amount_to'];

                if ($totalPrice >= $from && $totalPrice <= $to) {
                    $levelExists = [
                        'amount' => $level['amount'],
                        'type' => $level['type'], 
                    ];
                    break;
                }
            }

            if ($levelExists) {
                return response()->json([
                    'valid' => true,
                    'discount_id' => the_hash($discount->id),
                    'discount_amount' => $levelExists['amount'],
                    'discount_type' => $levelExists['type'],
                    'message' => "Discount applied successfully"
                ]);
            } else {
                return response()->json([
                    'valid' => true,
                    'discount_id' => the_hash($discount->id),
                    'discount_type' => $discount->type,
                    'discount_amount' => $discount->type == 'fixed' ? $discount->fixed_amount : $discount->percentage,
                    'message' => "Discount applied successfully"
                ]);
            }

        }

        return response()->json(['valid' => false, 'message' => __('Invalid or expired discount code.')]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $transaction = Transaction::findOrFail($id);

        $invoice = $this->invoice->invoicePDF($transaction->id);

        return $invoice->stream();  
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
