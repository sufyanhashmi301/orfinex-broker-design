<?php
namespace App\Jobs;

use App\Console\Commands\EmailBasedRebateDistribution;
use App\Console\Commands\MultiLevelRebateDistribution;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Support\Facades\Log;

class ProcessUserRebateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $startDate;

    public function __construct($userId, $startDate)
    {
        $this->userId = $userId;
        $this->startDate = $startDate;
    }

    public function handle()
    {
        try {
            DB::beginTransaction();

            $user = User::find($this->userId);
            if (!$user) return;

            $command = app(EmailBasedRebateDistribution::class); // Use service container for dependency injection
            $command->processUserDealsFromDate($user, Carbon::parse($this->startDate));

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error("Job failed for user {$this->userId}: {$e->getMessage()}");
            throw $e; // triggers retry if queue supports it
        }
    }

}
