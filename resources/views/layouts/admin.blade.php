<!DOCTYPE html>
<html lang="fr" class="scroll-smooth" x-data="{ sidebarOpen: false }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'UniGest') }} | @yield('title', 'Administration')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
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
                   w-72 h-full md:h-auto bg-slate-950 text-slate-100 flex flex-col">

            <!-- BRAND -->
            <div class="px-6 py-5 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shrink-0">
                        <i data-lucide="graduation-cap" class="w-5 h-5 text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold tracking-tight">UniGest</h1>
                        <p class="text-xs text-slate-400">Espace Administration</p>
                    </div>
                </div>
            </div>

            <!-- NAV -->
            <nav class="flex-1 px-3 py-5 space-y-3 text-sm overflow-y-auto"
                x-data="{
                     openAdmissions: false,
                     openAcademique: false,
                     openEvaluation: false,
                     openResults: false,
                     openOrganisation: false,
                     openProgrammation: false,
                     openCommunication: false,
                 }"
                x-init="
                     openAdmissions = {{ request()->routeIs('admin.inscriptions.*') || request()->routeIs('admin.etudiants.*') ? 'true' : 'false' }};
                     openAcademique = {{ request()->routeIs('admin.filieres.*') || request()->routeIs('admin.niveaux.*') || request()->routeIs('admin.semestres.*') || request()->routeIs('admin.modules.*') || request()->routeIs('admin.matieres.*') || request()->routeIs('admin.professeurs.*') || request()->routeIs('admin.professeur-matiere.*') ? 'true' : 'false' }};
                     openEvaluation = {{ request()->routeIs('admin.notes.*') || request()->routeIs('admin.dettes.*') ? 'true' : 'false' }};
                     openResults = {{ request()->routeIs('admin.resultats.*') || request()->routeIs('admin.progression.*') ? 'true' : 'false' }};
                     openOrganisation = {{ request()->routeIs('admin.emplois.*') ? 'true' : 'false' }};
                     openProgrammation = {{ request()->routeIs('admin.salles.*') || request()->routeIs('admin.creneaux.*') || request()->routeIs('admin.programme-cours.*') ? 'true' : 'false' }};
                 ">

                <div>
                    <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-widest px-3 mb-1">Général</p>
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i data-lucide="layout-dashboard" class="w-4 h-4 shrink-0"></i>
                        Tableau de bord
                    </a>
                </div>

                <div>
                    <button type="button"
                        @click="openAdmissions = !openAdmissions"
                        class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg transition text-left {{ request()->routeIs('admin.inscriptions.*') || request()->routeIs('admin.etudiants.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="flex items-center gap-3"><i data-lucide="file-text" class="w-4 h-4 shrink-0"></i> Admissions</span>
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </button>
                    <div x-show="openAdmissions" x-transition class="mt-2 space-y-1 px-2" style="display: none;">
                        <a href="{{ route('admin.inscriptions.index') }}"
                            class="block rounded-lg border-l-4 border-transparent px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('admin.inscriptions.*') ? 'bg-slate-800 text-white border-blue-500 font-semibold' : '' }}">
                            Inscriptions
                        </a>
                        <a href="{{ route('admin.etudiants.index') }}"
                            class="block rounded-lg border-l-4 border-transparent px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('admin.etudiants.*') ? 'bg-slate-800 text-white border-blue-500 font-semibold' : '' }}">
                            Étudiants
                        </a>
                    </div>
                </div>

                <div>
                    <button type="button"
                        @click="openAcademique = !openAcademique"
                        class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg transition text-left {{ request()->routeIs('admin.filieres.*') || request()->routeIs('admin.niveaux.*') || request()->routeIs('admin.semestres.*') || request()->routeIs('admin.modules.*') || request()->routeIs('admin.matieres.*') || request()->routeIs('admin.professeurs.*') || request()->routeIs('admin.professeur-matiere.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="flex items-center gap-3"><i data-lucide="layers" class="w-4 h-4 shrink-0"></i> Académique</span>
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </button>
                    <div x-show="openAcademique" x-transition class="mt-2 space-y-1 px-2" style="display: none;">
                        <a href="{{ route('admin.filieres.index') }}"
                            class="block rounded-lg border-l-4 border-transparent px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('admin.filieres.*') ? 'bg-slate-800 text-white border-blue-500 font-semibold' : '' }}">
                            Filières
                        </a>
                        <a href="{{ route('admin.niveaux.index') }}"
                            class="block rounded-lg border-l-4 border-transparent px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('admin.niveaux.*') ? 'bg-slate-800 text-white border-blue-500 font-semibold' : '' }}">
                            Niveaux
                        </a>
                        <a href="{{ route('admin.semestres.index') }}"
                            class="block rounded-lg border-l-4 border-transparent px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('admin.semestres.*') ? 'bg-slate-800 text-white border-blue-500 font-semibold' : '' }}">
                            Semestres
                        </a>
                        <a href="{{ route('admin.modules.index') }}"
                            class="block rounded-lg border-l-4 border-transparent px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('admin.modules.*') ? 'bg-slate-800 text-white border-blue-500 font-semibold' : '' }}">
                            Modules
                        </a>
                        <a href="{{ route('admin.matieres.index') }}"
                            class="block rounded-lg border-l-4 border-transparent px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('admin.matieres.*') ? 'bg-slate-800 text-white border-blue-500 font-semibold' : '' }}">
                            Matières
                        </a>
                        <a href="{{ route('admin.professeurs.index') }}"
                            class="block rounded-lg border-l-4 border-transparent px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('admin.professeurs.*') ? 'bg-slate-800 text-white border-blue-500 font-semibold' : '' }}">
                            Professeurs
                        </a>
                        <a href="{{ route('admin.professeur-matiere.index') }}"
                            class="block rounded-lg border-l-4 border-transparent px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('admin.professeur-matiere.*') ? 'bg-slate-800 text-white border-blue-500 font-semibold' : '' }}">
                            Affectations
                        </a>
                    </div>
                </div>

                <div>
                    <button type="button"
                        @click="openEvaluation = !openEvaluation"
                        class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg transition text-left {{ request()->routeIs('admin.notes.*') || request()->routeIs('admin.dettes.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="flex items-center gap-3"><i data-lucide="pen-line" class="w-4 h-4 shrink-0"></i> Évaluation</span>
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </button>
                    <div x-show="openEvaluation" x-transition class="mt-2 space-y-1 px-2" style="display: none;">
                        <a href="{{ route('admin.notes.index') }}"
                            class="block rounded-lg border-l-4 border-transparent px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('admin.notes.*') ? 'bg-slate-800 text-white border-blue-500 font-semibold' : '' }}">
                            Notes
                        </a>
                        <a href="{{ route('admin.dettes.index') }}"
                            class="block rounded-lg border-l-4 border-transparent px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('admin.dettes.*') ? 'bg-slate-800 text-white border-blue-500 font-semibold' : '' }}">
                            Dettes
                        </a>
                    </div>
                </div>

                <div>
                    <button type="button"
                        @click="openResults = !openResults"
                        class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg transition text-left {{ request()->routeIs('admin.resultats.*') || request()->routeIs('admin.progression.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="flex items-center gap-3"><i data-lucide="bar-chart-2" class="w-4 h-4 shrink-0"></i> Résultats</span>
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </button>
                    <div x-show="openResults" x-transition class="mt-2 space-y-1 px-2" style="display: none;">
                        <a href="{{ route('admin.resultats.semestre.index') }}"
                            class="block rounded-lg border-l-4 border-transparent px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('admin.resultats.semestre.*') ? 'bg-slate-800 text-white border-blue-500 font-semibold' : '' }}">
                            Semestriels
                        </a>
                        <a href="{{ route('admin.resultats.annuel.index') }}"
                            class="block rounded-lg border-l-4 border-transparent px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('admin.resultats.annuel.*') ? 'bg-slate-800 text-white border-blue-500 font-semibold' : '' }}">
                            Annuels
                        </a>
                        <a href="{{ route('admin.progression.index') }}"
                            class="block rounded-lg border-l-4 border-transparent px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('admin.progression.*') ? 'bg-slate-800 text-white border-blue-500 font-semibold' : '' }}">
                            Progression
                        </a>
                    </div>
                </div>

                <div>
                    <button type="button"
                        @click="openProgrammation = !openProgrammation"
                        class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg transition text-left {{ request()->routeIs('admin.salles.*') || request()->routeIs('admin.creneaux.*') || request()->routeIs('admin.programme-cours.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span class="flex items-center gap-3"><i data-lucide="calendar" class="w-4 h-4 shrink-0"></i> Programmation</span>
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </button>
                    <div x-show="openProgrammation" x-transition class="mt-2 space-y-1 px-2" style="display: none;">
                        <a href="{{ route('admin.salles.index') }}"
                            class="block rounded-lg border-l-4 border-transparent px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('admin.salles.*') ? 'bg-slate-800 text-white border-blue-500 font-semibold' : '' }}">
                            Salles
                        </a>
                        <a href="{{ route('admin.creneaux.index') }}"
                            class="block rounded-lg border-l-4 border-transparent px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('admin.creneaux.*') ? 'bg-slate-800 text-white border-blue-500 font-semibold' : '' }}">
                            Créneaux horaires
                        </a>
                        <a href="{{ route('admin.programme-cours.grille') }}"
                            class="block rounded-lg border-l-4 border-transparent px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('admin.programme-cours.*') ? 'bg-slate-800 text-white border-blue-500 font-semibold' : '' }}">
                            Grille hebdomadaire
                        </a>
                        <a href="{{ route('admin.programme-cours.index') }}"
                            class="block rounded-lg border-l-4 border-transparent px-3 py-2 text-slate-300 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('admin.programme-cours.index') ? 'bg-slate-800 text-white border-blue-500 font-semibold' : '' }}">
                            Liste des cours
                        </a>
                    </div>
                </div>

            </nav>

            <!-- FOOTER SIDEBAR -->
            <div class="px-4 py-4 border-t border-slate-800">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0">
                        {{ strtoupper(substr(auth()->user()->nom ?? 'A', 0, 1)) }}
                    </div>
                    <div class="text-xs overflow-hidden">
                        <p class="font-medium text-white truncate">{{ auth()->user()->nom }} {{ auth()->user()->prenom }}</p>
                        <p class="text-slate-400">Administrateur</p>
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

                <!-- Hamburger mobile -->
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 rounded-lg hover:bg-slate-100">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>

                <div class="min-w-0">
                    <h2 class="text-base font-semibold truncate">@yield('title', 'Tableau de bord')</h2>
                    <p class="text-xs text-slate-500 hidden sm:block">@yield('subtitle', 'Gestion du système universitaire')</p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <!-- Avatar -->
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->nom ?? 'A', 0, 1)) }}
                        </div>
                        <span class="text-sm font-medium hidden sm:block">{{ auth()->user()->nom }}</span>
                    </div>
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
