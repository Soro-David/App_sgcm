<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProcessMerchantPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-merchant-payments {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Traite les paiements automatiques des taxes des commerçants';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Début du traitement des paiements automatiques...');

        $force = $this->option('force');
        $request = $force ? new \Illuminate\Http\Request(['force' => true]) : null;

        $controller = new \App\Http\Controllers\Commercant\PayementController;
        $response = $controller->traiterPaiementsAutomatiques($request);

        $data = $response->getData();

        if ($data->status === 'success') {
            $this->info($data->message);
            $this->table(['Traités', 'Succès', 'Déjà payé', 'Solde insuffisant'], [
                [
                    $data->stats->traites,
                    $data->stats->succes,
                    $data->stats->deja_paye,
                    $data->stats->solde_insuffisant
                ]
            ]);
        } else {
            $this->error($data->message);
        }

        $this->info('Traitement terminé.');
    }
}
