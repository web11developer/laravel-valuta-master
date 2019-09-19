<?php

namespace App\Console\Commands;

use App\Subscribes;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendNewsletter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:newsletter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send newsletter to user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $users = Subscribes::all()->where('send', '=', false)->take(3);

        foreach ($users as $user) {



// несколько получателей
            $to = $user->email; // обратите внимание на запятую

// тема письма
            $subject = 'В обменнике курс был изменилось';

// текст письма
            $message = "
<html>
<head>
  <title>{$user->exchanger->title}</title>
</head>
<body>
Изменился кур обмена валют, на который вы были подписаны на сайте valuta.kz!
Перейти по <a href='https://www.valuta.kz/exchange/{$user->exchange_id}'>ссылке</a>.
</body>
</html>
";

            /* Для отправки HTML-почты вы можете установить шапку Content-type. */
            $headers= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";

            /* дополнительные шапки */
            $headers .= "From: Valuta.Kz <info@valuta.kz>\r\n";
            $headers .= "Cc: info@valuta.kz\r\n";
            $headers .= "Bcc: info@valuta.kz\r\n";

            /* и теперь отправим из */
            mail($to, $subject, $message, $headers);
            $user->send = true;
            $user->save();
        }

    }
}
