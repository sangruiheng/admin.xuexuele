<?php

namespace App\Console;

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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->call(function () {
            $userlist = DB::table('s_users')->get();
            foreach ($userlist as $user) {
                
                if($user->manvalue<30){
                     DB::table('s_users')->where('id',$user->id)->update(['manvalue'=>($user->manvalue+1)]);
                }
            }

        
        })->everyFiveMinutes();

        $schedule->call(function () {
            $deladminlogs = DB::table('admin_logs')->delete();
            $delapireq = DB::table('log_api_requests')->delete();
        })->weekly();

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
