<?php

namespace App\Http\Controllers\Backend;

use App\Imports\ImportLeads;
use App\Imports\CustomStringBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Enums\KYCStatus;
use App\Models\Lead;
use App\Models\Deal;
use App\Models\Admin;
use App\Models\LeadSource;
use App\Models\LeadPipeline;
use App\Models\Kyc;
use App\Models\KycLevel;
use App\Models\RiskProfileTag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('permission:lead-list', ['only' => ['index']]);
         $this->middleware('permission:lead-create', ['only' => ['store']]);
         $this->middleware('permission:lead-action', ['only' => ['destroy,update']]);

    }
    public function index(Request $request)
    {
        $loggedInUser = auth()->user();

        if ($request->ajax()) {

            if ($loggedInUser->hasRole('Super-Admin')) {
                $leads = Lead::with('owner')->select('leads.*');
            } else {
                $leads = Lead::whereIn('leads.lead_owner', [$loggedInUser->id])
                    ->with('owner')
                    ->select('leads.*');
            }

            return Datatables::of($leads)
                ->addIndexColumn()
                ->addColumn('username', 'backend.lead.include.__user')
                ->editColumn('client_email', function($lead) {
                    return '<span class="lowercase">' . $lead->client_email . '</span>';
                })
                ->editColumn('owner', 'backend.lead.include.__owner')
                ->addColumn('action', 'backend.lead.include.__action')
                ->orderColumn('username', function ($query, $direction) {
                    $query->orderBy('leads.first_name', $direction)
                          ->orderBy('leads.last_name', $direction);
                })
                ->orderColumn('owner', function ($query, $direction) {
                    $query->leftJoin('admins', 'leads.lead_owner', '=', 'admins.id')
                          ->orderBy('admins.first_name', $direction)
                          ->orderBy('admins.last_name', $direction)
                          ->select('leads.*');
                })
                ->rawColumns(['username', 'client_email', 'owner', 'action'])
                ->make(true);

        }

        return view('backend.lead.index');
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
        $pipelines = LeadPipeline::all();
        return view('backend.lead.create', compact('staff', 'sources', 'pipelines'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $leadValidator = Validator::make($request->all(), [
            'salutation' => 'nullable|string|max:50',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'client_email' => 'required|email',
            'phone' => 'required|string|max:20',
            'source_id' => 'required|exists:lead_sources,id',
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

        if ($leadValidator->fails()) {
            return redirect()->back()->withErrors($leadValidator)->withInput();
        }

        $dealData = null;
        if ($request->has('create_deal') && $request->create_deal == 'on') {
            $dealValidator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'lead_pipeline_id' => 'required|exists:lead_pipelines,id',
                'pipeline_stage_id' => 'required|exists:pipeline_stages,id',
                'close_date' => 'required|date',
                'value' => 'required|numeric',
            ]);

            if ($dealValidator->fails()) {
                return redirect()->back()->withErrors($dealValidator)->withInput();
            }

            $dealData = $dealValidator->validated();
        }

        DB::beginTransaction();

        try {
            $lead = Lead::create($leadValidator->validated());

            if ($dealData) {
                $dealData['added_by'] = auth()->id();
                $dealData['lead_id'] = $lead->id;
                Deal::create($dealData);
            }

            DB::commit();

            notify()->success(__('Lead created successfully.'));
            return redirect()->route('admin.lead.index');

        } catch (\Exception $e) {

            DB::rollBack();

            \Log::error('Error creating lead or deal: ' . $e->getMessage());

            notify()->error(__('An error occurred while creating the lead or deal.'));
            return redirect()->back();
        }
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
        $leadOwner = Admin::find($lead->lead_owner);

        return view('backend.lead.show', compact('lead', 'source', 'leadOwner'));
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

        return view('backend.lead.edit', compact('lead', 'staff', 'sources'));
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
        $validator = $request->validate([
            'salutation' => 'nullable|string|max:50',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'client_email' => 'required|email',
            'phone' => 'required|string|max:20',
            'source_id' => 'required|exists:lead_sources,id',
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

        $lead->update($validator);
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

    public function storeDeal($request, $lead)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lead_pipeline_id' => 'required|exists:lead_pipelines,id',
            'pipeline_stage_id' => 'required|exists:pipeline_stages,id',
            'close_date' => 'required|date',
            'value' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        $data['added_by'] = auth()->id();
        $data['lead_id'] = $lead->id;

        $deal = Deal::create($data);
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

    public function getLead($id)
    {
        $lead = Lead::find($id);

        return view('backend.deals.include.__contact_detail', compact('lead'));
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

    public function importLeads(Request $request)
    {
        $request->validate([
            'import_file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Cell::setValueBinder(new CustomStringBinder());

        $import = new ImportLeads;
        Excel::import($import, $request->file('import_file'));

        // Handle validation failures
        if ($import->failures()->isNotEmpty()) {
            foreach ($import->failures() as $failure) {
                $row = $failure->row(); // Row number
                foreach ($failure->errors() as $error) {
                    notify()->error("Row {$row}: {$error}");
                }
            }
            return back();
        }

        $successCount = $import->getSuccessCount();

        if ($successCount === 0) {
            notify()->warning('No leads were imported. All rows may have failed validation.');
        } else {
            notify()->success("{$successCount} leads imported successfully.");
        }
        return redirect()->back();

    }

}
