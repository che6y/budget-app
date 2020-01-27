<?php

namespace App\Console;

use App\Purchase;
use App\MonthlySpending;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\BackupDB',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $today     = date('Y-m-d', time());
            $date_from = date(
                'Y-m-d',
                mktime(
                    0,
                    0,
                    0,
                    date('n') - 1,
                    25,
                    date('Y')
                )
            );

            // This month total
            $total = Purchase::whereBetween( 'created_at', [$date_from, $today])
                                ->sum(DB::raw('cost * amount'));
            MonthlySpending::insert(array(
                'month'  => date('m', time()),
                'year'   => date('Y', time()),
                'amount' => $total
            ));
        })->monthlyOn(25, '05:00');

        $schedule->command('db:backup')->mondays()->at('07:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
