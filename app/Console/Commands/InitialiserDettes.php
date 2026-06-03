<?php

namespace App\Console\Commands;

use App\Models\Dette;
use App\Models\Note;
use App\Services\DetteService;
use Illuminate\Console\Command;

class InitialiserDettes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:initialiser-dettes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reconstruit la table des dettes à partir des notes actuelles';

    public function handle(DetteService $detteService): int
    {
        Dette::query()->delete();

        Note::with('matiere.module')->chunkById(200, function ($notes) use ($detteService) {
            foreach ($notes as $note) {
                $detteService->verifierDette($note);
            }
        });

        $this->info('Toutes les dettes ont été reconstruites avec succès.');

        return self::SUCCESS;
    }
}
