<div class="grid grid-cols-5 gap-1">
    <input wire:loading.attr="disabled" type="text" id="wire-account" wire:model="account" placeholder=" Account"
           class="h-10 border border-gray-300 rounded-lg mr-4">
    <input wire:loading.attr="disabled" type="number" id="wire-account" wire:model="prizeId" placeholder=" Prize ID"
           class="h-10 border border-gray-300 rounded-lg mr-4">
    <input wire:loading.attr="disabled" type="text" wire:model="startDate" id="wire-starts" placeholder=" Start Date"
           class="h-10 border border-gray-300 rounded-lg mr-4">
    <input type="text" wire:model="endDate" id="wire-ends" placeholder=" End Date"
           class="h-10 border border-gray-300 rounded-lg mr-4">

    <button wire:click="$emit('cleanAll')" class="h-10 bg-white hover:bg-gray-100 text-gray-800
    font-semibold py-2 px-4 border-2 border-gray-400 rounded shadow">
        Clean All
    </button>
</div>

@push('js')
    <script>
        window.livewire.on('cleanAll', function () {
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
