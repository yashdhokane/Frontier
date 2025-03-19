<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\SendCronMail;
use Mail;
use Log;

class SendRouteCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-route-cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $emailData = [
            'subject' => 'Automated Cron Mail',
            'body' => 'Yeh mail har 5 minute mein bheja ja raha hai!'
        ];

        // Mail bhejne ka process
        Mail::to('bawanesumit01@gmail.com')->send(new SendCronMail($emailData));

        // Logging for debugging
        Log::info('Mail sent successfully via cron!');

        // Console ke liye output
        $this->info('Mail sent successfully!');
    }
}
