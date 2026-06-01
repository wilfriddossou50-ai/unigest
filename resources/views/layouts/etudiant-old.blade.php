<!DOCTYPE html>
<html lang="fr" class="scroll-smooth" x-data="{ sidebarOpen: false, activeMenu: null }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'UniGest') }} | @yield('title', 'Mon espace')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 text-slate-900">

    <div class="min-h-screen lg:flex">

        <!-- OVERLAY MOBILE -->
        <div
            x-show="sidebarOpen"
            x-transition.opacity
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-black/40 backdrop-blur-sm z-20 lg:hidden">
        </div>

        <!-- SIDEBAR -->
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed lg:static lg:translate-x-0 z-30 transition-all duration-300 ease-out
                 w-72 h-screen lg:h-auto overflow-y-auto bg-gradient-to-b from-slate-900 via-slate-800 to-slate-950 text-slate-100 flex flex-col shadow-2xl lg:shadow-none">

            <!-- BRAND -->
            <div class="flex-shrink-0 px-6 py-6 border-b border-slate-700/50">
                <div class="flex items-center gap-3 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-sky-500 to-blue-600 rounded-2xl flex items-center justify-center shrink-0 shadow-lg group-hover:shadow-sky-500/50 group-hover:shadow-xl transition-all">
                        <i data-lucide="graduation-cap" class="w-6 h-6 text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold tracking-tight bg-gradient-to-r from-sky-300 to-blue-300 bg-clip-text text-transparent">UniGest</h1>
                        <p class="text-xs text-slate-400">Espace Étudiant</p>
                    </div>
                </div>
            </div>

            <!-- INFOS ETUDIANT -->
            @php
            $etudiant = auth()->user()->etudiant;
            $hasNotes = Route::has('etudiant.notes');
            $hasBulletin = Route::has('etudiant.bulletin.index');
            $hasModules = Route::has('etudiant.modules.index');
            $hasMatieres = Route::has('etudiant.matieres.index');
            $hasDettes = Route::has('etudiant.dettes.index');
            $hasEmploi = Route::has('etudiant.emploi.index');
            $hasProfil = Route::has('etudiant.profil.index');
            @endphp
            @if($etudiant)
            <div class="mx-4 mt-6 px-4 py-4 bg-gradient-to-br from-slate-700/40 to-slate-800/40 hover:from-slate-700/60 hover:to-slate-800/60 rounded-xl text-xs space-y-2 border border-slate-700/30 transition-all">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-slate-300 font-semibold text-sm">{{ $etudiant->numero_etudiant }}</p>
                        <p class="text-slate-400 text-[11px] mt-1">{{ $etudiant->filiere->libelle ?? '—' }}</p>
                    </div>
                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-sky-500/20 text-sky-300">
                        <i data-lucide="zap" class="w-3.5 h-3.5"></i>
                    </span>
                </div>
                <div class="pt-2 border-t border-slate-700/30">
                    <p class="text-slate-400 text-[10px]">Niveau: <span class="text-slate-300 font-medium">{{ $etudiant->niveau->nom ?? '—' }}</span></p>
                </div>
            </div>
            @endif

            <!-- NAV -->
            <nav class="flex-1 px-3 py-6 space-y-1 text-sm overflow-y-auto custom-scrollbar"
                x-data="{ openParcours: {{ request()->routeIs('etudiant.modules.*') || request()->routeIs('etudiant.matieres.*') ? 'true' : 'false' }}, openResultats: {{ request()->routeIs('etudiant.notes.*') || request()->routeIs('etudiant.bulletin.*') || request()->routeIs('etudiant.dettes.*') ? 'true' : 'false' }} }">

                <!-- Tableau de bord -->
                <div>
                    <a href="{{ route('etudiant.dashboard') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('etudiant.dashboard') ? 'bg-gradient-to-r from-sky-600 to-blue-600 text-white shadow-lg shadow-sky-500/30' : 'text-slate-300 hover:bg-slate-700/50 hover:text-white' }}">
                        <i data-lucide="layout-dashboard" class="w-5 h-5 shrink-0"></i>
                        <span class="font-medium">Tableau de bord</span>
                    </a>
                </div>

                <!-- Section Separator -->
                <div class="py-3 px-4">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Parcours</p>
                </div>

                <!-- Mon parcours (Collapsible) -->
                <div>
                    <button type="button"
                        @click="openParcours = !openParcours"
                        class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-left {{ request()->routeIs('etudiant.modules.*') || request()->routeIs('etudiant.matieres.*') ? 'bg-slate-700/40 text-white' : 'text-slate-300 hover:bg-slate-700/30 hover:text-white' }}">
                        <span class="flex items-center gap-3">
                            <i data-lucide="book-open" class="w-5 h-5 shrink-0"></i>
                            <span class="font-medium">Mon parcours</span>
                        </span>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" :class="openParcours && 'rotate-180'"></i>
                    </button>
                    <div x-show="openParcours" x-transition.origin.top class="mt-2 space-y-1 pl-2">
                        @if($hasModules)
                        <a href="{{ route('etudiant.modules.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-slate-300 hover:bg-slate-700/40 hover:text-white transition-all border-l-2 {{ request()->routeIs('etudiant.modules.*') ? 'bg-slate-700/40 text-white border-sky-500' : 'border-transparent' }}">
                            <i data-lucide="package" class="w-4 h-4"></i>
                            <span class="text-sm">Mes modules</span>
                        </a>
                        @else
                        <span class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-slate-500 bg-slate-800/30">
                            <i data-lucide="package" class="w-4 h-4"></i>
                            <span class="text-sm">Mes modules</span>
                        </span>
                        @endif

                        @if($hasMatieres)
                        <a href="{{ route('etudiant.matieres.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-slate-300 hover:bg-slate-700/40 hover:text-white transition-all border-l-2 {{ request()->routeIs('etudiant.matieres.*') ? 'bg-slate-700/40 text-white border-sky-500' : 'border-transparent' }}">
                            <i data-lucide="layers" class="w-4 h-4"></i>
                            <span class="text-sm">Matières</span>
                        </a>
                        @else
                        <span class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-slate-500 bg-slate-800/30">
                            <i data-lucide="layers" class="w-4 h-4"></i>
                            <span class="text-sm">Matières</span>
                        </span>
                        @endif
                    </div>
                </div>

                <div>
                    <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-widest px-3 mb-1">Résultats</p>
                    <button type="button"
                        @click="openResultats = !openResultats"
                        class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg transition text-left {{ request()->routeIs('etudiant.notes.*') || request()->routeIs('etudiant.bulletin.*') || request()->routeIs('etudiant.dettes.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="flex items-center gap-3"><i data-lucide="bar-chart-2" class="w-4 h-4 shrink-0"></i> Résultats</span>
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </button>
                    <div x-show="openResultats" x-transition class="mt-2 space-y-1 px-2" style="display: none;">
                        @if($hasNotes)
                        <a href="{{ route('etudiant.notes') }}"
                            class="block rounded-lg border-l-4 border-transparent px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('etudiant.notes.*') ? 'bg-slate-800 text-white border-blue-500 font-semibold' : '' }}">
                            Mes notes
                        </a>
                        @else
                        <span class="block rounded-lg px-3 py-2 text-slate-500 bg-slate-950/30">Mes notes</span>
                        @endif

                        @if($hasBulletin)
                        <a href="{{ route('etudiant.bulletin.index') }}"
                            class="block rounded-lg border-l-4 border-transparent px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('etudiant.bulletin.*') ? 'bg-slate-800 text-white border-blue-500 font-semibold' : '' }}">
                            Mon bulletin
                        </a>
                        @else
                        <span class="block rounded-lg px-3 py-2 text-slate-500 bg-slate-950/30">Mon bulletin</span>
                        @endif

                        @if($hasDettes)
                        <a href="{{ route('etudiant.dettes.index') }}"
                            class="block rounded-lg border-l-4 border-transparent px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('etudiant.dettes.*') ? 'bg-slate-800 text-white border-blue-500 font-semibold' : '' }}">
                            Mes dettes
                        </a>
                        @else
                        <span class="block rounded-lg px-3 py-2 text-slate-500 bg-slate-950/30">Mes dettes</span>
                        @endif
                    </div>
                </div>

                <div>
                    <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-widest px-3 mb-1">Planning</p>
                    @if($hasEmploi)
                    <a href="{{ route('etudiant.emploi.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('etudiant.emploi.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i data-lucide="clock" class="w-4 h-4 shrink-0"></i>
                        Emploi du temps
                    </a>
                    @else
                    <span class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-500 bg-slate-950/30">
                        <i data-lucide="clock" class="w-4 h-4 shrink-0"></i>
                        Emploi du temps
                    </span>
                    @endif
                </div>

                <div>
                    <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-widest px-3 mb-1">Compte</p>
                    @if($hasProfil)
                    <a href="{{ route('etudiant.profil.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('etudiant.profil.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i data-lucide="user-circle" class="w-4 h-4 shrink-0"></i>
                        Mon profil
                    </a>
                    @else
                    <span class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-500 bg-slate-950/30">
                        <i data-lucide="user-circle" class="w-4 h-4 shrink-0"></i>
                        Mon profil
                    </span>
                    @endif
                </div>

            </nav>

            <!-- FOOTER SIDEBAR -->
            <div class="px-4 py-4 border-t border-slate-800">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0">
                        {{ strtoupper(substr(auth()->user()->nom ?? 'E', 0, 1)) }}
                    </div>
                    <div class="text-xs overflow-hidden">
                        <p class="font-medium text-white truncate">{{ auth()->user()->nom }} {{ auth()->user()->prenom }}</p>
                        <p class="text-slate-400">Étudiant</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full flex items-center justify-center gap-2 bg-slate-800 hover:bg-red-600 transition py-2.5 rounded-lg text-sm text-slate-300 hover:text-white">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        Déconnexion
                    </button>
                </form>
            </div>

        </aside>

        <!-- MAIN AREA -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- TOP BAR -->
            <header class="sticky top-0 z-10 bg-white border-b border-slate-200 px-4 md:px-6 py-3 flex items-center justify-between gap-4">

                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 rounded-lg hover:bg-slate-100">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>

                <div class="min-w-0">
                    <h2 class="text-base font-semibold truncate">@yield('title', 'Mon espace')</h2>
                    <p class="text-xs text-slate-500 hidden sm:block">@yield('subtitle', 'Bienvenue dans votre espace étudiant')</p>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <div class="w-8 h-8 bg-sky-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                        {{ strtoupper(substr(auth()->user()->nom ?? 'E', 0, 1)) }}
                    </div>
                    <span class="text-sm font-medium hidden sm:block">{{ auth()->user()->nom }}</span>
                </div>

            </header>

            <!-- FLASH MESSAGES -->
            <div class="px-4 md:px-6 pt-4 space-y-2">
                @if(session('success'))
                <div class="flex items-start gap-3 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
                    <i data-lucide="check-circle" class="w-4 h-4 mt-0.5 shrink-0"></i>
                    {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div class="flex items-start gap-3 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                    <i data-lucide="x-circle" class="w-4 h-4 mt-0.5 shrink-0"></i>
                    {{ session('error') }}
                </div>
                @endif
                @if(session('warning'))
                <div class="flex items-start gap-3 rounded-xl bg-amber-50 border border-amber-200 px-4 py-3 text-sm text-amber-700">
                    <i data-lucide="alert-triangle" class="w-4 h-4 mt-0.5 shrink-0"></i>
                    {{ session('warning') }}
                </div>
                @endif
                @if($errors->any())
                <div class="flex items-start gap-3 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                    <i data-lucide="x-circle" class="w-4 h-4 mt-0.5 shrink-0"></i>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

            <!-- CONTENT -->
            <main class="flex-1 px-4 md:px-6 py-6">
                @yield('content')
            </main>

            <!-- FOOTER -->
            <footer class="px-6 py-3 border-t border-slate-200 text-xs text-slate-400 text-center">
                UniGest &copy; {{ date('Y') }} — Système de gestion universitaire
            </footer>

        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>

</body>

</html>