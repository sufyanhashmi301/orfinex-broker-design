<?php
namespace App\Services;

use App\Models\User;

class UserIbNetworkService
{
    protected array $networkUserIds = [];

    /**
     * Fetch user and all downline network user IDs recursively.
     */
    public function getNetworkUserIds(User $user): array
    {
        $this->networkUserIds = [$user->id];
        $this->gatherReferralNetwork([$user->id]);

        return $this->networkUserIds;
    }

    /**
     * Recursively gather all user IDs under a list of parent IDs
     */
    protected function gatherReferralNetwork(array $parentIds): void
    {
        $referrals = User::whereIn('ref_id', $parentIds)->get();

        $nextLevelParents = [];

        foreach ($referrals as $referral) {
            // Stop if the user has approved IB status and an ib_group_id
            if ($referral->ib_status === 'approved' && !is_null($referral->ib_group_id)) {
                continue;
            }

            $this->networkUserIds[] = $referral->id;
            $nextLevelParents[] = $referral->id;
        }

        if (!empty($nextLevelParents)) {
            $this->gatherReferralNetwork($nextLevelParents);
        }
    }

    /**
     * Apply a meta key/value to the user and their full network
     */
    public function syncMeta(User $user, string $metaKey, mixed $metaValue): int
    {
        $userIds = $this->getNetworkUserIds($user);
        $updated = 0;

        foreach ($userIds as $userId) {
            $target = User::find($userId);
            if ($target) {
                $target->user_metas()->updateOrCreate(
                    ['meta_key' => $metaKey],
                    ['meta_value' => $metaValue]
                );
                $updated++;
            }
        }

        return $updated;
    }
}
