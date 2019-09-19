<?php

namespace App\Jobs;

use App\Mail\SendNewsletter;
use App\Subscribes;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $message;
    protected $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($message, $id)
    {
        $this->message = $message;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


        $users = Subscribes::all()->where('send', '=', false)->take(1);
        foreach ($users as $user) {
            dd($user->exchanger);
//            echo $user->exchanger->title;
//            echo $user->exchanger->id;
//            echo $user->exchanger->email;
//            die(1);
        }
    }
}
