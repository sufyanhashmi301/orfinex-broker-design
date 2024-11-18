<div id="contract_content" class="space-y-5">
    <div class="space-y-2">
        <h2 class="text-lg font-semibold dark:text-white">
            {{ __('Funded Account Contract') }}
        </h2>
        <p class="text-sm dark:text-slate-300">
            {{ __("This contract ('Agreement') is entered into on 2024-11-10 by and between :company_name located at :company_address and operating under the website :company_website, hereinafter referred to as 'the Firm,' and :client_name, hereinafter referred to as 'the Client,' whose contact information is listed as :client_email.", [
                'company_name' => setting('site_title', 'global'),
                'company_website' => setting('company_website', 'global'),
                'company_address' => setting('registered_address', 'global'),
                'client_name' => $user->first_name . ' ' . $user->last_name,
                'client_email' => $user->email
            ]) }}
        </p>
    </div>
    <div class="space-y-2">
        <h2 class="text-lg font-semibold dark:text-white">
            {{ __('Scope of Agreement') }}
        </h2>
        <p class="text-sm dark:text-slate-300">
            {{ __("The Firm agrees to provide the Client with access to a funded trading account for the purpose of participating in trading activities under the Firm's guidelines. The Client understands and agrees to follow the rules and parameters set forth by the Firm, acknowledging that any breach of these rules may lead to termination of this Agreement and/or the funded account access.") }}
        </p>
    </div>
    <div class="space-y-2">
        <h2 class="text-lg font-semibold dark:text-white">
            {{ __('Client’s Responsibilities') }}
        </h2>
        <div class="text-sm dark:text-slate-300 space-y-2">
            <p>
                {{ __('The Client agrees to:') }}
            </p>
            <p>
                {{ __('- Comply with all applicable trading rules, strategies, and guidelines provided by the Firm.') }}
            </p>
            <p>
                {{ __('- Execute trades solely within the funded account, as stipulated, and understand that any deviation, including unauthorized withdrawals or transfers, will constitute a violation of this Agreement.') }}
            </p>
            <p>
                {{ __('- Maintain confidentiality regarding the Firm’s strategies, software, and other proprietary information.') }}
            </p>
        </div>
    </div>
    <div class="space-y-2">
        <h2 class="text-lg font-semibold dark:text-white">
            {{ __('Funding & Profit Sharing') }}
        </h2>
        <div class="text-sm dark:text-slate-300 space-y-2">
            <p>
                {{ __('- Funding Amount: The Firm shall provide a funded trading account with an initial capital as agreed between the Firm and the Client.') }}
            </p>
            <p>
                {{ __('- Profit Sharing: Profits generated within the funded account shall be split as follows: ___% to the Client and ___% to the Firm. Payouts shall be processed as per the terms communicated by the Firm.') }}
            </p>
            <p>
                {{ __('- Profit Withdrawals: Withdrawals of profits may be made only upon request and as per the Firm’s specified payout schedule, subject to any minimum profit or other conditions as set by the Firm.') }}
            </p>
        </div>
    </div>
    <div class="space-y-2">
        <h2 class="text-lg font-semibold dark:text-white">
            {{ __('Trading Rules & Restrictions') }}
        </h2>
        <div class="text-sm dark:text-slate-300 space-y-2">
            <p>
                {{ __('- Account Protection: The Client agrees to trade within the predefined risk parameters, which include but are not limited to drawdown limits, maximum loss thresholds, and trade volume restrictions.') }}
            </p>
            <p>
                {{ __('- Inactivity Clause: If the Client does not initiate any trading activities within a period of ___ days, the Firm reserves the right to review and possibly terminate the funded account access.') }}
            </p>
            <p>
                {{ __('- Prohibited Activities: The Client shall not engage in any trading practices deemed as high-risk, such as martingale, hedging, or other unapproved strategies, unless expressly allowed by the Firm.') }}
            </p>
        </div>
    </div>
    <div class="space-y-2">
        <h2 class="text-lg font-semibold dark:text-white">
            {{ __('Termination of Agreement') }}
        </h2>
        <div class="text-sm dark:text-slate-300 space-y-2">
            <p>
                {{ __('- Expiry of Contract: This Agreement will expire on _(Contract Expiry)_ unless renewed or terminated earlier as per the terms herein.') }}
            </p>
            <p>
                {{ __('- Termination for Breach: The Firm reserves the right to terminate this Agreement if the Client breaches any terms of this Agreement, fails to comply with trading guidelines, or engages in any activities detrimental to the Firm’s interests.') }}
            </p>
            <p>
                {{ __("- Effect of Termination: Upon termination, the Client's access to the funded account will be revoked, and any outstanding profits will be disbursed per the Firm’s policies, less any fees or damages owed to the Firm.") }}
            </p>
        </div>
    </div>
    <div class="space-y-2">
        <h2 class="text-lg font-semibold dark:text-white">
            {{ __('Confidentiality') }}
        </h2>
        <p class="text-sm dark:text-slate-300">
            {{ __('The Client agrees to keep confidential any proprietary information or trade secrets of the Firm and shall not disclose such information to any third party without prior written consent from the Firm.') }}
        </p>
    </div>
    <div class="space-y-2">
        <h2 class="text-lg font-semibold dark:text-white">
            {{ __('Liability') }}
        </h2>
        <p class="text-sm dark:text-slate-300">
            {{ __('The Client understands that trading in financial markets involves risk and agrees that the Firm is not liable for any losses incurred by the Client through the funded account. The Client accepts full responsibility for all trading actions taken under the funded account.') }}
        </p>
    </div>
    <div class="space-y-2">
        <h2 class="text-lg font-semibold dark:text-white">
            {{ __('Amendments') }}
        </h2>
        <p class="text-sm dark:text-slate-300">
            {{ __('The Firm reserves the right to amend the terms of this Agreement at any time. The Client will be notified of any amendments via the Firm’s dashboard or through email communication.') }}
        </p>
    </div>
    <div class="space-y-2 pt-10">
        <h2 class="text-lg font-semibold dark:text-white">
            {{ __('Signed by:') }}
        </h2>
        <p class="text-sm dark:text-slate-300">
            <span class="font-medium mr-2">
                {{ __('For: ') }}
            </span>
            {{ setting('site_title', 'global') }}
        </p>

        <div class="space-y-2 text-sm dark:text-slate-300">
            <div>
                <span class="font-medium mr-2">
                    {{ __('Client Name: ') }}
                </span>
                <span class="capitalize">
                    {{ $user->first_name . ' ' . $user->last_name }}
                </span>
            </div>
            <div>
                <p class="text-sm font-medium dark:text-slate-300 mb-1">{{ __('Client Signature:') }}</p>
                <div class="max-w-xl">
                    <img src="{{ $signature }}" height="75" alt="">
                    <div id="sig" class="h-[200px] rounded border dark:border-slate-700"></div>
                    @if($shouldHideElement)
                        <div class="text-right mt-3">
                            <button class="btn btn-sm btn-warning inline-flex items-center justify-center" id="clear">
                                <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="lsicon:clear-outline"></iconify-icon>
                                {{ __('Clear Signature') }}
                            </button>
                        </div>
                    @endif
                </div>
            </div>
            <div>
                <span class="font-medium mr-2">
                    {{ __('Date of Contract: ') }}
                </span>
                {{ __('2024-11-10') }}
            </div>
            <div>
                <span class="font-medium mr-2">
                    {{ __('Contract Expiry: ') }}
                </span>
                {{ __('2024-12-01') }}
            </div>
        </div>
    </div>
</div>
