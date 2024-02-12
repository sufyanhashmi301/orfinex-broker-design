<?php

namespace App\Console\Commands;

use App\Enums\IBStatus;
use App\Models\IbTransaction;
use App\Models\User;
use App\Traits\ForexApiTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;

class IBProfitRecord extends Command
{
    use ForexApiTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ib:record';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::whereNotNull('ib_login')->where('ib_status', IBStatus::APPROVED)->get();
//        dd($users);
        foreach ($users as $user) {
//            dd($user);
            if ($user->multi_ib_calc_at) {
                $startIbCalc = Carbon::parse($user->multi_ib_calc_at)->addDay();
            } else {
                $startIbCalc = Carbon::now()->startOfDay();
            }
//            dd($startIbCalc,Carbon::now());
            if ($startIbCalc > Carbon::now()) {
                return false;
            }
//            dd($user);
            $start = $startIbCalc->timestamp;
            $end = Carbon::parse($startIbCalc)->endOfDay()->timestamp;
//            dd($startIbCalc);
            if ($startIbCalc)
                $response = $this->getDealListUser($user->ib_login, $start, $end);
            if ($response) {
                $records = $response->object();
                if (count($records) > 0) {
                   $this->saveIBTransaction($records,$user,$startIbCalc);
                }
                $user->multi_ib_calc_at = $startIbCalc;
                $user->save();
            }
        }
        return Command::SUCCESS;
    }

    public function saveIBTransaction($records,$user,$startIbCalc)
    {
        foreach ($records as $record) {
//                    dd($record,$record->Deal);
            $comment = $record->Comment;
            if (strpos($comment, "agent '") !== false && strpos($comment, "' - #") !== false) {
                $clientNo = '';
                $tradeId = '';
                $pattern = "/agent '(\\d+)' - #(\\d+)/";
                if (preg_match($pattern, $comment, $matches)) {
                    $clientNo = $matches[1];
                    $tradeId = $matches[2];
                }
                $data = [];
                $data['user_id'] = $user->id;
                $data['deal'] = $record->Deal;
                $data['login'] = $record->Login;
                $data['profit'] = $record->Profit;
                $data['client_no'] = $clientNo;
                $data['trade_id'] = $tradeId;
                $data['level_share'] = $record->Profit;
                $data['process_time'] = Carbon::createFromTimestamp($record->Time)->toDateTimeString();
                $data['calc_at'] = $startIbCalc;
                if(!IbTransaction::where('deal',$record->Deal)->exists()) {
                    IbTransaction::create($data);
                }
            }
        }
    }
}
