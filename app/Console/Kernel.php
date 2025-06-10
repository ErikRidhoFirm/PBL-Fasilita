<?php

namespace App\Console;

use run;
use App\Models\Status;
use App\Models\Kriteria;
use App\Models\SkorTipe;
use App\Models\LaporanFasilitas;
use App\Models\SkorKriteriaLaporan;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
         $schedule->command('spk:run')
             ->cron('0 0 */3 * *')
                // ->everyMinute()
             ->withoutOverlapping()
             ->runInBackground()
              ->when(function () {
                 // Return true only if there are changes that require re-run.
                 // You can re-run your “have things changed?” logic here:
                 $last       = SkorTipe::where('tipe','global')->latest('created_at')->first();
                 $curAlt     = LaporanFasilitas::where('id_status',Status::VALID)
                                  ->where('is_active',true)
                                  ->whereDoesntHave('penugasan')
                                  ->count();
                 $curCri     = Kriteria::count();
                 $lastRaw   = SkorKriteriaLaporan::max('updated_at');
                 $lastScore = $lastRaw ? \Carbon\Carbon::parse($lastRaw) : null;

                 // Run if no previous run, or anything changed since last run:
                 if (! $last) {
                     return true;
                 }
                 // Alt/criteria count changed?
                 if ($last->alt_count !== $curAlt || $last->cri_count !== $curCri) {
                     return true;
                 }
                 // Any raw‐score change after last run?
                 if ($lastScore && $lastScore->gt($last->created_at)) {
                     return true;
                 }
                 // Otherwise skip
                 return false;
             });
}

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
