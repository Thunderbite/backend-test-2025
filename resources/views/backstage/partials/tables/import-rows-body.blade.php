<tbody class="bg-white">
    @if(count($rows))
        @foreach($rows as $key => $row)
            <tr class="@if( ($key+1) % 2 === 0 ) alternate @endif">
                @foreach($columns as $column)
                    @if( $column['title'] !== 'tools' )
                        <td class="px-6 py-3 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                            @if( !is_array($row->{$column['attribute']}))
                                {{ $row->{$column['attribute']} }}
                            @else
                                <pre class="text-xs bg-gray-50 border border-gray-300 p-2 rounded-lg">{!! json_encode($row->{$column['attribute']}, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT, 512) !!}</pre>
                            @endif
                        </td>
                    @else
                        <td class="px-6 py-3 whitespace-no-wrap text-left text-sm leading-5 font-medium">
                            @if( in_array('use', $column['tools'], true) )
                                <a href="{{ route('backstage.'.$resource.'.use', $row->id) }}" class="table-tool">
                                    <i class="fas fa-play"></i>
                                </a>
                            @endif

                            @if( in_array('edit', $column['tools'], true) )
                                <a href="{{ route('backstage.'.$resource.'.edit', $row->id) }}" class="table-tool">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endif

                            @if( in_array('delete', $column['tools'], true) && auth()->user()->isAdmin() )
                                <button wire:click="$dispatch('deleteResource', '{{ route('backstage.'.$resource.'.destroy', $row->id) }}', '{{ Str::singular($resource) }}')" class="table-tool">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            @endif

                            @if( in_array('show', $column['tools'], true) )
                                <a href="{{ route('backstage.'.$resource.'.show', $row->id) }}" class="table-tool">
                                    <i class="fas fa-eye"></i>
                                </a>
                            @endif

                            @if( in_array('view', $column['tools'], true) )
                                <a href="{{ route('backstage.'.$resource.'.show', ['model' => $model, 'log' => $row->id]) }}" class="table-tool">
                                    <i class="fas fa-eye"></i>
                                </a>
                            @endif
                        </td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    @else
        <tr>
            <td class="px-6 py-3 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900 text-center" colspan="{{ count($columns) }}">No data</td>
        </tr>
    @endif
</tbody>
