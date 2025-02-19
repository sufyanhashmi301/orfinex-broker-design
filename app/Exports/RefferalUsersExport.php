<?php
namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Http\Request;

class RefferalUsersExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function query()
    {
        return User::where('ref_id', $this->userId)
            ->select('first_name', 'last_name', 'username', 'email', 'phone', 'country', 'gender', 'comment');
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
            'Comment'
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
