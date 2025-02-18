<?php

declare(strict_types=1);

namespace App\Livewire\Backstage;

use App\Models\Game;

final class GameTable extends TableComponent
{
    public $sortField = 'revealed_at';

    public $extraFilters = 'games-filters';

    public $prizeId = null;

    public $account = null;

    public $startDate = null;

    public $endDate = null;

    private function getQuery()
    {
        return Game::filter($this->account, $this->prizeId ?: null, $this->startDate, $this->endDate)
            ->join('prizes', 'prizes.id', '=', 'games.won_prize_id')
            ->where('prizes.campaign_id', session('activeCampaign'))
            ->select('games.*', 'prizes.name as prize_name')
            ->orderBy($this->sortField, $this->sortDesc ? 'DESC' : 'ASC');
    }

    public function export()
    {
        $filename = sprintf('games_export_%s.csv', now()->format('Y-m-d_H-i-s'));
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $data = $this->getQuery()->get()->map(function ($row) {
            return [
                $row->account,
                $row->prize_name,
                $row->revealed_at,
                $row->won_at,
                $row->segment,
                $row->status->value,
            ];
        })
            ->all();

        array_unshift($data, ['Account', 'Prize Name', 'Revealed At', 'Won At', 'Segment', 'Status']);
        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

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
            'rows' => $this->getQuery()->paginate($this->perPage),
        ]);
    }
}
