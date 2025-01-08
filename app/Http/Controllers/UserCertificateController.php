<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Certificate;
use Illuminate\Http\Request;
use App\Enums\InvestmentStatus;
use App\Models\UserCertificate;
use App\Enums\CertificateHookEnums;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountTypeInvestment;
use App\Services\GenerateCertificateService;

class UserCertificateController extends Controller
{

    protected $certificate_service;

    public function __construct(GenerateCertificateService $certificate_service) {
        $this->certificate_service = $certificate_service;
    }

    private function newCertificate($certificate, $hook) {
        $new_certificate = New UserCertificate();
        $new_certificate->user_id = Auth::id();
        $new_certificate->hook = $hook;
        $new_certificate->certificate_image = $this->certificate_service->generateCertificate($certificate->id, Auth::id());
        $new_certificate->hook_triggered_at = Carbon::today();
        $new_certificate->save();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $certificates = Certificate::where('is_enabled', 1)->get();

        // Check Evaluation
        $passed_phase_one_accounts = AccountTypeInvestment::where('user_id', Auth::id())
                            ->where('status', InvestmentStatus::PASSED)
                            ->whereHas('accountTypePhase', function ($query) {
                                $query->where('phase_step', 1);
                            })
                            ->get();
        if(count($passed_phase_one_accounts) > 0) {
            $user_certificate = UserCertificate::where('user_id', Auth::id())->where('hook', CertificateHookEnums::PHASE_ONE);
            $certificate = Certificate::where('hook', CertificateHookEnums::PHASE_ONE)->first();

            if(!$user_certificate->exists()) {
                $this->newCertificate($certificate, CertificateHookEnums::PHASE_ONE);
            }
        }

        // Check Verification 
        $passed_phase_two_accounts = AccountTypeInvestment::where('user_id', Auth::id())
                            ->where('status', InvestmentStatus::PASSED)
                            ->whereHas('accountTypePhase', function ($query) {
                                $query->where('phase_step', 2);
                            })
                            ->get();
        if(count($passed_phase_two_accounts) > 0) {
            $user_certificate = UserCertificate::where('user_id', Auth::id())->where('hook', CertificateHookEnums::PHASE_TWO);
            $certificate = Certificate::where('hook', CertificateHookEnums::PHASE_TWO)->first();

            if(!$user_certificate->exists()) {
                $this->newCertificate($certificate, CertificateHookEnums::PHASE_TWO);
            }
        }

        $rewarded_certificates = UserCertificate::where('user_id', Auth::id())->get();
        $rewarded_certificates_hooks = $rewarded_certificates->pluck('hook')->toArray();

        return view('frontend::certificates.index', compact('certificates', 'rewarded_certificates', 'rewarded_certificates_hooks'));
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
