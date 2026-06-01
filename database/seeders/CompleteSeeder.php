<?php

namespace Database\Seeders;

use App\Models\CreneauHoraire;
use App\Models\EmploiDuTemps;
use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Inscription;
use App\Models\Matiere;
use App\Models\Module;
use App\Models\Note;
use App\Models\NotificationCustom;
use App\Models\Niveau;
use App\Models\Professeur;
use App\Models\ProgrammeCours;
use App\Models\ProgressionEtudiant;
use App\Models\ResultatAnnuel;
use App\Models\ResultatSemestre;
use App\Models\Salle;
use App\Models\Semestre;
use App\Models\User;
use App\Services\ProgressionService;
use App\Services\ResultatAnnuelService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CompleteSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@unigest.test'],
            [
                'nom' => 'Admin',
                'prenom' => 'Principal',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        $filieres = $this->seedFilieres();
        $niveaux = $this->seedNiveaux();
        $semestres = $this->seedSemestres($niveaux);
        $professeurs = $this->seedProfesseurs();
        $salles = $this->seedSalles();
        $creneaux = $this->seedCreneaux();

        $modulesByFiliere = $this->seedModulesAndMatieres($filieres, $semestres, $professeurs);
        $students = $this->seedEtudiants($filieres, $niveaux);

        $flags = $this->seedNotes($students, $modulesByFiliere);
        $this->seedSyntheticSemestreResults($students, $semestres, $niveaux);
        $this->seedAnnualResultsAndProgression($students, $niveaux);
        $this->seedPlanning($modulesByFiliere, $salles, $creneaux, $semestres, $niveaux);
        $this->seedNotifications($students, $flags);

        echo "Demo data seeded successfully.\n";
    }

    private function seedFilieres(): array
    {
        $data = [
            ['code' => 'INFO', 'libelle' => 'Informatique', 'description' => 'Parcours orientes developpement et systemes'],
            ['code' => 'GEST', 'libelle' => 'Gestion', 'description' => 'Parcours orientes comptabilite et management'],
            ['code' => 'RT', 'libelle' => 'Reseaux et Telecommunications', 'description' => 'Parcours orientes infrastructure et securite'],
        ];

        $filieres = [];
        foreach ($data as $item) {
            $filieres[$item['code']] = Filiere::updateOrCreate(
                ['code' => $item['code']],
                $item
            );
        }

        return $filieres;
    }

    private function seedNiveaux(): array
    {
        $data = [
            ['code' => 'L1', 'libelle' => 'Licence 1'],
            ['code' => 'L2', 'libelle' => 'Licence 2'],
            ['code' => 'L3', 'libelle' => 'Licence 3'],
        ];

        $niveaux = [];
        foreach ($data as $item) {
            $niveaux[$item['code']] = Niveau::updateOrCreate(
                ['code' => $item['code']],
                $item
            );
        }

        return $niveaux;
    }

    private function seedSemestres(array $niveaux): array
    {
        $data = [
            ['code' => 'S1', 'libelle' => 'Semestre 1', 'niveau_code' => 'L1'],
            ['code' => 'S2', 'libelle' => 'Semestre 2', 'niveau_code' => 'L1'],
            ['code' => 'S3', 'libelle' => 'Semestre 3', 'niveau_code' => 'L2'],
            ['code' => 'S4', 'libelle' => 'Semestre 4', 'niveau_code' => 'L2'],
            ['code' => 'S5', 'libelle' => 'Semestre 5', 'niveau_code' => 'L3'],
            ['code' => 'S6', 'libelle' => 'Semestre 6', 'niveau_code' => 'L3'],
        ];

        $semestres = [];
        foreach ($data as $item) {
            $semestres[$item['code']] = Semestre::updateOrCreate(
                ['code' => $item['code']],
                [
                    'code' => $item['code'],
                    'libelle' => $item['libelle'],
                    'niveau_id' => $niveaux[$item['niveau_code']]->id,
                ]
            );
        }

        return $semestres;
    }

    private function seedProfesseurs(): array
    {
        $data = [
            ['code' => 'P-INFO-1', 'nom' => 'Martin', 'prenom' => 'Jean', 'sexe' => 'M', 'email' => 'jean.martin@demo.test', 'specialite' => 'Algorithmique'],
            ['code' => 'P-INFO-2', 'nom' => 'Dupont', 'prenom' => 'Marie', 'sexe' => 'F', 'email' => 'marie.dupont@demo.test', 'specialite' => 'Base de donnees'],
            ['code' => 'P-GEST-1', 'nom' => 'Bernard', 'prenom' => 'Pierre', 'sexe' => 'M', 'email' => 'pierre.bernard@demo.test', 'specialite' => 'Comptabilite'],
            ['code' => 'P-GEST-2', 'nom' => 'Petit', 'prenom' => 'Sophie', 'sexe' => 'F', 'email' => 'sophie.petit@demo.test', 'specialite' => 'Statistiques'],
            ['code' => 'P-RT-1', 'nom' => 'Robert', 'prenom' => 'Luc', 'sexe' => 'M', 'email' => 'luc.robert@demo.test', 'specialite' => 'Reseaux'],
            ['code' => 'P-RT-2', 'nom' => 'Ngoma', 'prenom' => 'Claire', 'sexe' => 'F', 'email' => 'claire.ngoma@demo.test', 'specialite' => 'Cybersecurite'],
        ];

        $professeurs = [];
        foreach ($data as $item) {
            $professeurs[] = Professeur::updateOrCreate(
                ['code' => $item['code']],
                $item
            );
        }

        return $professeurs;
    }

    private function seedSalles(): array
    {
        $data = [
            ['nom_salle' => 'A101', 'statut' => 'Actif'],
            ['nom_salle' => 'A102', 'statut' => 'Actif'],
            ['nom_salle' => 'B201', 'statut' => 'Actif'],
            ['nom_salle' => 'Amphi A', 'statut' => 'Actif'],
            ['nom_salle' => 'Lab Info', 'statut' => 'Actif'],
        ];

        $salles = [];
        foreach ($data as $item) {
            $salles[] = Salle::updateOrCreate(
                ['nom_salle' => $item['nom_salle']],
                $item
            );
        }

        return $salles;
    }

    private function seedCreneaux(): array
    {
        $data = [
            ['heure_debut' => '08:00:00', 'heure_fin' => '10:00:00', 'est_actif' => true],
            ['heure_debut' => '10:00:00', 'heure_fin' => '12:00:00', 'est_actif' => true],
            ['heure_debut' => '13:00:00', 'heure_fin' => '15:00:00', 'est_actif' => true],
            ['heure_debut' => '15:00:00', 'heure_fin' => '17:00:00', 'est_actif' => true],
            ['heure_debut' => '17:00:00', 'heure_fin' => '19:00:00', 'est_actif' => true],
        ];

        $creneaux = [];
        foreach ($data as $item) {
            $creneaux[] = CreneauHoraire::updateOrCreate(
                [
                    'heure_debut' => $item['heure_debut'],
                    'heure_fin' => $item['heure_fin'],
                ],
                $item
            );
        }

        return $creneaux;
    }

    private function seedModulesAndMatieres(array $filieres, array $semestres, array $professeurs): array
    {
        $blueprints = [
            'INFO' => [
                ['code' => 'INFO-ALG', 'libelle' => 'Algorithmique et Programmation', 'semestre' => 'S1'],
                ['code' => 'INFO-POO', 'libelle' => 'Programmation Orientee Objet', 'semestre' => 'S2'],
                ['code' => 'INFO-BDD', 'libelle' => 'Bases de Donnees', 'semestre' => 'S3'],
                ['code' => 'INFO-WEB', 'libelle' => 'Developpement Web', 'semestre' => 'S4'],
                ['code' => 'INFO-SYS', 'libelle' => 'Architecture Systeme', 'semestre' => 'S5'],
            ],
            'GEST' => [
                ['code' => 'GEST-COM', 'libelle' => 'Comptabilite Generale', 'semestre' => 'S1'],
                ['code' => 'GEST-ECO', 'libelle' => 'Economie et Gestion', 'semestre' => 'S2'],
                ['code' => 'GEST-MKT', 'libelle' => 'Marketing et Vente', 'semestre' => 'S3'],
                ['code' => 'GEST-FIN', 'libelle' => 'Gestion Financiere', 'semestre' => 'S4'],
                ['code' => 'GEST-STA', 'libelle' => 'Statistique Appliquee', 'semestre' => 'S6'],
            ],
            'RT' => [
                ['code' => 'RT-ARC', 'libelle' => 'Architecture des Reseaux', 'semestre' => 'S1'],
                ['code' => 'RT-LIN', 'libelle' => 'Systemes Linux', 'semestre' => 'S2'],
                ['code' => 'RT-SEC', 'libelle' => 'Cybersecurite', 'semestre' => 'S3'],
                ['code' => 'RT-TEL', 'libelle' => 'Telecommunications', 'semestre' => 'S5'],
                ['code' => 'RT-CLO', 'libelle' => 'Cloud et Virtualisation', 'semestre' => 'S6'],
            ],
        ];

        $suffixes = ['Fondamentaux', 'Methodes', 'TP', 'Projet', 'Evaluation finale'];
        $modulesByFiliere = [];

        foreach ($blueprints as $filiereCode => $modulesData) {
            $modulesByFiliere[$filiereCode] = [];

            foreach ($modulesData as $moduleIndex => $moduleData) {
                $semestre = $semestres[$moduleData['semestre']];

                $module = Module::updateOrCreate(
                    ['code' => $moduleData['code']],
                    [
                        'semestre_id' => $semestre->id,
                        'filiere_id' => $filieres[$filiereCode]->id,
                        'code' => $moduleData['code'],
                        'libelle' => $moduleData['libelle'],
                    ]
                );

                $module->setRelation('semestre', $semestre);

                $matieres = [];
                foreach ($suffixes as $matiereIndex => $suffix) {
                    $professeur = $professeurs[($moduleIndex + $matiereIndex) % count($professeurs)];
                    $matiere = Matiere::updateOrCreate(
                        ['code' => $moduleData['code'] . '-' . ($matiereIndex + 1)],
                        [
                            'module_id' => $module->id,
                            'code' => $moduleData['code'] . '-' . ($matiereIndex + 1),
                            'libelle' => $moduleData['libelle'] . ' - ' . $suffix,
                        ]
                    );

                    $matiere->professeurs()->syncWithoutDetaching([$professeur->id]);
                    $matiere->setRelation('professeurs', collect([$professeur]));
                    $matieres[] = $matiere;
                }

                $module->setRelation('matieres', collect($matieres));
                $modulesByFiliere[$filiereCode][] = $module;
            }
        }

        return $modulesByFiliere;
    }

    private function seedEtudiants(array $filieres, array $niveaux): array
    {
        $blueprints = [
            'INFO' => [
                'L1' => [
                    ['nom' => 'Diallo', 'prenom' => 'Aminata', 'sexe' => 'F', 'profile' => 'excellent', 'email' => 'aminata.diallo.info.l1@demo.test'],
                    ['nom' => 'Barry', 'prenom' => 'Mamadou', 'sexe' => 'M', 'profile' => 'fragile', 'email' => 'mamadou.barry.info.l1@demo.test'],
                ],
                'L2' => [
                    ['nom' => 'Traore', 'prenom' => 'Nadia', 'sexe' => 'F', 'profile' => 'balanced', 'email' => 'nadia.traore.info.l2@demo.test'],
                    ['nom' => 'Kone', 'prenom' => 'Youssouf', 'sexe' => 'M', 'profile' => 'fragile', 'email' => 'youssouf.kone.info.l2@demo.test'],
                ],
                'L3' => [
                    ['nom' => 'Cisse', 'prenom' => 'Sara', 'sexe' => 'F', 'profile' => 'excellent', 'email' => 'sara.cisse.info.l3@demo.test'],
                    ['nom' => 'Sy', 'prenom' => 'Moussa', 'sexe' => 'M', 'profile' => 'fragile', 'email' => 'moussa.sy.info.l3@demo.test'],
                ],
            ],
            'GEST' => [
                'L1' => [
                    ['nom' => 'Ba', 'prenom' => 'Fatou', 'sexe' => 'F', 'profile' => 'excellent', 'email' => 'fatou.ba.gest.l1@demo.test'],
                    ['nom' => 'Camara', 'prenom' => 'Ibrahima', 'sexe' => 'M', 'profile' => 'fragile', 'email' => 'ibrahima.camara.gest.l1@demo.test'],
                ],
                'L2' => [
                    ['nom' => 'Sane', 'prenom' => 'Awa', 'sexe' => 'F', 'profile' => 'balanced', 'email' => 'awa.sane.gest.l2@demo.test'],
                    ['nom' => 'Diarra', 'prenom' => 'Oumar', 'sexe' => 'M', 'profile' => 'fragile', 'email' => 'oumar.diarra.gest.l2@demo.test'],
                ],
                'L3' => [
                    ['nom' => 'Ndiaye', 'prenom' => 'Rokhaya', 'sexe' => 'F', 'profile' => 'excellent', 'email' => 'rokhaya.ndiaye.gest.l3@demo.test'],
                    ['nom' => 'Tall', 'prenom' => 'Cheikh', 'sexe' => 'M', 'profile' => 'fragile', 'email' => 'cheikh.tall.gest.l3@demo.test'],
                ],
            ],
            'RT' => [
                'L1' => [
                    ['nom' => 'Ndiaye', 'prenom' => 'Aminata', 'sexe' => 'F', 'profile' => 'excellent', 'email' => 'aminata.ndiaye.rt.l1@demo.test'],
                    ['nom' => 'Fall', 'prenom' => 'Moussa', 'sexe' => 'M', 'profile' => 'fragile', 'email' => 'moussa.fall.rt.l1@demo.test'],
                ],
                'L2' => [
                    ['nom' => 'Gueye', 'prenom' => 'Khadija', 'sexe' => 'F', 'profile' => 'balanced', 'email' => 'khadija.gueye.rt.l2@demo.test'],
                    ['nom' => 'Balde', 'prenom' => 'Mamadou', 'sexe' => 'M', 'profile' => 'fragile', 'email' => 'mamadou.balde.rt.l2@demo.test'],
                ],
                'L3' => [
                    ['nom' => 'Sow', 'prenom' => 'Aicha', 'sexe' => 'F', 'profile' => 'excellent', 'email' => 'aicha.sow.rt.l3@demo.test'],
                    ['nom' => 'Wade', 'prenom' => 'Ousmane', 'sexe' => 'M', 'profile' => 'fragile', 'email' => 'ousmane.wade.rt.l3@demo.test'],
                ],
            ],
        ];

        $students = [];

        foreach ($blueprints as $filiereCode => $levels) {
            foreach ($levels as $niveauCode => $entries) {
                foreach ($entries as $index => $entry) {
                    $user = User::updateOrCreate(
                        ['email' => $entry['email']],
                        [
                            'nom' => $entry['nom'],
                            'prenom' => $entry['prenom'],
                            'password' => Hash::make('password'),
                            'role' => 'etudiant',
                        ]
                    );

                    $numero = sprintf('%s-%s-%02d', $filiereCode, $niveauCode, $index + 1);

                    $etudiant = Etudiant::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'user_id' => $user->id,
                            'filiere_id' => $filieres[$filiereCode]->id,
                            'niveau_id' => $niveaux[$niveauCode]->id,
                            'numero_etudiant' => $numero,
                            'created_by_admin' => true,
                            'statut' => 'actif',
                        ]
                    );

                    Inscription::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'filiere_id' => $filieres[$filiereCode]->id,
                            'niveau_id' => $niveaux[$niveauCode]->id,
                        ],
                        [
                            'nom' => $entry['nom'],
                            'prenom' => $entry['prenom'],
                            'sexe' => $entry['sexe'],
                            'statut' => 'approuvee',
                            'user_id' => $user->id,
                            'filiere_id' => $filieres[$filiereCode]->id,
                            'niveau_id' => $niveaux[$niveauCode]->id,
                        ]
                    );

                    $students[] = [
                        'user' => $user,
                        'etudiant' => $etudiant,
                        'filiere_code' => $filiereCode,
                        'niveau_code' => $niveauCode,
                        'profile' => $entry['profile'],
                    ];
                }
            }
        }

        return $students;
    }

    private function seedNotes(array $students, array $modulesByFiliere): array
    {
        $statusPatterns = [
            'excellent' => ['validee', 'validee', 'rattrapage_valide', 'validee', 'validee'],
            'balanced' => ['validee', 'rattrapage', 'validee', 'rattrapage_valide', 'validee'],
            'fragile' => ['rattrapage', 'reprise', 'rattrapage', 'echec', 'reprise'],
        ];

        $flags = [];
        foreach ($students as $studentIndex => $studentData) {
            /** @var Etudiant $etudiant */
            $etudiant = $studentData['etudiant'];
            $studentFlags = ['has_rattrapage' => false, 'has_reprise' => false];

            $modules = array_values(array_filter(
                $modulesByFiliere[$studentData['filiere_code']],
                fn(Module $module) => (string) $module->semestre?->niveau_id === (string) $etudiant->niveau_id
            ));

            foreach ($modules as $moduleIndex => $module) {
                $pattern = $statusPatterns[$studentData['profile']] ?? $statusPatterns['balanced'];

                foreach ($module->matieres as $matiereIndex => $matiere) {
                    $status = $pattern[($studentIndex + $moduleIndex + $matiereIndex) % count($pattern)];
                    $payload = $this->buildNotePayload($status, $studentIndex, $moduleIndex, $matiereIndex);

                    Note::updateOrCreate(
                        [
                            'etudiant_id' => $etudiant->id,
                            'matiere_id' => $matiere->id,
                        ],
                        $payload
                    );

                    if (in_array($status, ['rattrapage', 'rattrapage_valide'], true)) {
                        $studentFlags['has_rattrapage'] = true;
                    }
                    if (in_array($status, ['reprise', 'echec'], true)) {
                        $studentFlags['has_reprise'] = true;
                    }
                }
            }

            $flags[$etudiant->id] = $studentFlags;
        }

        return $flags;
    }

    private function buildNotePayload(string $status, int $studentIndex, int $moduleIndex, int $matiereIndex): array
    {
        $seed = ($studentIndex + 1) * 11 + ($moduleIndex + 1) * 7 + ($matiereIndex + 1);
        $cc = 10.0;
        $examen = 10.0;
        $rattrapage = null;
        $reprise = null;

        switch ($status) {
            case 'validee':
                $cc = 12 + ($seed % 5);
                $examen = 13 + (($seed + 2) % 5);
                break;

            case 'rattrapage_valide':
                $cc = 7 + ($seed % 3);
                $examen = 8 + (($seed + 1) % 3);
                $rattrapage = 11.0;
                break;

            case 'rattrapage':
                $cc = 7 + ($seed % 2);
                $examen = 8 + (($seed + 1) % 2);
                break;

            case 'reprise':
                $cc = 4 + ($seed % 3);
                $examen = 5 + (($seed + 1) % 3);
                $reprise = 7.0;
                break;

            case 'echec':
                $cc = 2 + ($seed % 3);
                $examen = 3 + (($seed + 1) % 3);
                break;
        }

        $noteCalculee = round(($cc * 0.4) + ($examen * 0.6), 2);
        $noteFinale = match ($status) {
            'validee' => $noteCalculee,
            'rattrapage_valide' => 10.00,
            'rattrapage' => $noteCalculee,
            'reprise' => $noteCalculee,
            'echec' => $noteCalculee,
            default => $noteCalculee,
        };

        return [
            'cc' => $cc,
            'examen' => $examen,
            'rattrapage' => $rattrapage,
            'reprise' => $reprise,
            'note_calculee' => $noteCalculee,
            'note_finale' => $noteFinale,
            'statut' => $status,
        ];
    }

    private function seedSyntheticSemestreResults(array $students, array $semestres, array $niveaux): void
    {
        foreach ($students as $studentData) {
            /** @var Etudiant $etudiant */
            $etudiant = $studentData['etudiant'];
            $niveau = $niveaux[$studentData['niveau_code']];

            $semestresNiveau = array_values(array_filter(
                $semestres,
                fn(Semestre $semestre) => (int) $semestre->niveau_id === (int) $niveau->id
            ));

            $existing = ResultatSemestre::where('etudiant_id', $etudiant->id)
                ->whereIn('semestre_id', array_map(fn($semestre) => $semestre->id, $semestresNiveau))
                ->with('semestre')
                ->get()
                ->sortBy(fn(ResultatSemestre $resultat) => $resultat->semestre?->code)
                ->values();

            if ($existing->isEmpty()) {
                continue;
            }

            $reference = $existing->first();
            $referenceAverage = (float) $reference->moyenne;
            $referenceDecision = $reference->decision;

            foreach ($semestresNiveau as $semestre) {
                $resultat = $existing->firstWhere('semestre_id', $semestre->id);

                if ($resultat) {
                    continue;
                }

                ResultatSemestre::updateOrCreate(
                    [
                        'etudiant_id' => $etudiant->id,
                        'semestre_id' => $semestre->id,
                    ],
                    [
                        'moyenne' => $referenceAverage,
                        'decision' => $referenceDecision,
                    ]
                );
            }
        }
    }

    private function seedAnnualResultsAndProgression(array $students, array $niveaux): void
    {
        $annualService = new ResultatAnnuelService();
        $progressionService = new ProgressionService();
        $anneeAcademique = (string) date('Y');

        foreach ($students as $studentData) {
            /** @var Etudiant $etudiant */
            $etudiant = $studentData['etudiant'];
            $niveau = $niveaux[$studentData['niveau_code']];

            $semestreIds = Semestre::where('niveau_id', $niveau->id)
                ->orderBy('code')
                ->pluck('id')
                ->all();

            $resultats = ResultatSemestre::where('etudiant_id', $etudiant->id)
                ->whereIn('semestre_id', $semestreIds)
                ->with('semestre')
                ->get()
                ->sortBy(fn(ResultatSemestre $resultat) => $resultat->semestre?->code)
                ->values();

            $s1 = (float) ($resultats->get(0)?->moyenne ?? 0);
            $s2 = (float) ($resultats->get(1)?->moyenne ?? $s1);

            $moyenne = $annualService->calculerMoyenne($s1, $s2);
            $decision = $annualService->decision($moyenne, $niveau);

            ResultatAnnuel::updateOrCreate(
                [
                    'etudiant_id' => $etudiant->id,
                    'niveau_id' => $niveau->id,
                    'annee_academique' => $anneeAcademique,
                ],
                [
                    'moyenne_s1' => $s1,
                    'moyenne_s2' => $s2,
                    'moyenne_annuelle' => $moyenne,
                    'decision' => $decision,
                ]
            );

            $statut = $progressionService->determinerStatutFromDecision($decision);
            if (($niveau->code ?? '') === 'L3' && $decision === 'diplome') {
                $statut = 'diplome';
            }

            ProgressionEtudiant::updateOrCreate(
                [
                    'etudiant_id' => $etudiant->id,
                    'annee_academique' => $anneeAcademique,
                ],
                [
                    'niveau_id' => $niveau->id,
                    'statut' => $statut,
                ]
            );
        }
    }

    private function seedPlanning(array $modulesByFiliere, array $salles, array $creneaux, array $semestres, array $niveaux): void
    {
        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
        $semesterById = [];
        foreach ($semestres as $semestre) {
            $semesterById[$semestre->id] = $semestre;
        }

        $index = 0;
        foreach ($modulesByFiliere as $filiereCode => $modules) {
            foreach ($modules as $module) {
                $matiere = $module->matieres->first();
                $professeur = $matiere?->professeurs->first();
                $semestre = $module->semestre;
                $niveau = $semestre?->niveau_id ? $niveaux['L' . (int) ceil(((int) $semestre->niveau_id / 2))] ?? null : null;

                if (!$matiere || !$professeur || !$semestre) {
                    continue;
                }

                $dayIndex = $index % count($jours);
                $slotIndex = intdiv($index, count($jours)) % count($creneaux);
                $jour = $jours[$dayIndex];
                $creneau = $creneaux[$slotIndex];
                $salle = $salles[$index % count($salles)];

                EmploiDuTemps::updateOrCreate(
                    [
                        'matiere_id' => $matiere->id,
                        'professeur_id' => $professeur->id,
                        'semestre_id' => $semestre->id,
                        'jour' => $jour,
                        'heure_debut' => $creneau->heure_debut,
                    ],
                    [
                        'matiere_id' => $matiere->id,
                        'professeur_id' => $professeur->id,
                        'semestre_id' => $semestre->id,
                        'niveau_id' => $semestre->niveau_id,
                        'salle' => $salle->nom_salle,
                        'jour' => $jour,
                        'heure_debut' => $creneau->heure_debut,
                        'heure_fin' => $creneau->heure_fin,
                        'code_seance' => strtoupper($filiereCode) . '-' . $module->code . '-' . ($index + 1),
                    ]
                );

                ProgrammeCours::updateOrCreate(
                    [
                        'matiere_id' => $matiere->id,
                        'professeur_id' => $professeur->id,
                        'creneau_id' => $creneau->id,
                        'jour_semaine' => $jour,
                        'semestre_id' => $semestre->id,
                        'niveau_id' => $semestre->niveau_id,
                        'filiere_id' => $module->filiere_id,
                    ],
                    [
                        'matiere_id' => $matiere->id,
                        'professeur_id' => $professeur->id,
                        'salle_id' => $salle->id,
                        'creneau_id' => $creneau->id,
                        'jour_semaine' => $jour,
                        'date_programme' => now()->startOfWeek()->addDays($dayIndex)->addWeeks($slotIndex)->toDateString(),
                        'semestre_id' => $semestre->id,
                        'niveau_id' => $semestre->niveau_id,
                        'filiere_id' => $module->filiere_id,
                        'duree_heures' => 2,
                        'statut' => 'Programmé',
                        'notes' => 'Seance de demo pour tester le planning.',
                    ]
                );

                $index++;
            }
        }
    }

    private function seedNotifications(array $students, array $flags): void
    {
        $anneeAcademique = (string) date('Y');

        foreach ($students as $studentData) {
            /** @var User $user */
            $user = $studentData['user'];
            /** @var Etudiant $etudiant */
            $etudiant = $studentData['etudiant'];

            $resultatAnnuel = ResultatAnnuel::where('etudiant_id', $etudiant->id)
                ->where('annee_academique', $anneeAcademique)
                ->first();

            $statutProgression = ProgressionEtudiant::where('etudiant_id', $etudiant->id)
                ->where('annee_academique', $anneeAcademique)
                ->first();

            NotificationCustom::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'type' => 'cours_programme',
                    'titre' => 'Emploi du temps genere',
                ],
                [
                    'message' => 'Votre planning academique de demo est disponible.',
                    'lu' => false,
                ]
            );

            if ($resultatAnnuel) {
                NotificationCustom::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'type' => 'resultats_publies',
                        'titre' => 'Resultats publies',
                    ],
                    [
                        'message' => sprintf(
                            'Votre moyenne annuelle de demo est de %.2f/20 pour l\'annee %s.',
                            $resultatAnnuel->moyenne_annuelle,
                            $anneeAcademique
                        ),
                        'lu' => false,
                    ]
                );
            }

            if (!empty($flags[$etudiant->id]['has_rattrapage'])) {
                NotificationCustom::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'type' => 'rattrapage_propose',
                        'titre' => 'Rattrapage propose',
                    ],
                    [
                        'message' => 'Au moins une matiere de demo est en rattrapage. Consultez vos notes.',
                        'lu' => false,
                    ]
                );
            }

            if (!empty($flags[$etudiant->id]['has_reprise'])) {
                NotificationCustom::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'type' => 'decision_reprise',
                        'titre' => 'Decision de reprise',
                    ],
                    [
                        'message' => 'Au moins une matiere de demo est en reprise. Suivez votre dossier.',
                        'lu' => false,
                    ]
                );
            }

            if ($statutProgression) {
                NotificationCustom::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'type' => 'progression_niveau',
                        'titre' => 'Progression academique',
                    ],
                    [
                        'message' => 'Votre statut de progression de demo est: ' . $statutProgression->statut . '.',
                        'lu' => false,
                    ]
                );
            }
        }
    }
}
