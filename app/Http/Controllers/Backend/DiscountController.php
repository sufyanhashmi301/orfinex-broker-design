<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDiscountRequest;
use App\Http\Requests\UpdateDiscountRequest;
use App\Models\Discount;
use DataTables;
use App\Services\DiscountService;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    protected $discountService;

    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Discount::latest('updated_at')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('type', function($row) {
                    return view('backend.discounts.include.__type', [
                        'type' => $row->type,
                        'percentage' => $row->percentage,
                        'fixed_amount' => $row->fixed_amount
                    ]);
                })
                ->editColumn('expire_at', function($row) {
                    return \Carbon\Carbon::parse($row->expire_at)->format('Y-m-d');
                })
                ->addColumn('status', 'backend.discounts.include.__status')
                ->addColumn('action', 'backend.discounts.include.__action')
                ->rawColumns(['status', 'action', 'type'])  // Add 'type' to rawColumns for rendering HTML
                ->make(true);
        }

        return view('backend.discounts.index');
    }





    // Create method
    public function create()
    {
        return response()->json();  // Returning a JSON response for creating a new discount
    }

    // Store method to save a discount
    public function store(StoreDiscountRequest $request)
    {
//        dd($request->all());
        $data = $request->validated();
        // Ensure fixed_amount is null if the type is 'percentage'
        if ($data['type'] === 'percentage') {
            $data['fixed_amount'] = null; // Set fixed_amount to null
        }

        // Ensure percentage is null if the type is 'fixed'
        if ($data['type'] === 'fixed') {
            $data['percentage'] = null; // Set percentage to null
        }
        $this->discountService->create($data);
        notify()->success(__('Discount created successfully.'));
        return redirect()->route('admin.discounts.index');
    }

    // Edit method for showing the edit form
        public function edit(Discount $discount)
    {
        return view('backend.discounts.include.__edit_form', compact('discount'))->render();
    }

    // Update method to modify an existing discount
    public function update(UpdateDiscountRequest $request, Discount $discount)
    {
        $data = $request->validated();
//        $data = $request->all();
//dd($data);
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
//        dd($data);

        // Update the discount using the validated data
        $this->discountService->update($discount, $data);

        notify()->success(__('Discount updated successfully.'));
        return redirect()->route('admin.discounts.index');
    }


    // Delete method to remove a discount
    public function destroy(Discount $discount)
    {
        $this->discountService->delete($discount);
        notify()->success(__('Discount deleted successfully.'));
        return redirect()->route('admin.discounts.index');
    }
}
