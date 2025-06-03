<?php

namespace App\Console\Commands;

use Illuminate\Http\Request;
use Illuminate\Console\Command;
use App\Http\Controllers\TopsisController;

class RunTopsis extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spk:run';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Jalankan perhitungan TOPSIS global untuk prioritas perbaikan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Instansiasi controller dan panggil method hitung
        $controller = app(TopsisController::class);
        $response = $controller->hitung(new Request());

        if ($response->getSession()->has('success')) {
            $this->info('TOPSIS run berhasil: ' . $response->getSession()->get('success'));
        } elseif ($response->getSession()->has('info')) {
            $this->info('TOPSIS run skipped: ' . $response->getSession()->get('info'));
        } elseif ($response->getSession()->has('error')) {
            $this->error('TOPSIS run error: ' . $response->getSession()->get('error'));
        } else {
            $this->info('TOPSIS run completed.');
        }

        return 0;
    }
}
