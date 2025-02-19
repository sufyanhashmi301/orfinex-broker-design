<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Enums\KYCStatus;
use App\Models\Lead;
use App\Models\Admin;
use App\Models\LeadSource;
use App\Models\LeadStage;
use App\Models\Kyc;
use App\Models\KycLevel;
use App\Models\RiskProfileTag;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stages = LeadStage::whereNotIn('name', ['Win', 'Lose'])->with('leads')->get();
        return view('backend.lead.index', compact('stages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $staff = Admin::all();
        $sources = LeadSource::all();
        $stages = LeadStage::all();
        return view('backend.lead.create', compact('staff', 'sources', 'stages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'salutation' => 'nullable|string|max:50',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'client_email' => 'required|email',
            'phone' => 'required|string|max:20',
            'source_id' => 'required|exists:lead_sources,id',
            'stage_id' => 'required|exists:lead_stages,id',
            'lead_owner' => 'required|exists:admins,id',
            'company_name' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'office_phone_number' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $lead = Lead::create($data);
        notify()->success(__('Lead created successfully.'));
        return redirect()->route('admin.lead.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lead = Lead::findOrFail($id);

        $source = LeadSource::find($lead->source_id);
        $stage = LeadStage::find($lead->stage_id);
        $leadOwner = Admin::find($lead->lead_owner);

        return view('backend.lead.show', compact('lead', 'source', 'stage', 'leadOwner'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $lead = Lead::findOrFail($id);
        $staff = Admin::all();
        $sources = LeadSource::all();
        $stages = LeadStage::all();

        return view('backend.lead.edit', compact('lead', 'staff', 'sources', 'stages'));
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
        $data = $request->validate([
            'salutation' => 'nullable|string|max:50',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'client_email' => 'required|email',
            'phone' => 'required|string|max:20',
            'source_id' => 'required|exists:lead_sources,id',
            'stage_id' => 'required|exists:lead_stages,id',
            'lead_owner' => 'required|exists:admins,id',
            'company_name' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'office_phone_number' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $lead = Lead::findOrFail($id);

        $lead->update($data);
        notify()->success(__('Lead updated successfully.'));
        return redirect()->route('admin.lead.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lead = Lead::findOrFail($id);

        $lead->delete();
        notify()->success(__('Lead deleted successfully.'));
        return redirect()->route('admin.lead.index');
    }

    public function stageUpdate($id, Request $request)
    {
        $lead = Lead::find($id);
        $lead->stage_id = $request->input('stage_id');
        $lead->save();

        return response()->json([
            'status' => 'success',
            'message' => __('Lead Stage Updated Successfully'),
        ]);
    }

    public function createClient($id)
    {
        $lead = Lead::findOrFail($id);
        // Assuming this function returns an array of countries
        $countries = getCountries();
        $kycLevels = KycLevel::where('status', 1)->get();
        $kycs = Kyc::where('kyc_sub_level_id', 3)->where('status', true)->get();
        // Get all risk profile tags
        $riskProfileTags = RiskProfileTag::all();
        $kycStatus = KYCStatus::cases();

        return view('backend.lead.create-client', compact('lead', 'countries', 'riskProfileTags', 'kycLevels', 'kycStatus', 'kycs'));
    }
}
