<?php

namespace App\Jobs;

use App\Cash;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class Updategridview implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var $model Cash
     */
    private  $model;
  //  public $delay
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Cash $model)
    {
        $this->model = $model;
        $this->delay = Carbon::now()->addMinutes(30);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->model->update(['update_list_at'=> Carbon::now()->toDateTimeString()]);

    }
}
