<div id="kycAlert" class="kyc-alert alert alert-dismissible ltr:ml-[248px] rtl:mr-[248px] dark:shadow-slate-700 !p-3 hidden" >
    <div class="flex items-center gap-3">
        <div class="flex item-center gap-3 mx-auto">
            <p class="text-sm text-slate-600 dark:text-slate-300">
                Verify your account to {{ $notice }}
            </p>
            <a href="{{ route('user.verification.index') }}" class="btn-link inline-flex items-center justify-center text-sm" >
                {{ __('Begin Verification') }}
                <iconify-icon class="text-base ltr:ml-2 rtl:mr-2 font-light" icon="lucide:arrow-right"></iconify-icon>
            </a>
        </div>
        <button id="closeKycAlertBtn" class="btn-link mr-3" type="button" aria-label="Close">
            <iconify-icon icon="line-md:close"></iconify-icon>
        </button>
    </div>
</div>

<style>
    #kycAlert {
        background: #f8d00856;
        padding: 13px 0px;
        border-radius: 0px
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const kycAlert = document.getElementById("kycAlert");
        const closeKycAlertBtn = document.getElementById("closeKycAlertBtn");

        if (kycAlert) {
            // Check if the alert was closed in the last 24 hours
            const kycAlertClosedTime = localStorage.getItem("kycAlertClosedTime");
            const currentTime = Date.now();

            if (!kycAlertClosedTime || currentTime - kycAlertClosedTime >= 24 * 60 * 60 * 1000) {
                kycAlert.style.display = "block"; // Show the alert
            }

            // Close alert and store timestamp in localStorage
            closeKycAlertBtn.addEventListener("click", function() {
                kycAlert.style.display = "none";
                localStorage.setItem("kycAlertClosedTime", Date.now());
            });
        }
    });
</script>

