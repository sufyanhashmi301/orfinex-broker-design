<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\UserOffer;
use Illuminate\Console\Command;

class ExpireOldUserOffers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'offers:expire-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire UserOffers based on their Offer validity';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();

        $userOffers = UserOffer::with('offer')
                                ->where('status', 'available')
                                ->get();

        $expiredCount = 0;

        foreach ($userOffers as $userOffer) {
            $validityDays = $userOffer->offer->validity ?? 0;
            $expirationDate = $userOffer->created_at->addDays($validityDays);

            if ($now->greaterThan($expirationDate)) {
                $userOffer->status = 'expired';
                $userOffer->save();
                $expiredCount++;
            }
        }

        $this->info("{$expiredCount} UserOffers expired based on Offer validity.");
    }
}
