<!DOCTYPE html>
<html lang="fr" class="scroll-smooth" x-data="{ sidebarOpen: false }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'UniGest') }} | @yield('title', 'Professeur')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="min-h-screen bg-slate-100 text-slate-900">

    <div class="min-h-screen md:flex">

        <!-- OVERLAY MOBILE -->
        <div
            x-show="sidebarOpen"
            x-transition.opacity
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-black/50 z-20 md:hidden">
        </div>

        <!-- SIDEBAR -->
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed md:static md:translate-x-0 z-30 transition-transform duration-300
                   w-72 h-full md:h-auto bg-gradient-to-b from-sky-950 to-sky-900 text-slate-100 flex flex-col">

            <!-- BRAND -->
            <div class="px-6 py-5 border-b border-sky-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-sky-600 rounded-xl flex items-center justify-center shrink-0">
                        <i data-lucide="book-open" class="w-5 h-5 text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold tracking-tight">UniGest</h1>
                        <p class="text-xs text-sky-200">Espace Professeur</p>
                    </div>
                </div>
            </div>

            <!-- NAV -->
            <nav class="flex-1 px-3 py-5 space-y-5 text-sm overflow-y-auto">

                <!-- GÉNÉRAL -->
                <div>
                    <p class="text-[10px] font-semibold text-sky-300 uppercase tracking-widest px-3 mb-1">Général</p>
                    <a href="{{ route('professeur.dashboard') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition
                               {{ request()->routeIs('professeur.dashboard') ? 'bg-sky-600 text-white' : 'text-slate-200 hover:bg-sky-800 hover:text-white' }}">
                        <i data-lucide="layout-dashboard" class="w-4 h-4 shrink-0"></i>
                        Tableau de bord
                    </a>
                </div>

                <!-- ENSEIGNEMENT -->
                <div>
                    <p class="text-[10px] font-semibold text-sky-300 uppercase tracking-widest px-3 mb-1">Enseignement</p>
                    <a href="#"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition text-slate-200 hover:bg-sky-800 hover:text-white">
                        <i data-lucide="book" class="w-4 h-4 shrink-0"></i>
                        Mes matières
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition text-slate-200 hover:bg-sky-800 hover:text-white">
                        <i data-lucide="users" class="w-4 h-4 shrink-0"></i>
                        Mes étudiants
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition text-slate-200 hover:bg-sky-800 hover:text-white">
                        <i data-lucide="award" class="w-4 h-4 shrink-0"></i>
                        Gestion des notes
                    </a>
                </div>

                <!-- PLANIFICATION -->
                <div>
                    <p class="text-[10px] font-semibold text-sky-300 uppercase tracking-widest px-3 mb-1">Planification</p>
                    <a href="#"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition text-slate-200 hover:bg-sky-800 hover:text-white">
                        <i data-lucide="calendar" class="w-4 h-4 shrink-0"></i>
                        Mon emploi du temps
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition text-slate-200 hover:bg-sky-800 hover:text-white">
                        <i data-lucide="file-text" class="w-4 h-4 shrink-0"></i>
                        Absences
                    </a>
                </div>

                <!-- OUTILS -->
                <div>
                    <p class="text-[10px] font-semibold text-sky-300 uppercase tracking-widest px-3 mb-1">Outils</p>
                    <a href="#"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition text-slate-200 hover:bg-sky-800 hover:text-white">
                        <i data-lucide="settings" class="w-4 h-4 shrink-0"></i>
                        Paramètres
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition text-slate-200 hover:bg-sky-800 hover:text-white">
                        <i data-lucide="help-circle" class="w-4 h-4 shrink-0"></i>
                        Aide
                    </a>
                </div>
            </nav>

            <!-- USER PROFILE -->
            <div class="px-3 py-4 border-t border-sky-800">
                <div class="flex items-center justify-between px-3 py-2.5">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="w-8 h-8 rounded-full bg-sky-600 flex items-center justify-center shrink-0">
                            <i data-lucide="user" class="w-4 h-4 text-white"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-sky-200 truncate">Professeur</p>
                        </div>
                    </div>
                    <button @click="sidebarOpen = false" class="text-slate-200 hover:text-white">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 text-slate-200 hover:text-white px-3 py-2 text-sm transition">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        Déconnexion
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col">
            <!-- HEADER -->
            <header class="bg-white shadow-sm sticky top-0 z-10">
                <div class="px-8 py-4 flex items-center justify-between">
                    <button
                        @click="sidebarOpen = true"
                        class="md:hidden text-slate-900 hover:bg-slate-100 p-2 rounded-lg transition">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>

                    <div class="flex-1 md:ml-0 ml-4">
                        <h2 class="text-xl font-bold text-slate-900">@yield('title', 'Tableau de bord')</h2>
                    </div>

                    <div class="flex items-center gap-4">
                        <button class="text-slate-600 hover:text-slate-900 p-2 hover:bg-slate-100 rounded-lg transition">
                            <i data-lucide="bell" class="w-5 h-5"></i>
                        </button>
                        <div class="w-10 h-10 rounded-full bg-sky-600 flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- PAGE CONTENT -->
            <main class="flex-1 overflow-auto">
                @yield('content')
            </main>

            <!-- FOOTER -->
            <footer class="bg-white border-t border-slate-200 py-4 px-8">
                <p class="text-center text-sm text-slate-600">
                    © 2026 UniGest - Système de Gestion Universitaire
                </p>
            </footer>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>