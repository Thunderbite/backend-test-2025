<div class="flex items-center gap-4">
    <input wire:loading.attr="disabled" type="text" id="wire-account" wire:model.live="account" placeholder="Account"
        class="h-10 border border-gray-300 rounded-lg px-3">
    <input wire:loading.attr="disabled" type="number" id="wire-prize" wire:model.live="prizeId" placeholder="Prize ID"
        class="h-10 border border-gray-300 rounded-lg px-3">
    <input wire:loading.attr="disabled" type="text" wire:model.live="startDate" id="wire-starts"
        placeholder="Start Date" class="h-10 border border-gray-300 rounded-lg px-3">
    <input type="text" wire:model.live="endDate" id="wire-ends" placeholder="End Date"
        class="h-10 border border-gray-300 rounded-lg px-3">
    <button wire:click="$dispatch('cleanAll')"
        class="h-10 bg-white hover:bg-gray-100 text-gray-800 font-semibold px-4 border-2 border-gray-200 rounded shadow">
        Clean All
    </button>
    <button wire:click="export"
        class="h-10 bg-white hover:bg-gray-100 text-gray-800 font-semibold px-4 border-2 border-gray-200 rounded shadow">
        Download as CSV
    </button>
</div>



@push('js')
    <script>
        Livewire.on('cleanAll', function() {
            @this.set('account', '');
            @this.set('prizeId', '');
            @this.set('startDate', '');
            @this.set('endDate', '');
        });

        flatpickr("#wire-starts", {
            allowInput: true,
            enableSeconds: true,
            dateFormat: 'd-m-Y H:i:S',
            defaultHour: 0,
            defaultMinute: 0,
            defaultSeconds: 0,
            enableTime: true,
            time_24hr: true,
        });

        flatpickr("#wire-ends", {
            allowInput: true,
            enableSeconds: true,
            dateFormat: 'd-m-Y H:i:S',
            defaultHour: 23,
            defaultMinute: 59,
            defaultSeconds: 59,
            enableTime: true,
            time_24hr: true,
        });
    </script>
@endpush
