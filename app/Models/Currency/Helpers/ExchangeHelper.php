<?php


namespace App\Models\Currency\Helpers;


use App\Currency;
use App\Models\Currency\ExchangeCurrency;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ExchangeHelper
{

    public static function getCodesByExcahge($exchage_id)
    {
        $codes = [];
        DB::transaction(function () use(&$codes,$exchage_id){
            $models = ExchangeCurrency::select('currency_id')->where('exchange_id',$exchage_id)->get();
            if($models && $currencyIds = self::getCurrencyId($models)):
                  $codes = self::getCurrencyCodesByIds($currencyIds);
            endif;
        });
        return implode($codes,',');

    }


    public static function getCurrenciesByExchangeId($exchage_id)
    {
        $currencies = [];
        DB::transaction(function () use(&$currencies,$exchage_id){
            $models = ExchangeCurrency::select('currency_id')->where('exchange_id',$exchage_id)->get();
            if($models && $currencyIds = self::getCurrencyId($models)):
                $currencies = self::getCurrenciesByIds($currencyIds);
            endif;
        });
        return $currencies;
    }

    /**
     * @return false|string
     */
    public static  function getCurrencyList()
    {
        return json_encode(Currency::select('code')->get()->map(function ($model) {
            return $model->code;
        })->all());
    }

    public static function getCurrencyId($models)
    {
        return $models->map(function ($model) {
            return $model->currency_id;
        })->all();
    }

    public static function getCurrencyCodesByIds($currencyIds)
    {
        return Currency::select('code')->whereIn('currency_id',$currencyIds)->get()->map(function ($model){
            return $model->code;
        })->all();
    }

    public static function getCurrenciesByIds($currencyIds)
    {
        return Currency::orderBy('order_number', 'asc')->whereIn('currency_id',$currencyIds)->get();
    }


    /**
     * @param $rates Collection
     * @param $code
     */
    public static function getDistanceText($rates,$currency_id)
    {
        if($rates):
            $rate = null;
           foreach ($rates as $key => $item){
               if($key == $currency_id){
                   $rate = $item;
                   break;
               }
           }
             if($rate && $rate->count() > 1):
                 $buy_diff =  (float)number_format($rate[0]->rate_value_buy - $rate[1]->rate_value_buy,3);
                 $sell_diff =  (float)number_format($rate[0]->rate_value_sell - $rate[1]->rate_value_sell,3);
                  return [
                     'buy'=>self::getHtmlDiff($buy_diff),
                     'sell'=>self::getHtmlDiff($sell_diff),
                 ];

             endif;
        endif;
        return [
            'buy'=>'',
            'sell'=>''
        ];
    }

    public static function getDifferenceTextHome($rates,$currency_id,$exchange_id)
    {
        $rates = collect($rates);
        $response = ['buy'=>'','sell'=>''];
        $item = $rates->filter(function ($item) use ($exchange_id,$currency_id){
            return $item->exchangeId == $exchange_id && $item->currencyId == $currency_id;
        })->first();
//        if($currency_id == 2 && $exchange_id == 26){
//            dd($item);
//        }
        if(!$item) return $response;
        $rates_buy = explode(',',$item->rate_value_buy);
        if(count($rates_buy) > 1) $response['buy'] = self::getHtmlDiff(self::getFloatValue(floatval($rates_buy[0]) - floatval($rates_buy[1])));
        $rates_sell = explode(',',$item->rate_value_sell);
        if(count($rates_sell) > 1) $response['sell']=self::getHtmlDiff(self::getFloatValue(floatval($rates_sell[0]) - floatval($rates_sell[1])));

        return $response;
    }

    protected static function getHtmlDiff($item)
    {
        if ($item == 0) return '';
        if ($item < 0) return '<span style="color: red;">('.$item.')</span>';
        return '<span style="color: green;">(+' . $item.')</span>';
    }


    public static function getClearItems($items){
        if($items):
            foreach ($items as $index => $value):
             $items[$index] = str_replace(',','.',$value);
            endforeach;
        endif;
       return $items;
    }

    protected  static function getFloatValue($value){
       return  (float)number_format($value,3);
    }

}