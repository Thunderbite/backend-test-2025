@if($sortField !== $field)
    <i class="fas fa-sort-alt"></i>
@elseif($sortDesc)
    <i class="fas fa-sort-amount-up"></i>
@else
    <i class="fas fa-sort-amount-down"></i>
@endif
