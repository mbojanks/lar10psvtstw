<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    @vite(['resources/css/app.css'])
</head>

<body data-theme="modern">
    <div style="display: contents" class="h-screen overflow-hidden" id="app"><input id="url" type="hidden" value="{{ $url }}"/></div>
    @vite(['resources/js/app.ts'])
</body>

</html>
