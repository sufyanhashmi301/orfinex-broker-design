<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Traits\ForexApiTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class IbDeals extends Command
{
    use ForexApiTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ib:deals';

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
        $users = User::whereNotNull('ib_login')->get();
        $filePath = 'app/exports/output.csv';

// Ensure that the directory exists, create it if not
        $storagePath = storage_path(dirname($filePath));
        if (!file_exists($storagePath)) {
            Storage::makeDirectory($storagePath, 0755, true);
        }

// Open the CSV file for writing
        $file = fopen(storage_path($filePath), 'w');

// Write the CSV header
        fputcsv($file, ['first_name', 'last_name', 'username', 'email', 'country', 'ib_login']);

// Write each user's data to the CSV file
        foreach ($users as $user) {
            $startIbCalc = Carbon::now()->subDay(90)->startOfDay();
            $start = $startIbCalc->timestamp;
            $end = Carbon::now()->endOfDay()->timestamp;

            $response = $this->getDealListUser($user->ib_login, $start, $end);

            if ($response) {
                $records = $response->object();
                if (count($records) == 0) {
                    // Write user data to the CSV file
                    $data = [
                        $user->first_name,
                        $user->last_name,
                        $user->username,
                        $user->email,
                        $user->country,
                        $user->ib_login,
                    ];
                    fputcsv($file, $data);
                }
            }
        }

// Close the file
        fclose($file);

// Provide a download link or message to the user
        echo "CSV file generated successfully. <a href='" . Storage::url($filePath) . "'>Download CSV</a>";
    }
    }
