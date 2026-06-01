<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Universitaire - Système de Gestion Académique</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50">
    <div x-data="{ open: false }" class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg fixed w-full z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 flex items-center">
                            <i class="fas fa-graduation-cap text-sky-600 text-3xl"></i>
                        </div>
                        <span class="font-bold text-xl text-gray-800">UniGestion</span>
                    </div>

                    <div class="hidden md:flex items-center gap-4">
                        <a href="/" class="text-gray-600 hover:text-sky-600 px-3 py-2 rounded-md font-medium transition">Accueil</a>
                        <a href="#features" class="text-gray-600 hover:text-sky-600 px-3 py-2 rounded-md font-medium transition">Aperçu</a>
                        <a href="#roles" class="text-gray-600 hover:text-sky-600 px-3 py-2 rounded-md font-medium transition">Utilisateurs</a>
                    </div>

                    <div class="hidden md:flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-sky-600 px-3 py-2 rounded-md font-medium transition">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}" class="bg-sky-600 hover:bg-sky-700 text-white px-4 py-2 rounded-lg font-medium transition">
                            Inscription
                        </a>
                    </div>

                    <button @click="open = !open" class="md:hidden inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white p-2 text-slate-600 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <span class="sr-only">Ouvrir le menu</span>
                        <svg x-cloak x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-cloak x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div x-show="open" x-transition class="md:hidden border-t border-slate-200 bg-white/95">
                <div class="space-y-3 px-4 py-4">
                    <a href="/" class="block text-slate-800 hover:text-sky-600 transition">Accueil</a>
                    <a href="#features" class="block text-slate-800 hover:text-sky-600 transition">Aperçu</a>
                    <a href="#roles" class="block text-slate-800 hover:text-sky-600 transition">Utilisateurs</a>
                    <div class="mt-4 space-y-2">
                        <a href="{{ route('login') }}" class="block w-full rounded-lg border border-slate-300 px-4 py-3 text-center text-sm font-medium text-slate-700 hover:bg-slate-100 transition">Connexion</a>
                        <a href="{{ route('register') }}" class="block w-full rounded-lg bg-sky-600 px-4 py-3 text-center text-sm font-semibold text-white hover:bg-sky-700 transition">Inscription</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="pt-32 pb-20 bg-gradient-to-br from-sky-600 to-sky-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                        Système de Gestion Universitaire
                    </h1>
                    <p class="text-xl md:text-2xl text-sky-100 mb-8 max-w-3xl mx-auto">
                        Une solution complète et moderne pour gérer les inscriptions, les notes, l'emploi du temps et la progression académique des étudiants.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        <a href="{{ route('register') }}" class="bg-white text-sky-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition shadow-lg">
                            <i class="fas fa-user-plus mr-2"></i>Commencer
                        </a>
                        <a href="#features" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white hover:text-sky-600 transition">
                            <i class="fas fa-info-circle mr-2"></i>En savoir plus
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Fonctionnalités Principales</h2>
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                        Découvrez toutes les fonctionnalités de notre système de gestion universitaire
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-gray-50 rounded-xl p-8 hover:shadow-lg transition">
                        <div class="w-16 h-16 bg-sky-100 rounded-lg flex items-center justify-center mb-6">
                            <i class="fas fa-user-graduate text-sky-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Gestion des Étudiants</h3>
                        <p class="text-gray-600">
                            Inscription, suivi académique, gestion des profils et progression des étudiants de manière efficace.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-gray-50 rounded-xl p-8 hover:shadow-lg transition">
                        <div class="w-16 h-16 bg-sky-100 rounded-lg flex items-center justify-center mb-6">
                            <i class="fas fa-chart-line text-sky-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Calculs Académiques</h3>
                        <p class="text-gray-600">
                            Calcul automatique des moyennes, validation des modules, rattrapages et progression de niveau.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-gray-50 rounded-xl p-8 hover:shadow-lg transition">
                        <div class="w-16 h-16 bg-sky-100 rounded-lg flex items-center justify-center mb-6">
                            <i class="fas fa-calendar-alt text-sky-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Emploi du Temps</h3>
                        <p class="text-gray-600">
                            Programmation hebdomadaire interactive avec détection automatique des conflits horaires.
                        </p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="bg-gray-50 rounded-xl p-8 hover:shadow-lg transition">
                        <div class="w-16 h-16 bg-sky-100 rounded-lg flex items-center justify-center mb-6">
                            <i class="fas fa-chalkboard-teacher text-sky-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Gestion des Enseignants</h3>
                        <p class="text-gray-600">
                            Assignation des enseignants aux matières, suivi des disponibilités et gestion des salles.
                        </p>
                    </div>

                    <!-- Feature 5 -->
                    <div class="bg-gray-50 rounded-xl p-8 hover:shadow-lg transition">
                        <div class="w-16 h-16 bg-sky-100 rounded-lg flex items-center justify-center mb-6">
                            <i class="fas fa-bell text-sky-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Notifications</h3>
                        <p class="text-gray-600">
                            Système de notifications multi-canaux pour les cours, examens, résultats et décisions académiques.
                        </p>
                    </div>

                    <!-- Feature 6 -->
                    <div class="bg-gray-50 rounded-xl p-8 hover:shadow-lg transition">
                        <div class="w-16 h-16 bg-sky-100 rounded-lg flex items-center justify-center mb-6">
                            <i class="fas fa-shield-alt text-sky-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Sécurité</h3>
                        <p class="text-gray-600">
                            Authentification sécurisée, gestion des rôles et protection des données sensibles.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-20 bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                    <div>
                        <div class="text-4xl md:text-5xl font-bold text-white mb-2">1000+</div>
                        <div class="text-gray-400">Étudiants</div>
                    </div>
                    <div>
                        <div class="text-4xl md:text-5xl font-bold text-white mb-2">50+</div>
                        <div class="text-gray-400">Enseignants</div>
                    </div>
                    <div>
                        <div class="text-4xl md:text-5xl font-bold text-white mb-2">20+</div>
                        <div class="text-gray-400">Filières</div>
                    </div>
                    <div>
                        <div class="text-4xl md:text-5xl font-bold text-white mb-2">99%</div>
                        <div class="text-gray-400">Satisfaction</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-20 bg-sky-600">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                    Prêt à transformer la gestion de votre université ?
                </h2>
                <p class="text-xl text-sky-100 mb-8">
                    Rejoignez-nous dès maintenant et découvrez la simplicité de la gestion académique.
                </p>
                <a href="{{ route('register') }}" class="inline-block bg-white text-sky-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition shadow-lg">
                    <i class="fas fa-rocket mr-2"></i>Démarrer Gratuitement
                </a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-400 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <div class="flex items-center mb-4">
                            <i class="fas fa-graduation-cap text-sky-600 text-2xl mr-2"></i>
                            <span class="font-bold text-xl text-white">UniGestion</span>
                        </div>
                        <p class="text-sm">
                            Système de gestion universitaire moderne et complet pour faciliter la vie académique.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-4">Liens Rapides</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="hover:text-white transition">Accueil</a></li>
                            <li><a href="#" class="hover:text-white transition">Fonctionnalités</a></li>
                            <li><a href="#" class="hover:text-white transition">Tarifs</a></li>
                            <li><a href="#" class="hover:text-white transition">Contact</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-4">Support</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="hover:text-white transition">Documentation</a></li>
                            <li><a href="#" class="hover:text-white transition">FAQ</a></li>
                            <li><a href="#" class="hover:text-white transition">Aide</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-4">Contact</h4>
                        <ul class="space-y-2 text-sm">
                            <li><i class="fas fa-envelope mr-2"></i>contact@unigestion.fr</li>
                            <li><i class="fas fa-phone mr-2"></i>+33 1 23 45 67 89</li>
                            <li><i class="fas fa-map-marker-alt mr-2"></i>Paris, France</li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm">
                    <p>&copy; 2026 UniGestion. Tous droits réservés.</p>
                </div>
            </div>
        </footer>
</body>

</html>