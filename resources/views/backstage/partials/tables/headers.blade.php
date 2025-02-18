<thead>
    <tr>
        @foreach ($columns as $column)
            <th class="table-header">
                @if ($column['sort'])
                    <a wire:click.prevent="sortBy('{{ $column['sortField'] ?? ($column['attribute'] ?? $column['title']) }}')"
                        role="button" href="#">
                        {{ $column['title'] }}
                        @include('backstage.partials.tables.sort-icon', [
                            'field' => $column['sortField'] ?? ($column['attribute'] ?? $column['title']),
                        ])
                    </a>
                @else
                    {{ $column['title'] }}
                @endif
            </th>
        @endforeach
    </tr>
</thead>
