<?php

namespace App\Http\Livewire\Backstage;

use App\Models\Prize;

class PrizeTable extends TableComponent
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
                'title' => 'level',
                'sort' => true,
            ],

            [
                'title' => 'weight',
                'sort' => true,
            ],

            [
                'title' => 'startDate',
                'sort' => true,
            ],

            [
                'title' => 'endDate',
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
            'resource' => 'prizes',
            'rows' => Prize::search($this->search)
                ->where('campaign_id', session('activeCampaign'))
                ->orderBy($this->sortField, $this->sortDesc ? 'DESC' : 'ASC')
                ->paginate($this->perPage),
        ]);
    }
}
