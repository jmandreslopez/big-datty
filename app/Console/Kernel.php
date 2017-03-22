<?php namespace App\Console;

use Laravel\Lumen\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Scheduling\Schedule;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [

        Commands\Amazon\Seller\SellerVerifyCommand::class,
        Commands\Amazon\Order\OrderCommand::class,
        Commands\Amazon\Product\ProductCommand::class,
        Commands\Amazon\Product\ProductReviewCommand::class,
        Commands\Amazon\Product\ProductQuestionCommand::class,
        Commands\Amazon\Product\ProductHijackCommand::class,
        Commands\Amazon\Report\ReportCommand::class,

    ];

    /**
     * Get the commands to add to the application.
     *
     * @return array
     */
    protected function getCommands()
    {
        $commands = $this->commands;

        if (env('APP_ENV') == 'local') {
            $commands = array_merge($commands, [
                Commands\Tor\TorIpCommand::class,
                Commands\Tor\TorRenewCommand::class,
                Commands\TestCommand::class,
            ]);
        }

        return array_merge($commands, [
            'Illuminate\Console\Scheduling\ScheduleRunCommand',
        ]);
    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}
