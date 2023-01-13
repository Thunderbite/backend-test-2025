<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Thunderbite</title>

    <script>
        var config = {!! $config !!};
    </script>

</head>

<body>

<script type="text/javascript" crossorigin="anonymous" src="{{ asset('js/game.js') }}"></script>

</body>

</html>
