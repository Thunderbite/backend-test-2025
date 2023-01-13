<?php

namespace App\Http\Livewire\Backstage;

use App\Models\Campaign;

class CampaignTable extends TableComponent
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
                'title' => 'timezone',
                'sort' => true,
            ],
            [
                'title' => 'starts_at',
                'sort' => true,
            ],
            [
                'title' => 'ends_at',
                'sort' => true,
            ],
        ];

        $columns[] = [
            'title' => 'tools',
            'sort' => false,
            'tools' => ['use', 'edit', 'delete'],
        ];

        return view('livewire.backstage.table', [
            'columns' => $columns,
            'resource' => 'campaigns',
            'rows' => Campaign::search($this->search)
                ->orderBy($this->sortField, $this->sortDesc ? 'DESC' : 'ASC')
                ->paginate($this->perPage),
        ]);
    }
}
