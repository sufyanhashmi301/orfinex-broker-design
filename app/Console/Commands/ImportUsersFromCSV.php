<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportUsersFromCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:import {filePath}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import users (login and email) from a CSV file into the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('filePath');

        if (!file_exists($filePath)) {
            $this->error("File not found: $filePath");
            return;
        }

        // Open the file
        if (($handle = fopen($filePath, 'r')) !== false) {
            $this->info('Processing file...');

            // Skip the header row
            $header = fgetcsv($handle);

            // Define an array to hold the data
            $users = [];

            while (($row = fgetcsv($handle)) !== false) {
                $login = $row[0] ?? null; // Assuming `login` is the first column
                $email = $row[6] ?? null; // Assuming `email` is the second column

                if ($login && $email) {
                    $users[] = [
                        'login' => $login,
                        'email' => $email,
                    ];
                }
            }

            fclose($handle);

            // Insert data into the database
            if (!empty($users)) {
                DB::table('accounts_1')->insert($users);
                $this->info('Import completed successfully.');
            } else {
                $this->warn('No valid data to import.');
            }
        } else {
            $this->error('Unable to open the file.');
        }
    }
}
