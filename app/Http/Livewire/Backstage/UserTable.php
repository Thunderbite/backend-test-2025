<?php

namespace App\Http\Livewire\Backstage;

use App\Models\User;

class UserTable extends TableComponent
{
    public $sortField = 'name';

    public function render()
    {
        $columns = [
            [
                'title' => 'name',
                'sort' => true,
            ],
            [
                'title' => 'email',
                'sort' => true,
            ],
            [
                'title' => 'level',
                'sort' => true,
            ],
        ];

        if (auth()->user()->isAdmin()) {
            $columns[] = [
                'title' => 'tools',
                'sort' => false,
                'tools' => ['edit', 'delete'],
            ];
        }

        return view('livewire.backstage.table', [
            'columns' => $columns,
            'resource' => 'users',
            'rows' => User::search($this->search)
                ->orderBy($this->sortField, $this->sortDesc ? 'DESC' : 'ASC')
                ->paginate($this->perPage),
        ]);
    }
}
