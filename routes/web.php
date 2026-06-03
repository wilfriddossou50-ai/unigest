<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\FiliereController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\MatiereController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ModuleResultatController;
use App\Http\Controllers\NiveauController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfesseurController;
use App\Http\Controllers\ResultatAnnuelController;
use App\Http\Controllers\ResultatSemestreController;
use App\Http\Controllers\ProgressionEtudiantController;
use App\Http\Controllers\DetteController;
use App\Http\Controllers\EmploiDuTempsController;
use App\Http\Controllers\SemestreController;
use App\Http\Controllers\SalleController;
use App\Http\Controllers\CreneauHoraireController;
use App\Http\Controllers\ProgrammeCoursController;
use App\Http\Controllers\ProfesseurMatiereController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('home');
})->name('home');

require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {

    // Redirection intelligente après connexion
    Route::get('dashboard', function () {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->role === 'admin') return redirect()->route('admin.dashboard');
        if ($user->role === 'etudiant') {
            return $user->canAccessStudentSpace() ? redirect()->route('etudiant.dashboard') : redirect()->route('attente');
        }
        return redirect('/');
    })->name('dashboard');

    Route::get('attente', [AuthController::class, 'attente'])->name('attente');

    // PROFIL UTILISATEUR
    Route::get('profil', [ProfileController::class, 'index'])->name('profile.edit');
    Route::match(['put', 'patch'], 'profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profil', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ESPACE ADMINISTRATION
    Route::prefix('admin')->name('admin.')->middleware(['role:admin'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

        Route::controller(InscriptionController::class)->group(function () {
            Route::get('inscriptions', 'index')->name('inscriptions.index');
            Route::get('inscriptions/{inscription}', 'show')->name('inscriptions.show');
            Route::put('inscriptions/{inscription}/approve', 'approuver')->name('inscriptions.approve');
            Route::put('inscriptions/{inscription}/refuse', 'refuser')->name('inscriptions.refuse');
        });

        Route::resource('filieres', FiliereController::class)->except(['show']);
        Route::resource('etudiants', EtudiantController::class)->except(['show']);
        Route::resource('professeurs', ProfesseurController::class)->except(['show']);
        Route::resource('niveaux', NiveauController::class)->except(['show']);
        Route::resource('semestres', SemestreController::class)->except(['show']);
        Route::resource('modules', ModuleController::class)->except(['show']);
        Route::resource('matieres', MatiereController::class)->except(['show']);
        Route::resource('salles', SalleController::class)->except(['show']);
        Route::resource('creneaux', CreneauHoraireController::class)->except(['show']);
        Route::resource('emplois', EmploiDuTempsController::class)->except(['show']);
        Route::get('modules/{module}/resultats', [ModuleResultatController::class, 'show'])->name('modules.resultats');

        Route::prefix('professeur-matiere')->name('professeur-matiere.')->controller(ProfesseurMatiereController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::delete('{professeur_matiere}', 'destroy')->name('destroy');
        });

        Route::prefix('notes')->name('notes.')->controller(NoteController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::post('{note}/rattrapage', 'rattrapage')->name('rattrapage');
            Route::post('{note}/reprise', 'reprise')->name('reprise');
        });

        Route::prefix('dettes')->name('dettes.')->controller(DetteController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('{etudiant}', 'etudiant')->name('etudiant');
            Route::post('{dette}/lever', 'lever')->name('lever');
        });

        Route::prefix('programme-cours')->name('programme-cours.')->group(function () {
            Route::get('/', [ProgrammeCoursController::class, 'index'])->name('index');
            Route::get('grille', [ProgrammeCoursController::class, 'grille'])->name('grille');
            Route::get('create', [ProgrammeCoursController::class, 'create'])->name('create');
            Route::post('/', [ProgrammeCoursController::class, 'store'])->name('store');
            Route::get('{programme}/edit', [ProgrammeCoursController::class, 'edit'])->name('edit');
            Route::put('{programme}', [ProgrammeCoursController::class, 'update'])->name('update');
            Route::put('{programme}/annuler', [ProgrammeCoursController::class, 'annuler'])->name('annuler');
            Route::delete('{programme}', [ProgrammeCoursController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('resultats')->group(function () {
            Route::get('semestre', [ResultatSemestreController::class, 'index'])->name('resultats.semestre.index');
            Route::post('semestre/calculer/{etudiant}/{semestre}', [ResultatSemestreController::class, 'calculer'])->name('resultats.semestre.calculer');
            Route::post('semestre/tout-calculer', [ResultatSemestreController::class, 'toutCalculer'])->name('resultats.semestre.tout-calculer');

            Route::get('annuel', [ResultatAnnuelController::class, 'index'])->name('resultats.annuel.index');
            Route::post('annuel/calculer/{etudiant}/{niveau}', [ResultatAnnuelController::class, 'calculer'])->name('resultats.annuel.calculer');
            Route::post('annuel/tout-calculer', [ResultatAnnuelController::class, 'toutCalculer'])->name('resultats.annuel.tout-calculer');
        });

        Route::get('progression', [ProgressionEtudiantController::class, 'index'])->name('progression.index');
        Route::post('progression/calculer/{etudiant}/{niveau}/{decisionSemestre}', [ProgressionEtudiantController::class, 'calculer'])->name('progression.calculer');
    });

    // ESPACE ÉTUDIANT
    Route::prefix('etudiant')->name('etudiant.')->middleware(['role:etudiant'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'etudiantDashboard'])->name('dashboard');

        // CORRECTION ICI : "notes" au lieu de "/etudiant/notes"
        Route::get('notes', [DashboardController::class, 'etudiantNotes'])->name('notes');

        Route::get('bulletin', [DashboardController::class, 'etudiantBulletin'])->name('bulletin.index');
        Route::get('emploi', [DashboardController::class, 'etudiantEmploi'])->name('emploi.index');
        Route::get('dettes', [DashboardController::class, 'etudiantDettes'])->name('dettes.index');
        Route::get('profil', [ProfileController::class, 'index'])->name('profil.index');
        Route::match(['put', 'patch'], 'profil', [ProfileController::class, 'update'])->name('profil.update');
        Route::delete('profil', [ProfileController::class, 'destroy'])->name('profil.destroy');
        Route::get('modules', [DashboardController::class, 'etudiantModules'])->name('modules.index');
        Route::get('matieres', [DashboardController::class, 'etudiantMatieres'])->name('matieres.index');
    });
    // API AJAX
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('niveaux/{filiereId}', fn($filiereId) => response()->json(\App\Models\Niveau::orderBy('libelle')->get()))->name('niveaux');
        Route::get('semestres/{niveauId}', fn($niveauId) => response()->json(\App\Models\Semestre::where('niveau_id', $niveauId)->get()))->name('semestres');
        Route::get('modules/{semestreId}/{filiereId}', fn($semestreId, $filiereId) => response()->json(\App\Models\Module::where('semestre_id', $semestreId)->where('filiere_id', $filiereId)->get()))->name('modules');
        Route::get('matieres/{moduleId}', fn($moduleId) => response()->json(\App\Models\Matiere::where('module_id', $moduleId)->get()))->name('matieres');
        Route::get('enseignants/{matiereId}', fn($matiereId) => response()->json(\App\Models\Matiere::with('professeurs')->find($matiereId)?->professeurs ?? []))->name('enseignants');
    });
});
