<?php
namespace App;

use App\DTO\CashDTO;
use App\Observers\CashObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\DomCrawler\Form;

class Cash extends Model {

    protected $table = 'front_page_data';
    protected $guarded  = array('id');

    public function exchange()
    {
        return $this->belongsTo('App\Exchangers');
    }

    protected $casts = [
        'rates_json' => 'array',
    ];

//    public static function boot()
//    {
//        parent::boot();
//        self::observe(CashObserver::class);
//    }

    /**
     * @param  $dto CashDTO
     */
    public static function createItem($dto)
    {
        DB::transaction(function () use ($dto){
            $items  = $dto->getItems();
            DB::table('front_page_data')->insert([
                'exchange_id' => $dto->newCacheValue['exchange_id'],
                'rates_json' => json_encode($items),
                'updated_at' => $dto->date
            ]);

            self::createCacheRates($dto);
        });
    }

    /**
     * @param  $dto CashDTO
     */
    public static function updateItem($dto)
    {
        DB::transaction(function () use ($dto){
            $items  = $dto->getItems();
            DB::table('front_page_data')->where('id',$dto->getCashId())->update([
                'rates_json'=>json_encode($items),
                'updated_at'=>$dto->date
            ]);

            self::createCacheRates($dto);
        });
    }




    /**
     * @param $dto CashDTO
     */
    public static function createCacheRates($dto)
    {
        $items = $dto->getItems();
        if($dto->currencies && $items):
            $insertCacheRates = [];
            foreach ($dto->currencies as $currency):
                $dyData = [];
                if(isset($items[strtolower($currency->code) .'_buy']) && isset($items[strtolower($currency->code) .'_sell'])):
                    $dyData['rate_value_buy'] = $items[strtolower($currency->code) .'_buy'];
                    $dyData['rate_value_sell'] = $items[strtolower($currency->code) .'_sell'];
                    $dyData['exchange_id'] = $dto->newCacheValue['exchange_id'];
                    $dyData['currency_id'] = $currency->currency_id;
                    $dyData['changed_at'] = Carbon::now()->format('Y-m-d H:i:s');
                    $insertCacheRates[]=$dyData;
                endif;
            endforeach;
            if($insertCacheRates):
                DB::table('cash_rates')->insert($insertCacheRates);
            endif;
        endif;
    }

}