<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body>
    @include('components.navbar')
    <div style="min-height: 70vh">
        {{ $slot }}
    </div>
    @include('components.footer')
    @livewireScripts
</body>
</html>
