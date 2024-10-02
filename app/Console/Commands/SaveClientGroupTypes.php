<?php

namespace App\Console\Commands;

use App\Services\x9ApiService;
use Illuminate\Console\Command;
use App\Services\ForexApiService;
use App\Models\X9ClientGroupType;

class SaveClientGroupTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'save:client-group-types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save client group types from Forex API into the database';

    /**
     * ForexApiService instance
     */
    protected $forexApiService;

    public function __construct(x9ApiService $forexApiService)
    {
        parent::__construct();
        $this->forexApiService = $forexApiService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Fetch client group types from the API
        $response = $this->forexApiService->getClientGroupType();
//        dd($response);

        // Check if the response was successful
        if ($response['success']) {
            $clientGroupTypes = $response['result']['client_group_types'];
//            dd($clientGroupTypes);

            foreach ($clientGroupTypes as $groupType) {
                X9ClientGroupType::updateOrCreate(
                    ['id' => $groupType['id']],
                    [
                        'id' => $groupType['id'],
                        'name' => $groupType['name'],
                        'description' => $groupType['description'],
                        'is_visible' => $groupType['is_visible'],
                    ]
                );
            }

            $this->info('Client group types have been successfully saved.');
        } else {
            $this->error('Failed to fetch client group types from the API.');
        }

        return 0;
    }
}
