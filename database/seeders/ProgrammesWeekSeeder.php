<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\ProgrammeCours;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Semestre;
use App\Models\Matiere;
use App\Models\Professeur;
use App\Models\Salle;
use App\Models\CreneauHoraire;
use App\Models\Module;

class ProgrammesWeekSeeder extends Seeder
{
    public function run()
    {
        // Remove only programme_cours entries (keep other seed data intact)
        DB::table('programme_cours')->delete();

        // Ensure lookup data exists or create minimal records
        $filiere = Filiere::firstOrCreate(['code' => 'EX-FIL'], ['libelle' => 'Exemple Filière', 'description' => 'Filière exemple']);
        $niveau = Niveau::firstOrCreate(['code' => 'L1'], ['libelle' => 'Licence 1']);
        $semestre = Semestre::firstOrCreate(['code' => 'S1'], ['libelle' => 'S1', 'niveau_id' => $niveau->id]);
        $module = Module::firstOrCreate(['code' => 'MOD1'], ['libelle' => 'Module Exemple', 'semestre_id' => $semestre->id, 'filiere_id' => $filiere->id]);
        $matiere = Matiere::firstOrCreate(['code' => 'MAT-ALGO'], ['libelle' => 'Algorithmique', 'module_id' => $module->id]);
        $professeur = Professeur::firstOrCreate(['code' => 'P-DUPONT'], ['nom' => 'Dupont', 'prenom' => 'Jean', 'email' => 'jean.dupont@example.test']);
        $salleNames = ['Salle A', 'Salle B', 'Salle C'];
        $salles = collect($salleNames)->map(function ($name) {
            return Salle::firstOrCreate(['nom_salle' => $name], ['statut' => 'Actif']);
        })->values();

        // Ensure at least 3 créneaux exist
        $existing = CreneauHoraire::orderBy('heure_debut')->get();
        if ($existing->count() < 3) {
            CreneauHoraire::create(['heure_debut' => '08:00:00', 'heure_fin' => '10:00:00', 'est_actif' => true]);
            CreneauHoraire::create(['heure_debut' => '10:15:00', 'heure_fin' => '12:15:00', 'est_actif' => true]);
            CreneauHoraire::create(['heure_debut' => '14:00:00', 'heure_fin' => '16:00:00', 'est_actif' => true]);
            $existing = CreneauHoraire::orderBy('heure_debut')->get();
        }

        $creneaux = $existing->values();

        $startOfWeek = Carbon::now()->startOfWeek();
        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];

        for ($i = 0; $i < 5; $i++) {
            $assignedSalle = $salles[$i % $salles->count()];

            ProgrammeCours::create([
                'matiere_id' => $matiere->id,
                'professeur_id' => $professeur->id,
                'salle_id' => $assignedSalle->id,
                'creneau_id' => $creneaux[$i % $creneaux->count()]->id,
                'jour_semaine' => $jours[$i % count($jours)],
                'date_programme' => $startOfWeek->copy()->addDays($i)->format('Y-m-d'),
                'semestre_id' => $semestre->id,
                'niveau_id' => $niveau->id,
                'filiere_id' => $filiere->id,
                'type_cours' => 'Cours',
                'duree_heures' => 2,
                'statut' => 'Programmé',
                'notes' => 'Cours d\'exemple pour la semaine.'
            ]);
        }
    }
}
