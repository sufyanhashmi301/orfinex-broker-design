<?php

namespace App\Exports;

use App\Models\User;
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

        // Apply filters
        if ($this->request->has('global_search') && $this->request->global_search) {
            $search = $this->request->global_search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if ($this->request->has('phone') && $this->request->phone) {
            $query->where('phone', 'like', "%" . $this->request->phone . "%");
        }
        if ($this->request->has('country') && $this->request->country) {
            $query->where('country', 'like', "%" . $this->request->country . "%");
        }
        if ($this->request->has('status') && $this->request->status !== '') {
            $query->where('status', $this->request->status);
        }
        if ($this->request->has('created_at') && $this->request->created_at) {
            $query->whereDate('created_at', $this->request->created_at);
        }
        if ($this->request->has('tag') && $this->request->tag) {
            $query->where('comment', 'like', "%" . $this->request->tag . "%");
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
