<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-950 font-sans text-slate-900 antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-10">
        <div class="w-full max-w-2xl rounded-[2rem] border border-white/10 bg-slate-900/90 p-8 shadow-2xl shadow-slate-950/40 backdrop-blur-xl">
            <div class="flex flex-col items-center gap-6 text-center text-slate-100">
                <a href="/" class="inline-flex items-center gap-3 text-white/90 hover:text-white">
                    <x-application-logo class="w-14 h-14 fill-current text-sky-400" />
                    <span class="text-xl font-semibold">{{ config('app.name', 'Laravel') }}</span>
                </a>
            </div>
            <div class="mt-8 rounded-[1.75rem] bg-white p-6 shadow-xl shadow-slate-950/10">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>

</html>