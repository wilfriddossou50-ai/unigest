@extends('layouts.public')

@section('content')

<section class="relative overflow-hidden bg-slate-950 text-white">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.35),_transparent_35%),radial-gradient(circle_at_bottom_right,_rgba(56,189,248,0.2),_transparent_30%)]"></div>
    <div class="relative max-w-7xl mx-auto px-6 py-24 lg:py-32">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <p class="inline-flex items-center gap-2 text-sm uppercase tracking-[0.24em] font-semibold text-sky-300">
                    <span class="h-1.5 w-1.5 rounded-full bg-sky-300"></span>
                    Plateforme académique
                </p>
                <h1 class="text-4xl sm:text-5xl font-bold tracking-tight">
                    Gestion universitaire moderne & centralisée
                </h1>
                <p class="max-w-2xl text-slate-200 leading-8">
                    Simplifiez la gestion des étudiants, des notes et des résultats avec une interface claire, des tableaux réactifs et un suivi académique transparent.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full bg-sky-400 px-7 py-3 text-sm font-semibold text-slate-950 shadow-lg shadow-sky-500/20 transition hover:bg-sky-300">
                        Commencer maintenant
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full border border-slate-300/30 px-7 py-3 text-sm font-semibold text-white/90 transition hover:border-white/60 hover:text-white">
                        Se connecter
                    </a>
                </div>
                <div class="grid grid-cols-3 gap-4 mt-10 text-center">
                    <div class="rounded-3xl bg-white/5 p-5">
                        <p class="text-3xl font-bold text-white">+150</p>
                        <p class="text-xs uppercase tracking-[0.24em] text-slate-400 mt-2">Utilisateurs</p>
                    </div>
                    <div class="rounded-3xl bg-white/5 p-5">
                        <p class="text-3xl font-bold text-white">100%</p>
                        <p class="text-xs uppercase tracking-[0.24em] text-slate-400 mt-2">Satisfaction</p>
                    </div>
                    <div class="rounded-3xl bg-white/5 p-5">
                        <p class="text-3xl font-bold text-white">24/7</p>
                        <p class="text-xs uppercase tracking-[0.24em] text-slate-400 mt-2">Support</p>
                    </div>
                </div>
            </div>
            <div class="relative rounded-[2rem] bg-slate-900/80 border border-white/10 p-8 shadow-2xl shadow-slate-950/30 backdrop-blur-xl">
                <div class="space-y-6">
                    <div class="rounded-3xl bg-slate-800/90 p-6 border border-slate-700/80">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs uppercase tracking-[0.3em] text-sky-300">Tableau</span>
                            <span class="text-xs text-slate-400">Vue globale</span>
                        </div>
                        <div class="space-y-3">
                            <div class="h-2 rounded-full bg-slate-700"></div>
                            <div class="h-2 rounded-full bg-slate-700 w-5/6"></div>
                            <div class="h-2 rounded-full bg-slate-700 w-3/4"></div>
                        </div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-3xl bg-slate-800/90 p-5 border border-slate-700/80">
                            <p class="text-sm text-slate-400">Résultats</p>
                            <p class="text-3xl font-semibold text-white">Décisions rapides</p>
                        </div>
                        <div class="rounded-3xl bg-slate-800/90 p-5 border border-slate-700/80">
                            <p class="text-sm text-slate-400">Modules</p>
                            <p class="text-3xl font-semibold text-white">Analyse instantanée</p>
                        </div>
                    </div>
                    <div class="rounded-3xl bg-slate-800/90 p-5 border border-slate-700/80">
                        <p class="text-sm text-slate-400">Notifications</p>
                        <p class="text-white">Gardez vos étudiants informés des mises à jour importantes.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-slate-50 py-20">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-16">
            <p class="text-sm uppercase tracking-[0.24em] text-sky-500">Fonctionnalités</p>
            <h2 class="mt-4 text-3xl font-semibold text-slate-900">Un espace pensé pour l’administration et les étudiants</h2>
        </div>
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <h3 class="text-xl font-semibold text-slate-900">Gestion des inscriptions</h3>
                <p class="mt-4 text-slate-600">Organisez les filières, niveaux et inscriptions avec une expérience fluide.</p>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <h3 class="text-xl font-semibold text-slate-900">Suivi des notes</h3>
                <p class="mt-4 text-slate-600">Visualisez les notes par semestre et module, avec des décisions claires et rapides.</p>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <h3 class="text-xl font-semibold text-slate-900">Tableaux responsives</h3>
                <p class="mt-4 text-slate-600">Des listes filtrables et des interfaces adaptées aux grands volumes de données.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        <div class="rounded-[2rem] bg-slate-950 text-white p-10 shadow-2xl shadow-slate-900/30">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.28em] text-sky-300">Prêt à démarrer ?</p>
                    <h2 class="mt-3 text-3xl font-semibold">Rejoignez une plateforme conçue pour les établissements modernes.</h2>
                </div>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full bg-sky-400 px-8 py-3 text-sm font-semibold text-slate-950 transition hover:bg-sky-300">
                    Créer un compte étudiant
                </a>
            </div>
        </div>
    </div>
</section>

@endsection