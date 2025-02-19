<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class AwsDeleteTestUploads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aws-s3:delete-test-uploads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will run every 2 days to remove all the test uploads from AWS S3';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        try {
            // Get all files inside the admin/test/ directory
            $files = Storage::disk('s3')->files('admin/test');
        
            // Delete all the files
            Storage::disk('s3')->delete($files);
        
            $this->info('All files deleted from admin/test/');
        
            return true;
        } catch (\Exception $e) {
            $this->info('Error deleting files from admin/test/: ' . $e->getMessage());
            return false;
        }

        return Command::SUCCESS;
    }
}
