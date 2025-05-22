<?php

namespace App\Http\Controllers\Backend;

use DataTables;
use App\Models\Offer;
use App\Models\Discount;
use App\Models\UserOffer;
use App\Models\AccountType;
use Illuminate\Http\Request;
use App\Services\DiscountService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDiscountRequest;
use App\Http\Requests\UpdateDiscountRequest;

class DiscountController extends Controller
{
    protected $discountService;

    public function __construct(DiscountService $discountService)
    {
        $this->middleware('permission:discount-code-list', ['only' => ['index']]);
        $this->middleware('permission:discount-code-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:discount-code-edit', ['only' => ['update']]);
        $this->middleware('permission:discount-code-delete', ['only' => ['destroy']]);

        $this->discountService = $discountService;
    }

    public function index(Request $request)
    {
        
        $account_types = AccountType::all();
        $discount_codes = Discount::paginate(10);
        
        return view('backend.discounts.index', get_defined_vars());
    }

    // Create method
    public function create()
    {
        return response()->json();  // Returning a JSON response for creating a new discount
    }

    // Store method to save a discount
    public function store(StoreDiscountRequest $request)
    {

        $data = $request->validated();
        
        // Ensure fixed_amount is null if the type is 'percentage'
        if ($data['type'] === 'percentage') {
            $data['fixed_amount'] = null; // Set fixed_amount to null
        }

        // Ensure percentage is null if the type is 'fixed'
        if ($data['type'] === 'fixed') {
            $data['percentage'] = null; // Set percentage to null
        }

        Discount::create($data);

        notify()->success(__('Discount created successfully.'));
        return redirect()->route('admin.discounts.index');
    }

    // Edit method for showing the edit form
    public function edit(Discount $discount) {
        $account_types = AccountType::all();
        return view('backend.discounts.include.__edit_form', get_defined_vars())->render();
    }

    // Update method to modify an existing discount
    public function update(UpdateDiscountRequest $request, Discount $discount)
    {
        $data = $request->validated();

        // Handle `fixed_amount` and `percentage` based on the type
        if ($data['type'] === 'fixed') {
            $data['percentage'] = null;  // Set percentage to null if the type is fixed
            if (empty($data['fixed_amount'])) {
                $data['fixed_amount'] = 0; // Default fixed_amount to 0 if it's empty
            }
        } elseif ($data['type'] === 'percentage') {
            $data['fixed_amount'] = null;  // Set fixed_amount to null if the type is percentage
            if (empty($data['percentage'])) {
                $data['percentage'] = 0;  // Default percentage to 0 if it's empty
            }
        }

        // Update the discount using the validated data
        $discount->update($data);

        notify()->success(__('Discount updated successfully.'));
        return redirect()->route('admin.discounts.index');
    }

    /**
     * Discount Levels Update
     */
    public function updateLevels(Request $request) {
        $discount = Discount::find($request->discount_id);

        if(!$discount) {
            notify()->error('Unknown error occured.');
            return redirect()->back();
        }

        $discount->update(['discount_levels' => array_values($request->data ?? [])]);

        notify()->success('Discount Levels updated successfully!');
        return redirect()->back();

    }


    // Delete method to remove a discount
    public function destroy(Discount $discount)
    {
        if($discount->purpose == "offers") {
            // Step 1: Get all Offer IDs for the given discount
            $offerIds = Offer::where('discount_id', $discount->id)->pluck('id');

            // Step 2: Update related UserOffer records to 'expired'
            UserOffer::whereIn('offer_id', $offerIds)->where('status', 'available')->update(['status' => 'expired']);

            // Step 3: Delete all Offers
            Offer::whereIn('id', $offerIds)->delete();
        }

        $this->discountService->delete($discount);
        notify()->success(__('Discount deleted successfully.'));
        return redirect()->route('admin.discounts.index');
    }
}
