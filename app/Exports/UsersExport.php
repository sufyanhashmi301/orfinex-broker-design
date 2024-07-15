<?php

namespace App\Exports;

use App\Enums\ForexAccountStatus;
use App\Models\ForexAccount;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class UsersExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = User::query();

        // Global search condition
        if ($search = $this->request->global_search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('username', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Individual filters
        $filters = [
            'phone' => 'like',
            'country' => 'like',
            'status' => '=',
            'created_at' => '=',
            'tag' => 'like',
        ];

        foreach ($filters as $field => $operator) {
            if ($value = $this->request->{$field}) {
                if ($field == 'created_at') {
                    $query->whereDate($field, $value);
                } else {
                    $query->where($field, $operator, "%{$value}%");
                }
            }
        }

        // Balance status filter
        if ($balanceStatus = $this->request->balanceStatus) {
            $realForexAccounts = ForexAccount::where('status', ForexAccountStatus::Ongoing)->pluck('login');
            
            $balanceCondition = $balanceStatus == 1 ? '>' : '=';
            $balanceValue = $balanceStatus == 1 ? 0 : 0;

            $userIds = DB::connection('mt5_db')
                ->table('mt5_accounts')
                ->whereIn('Login', $realForexAccounts)
                ->where('Balance', $balanceCondition, $balanceValue)
                ->pluck('Login');

            $userIds = ForexAccount::whereIn('login', $userIds)->pluck('user_id');

            $query->whereIn('id', $userIds);
        }

        return $query->select('first_name', 'last_name', 'username', 'email', 'phone', 'country', 'gender', 'comment');
    }

    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Username',
            'Email',
            'Phone',
            'Country',
            'Gender',
            'Tag' // Heading for the comment column
        ];
    }

    public function map($user): array
    {
        return [
            $user->first_name,
            $user->last_name,
            $user->username,
            $user->email,
            $user->phone,
            $user->country,
            $user->gender,
            $user->comment,
        ];
    }
}
