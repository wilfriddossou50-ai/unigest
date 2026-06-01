@extends('layouts.public')

@section('content')

<section class="min-h-[80vh] flex items-center justify-center px-6 py-10">
    <div class="w-full max-w-3xl bg-slate-100 rounded-[2rem] border border-slate-200 p-8 shadow-2xl shadow-slate-900/10">
        <div class="grid gap-10 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
            <div>
                <div class="mb-6">
                    <p class="text-sm uppercase tracking-[0.3em] text-sky-500">Inscription Étudiant</p>
                    <h1 class="mt-3 text-3xl font-semibold text-slate-900">Créez votre compte universitaire</h1>
                    <p class="mt-3 text-slate-600">Renseignez vos informations pour accéder à votre espace d’étudiant et suivre vos notes.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium text-slate-700">Nom</label>
                            <input type="text" name="nom" value="{{ old('nom') }}"
                                class="mt-1 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-sky-400 focus:ring-2 focus:ring-sky-200"
                                required>
                            @error('nom')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-700">Prénom</label>
                            <input type="text" name="prenom" value="{{ old('prenom') }}"
                                class="mt-1 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-sky-400 focus:ring-2 focus:ring-sky-200"
                                required>
                            @error('prenom')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium text-slate-700">Sexe</label>
                            <select name="sexe" required
                                class="mt-1 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-sky-400 focus:ring-2 focus:ring-sky-200">
                                <option value="">Choisir</option>
                                <option value="M" {{ old('sexe') === 'M' ? 'selected' : '' }}>Masculin</option>
                                <option value="F" {{ old('sexe') === 'F' ? 'selected' : '' }}>Féminin</option>
                            </select>
                            @error('sexe')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-700">Date de naissance</label>
                            <input type="date" name="date_naissance" value="{{ old('date_naissance') }}"
                                class="mt-1 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-sky-400 focus:ring-2 focus:ring-sky-200"
                                required>
                            @error('date_naissance')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium text-slate-700">Filière</label>
                            <select name="filiere_id" id="filiere-select" required
                                class="mt-1 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-sky-400 focus:ring-2 focus:ring-sky-200">
                                <option value="">Choisir une filière</option>
                                @foreach($filieres as $filiere)
                                <option value="{{ $filiere->id }}" {{ old('filiere_id') == $filiere->id ? 'selected' : '' }}>
                                    {{ $filiere->libelle }}
                                </option>
                                @endforeach
                            </select>
                            @error('filiere_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-700">Niveau</label>
                            <select name="niveau_id" id="niveau-select" required
                                class="mt-1 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-sky-400 focus:ring-2 focus:ring-sky-200"
                                {{ old('filiere_id') ? '' : 'disabled' }}>
                                <option value="">{{ old('filiere_id') ? 'Choisir un niveau' : 'Sélectionnez d’abord une filière' }}</option>
                                @foreach($niveaux as $niveau)
                                <option value="{{ $niveau->id }}" {{ old('niveau_id') == $niveau->id ? 'selected' : '' }}>
                                    {{ $niveau->libelle }}
                                </option>
                                @endforeach
                            </select>
                            @error('niveau_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="mt-1 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-sky-400 focus:ring-2 focus:ring-sky-200"
                                required>
                            @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-700">Mot de passe</label>
                            <div class="relative">
                                <input id="password" type="password" name="password"
                                    class="mt-1 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 pr-12 text-slate-900 outline-none transition focus:border-sky-400 focus:ring-2 focus:ring-sky-200"
                                    required>
                                <button type="button" id="toggle-password" aria-label="Afficher ou masquer le mot de passe"
                                    class="absolute inset-y-0 right-3 inline-flex items-center text-slate-500 transition hover:text-slate-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-5 w-5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-700">Confirmation</label>
                        <input id="password_confirmation" type="password" name="password_confirmation"
                            class="mt-1 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-sky-400 focus:ring-2 focus:ring-sky-200"
                            required>
                        @error('password_confirmation')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-start gap-3">
                        <input id="terms" type="checkbox" name="terms" value="1"
                            class="mt-1 h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                        <label for="terms" class="text-sm text-slate-700">
                            J’accepte les <a href="#" class="font-semibold text-sky-600 hover:text-sky-700">conditions générales d’utilisation</a> du service.
                        </label>
                    </div>
                    @error('terms')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror

                    <button type="submit" class="w-full rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                        Créer le compte étudiant
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-slate-600">
                    Déjà un compte ?
                    <a href="{{ route('login') }}" class="font-semibold text-sky-600 hover:text-sky-700">Se connecter</a>
                </p>
            </div>
            <div class="rounded-[1.75rem] bg-sky-500/10 p-6 text-slate-900">
                <div class="space-y-6">
                    <div class="rounded-3xl bg-white/90 p-6 shadow-sm">
                        <h2 class="text-xl font-semibold">Pourquoi s’inscrire ?</h2>
                        <p class="mt-3 text-sm text-slate-700">Suivez vos notes, consultez vos bulletins et accédez à toutes les informations du parcours universitaire.</p>
                    </div>
                    <ul class="space-y-4 text-sm text-slate-700">
                        <li class="flex items-start gap-3">
                            <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-sky-500 text-white">✓</span>
                            <span>Suivi des résultats et moyennes par semestre.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-sky-500 text-white">✓</span>
                            <span>Une interface simple pour consulter vos modules et décisions.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-sky-500 text-white">✓</span>
                            <span>Une inscription sécurisée avec validation de l’administration.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filiereSelect = document.querySelector('#filiere-select');
        const niveauSelect = document.querySelector('#niveau-select');
        const passwordInput = document.querySelector('#password');
        const toggleButton = document.querySelector('#toggle-password');

        if (filiereSelect && niveauSelect) {
            function updateNiveauState() {
                if (filiereSelect.value === '') {
                    niveauSelect.disabled = true;
                    const defaultOption = niveauSelect.querySelector('option[value=""]');
                    if (defaultOption) {
                        defaultOption.textContent = 'Sélectionnez d’abord une filière';
                    }
                } else {
                    niveauSelect.disabled = false;
                    const defaultOption = niveauSelect.querySelector('option[value=""]');
                    if (defaultOption) {
                        defaultOption.textContent = 'Choisir un niveau';
                    }
                }
            }

            filiereSelect.addEventListener('change', updateNiveauState);
            updateNiveauState();
        }

        if (passwordInput && toggleButton) {
            toggleButton.addEventListener('click', function() {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                this.innerHTML = isPassword ?
                    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-5 w-5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.06 10.06 0 0 1 12 20c-7 0-11-8-11-8a20.38 20.38 0 0 1 5.06-5.94"/><path d="M1 1l22 22"/></svg>' :
                    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-5 w-5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
            });
        }
    });
</script>

@endsection