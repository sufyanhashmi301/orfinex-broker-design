<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerGroupRequest;
use App\Http\Requests\UpdateCustomerGroupRequest;
use App\Models\CustomerGroup;
use App\Services\CustomerGroupService;

class CustomerGroupController extends Controller
{
    protected $customerGroupService;

    public function __construct(CustomerGroupService $customerGroupService)
    {
        $this->customerGroupService = $customerGroupService;
    }

    public function index()
    {
        $customerGroups = CustomerGroup::paginate(10);

        return view('backend.customer_groups.index', compact('customerGroups'));
    }

    public function create()
    {
        return view('backend.customer_groups.create');
    }

    public function store(StoreCustomerGroupRequest $request)
    {

        $this->customerGroupService->create($request->validated());
        notify()->success(__(' Customer Group Created'));
        return redirect()->route('admin.customer-groups.index');

    }

    public function show(CustomerGroup $customerGroup)
    {
        return view('customer_groups.show', compact('customerGroup'));
    }

    public function edit(CustomerGroup $customerGroup)
    {
        return view('backend.customer_groups.edit', compact('customerGroup'));
    }

    public function update(UpdateCustomerGroupRequest $request, CustomerGroup $customerGroup)
    {
        $this->customerGroupService->update($customerGroup, $request->validated());
        notify()->success(__(' Customer Group updated successfully.'));
        return redirect()->route('admin.customer-groups.index');

    }

    public function destroy(CustomerGroup $customerGroup)
    {

        $this->customerGroupService->delete($customerGroup);
        notify()->success(__(' Customer Group deleted successfully.'));
        return redirect()->route('admin.customer-groups.index');

    }
}
