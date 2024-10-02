<?php

namespace App\Console\Commands;

use App\Services\x9ApiService;
use Illuminate\Console\Command;
use App\Models\X9ClientGroup;
use App\Models\X9ClientGroupType;
use App\Services\ForexApiService;

class SaveClientGroups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'save:client-groups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save client groups from Forex API into the x9_client_groups table based on client group types';

    /**
     * ForexApiService instance.
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
        // Fetch all client group types from X9ClientGroupType
        $clientGroupTypes = X9ClientGroupType::all();
//        dd($clientGroupTypes);


        foreach ($clientGroupTypes as $clientGroupType) {
//            dd($clientGroupType);
            $type = $clientGroupType->id; // Get the type dynamically based on the id from X9ClientGroupType

            // Call the ForexApiService to get client groups by type
            $response = $this->forexApiService->getClientGroup($type);

            // Chec
            //k if the response status is true
            if ($response['success']) {
                $clientGroups = $response['result']['client_groups_by_type'];

                foreach ($clientGroups as $group) {
                    X9ClientGroup::updateOrCreate(
                        ['id' => $group['id']],
                        [
                            'client_group_type_id' => $group['client_group_type_id'],
                            'name' => $group['name'],
                            'currency' => $group['currency'],
                            'type' => $group['type'],
                        ]
                    );
                }

                $this->info("Client groups for type {$type} ({$clientGroupType->name}) have been successfully saved.");
            } else {
                $this->error("Failed to fetch client groups for type {$type}.");
            }
        }

        return 0;
    }
}
