<?php

namespace App\Livewire\Backstage;

use App\Models\Game;

class GameTable extends TableComponent
{
    public $sortField = 'revealed_at';

    public $extraFilters = 'games-filters';

    public $prizeId = null;

    public $account = null;

    public $startDate = null;

    public $endDate = null;

    public function export() {}

    public function render()
    {
        $columns = [
            [
                'title' => 'account',
                'sort' => true,
            ],

            [
                'title' => 'prize name',
                'attribute' => 'prize_name',
                'sort' => true,
            ],

            [
                'title' => 'revealed at',
                'attribute' => 'revealed_at',
                'sort' => true,
            ],
        ];

        return view('livewire.backstage.table', [
            'columns' => $columns,
            'resource' => 'games',
            'rows' => Game::filter($this->account, $this->prizeId ?: null, $this->startDate, $this->endDate)
                ->join('prizes', 'prizes.id', '=', 'games.won_prize_id')
                ->where('prizes.campaign_id', session('activeCampaign'))
                ->select('games.*', 'prizes.name as prize_name')
                ->orderBy($this->sortField, $this->sortDesc ? 'DESC' : 'ASC')
                ->paginate($this->perPage),
        ]);
    }
}
