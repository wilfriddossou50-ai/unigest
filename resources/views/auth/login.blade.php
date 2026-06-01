@extends('layouts.public')

@section('content')

<section class="min-h-[80vh] flex items-center justify-center px-6">
    <div class="w-full max-w-md bg-slate-100 rounded-[1.75rem] border border-slate-200 p-8 shadow-xl shadow-slate-900/10">
        <div class="text-center mb-7">
            <h1 class="text-3xl font-semibold text-slate-900">Connexion</h1>
            <p class="mt-2 text-sm text-slate-600">Accédez à votre espace universitaire en toute simplicité.</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div class="space-y-2">
                <label class="text-sm font-medium text-slate-700">Email</label>
                <input type="email" name="email"
                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-sky-400 focus:ring-2 focus:ring-sky-200"
                    value="{{ old('email') }}" required autofocus>
                @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="text-sm font-medium text-slate-700">Mot de passe</label>
                <div class="relative">
                    <input id="password" type="password" name="password"
                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 pr-12 text-slate-900 outline-none transition focus:border-sky-400 focus:ring-2 focus:ring-sky-200"
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

            <button type="submit"
                class="w-full rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                Se connecter
            </button>
        </form>

        <p class="text-center text-sm text-slate-600 mt-6">
            Pas de compte ?
            <a href="{{ route('register') }}" class="font-semibold text-sky-600 hover:text-sky-700">S'inscrire</a>
        </p>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.querySelector('#password');
        const toggleButton = document.querySelector('#toggle-password');

        if (!passwordInput || !toggleButton) {
            return;
        }

        toggleButton.addEventListener('click', function() {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            this.innerHTML = isPassword ?
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-5 w-5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.06 10.06 0 0 1 12 20c-7 0-11-8-11-8a20.38 20.38 0 0 1 5.06-5.94"/><path d="M1 1l22 22"/></svg>' :
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-5 w-5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
        });
    });
</script>

@endsection