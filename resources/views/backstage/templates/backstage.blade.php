<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ env('APP_NAME') }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ mix('/css/backstage.css') }}" rel="stylesheet">

    @livewireStyles

</head>

<body class="bg-primary">

<!-- Header -->
<div id="header-border" class="w-full pb-1 mb-10">
    <div id="header" class="w-full p-4">
        <div class="container mx-auto text-white px-10 font-bold flex justify-between">
            <div class="flex">
                <svg width="26" height="29" xmlns="http://www.w3.org/2000/svg" class="mr-2"><defs><linearGradient x1="1749.9%" y1="-13012.15%" x2="-26733.6%" y2="10724.05%" id="a"><stop stop-color="#B6B6B6" offset="0%"/><stop stop-color="#D9D9D9" offset="100%"/></linearGradient><linearGradient x1="50.314%" y1="44.709%" x2="40.938%" y2="145.169%" id="b"><stop stop-color="#B4B4B4" offset="0%"/><stop stop-color="#D7D7D7" offset="100%"/></linearGradient><linearGradient x1="0%" y1="50%" x2="100%" y2="50%" id="c"><stop stop-color="#F05923" offset="0%"/><stop stop-color="#FEF301" offset="70%"/><stop stop-color="#FEEE0C" offset="80%"/><stop stop-color="#FBB03B" offset="100%"/></linearGradient><linearGradient x1="0%" y1="50.048%" x2="100.007%" y2="50.048%" id="d"><stop stop-color="#FF0" offset="0%"/><stop stop-color="#FFFF3C" offset="10%"/><stop stop-color="#FFFF8A" offset="20%"/><stop stop-color="#FFFFC4" offset="30%"/><stop stop-color="#FFFFE9" offset="40%"/><stop stop-color="#FFFFFD" offset="60%"/><stop stop-color="#FFFFE7" offset="70%"/><stop stop-color="#FFFFB6" offset="80%"/><stop stop-color="#FFFF69" offset="90%"/><stop stop-color="#FF0" offset="100%"/></linearGradient><linearGradient x1="84.094%" y1="46.807%" x2="-60.153%" y2="61.16%" id="e"><stop stop-color="#F05923" offset="0%"/><stop stop-color="#F9A437" offset="15%"/><stop stop-color="#FBB03B" offset="50%"/><stop stop-color="#F9A437" offset="65%"/><stop stop-color="#FBB03B" offset="100%"/></linearGradient><linearGradient x1="51.278%" y1="38.867%" x2="47.87%" y2="68.869%" id="f"><stop stop-color="#FFD821" offset="0%"/><stop stop-color="#F69C18" offset="100%"/></linearGradient><linearGradient x1="51.912%" y1="20.678%" x2="49.528%" y2="100.833%" id="g"><stop stop-color="#FFD821" offset="0%"/><stop stop-color="#F69C18" offset="100%"/></linearGradient><linearGradient x1="0%" y1="50%" x2="99.999%" y2="50%" id="h"><stop stop-color="#FF0" offset="0%"/><stop stop-color="#FFFF3C" offset="10%"/><stop stop-color="#FFFF8A" offset="20%"/><stop stop-color="#FFFFC4" offset="30%"/><stop stop-color="#FFFFE9" offset="40%"/><stop stop-color="#FFFFFD" offset="60%"/><stop stop-color="#FFFFE7" offset="70%"/><stop stop-color="#FFFFB6" offset="80%"/><stop stop-color="#FFFF69" offset="90%"/><stop stop-color="#FF0" offset="100%"/></linearGradient><linearGradient x1="50.117%" y1="6.318%" x2="44%" y2="66.418%" id="i"><stop stop-color="#F05923" offset="0%"/><stop stop-color="#F9A530" offset="90%"/><stop stop-color="#FBB03B" offset="100%"/></linearGradient><linearGradient x1="54.977%" y1="1.464%" x2="46.497%" y2="93.78%" id="j"><stop stop-color="#F6931D" offset="0%"/><stop stop-color="#FF0" offset="100%"/></linearGradient></defs><g fill="none" fill-rule="evenodd"><path d="M10.874 28.26h-.003.003z" fill="url(#a)" transform="translate(.74 .154)"/><path d="M10.883 28.262l-.01-.001c.281 0 1.978-28.06 1.978-28.06s-1.216 28.062-1.968 28.061zm-.012-.002c-.07-.029-.137-.099-.203-.204a1.1 1.1 0 00.203.204z" fill="url(#b)" transform="translate(.74 .154)"/><path d="M24.788 13.46c.873-1.054.123-2.65-1.247-2.65h-8.34c-.633 0-.54-.187-.539-.82l.022-8.686C14.686.14 13.23-.39 12.485.503L.377 14.982c-.875 1.047-.13 2.64 1.234 2.64h8.284c.634 0 .54.513.542 1.146l.017 8.562c.002 1.163 1.458 1.687 2.2.79l12.134-14.66z" fill="url(#c)" transform="translate(.74 .154)"/><path d="M24.282 10.964h-8.34c-.285 0-.423-.038-.488-.137l-6.81 4.594h1.035s.498.017 1.332.007c3.076-.036 10.72-.442 14.773-3.447-.258-.826-1.36-1.017-1.502-1.017z" fill="#FFD821" fill-rule="nonzero"/><path d="M14.667 7.997l-.651.124.65-.124z" fill="url(#d)" transform="translate(.74 .154)"/><path d="M7.32 15.267c.95-.664 4.485-5.838 5.731-9.576.728-2.184 1.663-4.957.775-5.57-.389-.269-.978-.052-1.34.382L.376 14.982c-.875 1.047-.13 2.64 1.234 2.64 0 0 2.337 0 5.709-2.355z" fill="url(#e)" transform="translate(.74 .154)"/><path d="M13.326.01c-.292.037-.589.19-.84.493.226-.271.54-.458.84-.494z" fill="url(#f)" transform="translate(.74 .154)"/><path d="M14.662 9.99l.022-8.686c.002-.818-.665-1.384-1.357-1.295a.71.71 0 01.499.112c.888.613-.047 3.386-.775 5.57-1.046 3.14-3.707 7.29-5.095 8.933l-.053.643 6.81-4.595c-.08-.121-.052-.334-.05-.682z" fill="url(#g)" transform="translate(.74 .154)"/><path d="M7.902 15.267c1.216-1.213 4.137-5.898 5.284-9.34.66-1.981 1.38-4.641.845-5.576.507.903-.32 3.363-.98 5.34-.955 2.865-4.426 8.944-6.257 9.928 0 0 13.23.733 18.25-3.792-4.301 3.188-12.06 3.44-17.142 3.44z" fill="url(#h)" transform="translate(.74 .154)"/><path d="M15.915 21.37c.617-1.573 2.26-4.75-.217-4.317-2.454.43-5.803.568-5.803.568.634 0 .54.514.542 1.147l.017 8.562c.002 1.05 1.186 1.574 1.962 1.01 1.023-1.291 2.527-4.492 3.499-6.97z" fill="url(#i)" transform="translate(.74 .154)"/><path d="M16.198 17.017c1.726.086.286 2.901-.283 4.353-.972 2.478-2.476 5.679-3.499 6.97l-.008.009c.088-.063.17-.138.246-.228l.613-.741c.943-1.559 2.37-4.32 3.152-6.313.59-1.505 1.84-4.2-.221-4.05z" fill="url(#j)" transform="translate(.74 .154)"/></g></svg>
                {{ env('APP_NAME') }}
            </div>
            <div>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Header -->

