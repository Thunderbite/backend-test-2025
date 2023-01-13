<div>
    @include('backstage.partials.tables.top')
    <div class="flex flex-col">
        <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div class="align-middle inline-block min-w-full overflow-hidden">

                <div class="align-middle inline-block min-w-full overflow-hidden">
                    <div wire:loading class="absolute w-full h-screen z-10">
                        <div class="flex items-center justify-center h-screen w-full">
                            <div class="-mt-60 -ml-16 rounded bg-white shadow-lg text-center w-48 pt-4 h-16">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-black inline-block" xmlns="http://www.w3.org/2000/svg"
                                     fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4">
                                    </circle>
                                    <path class="opacity-75" fill="currentColor"
                                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Loading
                            </div>
                        </div>
                    </div>

                <table class="min-w-full">
                    @include('backstage.partials.tables.headers')

                    @include('backstage.partials.tables.body')
                </table>
            </div>
        </div>
    </div>
    @include('backstage.partials.tables.footer')

</div>

@push('js')
    <script>
        window.livewire.on('deleteResource', function(url, resource){
            swal({
                title: "Are you sure you want to delete this "+resource+"?",
                text: "The data will be permanently removed from our servers forever. This action cannot be undone!",
                icon: "warning",
                buttons: {
                    cancel: {
                        text: "No",
                        value: false,
                        visible: true,
                        closeModal: true,
                    },
                    confirm: {
                        className: 'swal-delete-button',
                        text: "Yes",
                        value: true,
                        visible: true,
                        closeModal: false,
                    },
                },
            }).then(doDelete => {
                if(doDelete) {
                    axios.post(url, { _method: 'delete' })
                        .then(function (response) {
                            swal({
                                title: "Success!",
                                text: "The "+resource+" has been removed.",
                                icon: "success",
                                buttons: false,
                                timer: 1000,
                            });
                            window.livewire.emit('resourceDeleted');
                        });
                }
            });
        });
    </script>
@endpush
