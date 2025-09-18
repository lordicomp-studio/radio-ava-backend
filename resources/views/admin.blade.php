<!DOCTYPE html>
<html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @routes

        @vite(['resources/js/admin.js'])
        @inertiaHead
    </head>
    <body class="antialiased">
        @inertia

{{--        @env ('local')--}}
{{--            <script src="http://localhost:3000/browser-sync/browser-sync-client.js"></script>--}}
{{--        @endenv--}}
    </body>
</html>
