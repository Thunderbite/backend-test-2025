<?php

namespace App\Http\Livewire\Backstage;

use Livewire\Component;
use Livewire\WithPagination;

class TableComponent extends Component
{
    use WithPagination;

    public $perPage = 10;

    public $search = '';

    public $sortDesc = true;

    public $hasSearch = true;

    protected $listeners = ['resourceDeleted'];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDesc = ! $this->sortDesc;
        } else {
            $this->sortDesc = true;
        }

        $this->sortField = $field;
    }

    public function resourceDeleted()
    {
        // No need to do anything
        //we just reload the data
    }
}
