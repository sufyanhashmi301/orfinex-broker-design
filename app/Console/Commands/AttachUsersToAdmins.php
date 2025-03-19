<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserImport;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;

class AttachUsersToAdmins extends Command
{
    protected $signature = 'attach:users-admins';
    protected $description = 'Attach users to admins based on staff_name, skipping null and Unassigned staff_names';

    public function handle()
    {
        // Get all user_imports where staff_name is not null and not 'Unassigned'
        $userImports = UserImport::whereNotNull('staff_name')
            ->where('staff_name', '!=', 'Unassigned')
            ->get();

        foreach ($userImports as $import) {
            // Find the user in users table by email
            $user = User::where('email', $import->email)->first();

            if (!$user) {
                $this->info("No user found for email: {$import->email}");
                continue;
            }

            // Find the admin using LIKE on first_name or last_name
            $admin = Admin::where('first_name', 'LIKE', "%{$import->staff_name}%")
                ->orWhere('last_name', 'LIKE', "%{$import->staff_name}%")
                ->first();

            if (!$admin) {
                $this->info("No admin found for staff_name: {$import->staff_name}");
                continue;
            }

            // Check if the user is already attached to the admin
            if ($admin->users()->where('users.id', $user->id)->exists()) {
                $this->info("User {$user->email} is already attached to Admin {$admin->first_name} {$admin->last_name}");
                continue;
            }

            // Attach user to admin
            $admin->users()->attach($user->id);
//            dd('ss');

            $this->info("Attached User: {$user->email} to Admin: {$admin->first_name} {$admin->last_name}");
        }

        $this->info('User-admin attachment process completed.');
    }
}