<!-- Content -->
<div class="container mx-auto px-8 relative z-10">
    <div class="grid grid-cols-12 gap-8 pb-8">
        <div class="col-span-6">
            @if($activeCampaign)
                {{ $activeCampaign->name }}<br>
                <a href="{{ secure_url($activeCampaign->slug.'?a=account&segment=low') }}" target="_blank" class="text-xs">{{ secure_url($activeCampaign->slug.'?a=account&segment=low') }}</a>
            @endif
        </div>
        <div class="col-span-6 text-right">
            @yield('tools')
        </div>
    </div>
    <div class="grid grid-cols-12 gap-8">
        <div class="col-span-2">
            @include('backstage.partials.navigation')
        </div>
        <div class="col-span-10 pt-14">
            @yield('content')

            <!-- Footer -->
            <div class="container mx-auto px-6 text-xs text-center py-2 pt-8">
                Copyright {{ date('Y') }} Thunderbite
            </div>
            <!-- End footer -->
        </div>
    </div>
</div>

<script src="{{ mix('/js/backstage.js') }}"></script>

@livewireScripts

@stack('js')

<script>
    function showDeleteSwal(url, reload = false) {
        swal({
            title: "Are you sure you want to do this?",
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
                            text: "The items have been removed.",
                            icon: "success",
                            buttons: false,
                            timer: 1000,
                        });
                    });
                    if( reload ) {
                        setTimeout(() => { location.reload(); }, 1000);
                    }
            }
        });
    }
</script>

@include('backstage.partials.flash-messages')

@include('backstage.partials.support')

</body>

</html>
