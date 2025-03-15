<?php

namespace App\Listeners;

use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Events\UserReferred;
use App\Models\ReferralLink;
use App\Models\ReferralRelationship;
use App\Models\User;
use App\Traits\NotifyTrait;
use Txn;

class RewardUser
{
    use NotifyTrait;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(UserReferred $event)
    {
        $referral = ReferralLink::where('code', $event->referralId)->first();

        if (!is_null($referral)) {
            // Create a referral relationship
            ReferralRelationship::create([
                'referral_link_id' => $referral->id,
                'user_id' => $event->user->id,
                'forex_schema_id' => $event->schemaID,
            ]);

            // Update the referred user's `ref_id`
            User::find($event->user->id)->update([
                'ref_id' => $referral->user->id,
            ]);

            // Check if the referral is attached to any staff
            $staff = $referral->user->staff()->first(); // Assuming `user` has a `staff` relationship

            if ($staff) {
                // Attach the child user under the same staff
                $staff->users()->attach($event->user->id);
            }

            // Fetch the referring user
            $referralUser = User::find($referral->user_id);

            // Prepare shortcodes for the email
            $shortcodes = [
                '[[full_name]]' => $referralUser->first_name . ' ' . $referralUser->last_name,
                '[[child_full_name]]' => $event->user->first_name . ' ' . $event->user->last_name,
                '[[child_email]]' => $event->user->email,
                '[[message]]' => 'New User added under your IB.',
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
            ];

            // Send email to the referring user
            $this->mailNotify($referralUser->email, 'new_user_ib', $shortcodes);

            // Sign-Up Referral Bonus logic (if needed)
            // Uncomment and modify as required
            // if (setting('sign_up_referral', 'permission') && null !== $event->user->email_verified_at) {
            //     $referralBonus = (float) setting('referral_bonus', 'fee');
            //     Txn::new($referralBonus, 0, $referralBonus, 'system', 'Referral Bonus via ' . $event->user->full_name, TxnType::Referral, TxnStatus::Success, null, null, $referralUser->id);
            // }
        }
    }

}
