<!DOCTYPE html>
<html lang="fr" class="scroll-smooth" x-data="{ sidebarOpen: false }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'UniGest') }} | @yield('title', 'Espace Étudiant')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
</head>

<body class="min-h-screen bg-slate-50 text-slate-900 overflow-hidden font-sans">
    <div class="min-h-screen md:flex">

        <div
            x-show="sidebarOpen"
            x-transition.opacity
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-black/40 z-20 md:hidden"></div>

        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed md:static z-30 inset-y-0 left-0 transform md:translate-x-0 transition-transform duration-300 w-72 md:w-72 h-full bg-slate-900 text-slate-100 flex flex-col shadow-2xl">
            <div class="p-8 border-b border-slate-800">
                <h1 class="text-2xl font-black tracking-tighter text-sky-400">UNIGEST</h1>
                <p class="text-[10px] uppercase tracking-widest text-slate-500 mt-1">Espace Étudiant</p>
            </div>

            <div class="px-6 py-6 space-y-4">
                <div class="bg-slate-800/50 p-4 rounded-xl border border-slate-700/50">
                    <p class="text-sm font-semibold truncate">{{ auth()->user()->nom }} {{ auth()->user()->prenom }}</p>
                    <p class="text-[11px] text-slate-400 mt-1">Filière : <span class="text-sky-300">{{ auth()->user()->etudiant?->filiere->libelle ?? 'Non assignée' }}</span></p>
                </div>
            </div>

            <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
                <x-nav-link :href="route('etudiant.dashboard')" icon="layout-dashboard" label="Tableau de bord" />
                <x-nav-link :href="route('etudiant.modules.index')" icon="package" label="Mes modules" />
                <x-nav-link :href="route('etudiant.matieres.index')" icon="layers" label="Mes matières" />
                <x-nav-link :href="route('etudiant.notes')" icon="file-text" label="Mes notes" />
                <x-nav-link :href="route('etudiant.emploi.index')" icon="calendar" label="Emploi du temps" />
                <x-nav-link :href="route('etudiant.bulletin.index')" icon="award" label="Bulletin officiel" />
                <x-nav-link :href="route('etudiant.profil.index')" icon="user-cog" label="Mon profil" />
            </nav>

            <div class="p-4 border-t border-slate-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-400 hover:bg-rose-900/20 rounded-lg transition">
                        <i data-lucide="log-out" class="w-4 h-4"></i> Déconnexion
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-y-auto bg-slate-50 md:ml-72">
            <header class="sticky top-0 z-10 bg-white border-b border-slate-200 px-4 py-4 flex items-center justify-between gap-4 shadow-sm md:px-8">
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 rounded-lg hover:bg-slate-100 bg-slate-50 border border-slate-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h2 class="text-xl font-bold text-slate-800">@yield('title')</h2>
            </header>
            <main class="p-4 md:p-8">
                @yield('content')
            </main>
        </div>
    </div>
    <script>
        lucide.createIcons();
    </script>
</body>

</html>