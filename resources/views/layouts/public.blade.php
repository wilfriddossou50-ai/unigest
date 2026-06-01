<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'UniGest') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-950 text-slate-100">

    <!-- NAVBAR -->
    <header x-data="{ open: false }" class="sticky top-0 z-30 border-b border-slate-800/70 bg-slate-950/95 backdrop-blur-xl">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
            <a href="/" class="inline-flex items-center gap-3 text-lg font-semibold text-white">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-sky-500 text-white">U</span>
                UniGest
            </a>

            <nav class="hidden md:flex items-center gap-8 text-sm text-slate-300">
                <a href="/" class="transition hover:text-white">Accueil</a>
                <a href="#features" class="transition hover:text-white">Aperçu</a>
                <a href="#roles" class="transition hover:text-white">Utilisateurs</a>
            </nav>

            <div class="hidden md:flex items-center gap-3">
                @guest
                <a href="{{ route('login') }}"
                    class="rounded-full border border-slate-700/70 bg-slate-900/80 px-5 py-2 text-sm text-white transition hover:border-sky-400 hover:text-sky-300">
                    Connexion
                </a>
                <a href="{{ route('register') }}"
                    class="rounded-full bg-sky-500 px-5 py-2 text-sm font-semibold text-slate-950 transition hover:bg-sky-400">
                    Inscription
                </a>
                @else
                <a href="{{ route('dashboard') }}"
                    class="rounded-full border border-slate-700/70 bg-slate-900/80 px-5 py-2 text-sm text-white transition hover:border-sky-400 hover:text-sky-300">
                    Mon espace
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                        class="rounded-full bg-slate-200 px-5 py-2 text-sm font-semibold text-slate-950 transition hover:bg-slate-100">
                        Déconnexion
                    </button>
                </form>
                @endguest
            </div>

            <button @click="open = !open" class="md:hidden inline-flex items-center justify-center rounded-full border border-slate-700/70 bg-slate-900/80 p-2 text-slate-200 transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-400">
                <span class="sr-only">Ouvrir le menu</span>
                <svg x-cloak x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-cloak x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div x-show="open" x-transition class="md:hidden border-t border-slate-800/70 bg-slate-950/95 px-6 py-4">
            <nav class="flex flex-col gap-3 text-sm text-slate-300">
                <a href="/" class="transition hover:text-white">Accueil</a>
                <a href="#features" class="transition hover:text-white">Aperçu</a>
                <a href="#roles" class="transition hover:text-white">Utilisateurs</a>
            </nav>

            <div class="mt-4 flex flex-col gap-3">
                @guest
                <a href="{{ route('login') }}"
                    class="rounded-full border border-slate-700/70 bg-slate-900/80 px-5 py-2 text-sm text-white transition hover:border-sky-400 hover:text-sky-300 text-center">
                    Connexion
                </a>
                <a href="{{ route('register') }}"
                    class="rounded-full bg-sky-500 px-5 py-2 text-sm font-semibold text-slate-950 transition hover:bg-sky-400 text-center">
                    Inscription
                </a>
                @else
                <a href="{{ route('dashboard') }}"
                    class="rounded-full border border-slate-700/70 bg-slate-900/80 px-5 py-2 text-sm text-white transition hover:border-sky-400 hover:text-sky-300 text-center">
                    Mon espace
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline-flex w-full">
                    @csrf
                    <button type="submit"
                        class="w-full rounded-full bg-slate-200 px-5 py-2 text-sm font-semibold text-slate-950 transition hover:bg-slate-100">
                        Déconnexion
                    </button>
                </form>
                @endguest
            </div>
        </div>

    <!-- CONTENT -->
    <main class="min-h-[calc(100vh-80px)]">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="border-t border-slate-800/70 bg-slate-950 py-10 text-center text-sm text-slate-500">
        <div class="mx-auto max-w-7xl px-6">
            UniGest — Système de gestion universitaire © {{ date('Y') }}
        </div>
    </footer>

</body>

</html>