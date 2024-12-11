<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '')</title>

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    @livewireStyles
</head>

<body>

@yield('content')


<script src="{{ mix('js/app.js') }}"></script>

@livewireScripts
</body>
</html>